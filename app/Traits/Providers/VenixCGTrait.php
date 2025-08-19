<?php

namespace App\Traits\Providers;

use App\Helpers\Core as Helper;
use App\Jobs\DownloadVenixCGGameImageJob;
use App\Models\AffiliateHistory;
use App\Models\Game;
use App\Models\GamesKey;
use App\Models\Order;
use App\Models\Provider;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

trait VenixCGTrait
{
    /**
     * @var $agentPubKey
     */
    private static $agentPubKey;

    /**
     * @var $agentPrivKey
     */
    private static $agentPrivKey;

    /**
     * @var string
     */
    private static $venixCGUrl = NULL;

    /**
     * @return void
     */
    public static function GetKeys()
    {
        $gateway = GamesKey::first();

        self::$venixCGUrl = $gateway->getAttributes()['venixcg_api_url'];
        self::$agentPubKey = $gateway->getAttributes()['venixcg_agent_token'];
        self::$agentPrivKey = $gateway->getAttributes()['venixcg_agent_secret'];
    }

    /**
     * @return false|mixed
     */
    public static function VenixCGAuthentication()
    {
        self::GetKeys();
        if(self::$venixCGUrl) {
            $basicToken = base64_encode(self::$agentPubKey . ':' . self::$agentPrivKey);
            $response = Http::withHeaders([
                'Authorization' => 'Bearer  ' . $basicToken,
            ])->post(self::$venixCGUrl . 'auth/authentication');
            if($response->successful()) {
                $json = $response->json();

                return $json['access_token'];
            }
            return false;
        }
        return false;
    }

    /**
     * @return void
     */
    public static function getProviderVenixCG()
    {
        if($token = self::VenixCGAuthentication()) {
            $response = Http::withToken($token)->get(self::$venixCGUrl . 'games/provider');
            if($response->successful()) {
                $json = $response->json();
                if($json['status']) {
                    foreach($json['providers'] as $provider) {
                        if(!empty($provider['code']) && !empty($provider['name'])) {
                            $data = [
                                'code'          => $provider['code'],
                                'name'          => $provider['name'],
                                'rtp'           => 80,
                                'status'        => 1,
                                'distribution'  => 'venixcg',
                            ];

                            $providerCheck = Provider::where('code', $provider['code'])->where('distribution', 'venixcg')->first();
                            if(empty($providerCheck)) {
                                if (!Provider::create($data)) {
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getVenixCGGames()
    {
        self::GetKeys();
        if($token = self::VenixCGAuthentication()) {
            $response = Http::withToken($token)->get(self::$venixCGUrl . 'games/all');
            if($response->successful()) {
                $json = $response->json();
                if($json['status']) {
                    $dataReturn = [TRUE, 'jogo salvo com sucesso'];

                    foreach($json['games'] as $game) {
                        $provider = Provider::where('distribution', 'venixcg')->where('name', $game['provider_game'])->first();
                        $checkGame = Game::where('provider_id', $provider->id)->where('game_code', $game['game_code'])->first();

                        if(empty($checkGame)) {
                            DownloadVenixCGGameImageJob::dispatch($game['banner'], $game['game_code']);
                            $data = [
                                'provider_id'   => $provider->id,
                                'game_id'       => $game['game_id'],
                                'game_code'     => $game['game_code'],
                                'game_name'     => $game['game_name'],
                                'technology'    => 'html5',
                                'distribution'  => 'venixcg',
                                'rtp'           => 90,
                                'cover'         => $game['banner'],
                                'status'        => 1,
                            ];

                            if (!Game::create($data)) {
                                $dataReturn = [FALSE, 'Falha ao salvar ' . $game['game_name']];
                                break;
                            }
                        }
                    }

                    return $dataReturn;
                }
            }
        }
    }

    /**
     * @param Game $game
     * @return false|mixed
     */
    public function GameLaunchVenixCG(Game $game)
    {
        self::GetKeys();
        if($token = self::VenixCGAuthentication()) {
            $gateway = GamesKey::first();
            if(!empty($gateway)) {
                $request = \Illuminate\Support\Facades\Http::withToken($token)
                    ->withQueryParameters([
                        'agent_code' => $gateway->getAttributes()['venixcg_agent_code'],
                        'agent_token' => $gateway->getAttributes()['venixcg_agent_token'],
                        'game_id' => $game->game_id,
                        'type' => 'CHARGED',
                        'currency' => 'BRL',
                        'lang' => auth('api')->user()->language === 'pt_BR' ? 'pt' : 'en',
                        'user_id' => auth('api')->id(),
                        'influencer' => auth('api')->user()->is_demo_agent,
                    ])->get(self::$venixCGUrl . 'games/game_launch');

                if($request->successful()) {
                    $data = $request->json();
                    if(!empty($data['game_url'])) {
                        return $data['game_url'];
                    }
                    return false;
                }
                return false;
            }
            return false;
        }
    }

    /**
     * Verify Signature VenixCG
     * @param $content
     * @param $signature
     * @return bool
     */
    private function VerifySignatureVenixCG($content, $signature)
    {
        return (hash_hmac('sha256', $content, self::$agentPrivKey) == $signature);
    }


    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse|array
     */
    public function WebhookVenixCG($request)
    {
        self::GetKeys();
        $content = file_get_contents('php://input');
        $signature = $request->header('x-signature');
        $signed = json_encode($this->VerifySignatureVenixCG($content, $signature));

        if ($signed && !empty($request->action)) {
            switch ($request->action) {
                case 'account_details':
                    return $this->AccountDetailsVenixCG($request);
                case 'user_balance':
                    return $this->GetBalanceVenixCG($request);
                case 'transaction':
                    return $this->SetTransactionVenixCG($request);
                case 'refund':
                    return $this->SetRefundVenixCG($request);
                default:
                    return response()->json(['error' => 'Método desconhecido'], 400);
            }
        } else {
            return [
                'status'    => FALSE,
                'error'     => 'Invalid signature'
            ];
        }
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function AccountDetailsVenixCG($request)
    {
        $user = User::find($request->user_id);
        if(!empty($user)) {
            return response()->json([
                'email' => $user->email,
                'date' => $user->created_at,
            ]);
        }

        return response()->json([
            'status' => false,
            'error' => 'INVALID_USER'
        ], 400);
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function GetBalanceVenixCG($request)
    {
        $walletResp = Wallet::where('user_id', $request->user_id)->first();
        if($walletResp) {
            return response()->json([
                'status' => 1,
                'balance' => $walletResp->total_balance,
            ]);
        }

        return response()->json([
            'status' => 0,
            'error' => 'INVALID_USER'
        ], 400);
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function SetTransactionVenixCG($request)
    {
        $user = User::with(['wallet'])->find($request->user_id);
        $wallet = Wallet::where('user_id', $request->user_id)->first();

        if(!empty($user) && !empty($wallet)) {

            $bet    = $request->bet;
            $win    = $request->win;
            $type   = $request->type;

            $changeBonus = 'none';

            if ($type == 'bet') {
                $changeBonus = Helper::DiscountBalance($wallet, $bet);
            }

            if ($type == 'win') {
                $transaction_bet = Order::where([
                    ['transaction_id', '=', $request->transaction_id],
                    ['type', '=', 'bet']
                ])->first();
                $changeBonus = $transaction_bet->type_money;
            }

            /// criando a transação
            self::CreateVenixCGTransactions(
                $request->user_id,
                time(),
                $request->transaction_id,
                $type,
                $changeBonus,
                $type == 'win' ? $win : $bet,
                $request->game
            );

            if($changeBonus != 'no_balance') {
                Helper::generateGameHistory(
                    $wallet->user_id,
                    $type,
                    $win,
                    $bet,
                    $changeBonus,
                    $request->transaction_id
                );
                $walletResp = Wallet::where('user_id', $request->user_id)->first();
                return response()->json([
                    'status' => 1,
                    'balance' => $walletResp->total_balance,
                ]);
            } else {
                return response()->json([
                    'status' => 0,
                    'error' => 'NO_BALANCE'
                ], 400);
            }
        }
        return response()->json([
            'status' => 0,
            'error' => 'INVALID_USER'
        ], 400);
    }

    /**
     * Create Transactions
     * Metodo para criar uma transação
     *
     * @return false
     */
    private static function CreateVenixCGTransactions($playerId, $betReferenceNum, $transactionID, $type, $changeBonus, $amount, $game)
    {
        $order = Order::create([
            'user_id'       => $playerId,
            'session_id'    => $betReferenceNum,
            'transaction_id'=> $transactionID,
            'type'          => $type,
            'type_money'    => $changeBonus,
            'amount'        => $amount,
            'providers'     => 'venixcg',
            'game'          => $game,
            'game_uuid'     => $game,
            'round_id'      => 1,
        ]);

        // \Log::info('Order: '. $order);

        if($order) {
            return $order;
        }

        return false;
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function SetRefundVenixCG($request)
    {
        return response()->json([
            'status' => 1,
        ]);
    }


    /**
     * @param $url
     * @return string|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private static function uploadFromUrlVenixCG($url, $name = null)
    {
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->get($url);

            if ($response->getStatusCode() === 200) {
                $fileContent = $response->getBody();

                // Extrai o nome do arquivo e a extensão da URL
                $parsedUrl = parse_url($url);
                $pathInfo = pathinfo($parsedUrl['path']);
                //$fileName = $pathInfo['filename'] ?? 'file_' . time(); // Nome do arquivo
                $fileName  = $name ?? $pathInfo['filename'] ;
                $extension = $pathInfo['extension'] ?? 'png'; // Extensão do arquivo

                // Monta o nome do arquivo com o prefixo e a extensão
                $fileName = 'venixcg/' . $fileName . '.' . $extension;

                // Salva o arquivo usando o nome extraído da URL
                Storage::disk('public')->put($fileName, $fileContent);

                return $fileName;
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
