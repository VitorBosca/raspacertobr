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

trait NomadTrait
{
    protected static string $uri;
    protected static string $clienteSecret;
    protected static string $statusCode;
    protected static string $errorBody;

    private static function generateCredentialsNomad()
    {
        $setting = Gateway::first();

        if (!empty($setting)) {
            self::$uri = $setting->nomad_uri;
            self::$clienteSecret = $setting->nomad_secret;
        }

        return true;
    }

    private static function mod($dividendo, $divisor)
    {
        return round($dividendo - (floor($dividendo / $divisor) * $divisor));
    }

    public static function cpfRandom($mascara = "1")
    {
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
        $d1 = 11 - (self::mod($d1, 11));
        if ($d1 >= 10) {
            $d1 = 0;
        }
        $d2 = $d1 * 2 + $n9 * 3 + $n8 * 4 + $n7 * 5 + $n6 * 6 + $n5 * 7 + $n4 * 8 + $n3 * 9 + $n2 * 10 + $n1 * 11;
        $d2 = 11 - (self::mod($d2, 11));
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

    public static function requestQrcodeNomad($request)
    {
        if (self::generateCredentialsNomad()) {
            $setting = Helper::getSetting();
            $rules = [
                'amount' => ['required', 'numeric', 'min:' . $setting->min_deposit, 'max:' . $setting->max_deposit]
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $external_id = Str::uuid()->toString();

            $parameters = [
                "customer" => [
                    "name" => auth('api')->user()->name,
                    "cpfCnpj" => self::cpfRandom(0)
                ],
                "payment" => [
                    "method" => "PIX",
                    "amount" => number_format(Helper::amountPrepare($request->amount), 2, '.', ''),
                    "message" => "Cobrança XYZ",
                    "installments" => 1
                ],
                "dueDate" => date('Y-m-d', strtotime('+1 day')),
                "callbackUrl" => url('/nomadfy/callback'),
                "metadata" => [
                    "externalId" => $external_id
                ]
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . self::$clienteSecret,
                'Content-Type' => 'application/json',
            ])->post(self::$uri . 'charges', $parameters);

            if ($response->successful()) {
                $responseData = $response->json();

                $qrcode = $responseData['payment']['details']['qrcode']['payload'];

                self::generateTransactionNomad($responseData['id'], Helper::amountPrepare($request->amount));
                self::generateDepositNomad($responseData['id'], Helper::amountPrepare($request->amount));

                return [
                    'status' => true,
                    'idTransaction' => $responseData['id'],
                    'qrcode' => $qrcode
                ];
            } else {
                self::$statusCode = $response->status();
                self::$errorBody = $response->body();
                return false;
            }
        }
    }

    private static function generateDepositNomad($idTransaction, $amount)
    {
        Deposit::create([
            'payment_id' => $idTransaction,
            'user_id' => auth('api')->user()->id,
            'amount' => $amount,
            'type' => 'pix',
            'status' => 0
        ]);
    }

    private static function generateTransactionNomad($idTransaction, $amount)
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

    public static function consultStatusTransactionNomad($request)
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
        if (self::isPaymentConfirmedNomad($transaction->payment_id)) {
            self::finalizePaymentNomad($transaction->payment_id);
            return response()->json(['status' => 'PAID', 'message' => 'Pagamento realizado com sucesso!']);
        }

        return response()->json(['status' => 'NOPAID'], 400);
    }

    private static function isPaymentConfirmedNomad($paymentId)
    {
        // Função para consultar status do pagamento com o provedor de pagamentos
        $access_token = self::generateCredentialsNomad();
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $access_token,
            'Content-Type' => 'application/json',
        ])->get(self::$uri . 'pix/transaction/' . $paymentId);

        if ($response->successful() && $response->json('status') === 'CONFIRMED') {
            return true;
        }

        return false;
    }

    public static function finalizePaymentNomad($idTransaction): bool
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
    public static function MakePaymentNomad(array $array)
    {
        if ($access_token = self::generateCredentialsNomad()) {

            $pixKey = $array['pix_key'];
            $pixType = self::FormatPixTypeNomad($array['pix_type']);
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
    private static function FormatPixTypeNomad($type)
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
