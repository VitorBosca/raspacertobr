<?php

namespace App\Http\Controllers\Gateway;

use App\Http\Controllers\Controller;
use App\Models\AffiliateWithdraw;
use App\Models\PradaPayPayment;
use App\Models\Wallet;
use App\Models\Withdrawal;
use App\Traits\Gateways\PradapayTrait;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PradaPayController extends Controller
{
    use PradapayTrait;

    /*** @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function callbackMethodPayment(Request $request)
    {
        $data = $request->all();
        \DB::table('debug')->insert(['text' => json_encode($request->all())]);

        return response()->json([], 200);
    }

    /*** @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function callbackMethod(Request $request)
    {
        $data = $request->json()->all();
        Log::info($data['status']);
        if (isset($data['idTransaction']) && $data['paymentMethod'] == 'PIX') {
            if ($data['status'] == "PAID_OUT" || $data['status'] == "PAYMENT_ACCEPT" || $data['status'] == "paid") {
                if (self::finalizePaymentPradaPay($data['idTransaction'])) {
                    return response()->json([], 200);
                }
            }
        }
    }

    /*** @param Request $request
     * @return null
     */
    public function getQRCodePix(Request $request)
    {
        return self::requestQrcodePradaPay($request);
    }

    /*** Show the form for creating a new resource.
     */
    public function consultStatusTransactionPix(Request $request)
    {
        return self::consultStatusTransactionPradaPay($request);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function confirmWithdrawalUser($id)
    {
        $withdrawal = Withdrawal::find($id);
        if(!empty($withdrawal)) {
            $pradapayment = PradaPayPayment::create([
                'withdrawal_id' => $withdrawal->id,
                'user_id'       => $withdrawal->user_id,
                'pix_key'       => $withdrawal->pix_key,
                'pix_type'      => $withdrawal->pix_type,
                'amount'        => $withdrawal->amount,
                'observation'   => 'pradapay',
            ]);

            if($pradapayment) {
                $parm = [
                    'user_id'           => $withdrawal->user_id,
                    'pix_key'           => $withdrawal->pix_key,
                    'pix_type'          => $withdrawal->pix_type,
                    'amount'            => $withdrawal->amount,
                    'pradapayment_id'    => $pradapayment->id
                ];

                $resp = self::pixCashOutPradaPay($parm);
                

                if($resp) {
                    $withdrawal->update(['status' => 1]);
                    Notification::make()
                        ->title('Saque solicitado')
                        ->body('Saque solicitado com sucesso')
                        ->success()
                        ->send();

                    return back();
                }else{
                    Notification::make()
                        ->title('Erro no saque')
                        ->body('Erro ao solicitar o saque')
                        ->danger()
                        ->send();

                    return back();
                }
            }
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function confirmWithdrawalAffiliate($id)
    {
        $withdrawal = AffiliateWithdraw::find($id);

        if(!empty($withdrawal)) {
            $pradapayment = PradaPayPayment::create([
                'withdrawal_id' => $withdrawal->id,
                'user_id'       => $withdrawal->user_id,
                'pix_key'       => $withdrawal->pix_key,
                'pix_type'      => $withdrawal->pix_type,
                'amount'        => $withdrawal->amount,
                'observation'   => 'suitpay',
            ]);

            if($pradapayment) {
                $parm = [
                    'user_id'           => $withdrawal->user_id,
                    'pix_key'           => $withdrawal->pix_key,
                    'pix_type'          => $withdrawal->pix_type,
                    'amount'            => $withdrawal->amount,
                    'pradapayment_id'    => $pradapayment->id
                ];

                $resp = self::pixCashOutPradaPay($parm);

                if($resp) {
                    $withdrawal->update(['status' => 1]);
                    Notification::make()
                        ->title('Saque solicitado')
                        ->body('Saque solicitado com sucesso')
                        ->success()
                        ->send();

                    return back();
                }else{
                    Notification::make()
                        ->title('Erro no saque')
                        ->body('Erro ao solicitar o saque')
                        ->danger()
                        ->send();

                    return back();
                }
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function withdrawalFromModalPradaPay($id, $action)
    {
        if(auth()->user()->hasRole('admin')) {
            if($action == 'user') {
                return $this->confirmWithdrawalUser($id);
            }
            
            if($action == 'affiliate') {
                return $this->confirmWithdrawalAffiliate($id);
            }
        }
    }

    /**
     * Cancel Withdrawal
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancelWithdrawalFromModalPradaPay($id, $action)
    {
        if(auth()->user()->hasRole('admin')) {
            if($action == 'user') {
                return $this->cancelWithdrawalUser($id);
            }
            
            if($action == 'affiliate') {
                return $this->cancelWithdrawalAffiliate($id);
            }
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    private function cancelWithdrawalAffiliate($id)
    {
        $withdrawal = AffiliateWithdraw::find($id);
        if(!empty($withdrawal)) {
            $wallet = Wallet::where('user_id', $withdrawal->user_id)
                ->where('currency', $withdrawal->currency)
                ->first();
                
            if(!empty($wallet)) {
                $wallet->increment('refer_rewards', $withdrawal->amount);
                
                $withdrawal->update(['status' => 2]);
                Notification::make()
                    ->title('Saque cancelado')
                    ->body('Saque cancelado com sucesso')
                    ->success()
                    ->send();
                    
                return back();
            }
            return back();
        }
        return back();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    private function cancelWithdrawalUser($id)
    {
        $withdrawal = Withdrawal::find($id);
        if(!empty($withdrawal)) {
            $wallet = Wallet::where('user_id', $withdrawal->user_id)
                ->where('currency', $withdrawal->currency)
                ->first();
                
            if(!empty($wallet)) {
                $wallet->increment('balance_withdrawal', $withdrawal->amount);
                
                $withdrawal->update(['status' => 2]);
                Notification::make()
                    ->title('Saque cancelado')
                    ->body('Saque cancelado com sucesso')
                    ->success()
                    ->send();
                    
                return back();
            }
            return back();
        }
        return back();
    }
}
