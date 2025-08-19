<?php

namespace App\Traits\Gateways;

use App\Models\AffiliateHistory;
use App\Models\Deposit;
use App\Models\GamesKey;
use App\Models\Gateway;
use App\Models\Setting;
use App\Models\PradaPayPayment;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Notifications\NewDepositNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Core as Helper;
use App\Models\AffiliateLogs;
use Exception;
use Illuminate\Support\Facades\Log;

trait PradapayTrait
{
    /**
     * @var $uri
     * @var $clienteId
     * @var $clienteSecret
     */
    protected static string $uri;
    protected static string $apikey;

    private static function modPrada($dividendo, $divisor) {
        return round($dividendo - (floor($dividendo / $divisor) * $divisor));
    }

    public static function cpfRandomPrada($mascara = "1") {
        $n1 = rand(0, 9);
        $n2 = rand(0, 9);
        $n3 = rand(0, 9);
        $n4 = rand(0, 9);
        $n5 = rand(0, 9);
        $n6 = rand(0, 9);
        $n7 = rand(0, 9);
        $n8 = rand(0, 9);
        $n9 = rand(0, 9);
        $d1 = $n9 * 2 + $n8 * 3 + $n7 * 4 + $n6 * 5 + $n5 * 6 + $n4 * 7 + $n3 * 8 + $n2 * 9 + $n1 * 10;
        $d1 = 11 - (self::modPrada($d1, 11) );
        if ($d1 >= 10) {
            $d1 = 0;
        }
        $d2 = $d1 * 2 + $n9 * 3 + $n8 * 4 + $n7 * 5 + $n6 * 6 + $n5 * 7 + $n4 * 8 + $n3 * 9 + $n2 * 10 + $n1 * 11;
        $d2 = 11 - (self::modPrada($d2, 11) );
        if ($d2 >= 10) {
            $d2 = 0;
        }
        $retorno = '';
        if ($mascara == 1) {
            $retorno = '' . $n1 . $n2 . $n3 . "." . $n4 . $n5 . $n6 . "." . $n7 . $n8 . $n9 . "-" . $d1 . $d2;
        } else {
            $retorno = '' . $n1 . $n2 . $n3 . $n4 . $n5 . $n6 . $n7 . $n8 . $n9 . $d1 . $d2;
        }
        return $retorno;
    }

    /**
     * Generate generateCredentialsPradaPay
     * Metodo para gerar credenciais
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     * @return void
     */
    private static function generateCredentialsPradaPay()
    {
        $setting = Gateway::first();
        if (!empty($setting)) {
            self::$uri = $setting->getAttributes()['pradapay_uri'];
            self::$apikey = trim($setting->getAttributes()['pradapay_apikey']); // Remove espaços em branco
        }
    }

    public static function requestQrcodePradaPay($request)
    {
        try {
            $setting = \Helper::getSetting();

            $rules = [
                'amount' => ['required', 'numeric', 'min:' . $setting->min_deposit, 'max:' . $setting->max_deposit],
                //'cpf'    => ['required', 'string', 'max:255'],
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            self::generateCredentialsPradaPay();
            $idUnico = uniqid();
            $response = Http::post(self::$uri . 'v1/gateway/', [
                "requestNumber" => $idUnico,
                "amount" => \Helper::amountPrepare($request->amount),
                "quantidade" => "1",
                "utm_content" => "",
                "utm_medium" => "",
                "utm_campaign" => "",
                "utm_source" => "",
                "utm_term" => "",
                "api-key" => self::$apikey,
                "postback" => url('/pradapay/callback'),
                "client" => [
                    "name" => auth('api')->user()->name,
                    "document" => self::cpfRandomPrada(),
                    "email" => auth('api')->user()->email,
                    "userPhone" => auth('api')->user()->phone
                ],
            ]);
            
            if ($response->successful()) {
                $responseData = $response->json();
                $transaction = self::generateTransactionPradaPay($responseData['idTransaction'], \Helper::amountPrepare($request->amount), $request->accept_bonus); /// gerando historico
                self::generateDepositPradaPay($responseData['idTransaction'], \Helper::amountPrepare($request->amount)); /// gerando deposito
                
                /// pra quem gerou o pix ****************************************************************
                //\Helper::CreateReport('Pix gerado', 'O usuário '. auth('api')->user()->name. ' gerou um PIX no valor de: R$'. $request->amount);
              
                return [
                    'status' => true,
                    'idTransaction' => $transaction->payment_id,
                    'qrcode' => $responseData['paymentCode']
                ];
            }

            return [
                'status' => false,
            ];
        } catch (Exception $e) {
            Log::info($e);
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    /**
     * Consult Status Transaction
     * Consultar o status da transação
     * @dev victormsalatiel - Corra de golpista, me chame no instagram - Modificado por Isaac Roque(+55 22 99704-1681)
     *
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public static function consultStatusTransactionPradaPay($request)
    {
        self::generateCredentialsPradaPay();
        
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post(self::$uri . 'v1/webhook/', [
            "idtransaction" => $request->idTransaction,
        ]);
        
        $data = $response->json();
        
        $transaction = Transaction::where('payment_id', $request->idTransaction)->first();
        
        if (isset($data['status']) && $data['status'] == "PAID_OUT" || isset($data['status']) && $data['status'] == "PAYMENT_ACCEPT") {
           self::finalizePaymentPradaPay($request->input("idTransaction"));
        }
        
        if ($transaction != null && $transaction->status) {
            return response()->json(['status' => 'PAID']);
        } else {
            return response()->json(['status' => false], 400);
        }
    }

    /**
     * @param $idTransaction
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     * @return bool
     */
    public static function finalizePaymentPradapay($idTransaction): bool
    {
        $transaction = Transaction::where('payment_id', $idTransaction)->where('status', 0)->first();
        $setting = \Helper::getSetting();

        if (!empty($transaction)) {
            $user = User::find($transaction->user_id);

            $wallet = Wallet::where('user_id', $transaction->user_id)->first();
            if (!empty($wallet)) {
                $setting = Setting::first();

                /// verifica se é o primeiro deposito, verifica as transações, somente se for transações concluidas
                $checkTransactions = Transaction::where('user_id', $transaction->user_id)
                    ->where('status', 1)
                    ->count();

                if ($checkTransactions == 0 || empty($checkTransactions)) {
                    /// pagar o bonus
                    $bonus = Helper::porcentagem_xn($setting->initial_bonus, $transaction->price);
                    $wallet->increment('balance_bonus', $bonus);
                    $wallet->update(['balance_bonus_rollover' => $bonus * $setting->rollover]);
                }

                /// rollover deposito
                $wallet->update(['balance_deposit_rollover' => $transaction->price * intval($setting->rollover_deposit)]);

                /// acumular bonus
                Helper::payBonusVip($wallet, $transaction->price);

                if ($wallet->increment('balance', $transaction->price)) {
                    if ($transaction->update(['status' => 1])) {
                        $deposit = Deposit::where('payment_id', $idTransaction)->where('status', 0)->first();
                        if (!empty($deposit)) {

                            /// fazer o deposito em cpa
                            $affHistoryCPA = AffiliateHistory::where('user_id', $user->id)
                                ->where('commission_type', 'cpa')
                                //->where('deposited', 1)
                                ->where('status', 0)
                                ->first();

                            if (!empty($affHistoryCPA)) {

                                /// verifcia se já pode receber o cpa
                                $sponsorCpa = User::find($user->inviter);
                                if (!empty($sponsorCpa)) {
                                    if ($affHistoryCPA->deposited_amount >= $sponsorCpa->affiliate_baseline || $deposit->amount >= $sponsorCpa->affiliate_baseline) {
                                        $walletCpa = Wallet::where('user_id', $affHistoryCPA->inviter)->first();
                                        if (!empty($walletCpa)) {

                                            /// paga o valor de CPA
                                            $walletCpa->increment('refer_rewards', $sponsorCpa->affiliate_cpa); /// coloca a comissão
                                            $affHistoryCPA->update(['status' => 1, 'commission_paid' => $sponsorCpa->affiliate_cpa]); /// desativa cpa

                                        }
                                    } else {
                                        $affHistoryCPA->update(['deposited_amount' => $transaction->price]);
                                    }
                                }
                            }

                            if ($deposit->update(['status' => 1])) {
                                $admins = User::where('role_id', 0)->get();
                                foreach ($admins as $admin) {
                                    $admin->notify(new NewDepositNotification($user->name, $transaction->price));
                                }

                                return true;
                            }
                            return false;
                        }
                        return false;
                    }
                }

                return false;
            }
            return false;
        }
        
        // Log do erro se a resposta não for bem-sucedida
        /*\Log::error('PradaPay API Request failed:', [
            'status' => $response->status(),
            'body' => $response->body(),
            'request_data' => [
                'api-key' => '***hidden***',
                'name' => $user->name,
                'cpf' => $user->cpf,
                'keypix' => $array['pix_key'],
                'amount' => $array['amount']
            ]
        ]);*/
        
        return false;
    }

    /**
     * @param $idTransaction
     * @param $amount
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     * @return void
     */
    private static function generateDepositPradaPay($idTransaction, $amount)
    {
        $userId = auth('api')->user()->id;
        $wallet = Wallet::where('user_id', $userId)->first();

        Deposit::create([
            'payment_id' => $idTransaction,
            'user_id'   => $userId,
            'amount'    => $amount,
            'type'      => 'pix',
            'currency'  => $wallet->currency,
            'symbol'    => $wallet->symbol,
            'status'    => 0
        ]);
    }

    /**
     * @param $idTransaction
     * @param $amount
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     * @return void
     */
    private static function generateTransactionPradaPay($idTransaction, $amount, $accept_bonus)
    {
        $setting = \Helper::getSetting();

        return Transaction::create([
            'payment_id' => $idTransaction,
            'user_id' => auth('api')->user()->id,
            'payment_method' => 'pix',
            'price' => $amount,
            'currency' => $setting->currency_code,
            'accept_bonus' => $accept_bonus,
            'status' => 0,
        ]);
    }

    /**
     * @param $request
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     * @return \Illuminate\Http\JsonResponse|void
     */
    public static function pixCashOutPradaPay(array $array): bool
    {
        \Log::info("PradaPay: Iniciando pixCashOutPradaPay", $array);
        
        self::generateCredentialsPradaPay();
        \Log::info("PradaPay: Credenciais geradas", [
            'uri' => self::$uri,
            'apikey_length' => strlen(self::$apikey),
            'apikey_starts_with' => substr(self::$apikey, 0, 3) . '...' // Mostra apenas os primeiros 3 caracteres
        ]);
        
        $user = User::find($array['user_id']);
        
        // Verificar se o usuário existe e tem CPF
        if (empty($user)) {
            \Log::error('PradaPay: Usuário não encontrado', ['user_id' => $array['user_id']]);
            return false;
        }
        
        /*if (empty($user->cpf)) {
            \Log::error('PradaPay: Usuário sem CPF cadastrado', [
                'user_id' => $user->id,
                'user_name' => $user->name
            ]);
            return false;
        }*/

        \Log::info("PradaPay: Dados do usuário validados", [
            'user_id' => $user->id,
            'name' => $user->name,
            'cpf' => $array['pix_key'],
            'pix_key' => $array['pix_key'],
            'amount' => $array['amount']
        ]);

        // Remove formatação do CPF e chave PIX
        $cpfLimpo = preg_replace('/[^0-9]/', '', $array['pix_key']);
        $pixKeyLimpa = preg_replace('/[^0-9]/', '', $array['pix_key']);
        
        $requestData = [
            'api-key' => self::$apikey,
            "name" => $user->name,
            "cpf" => $cpfLimpo,
            "keypix" => $pixKeyLimpa,
            "amount" => (float) $array['amount']
        ];
        
        \Log::info("PradaPay: Enviando requisição para API", [
            'url' => self::$uri . 'c1/cashout/',
            'data' => array_merge($requestData, ['api-key' => '***hidden***'])
        ]);

        try {
            $response = Http::timeout(30)->post(self::$uri . 'c1/cashout/', $requestData);
            
            \Log::info('PradaPay: Resposta HTTP recebida', [
                'status' => $response->status(),
                'successful' => $response->successful(),
                'body' => $response->body(),
                'headers' => $response->headers()
            ]);
            
        } catch (\Exception $e) {
            \Log::error('PradaPay: Erro na requisição HTTP', [
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return false;
        }

        if ($response->successful()) {
            $responseData = $response->json();
            
            // Log da resposta para debug
            \Log::info('PradaPay API Response:', $responseData);

            // Verificar se a resposta indica sucesso (estrutura real da API)
            if (isset($responseData['status']) && $responseData['status'] == 'success') {
                
                // Verificar se K-200 realmente significa sucesso
                if (isset($responseData['message']) && $responseData['message'] == 'K-200') {
                    \Log::warning('🚨 PradaPay: PROBLEMA DETECTADO! K-200 não significa pagamento processado!', [
                        'problema' => 'K-200 indica apenas que a requisição foi aceita, NÃO que o saque foi processado',
                        'evidencia' => 'Webhook retorna: No matching record found',
                        'causa_provavel' => 'Conta PradaPay SEM permissão para processar saques',
                        'solucao' => 'ENTRAR EM CONTATO COM PRADAPAY para habilitar funcionalidade de SAQUE',
                        'nota' => 'Depósitos funcionam normalmente, apenas saques têm problema'
                    ]);
                    
                    // Tentar consultar status após 2 segundos
                    sleep(2);
                    $paymentId = 'PP_' . time() . '_' . $array['pradapayment_id'];
                    
                    // Consultar status via webhook
                    try {
                        $statusResponse = Http::timeout(10)->post('https://api.pradapay.com/v1/webhook/', [
                            'idtransaction' => $paymentId
                        ]);
                        
                        if ($statusResponse->successful()) {
                            $statusData = $statusResponse->json();
                            \Log::info('PradaPay: Status consultado', ['payment_id' => $paymentId, 'status_response' => $statusData]);
                            
                            if (isset($statusData['status']) && in_array($statusData['status'], ['PAID_OUT', 'COMPLETED'])) {
                                \Log::info('PradaPay: Pagamento confirmado via webhook');
                            } else {
                                \Log::warning('PradaPay: Pagamento não confirmado', $statusData);
                            }
                        } else {
                            \Log::error('PradaPay: Erro ao consultar status', [
                                'status' => $statusResponse->status(),
                                'body' => $statusResponse->body()
                            ]);
                        }
                    } catch (\Exception $e) {
                        \Log::error('PradaPay: Erro na consulta de status', ['error' => $e->getMessage()]);
                    }
                }
                $pradaPayPayment = PradaPayPayment::lockForUpdate()->find($array['pradapayment_id']);
                if (!empty($pradaPayPayment)) {
                    // PradaPay não retorna idTransaction na resposta de cashout, então usamos um ID baseado no timestamp
                    $paymentId = 'PP_' . time() . '_' . $array['pradapayment_id'];
                    if ($pradaPayPayment->update(['status' => 1, 'payment_id' => $paymentId])) {
                        \Log::info('PradaPay cashout successful:', [
                            'pradapayment_id' => $array['pradapayment_id'],
                            'payment_id' => $paymentId,
                            'amount' => $array['amount']
                        ]);
                        return true;
                    }
                    return false;
                }
                return false;
            }
            
            // Se o status não for success, logar o erro
            \Log::error('PradaPay API Response not successful:', [
                'response' => $responseData,
                'status' => $responseData['status'] ?? 'unknown',
                'message' => $responseData['message'] ?? 'no message'
            ]);
            
            return false;
        }
        
        // Log do erro se a resposta não for bem-sucedida
        \Log::error('PradaPay: Requisição HTTP não foi bem-sucedida', [
            'status' => $response->status(),
            'body' => $response->body(),
            'headers' => $response->headers(),
            'request_data' => array_merge($requestData, ['api-key' => '***hidden***'])
        ]);
        
        return false;
    }
}
