<?php

namespace App\Traits\Gateways;

use App\Models\AffiliateHistory;
use App\Models\Deposit;
use App\Models\Gateway;
use App\Models\Setting;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Models\Withdrawal;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use App\Notifications\NewDepositNotification;
use App\Helpers\Core as Helper;

trait BsPayTrait
{
    protected static string $uri;
    protected static string $clienteId;
    protected static string $clienteSecret;
    protected static string $statusCode;
    protected static string $errorBody;

    private static function generateCredentials()
    {
        $setting = Gateway::first();

        if (!empty($setting)) {
            self::$uri = $setting->bspay_uri;
            self::$clienteId = $setting->bspay_cliente_id;
            self::$clienteSecret = $setting->bspay_cliente_secret;

            return self::authentication();
        }

        return false;
    }

    private static function authentication()
    {
        $credentials = base64_encode(self::$clienteId . ":" . self::$clienteSecret);

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $credentials,
            'Content-Type' => 'application/x-www-form-urlencoded',
        ])->post(self::$uri . 'oauth/token', [
                    'grant_type' => 'client_credentials',
                ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['access_token'];
        } else {
            self::$statusCode = $response->status();
            self::$errorBody = $response->body();
            return false;
        }
    }

    public static function requestQrcodeBsPay($request)
    {
        if ($access_token = self::generateCredentials()) {
            $setting = Helper::getSetting();
            $rules = [
                'amount' => ['required', 'numeric', 'min:' . $setting->min_deposit, 'max:' . $setting->max_deposit],
                'cpf' => ['required', 'max:255'],
            ];

            dd('teste');

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $external_id = Str::uuid()->toString();

            $parameters = [
                'amount' => Helper::amountPrepare($request->amount),
                "external_id" => $external_id,
                "payerQuestion" => "Pagamento referente ao serviço/produto X",
                "postbackUrl" => url('/bspay/callback'),
                "payer" => [
                    "name" => auth('api')->user()->name,
                    "document" => '04621368095',//Helper::soNumero($request->cpf),
                    "email" => auth('api')->user()->email
                ]
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $access_token,
                'Content-Type' => 'application/json',
            ])->post(self::$uri . 'pix/qrcode', $parameters);

            if ($response->successful()) {
                $responseData = $response->json();

                self::generateTransaction($responseData['transactionId'], Helper::amountPrepare($request->amount));
                self::generateDeposit($responseData['transactionId'], Helper::amountPrepare($request->amount));

                return [
                    'status' => true,
                    'idTransaction' => $responseData['transactionId'],
                    'qrcode' => $responseData['qrcode']
                ];
            } else {
                self::$statusCode = $response->status();
                self::$errorBody = $response->body();
                return false;
            }
        }
    }

    private static function generateDeposit($idTransaction, $amount)
    {
        Deposit::create([
            'payment_id' => $idTransaction,
            'user_id' => auth('api')->user()->id,
            'amount' => $amount,
            'type' => 'pix',
            'status' => 0
        ]);
    }

    private static function generateTransaction($idTransaction, $amount)
    {
        $setting = Helper::getSetting();

        Transaction::create([
            'payment_id' => $idTransaction,
            'user_id' => auth('api')->user()->id,
            'payment_method' => 'pix',
            'price' => $amount,
            'currency' => $setting->currency_code,
            'status' => 0
        ]);
    }

    public static function consultStatusTransaction($request)
    {
        // Consultando status de pagamento
        $transaction = Transaction::where('payment_id', $request->idTransaction)->first();

        if (!$transaction) {
            return response()->json(['status' => 'NOT FOUND'], 404);
        }

        // Confirmando se o pagamento já foi processado
        if ($transaction->status === 1) {
            return response()->json(['status' => 'PAID']);
        }
        // Chamada para confirmar status do pagamento com o provedor
        if (self::isPaymentConfirmed($transaction->payment_id)) {
            self::finalizePayment($transaction->payment_id);
            return response()->json(['status' => 'PAID', 'message' => 'Pagamento realizado com sucesso!']);
        }

        return response()->json(['status' => 'NOPAID'], 400);
    }

    private static function isPaymentConfirmed($paymentId)
    {
        // Função para consultar status do pagamento com o provedor de pagamentos
        $access_token = self::generateCredentials();
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $access_token,
            'Content-Type' => 'application/json',
        ])->get(self::$uri . 'pix/transaction/' . $paymentId);

        if ($response->successful() && $response->json('status') === 'CONFIRMED') {
            return true;
        }

        return false;
    }

    public static function finalizePayment($idTransaction): bool
    {
        \Log::info('Iniciando finalização de pagamento', ['transaction_id' => $idTransaction]);

        $transaction = Transaction::where('payment_id', $idTransaction)
            ->where('status', 0)
            ->lockForUpdate()
            ->first();

        if (!$transaction) {
            \Log::warning('Transação não encontrada ou já processada', ['transaction_id' => $idTransaction]);
            return false;
        }

        $user = User::find($transaction->user_id);
        $wallet = Wallet::where('user_id', $transaction->user_id)->first();

        if (!$wallet) {
            \Log::error('Carteira do usuário não encontrada', ['user_id' => $transaction->user_id]);
            return false;
        }

        $setting = Helper::getSetting();

        if ($setting->disable_rollover) {
            $wallet->increment('balance_withdrawal', $transaction->price);
        } else {
            $wallet->increment('balance', $transaction->price);
        }

        if ($transaction->update(['status' => 1])) {
            $deposit = Deposit::where('payment_id', $idTransaction)->where('status', 0)->first();
            if (!empty($deposit)) {
                $deposit->update(['status' => 1]);

                foreach (User::where('role_id', 0)->get() as $admin) {
                    $admin->notify(new NewDepositNotification($user->name, $transaction->price));
                }

                return true;
            }
            return false;
        }

        return false;
    }
    public static function MakePayment(array $array)
    {
        if ($access_token = self::generateCredentials()) {

            $pixKey = $array['pix_key'];
            $pixType = self::FormatPixType($array['pix_type']);
            $amount = $array['amount'];
            $doc = \Helper::soNumero($array['document']);

            $parameters = [
                'amount' => floatval(\Helper::amountPrepare($amount)),
                "external_id" => $array['payment_id'],
                "payerQuestion" => "Fazendo pagamento.",
                "creditParty" => [
                    "key" => $pixKey,
                    "keyType" => $pixType,
                    "document" => $doc
                ]
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $access_token,
                'Content-Type' => 'application/json',
            ])->post(self::$uri . 'pix/payment', $parameters);

            if ($response->successful()) {
                $responseData = $response->json();
                \Log::debug($responseData);

                if ($responseData['status'] === 'PROCESSING') {
                    $withdrawal = Withdrawal::find($array['payment_id']);
                    if (!empty($withdrawal)) {
                        $deposit = Deposit::where('payment_id', $responseData['transactionId'])->first();
                        if (!empty($deposit)) {
                            $deposit->update(['status' => 1]);
                        }

                        $withdrawal->update([
                            'proof' => $responseData['transactionId'],
                            'status' => 1,
                        ]);
                        return true;
                    }
                    return false;
                }
                return false;
            }
            return false;
        }
        return false;
    }
    private static function FormatPixType($type)
    {
        switch ($type) {
            case 'email':
                return 'EMAIL';
            case 'document':
                return 'CPF';
            case 'document':
                return 'CNPJ';
            case 'randomKey':
                return 'ALEATORIA';
            case 'phoneNumber':
                return 'TELEFONE';
        }
    }
}
