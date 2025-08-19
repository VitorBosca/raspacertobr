<?php

namespace App\Http\Controllers\Api\Games;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Wallet;
use App\Models\Order;
use App\Services\PrizeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Helpers\Core as Helper;

class ScratchCardController extends Controller
{
    public function createDemoGame(Request $request)
    {
        $userId = auth('api')->id();
        $gameId = $request->get('gameId', null);

        if (!$gameId) {
            return response()->json([
                'success' => false,
                'message' => 'ID do jogo não fornecido'
            ], 400);
        }

        $game = Game::find($gameId);
        if (!$game) {
            return response()->json([
                'success' => false,
                'message' => 'Jogo não encontrado'
            ], 404);
        }

        $jaFoiResgatado = Order::where('user_id', $userId)
            ->where('game_uuid', $gameId)
            ->where('type', 'Raspadinha [BONUS]')
            ->exists();

        if ($jaFoiResgatado) {
            return response()->json([
                'success' => false,
                'message' => 'Você já resgatou esse prêmio.'
            ], 400);
        }

        $prizeService = new PrizeService();

        $gameItems = $prizeService->getDemoPrizes();
        $gameWinItems = $prizeService->getDemoPrizesWin();

        Order::create([
            'user_id' => $userId,
            'game_id' => $gameId,
            'game_items' => $gameItems,
            'winning_prize' => $gameWinItems,
            'status' => 0,
            'hash' => 'demo_' . hash('sha256', $userId . $gameId . time()),
            'round_id' => 'demo_' . Str::uuid(),
            'providers' => 'scratch_card',
            'game_uuid' => $gameId,
            'type_money' => 'credit',
            'game' => $game->game_name,
            'transaction_id' => Str::uuid(),
            'session_id' => session()->getId() ?? Str::random(32),
            'prize_amount' => 10,
            'type' => 'Raspadinha [BONUS]',
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    public function getNotFinalizedGames(Request $request)
    {
        $userId = auth('api')->id();
        $gameId = $request->get('game_id', null);

        $games = Order::where('user_id', $userId)
            ->where('status', 0)
            ->where('providers', 'scratch_card')
            ->where('game_uuid', $gameId)
            ->with('game')
            ->orderBy('created_at', 'desc')
            ->first();

        return response()->json([
            'success' => true,
            'data' => [
                'game_items' => $games->game_items ?? [],
                'order_id' => $games->id ?? null,
            ]
        ]);
    }

    /**
     * Comprar uma cartela de raspadinha
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function buyScratchCard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'game_id' => 'required|exists:games,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos',
                'errors' => $validator->errors()
            ], 400);
        }

        $userId = auth('api')->id();
        $gameId = $request->game_id;
        $isDemo = $request->is_demo ?? false;

        $gameInfo = Game::select(['game_name', 'valor'])->where('id', $gameId)->first();
        if (!$gameInfo) {
            return response()->json([
                'success' => false,
                'message' => 'Jogo não encontrado'
            ], 404);
        }

        $amount = $gameInfo ? $gameInfo->valor : 1;

        try {
            DB::beginTransaction();

            $wallet = Wallet::where('user_id', $userId)
                ->where('active', 1)
                ->first();

            if (!$wallet) {
                return response()->json([
                    'success' => false,
                    'message' => 'Carteira não encontrada'
                ], 404);
            }

            $totalBalance = $wallet->total_balance;
            if ($totalBalance < $amount && !$isDemo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Saldo insuficiente',
                    'current_balance' => $totalBalance,
                    'required_amount' => $amount
                ], 400);
            }

            $remainingAmount = $amount;
            $changeBonus = 'balance';

            if (!$isDemo) {
                if ($wallet->balance >= $remainingAmount) {
                    $wallet->balance -= $remainingAmount;
                    $remainingAmount = 0;
                    $changeBonus = 'balance';
                } else if ($wallet->balance > 0) {
                    $remainingAmount -= $wallet->balance;
                    $wallet->balance = 0;
                    $changeBonus = 'balance';
                }

                if ($remainingAmount > 0 && $wallet->balance_bonus >= $remainingAmount) {
                    $wallet->balance_bonus -= $remainingAmount;
                    $remainingAmount = 0;
                    $changeBonus = 'balance_bonus';
                } else if ($remainingAmount > 0 && $wallet->balance_bonus > 0) {
                    $remainingAmount -= $wallet->balance_bonus;
                    $wallet->balance_bonus = 0;
                    $changeBonus = 'balance_bonus';
                }

                if ($remainingAmount > 0) {
                    $wallet->balance_withdrawal -= $remainingAmount;
                    $changeBonus = 'balance_withdrawal';
                }
            } else {
                $wallet->balance_demo -= $amount;
            }

            $wallet->save();

            /*
            WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'user_id' => $userId,
                'type' => 'debit',
                'amount' => $amount,
                'description' => 'Compra de cartela de raspadinha',
                'reference' => 'scratch_card_purchase',
                'status' => 'completed'
            ]);
            */

            $gameItems = $this->generateScratchCardItems($gameId, $isDemo);

            array_walk($gameItems, function(&$product) {
                if(strpos($product['image'], 'games/covers') !== false) {
                    $product['image'] = "/storage/" . $product['image'];
                }
            });

            $game = Game::find($gameId);

            $order = Order::create([
                'user_id' => $userId,
                'session_id' => session()->getId() ?? Str::random(32),
                'transaction_id' => Str::uuid(),
                'game' => $game->game_name ?? 'Raspadinha',
                'game_uuid' => $gameId,
                'type' => $isDemo ? 'Raspadinha [DEMO]' : 'Raspadinha',
                'type_money' => $changeBonus,
                'amount' => $amount,
                'providers' => $isDemo ? 'scratch_card_demo' : 'scratch_card',
                'refunded' => 0,
                'round_id' => Str::uuid(),
                'status' => 0,
                'hash' => hash('sha256', $userId . $gameId . time()),
                'game_items' => $gameItems,
                'has_won' => false,
                'winning_prize' => null,
                'prize_amount' => 0
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cartela comprada com sucesso!',
                'data' => [
                    'order_id' => $order->id,
                    'game_items' => $gameItems,
                    'new_balance' => $wallet->fresh()->total_balance,
                    'amount_spent' => $amount
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor',
                'error' => config('app.debug') ? $e->getMessage() : 'Tente novamente mais tarde'
            ], 500);
        }
    }

    /**
     * Processar resultado da raspadinha
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function processScratchResult(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'game_items' => 'required|array|size:9',
            'has_won' => 'required|boolean',
            'winning_prize' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos',
                'errors' => $validator->errors()
            ], 400);
        }

        $userId = auth('api')->id();
        $orderId = $request->order_id;
        $hasWon = $request->has_won;
        $winningPrize = $request->winning_prize;

        try {
            $order = Order::where('id', $orderId)
                ->where('user_id', $userId)
                ->whereIn('providers', ['scratch_card', 'scratch_card_demo'])
                ->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jogada não encontrada ou não pertence ao usuário'
                ], 404);
            }

            $isDemo = $order->providers == 'scratch_card_demo';

            $order->update([
                'status' => 1
            ]);

            $prizeAmount = 0;
            $message = 'Que pena! Tente novamente na próxima!';

            if ($hasWon && $winningPrize) {
                $message = 'Parabéns! Você ganhou: ' . $winningPrize['name'];
                $prizeAmount = $winningPrize['cash_value'] ?? 0;

                if (isset($winningPrize['type']) && $winningPrize['type'] === 'money' && $prizeAmount > 0) {
                    try {
                        DB::beginTransaction();

                        $wallet = Wallet::where('user_id', $userId)
                            ->where('active', 1)
                            ->first();

                        if ($wallet) {
                            if ($isDemo) {
                                $wallet->balance_demo += $prizeAmount;
                            } else {
                                $wallet->balance_withdrawal += $prizeAmount;
                            }
                            $wallet->save();

                            $message = sprintf(
                                'Parabéns! Você ganhou %s e o valor de R$ %.2f foi depositado na sua conta!',
                                $winningPrize['name'],
                                $prizeAmount
                            );
                        }

                        $order->update([
                            'has_won' => $hasWon,
                            'winning_prize' => $winningPrize,
                            'prize_amount' => $prizeAmount,
                            'type_money' => $hasWon && $prizeAmount > 0 ? $order->type_money : 'debit'
                        ]);

                        DB::commit();

                    } catch (\Exception $e) {
                        DB::rollBack();
                        $order->update([
                            'has_won' => $hasWon,
                            'winning_prize' => $winningPrize,
                            'prize_amount' => $prizeAmount
                        ]);
                        $message .= ' (Valor será creditado em breve)';
                    }
                } else {
                    $order->update([
                        'has_won' => $hasWon,
                        'winning_prize' => $winningPrize,
                        'prize_amount' => 0
                    ]);
                }
            } else {
                $order->update([
                    'has_won' => false,
                    'winning_prize' => null,
                    'prize_amount' => 0
                ]);
            }

            $typeAction = $hasWon ? 'win' : 'bet';
            $bet = $order->amount;
            $changeBonus = $order->type_money && !$isDemo ? ($order->type_money ?? 'balance') : 'balance_demo';
            $txnId = $order->transaction_id ?? Str::uuid();

            Helper::generateGameHistory($userId, $typeAction, $prizeAmount, $bet, $changeBonus, $txnId);

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'order_id' => $order->id,
                    'has_won' => $hasWon,
                    'prize' => $winningPrize,
                    'money_deposited' => $hasWon && isset($winningPrize['type']) && $winningPrize['type'] === 'money',
                    'deposit_amount' => $prizeAmount
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor',
                'error' => config('app.debug') ? $e->getMessage() : 'Tente novamente mais tarde'
            ], 500);
        }
    }

    /**
     * Gerar itens da cartela de raspadinha baseado no RTP dos prêmios
     *
     * @param int $gameId
     * @param bool $isDemo
     * @return array
     */
    private function generateScratchCardItems($gameId, $isDemo = false)
    {
        Log::info('=== DEBUG generateScratchCardItems INÍCIO ===', [
            'gameId' => $gameId,
            'isDemo' => $isDemo,
            'timestamp' => now()
        ]);

        $game = Game::find($gameId);
        if (!$game) {
            Log::error('Jogo não encontrado', ['gameId' => $gameId]);
            return [];
        }

        Log::info('Game encontrado:', [
            'game_id' => $game->id,
            'game_name' => $game->game_name,
            'game_value' => $game->valor ?? 1,
            'is_demo' => $isDemo
        ]);

        // Obter prêmios configurados
        $configuredPrizes = [];
        if ($game && $game->premios && is_array($game->premios) && !empty($game->premios)) {
            $configuredPrizes = $game->premios;
        }

        $products = PrizeService::getCardPrizes($configuredPrizes);

        $prizesByType = PrizeService::separatePrizesByType($products);
        $allPrizes = array_merge($prizesByType['money'], $prizesByType['product']);

        Log::info('Prêmios carregados:', [
            'money_count' => count($prizesByType['money']),
            'physical_count' => count($prizesByType['product']),
            'total_prizes' => count($allPrizes),
            'prizes_with_probability' => collect($allPrizes)->pluck('probability', 'name')->toArray()
        ]);

        // Determinar se haverá vitória baseado nas probabilidades dos prêmios
        $willWin = $this->determineWinFromPrizeProbabilities($allPrizes, $isDemo);
        $winningProduct = null;

        if ($willWin) {
            $winningProduct = $this->selectWinningPrizeFromProbabilities($allPrizes);
            Log::info('Prêmio vencedor selecionado:', [
                'prize' => $winningProduct,
                'probability' => $winningProduct['probability'] ?? 0
            ]);
        }

        // Gerar cartela
        $gameItems = $this->generateGameGrid($allPrizes, $winningProduct, $willWin, $isDemo);

        Log::info('=== DEBUG generateScratchCardItems FIM ===', [
            'final_count' => count($gameItems),
            'will_win' => $willWin,
            'winning_prize' => $winningProduct ? $winningProduct['name'] : null,
            'winning_value' => $winningProduct ? ($winningProduct['cash_value'] ?? 0) : 0,
            'winning_probability' => $winningProduct ? ($winningProduct['probability'] ?? 0) : 0,
            'timestamp' => now()
        ]);

        return $gameItems;
    }

    /**
     * Determinar se haverá vitória baseado nas probabilidades predefinidas dos prêmios
     *
     * @param array $prizes
     * @param bool $isDemo
     * @return bool
     */
    private function determineWinFromPrizeProbabilities($prizes, $isDemo)
    {
        if (empty($prizes)) {
            return false;
        }

        // Calcular probabilidade total de vitória baseado nas probabilidades dos prêmios
        $totalWinProbability = 0;
        foreach ($prizes as $prize) {
            $probability = ($prize['probability'] ?? 0) / 100; // Converter de % para decimal
            $totalWinProbability += $probability;
        }

        // Limitar probabilidade total para manter RTP controlado
        $maxWinProbability = $isDemo ? 1.00 : 0.20; // 100% demo, 20% real
        $totalWinProbability = min($totalWinProbability, $maxWinProbability);

        // Ajustar para demo (multiplicador para demonstração)
        if ($isDemo) {
            $totalWinProbability = min($totalWinProbability * 1.5, 0.4); // Máximo 40% para demo
        }

        $roll = mt_rand(1, 10000) / 10000; // Precisão de 0.01%
        
        Log::info('Determinando vitória pelas probabilidades dos prêmios:', [
            'total_win_probability' => $totalWinProbability,
            'max_allowed' => $maxWinProbability,
            'roll' => $roll,
            'will_win' => $roll <= $totalWinProbability,
            'is_demo' => $isDemo
        ]);

        return $roll <= $totalWinProbability;
    }

    /**
     * Selecionar prêmio vencedor baseado nas probabilidades predefinidas
     *
     * @param array $prizes
     * @return array|null
     */
    private function selectWinningPrizeFromProbabilities($prizes)
    {
        if (empty($prizes)) {
            return null;
        }

        // Filtrar apenas prêmios com probabilidade > 0
        $eligiblePrizes = array_filter($prizes, function($prize) {
            return ($prize['probability'] ?? 0) > 0;
        });

        if (empty($eligiblePrizes)) {
            return null;
        }

        // Criar distribuição acumulativa baseada nas probabilidades predefinidas
        $cumulativeProbabilities = [];
        $cumulative = 0;

        foreach ($eligiblePrizes as $prize) {
            $probability = $prize['probability'] ?? 0;
            $cumulative += $probability;
            $cumulativeProbabilities[] = [
                'prize' => $prize,
                'cumulative' => $cumulative
            ];
        }

        // Fazer roll baseado na probabilidade total
        $totalProbability = $cumulative;
        $roll = mt_rand(1, (int)($totalProbability * 100)) / 100;

        Log::info('Selecionando prêmio por probabilidade:', [
            'total_probability' => $totalProbability,
            'roll' => $roll,
            'eligible_prizes_count' => count($eligiblePrizes)
        ]);

        // Encontrar o prêmio correspondente ao roll
        foreach ($cumulativeProbabilities as $entry) {
            if ($roll <= $entry['cumulative']) {
                return $entry['prize'];
            }
        }

        // Fallback: retornar o último prêmio
        return end($eligiblePrizes);
    }

    /**
     * Selecionar prêmio aleatório para preenchimento baseado nas probabilidades
     *
     * @param array $prizes
     * @param bool $isDemo
     * @return array
     */
    private function selectRandomPrize($prizes, $isDemo)
    {
        if (empty($prizes)) {
            // Fallback para prêmios padrão
            $defaultPrizes = PrizeService::getDefaultPrizes();
            
            Log::info('Usando prêmios padrão como fallback:', [
                'default_count' => count($defaultPrizes)
            ]);
            
            return $defaultPrizes[array_rand($defaultPrizes)];
        }

        // Usar as probabilidades predefinidas dos prêmios para seleção ponderada
        $weightedPrizes = [];
        
        foreach ($prizes as $prize) {
            $probability = $prize['probability'] ?? 1;
            
            // Usar a probabilidade como peso, mas limitar para não criar muita discrepância
            $weight = max(1, min($probability, 50)); // Entre 1 e 50
            
            for ($i = 0; $i < $weight; $i++) {
                $weightedPrizes[] = $prize;
            }
        }

        if (empty($weightedPrizes)) {
            // Se por algum motivo não conseguiu criar o array ponderado, usar seleção simples
            return $prizes[array_rand($prizes)];
        }

        return $weightedPrizes[array_rand($weightedPrizes)];
    }

    /**
     * Gerar grade do jogo (3x3)
     *
     * @param array $allPrizes
     * @param array|null $winningProduct
     * @param bool $willWin
     * @param bool $isDemo
     * @return array
     */
    private function generateGameGrid($allPrizes, $winningProduct, $willWin, $isDemo)
    {
        $gameItems = [];

        // Se há vitória, colocar 3 itens vencedores em posições aleatórias
        if ($willWin && $winningProduct) {
            $winningPositions = $this->getRandomPositions(3);
            
            foreach ($winningPositions as $pos) {
                $gameItems[$pos] = $winningProduct;
                $gameItems[$pos]['isWinning'] = true;
            }

            Log::info('Posições vencedoras definidas:', [
                'positions' => $winningPositions,
                'winning_prize' => $winningProduct['name'],
                'winning_value' => $winningProduct['cash_value'] ?? 0
            ]);
        }

        // Filtrar prêmios para preenchimento (excluir o prêmio vencedor se houver)
        $fillPrizes = $allPrizes;
        if ($willWin && $winningProduct) {
            $fillPrizes = array_filter($allPrizes, function($prize) use ($winningProduct) {
                return $prize['name'] !== $winningProduct['name'];
            });
            
            Log::info('Prêmios para preenchimento:', [
                'total_prizes' => count($allPrizes),
                'fill_prizes' => count($fillPrizes),
                'excluded_prize' => $winningProduct['name']
            ]);
        }

        // Preencher posições restantes
        for ($i = 0; $i < 9; $i++) {
            if (!isset($gameItems[$i])) {
                $selectedPrize = $this->selectRandomPrize($fillPrizes, $isDemo);
                $selectedPrize['isWinning'] = false;
                
                // Evitar criar combinação vencedora acidental com outros prêmios
                $attempts = 0;
                while ($this->wouldCreateWinningCombo($gameItems, $selectedPrize, $i, $isDemo) && $attempts < 20) {
                    $selectedPrize = $this->selectRandomPrize($fillPrizes, $isDemo);
                    $selectedPrize['isWinning'] = false;
                    $attempts++;
                    
                    Log::info('Evitando combo vencedor acidental:', [
                        'attempt' => $attempts,
                        'position' => $i,
                        'prize_name' => $selectedPrize['name'],
                        'is_demo' => $isDemo
                    ]);
                }

                if ($attempts >= 20) {
                    Log::warning('Max tentativas atingido para posição', ['position' => $i]);
                }
                
                $gameItems[$i] = $selectedPrize;
            }
        }

        // Garantir ordem correta
        ksort($gameItems);
        $finalItems = array_values($gameItems);

        // Log final para debug
        $itemCounts = [];
        foreach ($finalItems as $item) {
            $name = $item['name'];
            $itemCounts[$name] = ($itemCounts[$name] ?? 0) + 1;
        }

        Log::info('Grade final gerada:', [
            'total_items' => count($finalItems),
            'item_counts' => $itemCounts,
            'has_winning_combo' => $willWin,
            'winning_prize' => $winningProduct ? $winningProduct['name'] : null
        ]);

        return $finalItems;
    }

    /**
     * Verificar se adicionar um produto criaria uma combinação vencedora acidental
     */
    private function wouldCreateWinningCombo($gameItems, $newProduct, $position, $isDemo = false)
    {
        $tempItems = $gameItems;
        $tempItems[$position] = $newProduct;

        $productCounts = [];

        foreach ($tempItems as $item) {
            if ($item && isset($item['name'])) {
                $key = $item['name'];
                $productCounts[$key] = ($productCounts[$key] ?? 0) + 1;

                // Para DEMO: permitir apenas 1 de cada prêmio (exceto o vencedor)
                // Para REAL: evitar 3 ou mais do mesmo prêmio (exceto o vencedor)
                $maxAllowed = $isDemo ? 1 : 2; // Demo: max 1, Real: max 2 (para evitar 3)
                
                if ($productCounts[$key] > $maxAllowed && !($item['isWinning'] ?? false)) {
                    Log::info('Combo vencedor acidental detectado:', [
                        'prize_name' => $key,
                        'count' => $productCounts[$key],
                        'max_allowed' => $maxAllowed,
                        'position' => $position,
                        'is_demo' => $isDemo
                    ]);
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Obter posições aleatórias
     *
     * @param int $count
     * @return array
     */
    private function getRandomPositions($count)
    {
        $positions = [];
        while (count($positions) < $count) {
            $pos = mt_rand(0, 8);
            if (!in_array($pos, $positions)) {
                $positions[] = $pos;
            }
        }
        return $positions;
    }

    /**
     * Obter saldo atual do usuário
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserBalance()
    {
        $userId = auth('api')->id();

        $wallet = Wallet::where('user_id', $userId)
            ->where('active', 1)
            ->first();

        if (!$wallet) {
            return response()->json([
                'success' => false,
                'message' => 'Carteira não encontrada'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'balance' => $wallet->balance,
                'balance_bonus' => $wallet->balance_bonus,
                'balance_withdrawal' => $wallet->balance_withdrawal,
                'total_balance' => $wallet->total_balance
            ]
        ]);
    }

    /**
     * Obter prêmios configurados de um jogo
     *
     * @param int $gameId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGamePrizes($gameId)
    {
        try {
            $game = Game::find($gameId);

            if (!$game) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jogo não encontrado'
                ], 404);
            }

            $configuredPrizes = [];
            if ($game->premios && is_array($game->premios) && !empty($game->premios)) {
                $configuredPrizes = $game->premios;
            }

            $prizes = PrizeService::getDisplayPrizes($configuredPrizes);

            return response()->json([
                'success' => true,
                'data' => [
                    'game_id' => $gameId,
                    'game_name' => $game->game_name,
                    'prizes' => $prizes,
                    'total_prizes' => count($prizes),
                    'configured_prizes' => count($configuredPrizes),
                    'uses_default_prizes' => count($configuredPrizes) < 8
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor',
                'error' => config('app.debug') ? $e->getMessage() : 'Tente novamente mais tarde'
            ], 500);
        }
    }

    /**
     * Obter histórico de jogadas do usuário
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGameHistory(Request $request)
    {
        $userId = auth('api')->id();
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 20);

        try {
            $query = Order::scratchCard()
                ->byUser($userId)
                ->with('user')
                ->orderBy('created_at', 'desc');

            $history = $query->paginate($perPage, ['*'], 'page', $page);

            $formattedHistory = collect($history->items())->map(function ($order) {
                return [
                    'id' => $order->id,
                    'game_name' => $order->game,
                    'amount_spent' => $order->amount,
                    'has_won' => $order->has_won,
                    'result' => $order->getGameResult(),
                    'prize_description' => $order->getPrizeDescription(),
                    'prize_amount' => $order->prize_amount,
                    'game_items' => $order->game_items,
                    'winning_prize' => $order->winning_prize,
                    'played_at' => $order->created_at->format('d/m/Y H:i:s'),
                    'played_at_human' => $order->dateHumanReadable
                ];
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'history' => $formattedHistory,
                    'pagination' => [
                        'current_page' => $history->currentPage(),
                        'last_page' => $history->lastPage(),
                        'per_page' => $history->perPage(),
                        'total' => $history->total(),
                        'from' => $history->firstItem(),
                        'to' => $history->lastItem()
                    ],
                    'statistics' => $this->getUserGameStatistics($userId)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor',
                'error' => config('app.debug') ? $e->getMessage() : 'Tente novamente mais tarde'
            ], 500);
        }
    }

    /**
     * Obter estatísticas das jogadas do usuário
     *
     * @param int $userId
     * @return array
     */
    private function getUserGameStatistics($userId)
    {
        $totalGames = Order::scratchCard()->byUser($userId)->count();
        $totalWins = Order::scratchCard()->byUser($userId)->winning()->count();
        $totalSpent = Order::scratchCard()->byUser($userId)->sum('amount');
        $totalWon = Order::scratchCard()->byUser($userId)->winning()->sum('prize_amount');

        $winRate = $totalGames > 0 ? round(($totalWins / $totalGames) * 100, 2) : 0;
        $profit = $totalWon - $totalSpent;

        return [
            'total_games' => $totalGames,
            'total_wins' => $totalWins,
            'total_losses' => $totalGames - $totalWins,
            'win_rate' => $winRate,
            'total_spent' => round($totalSpent, 2),
            'total_won' => round($totalWon, 2),
            'profit_loss' => round($profit, 2),
            'is_profitable' => $profit > 0
        ];
    }
}
