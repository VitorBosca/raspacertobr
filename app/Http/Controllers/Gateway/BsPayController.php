<?php

namespace App\Http\Controllers\Gateway;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Models\Withdrawal;
use App\Traits\Affiliates\AffiliateHistoryTrait;
use App\Traits\Gateways\BsPayTrait;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;

class BsPayController extends Controller
{
    use BsPayTrait, AffiliateHistoryTrait;

    public function getQRCodePix(Request $request)
    {
        return self::requestQrcode($request);
    }

    public function callbackMethod(Request $request)
    {
        $data = $request->requestBody;

        if (isset($data['transactionId']) && $data['transactionType'] == 'RECEIVEPIX') {
            if ($data['status'] == "PAID") {
                if (self::finalizePayment($data['transactionId'])) {
                    return response()->json([], 200);
                }
            }
        }
    }

    public function consultStatusTransactionPix(Request $request)
    {
        return self::consultStatusTransaction($request);
    }

    public function withdrawalFromModal($id)
    {
        $withdrawal = Withdrawal::find($id);

        \Log::debug('Withdrawal details:', ['withdrawal' => $withdrawal]);

        if (!empty($withdrawal)) {
            $parm = [
                'pix_key'    => $withdrawal->pix_key,
                'pix_type'   => $withdrawal->pix_type,
                'amount'     => $withdrawal->amount,
                'document'   => $withdrawal->bank_info,
                'payment_id' => $withdrawal->id
            ];

            $resp = self::MakePayment($parm);

            if ($resp) {
                $withdrawal->update(['status' => 1]);

                Notification::make()
                    ->title('Saque solicitado')
                    ->body('Saque solicitado com sucesso')
                    ->success()
                    ->send();

                return back();
            } else {
                Notification::make()
                    ->title('Erro no saque')
                    ->body('Erro ao solicitar o saque')
                    ->danger()
                    ->send();

                return back();
            }
        }
    }

   public function cancelWithdrawalFromModal($id)
{
    $withdrawal = Withdrawal::find($id);

    if (!$withdrawal) {
        return back()->with('error', 'Saque não encontrado.');
    }

    if ($withdrawal->status != 0) {
        return back()->with('error', 'Saque já processado e não pode ser cancelado.');
    }

    // Atualiza o status do saque para cancelado
    $withdrawal->update(['status' => 3]);

    // Estorna o valor para a carteira do usuário
    $wallet = Wallet::where('user_id', $withdrawal->user_id)->first();

    if ($wallet) {
        $wallet->balance += $withdrawal->amount;
        $wallet->save();
    }

    Notification::make()
        ->title('Saque cancelado')
        ->body('Saque cancelado com sucesso e valor estornado.')
        ->success()
        ->send();

    return back();
}

}
