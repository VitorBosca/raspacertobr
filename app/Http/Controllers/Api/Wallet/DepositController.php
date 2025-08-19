<?php

namespace App\Http\Controllers\Api\Wallet;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Traits\Gateways\BsPayTrait;
use App\Traits\Gateways\NomadTrait;
use App\Traits\Gateways\SharkPayTrait;
use App\Traits\Gateways\PradaPayTrait;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    Use SharkPayTrait, BsPayTrait, NomadTrait, PradaPayTrait;

    public function submitPayment(Request $request)
    {
        switch ($request->gateway) {
            case 'bspay':
                return self::requestQrcodeBsPay($request);
            case 'sharkpay':
                return self::requestQrcodeSharkPay($request);
            case 'nomad':
                return self::requestQrcodeNomad($request);
            case 'pradapay':
                return self::requestQrcodePradaPay($request);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function consultStatusTransactionPix(Request $request)
    {
        switch ($request->input("gateway")) {
            case 'suitpay':
                return self::consultStatusTransaction($request);
            case 'pradapay':
                return self::consultStatusTransactionPradaPay($request);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $deposits = Deposit::whereUserId(auth('api')->id())->paginate();
        return response()->json(['deposits' => $deposits], 200);
    }

}
