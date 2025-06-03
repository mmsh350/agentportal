<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;

class DashboardController extends Controller
{
    public function index()
    {

        $status = auth()->user()->kyc_status;

        $kycPending = session('kyc_pending', false);

        if ($status == 'Pending') {
            $kycPending = true;
        }

        // //Check is user old balance has moved
        // $check =  auth()->user()->has_moved;


        // if (!$check) {
        //     //check if wallet existed and update the wallet
        //     $exist = User::where('id', auth()->user()->id)
        //         ->where('wallet_is_created', 1)
        //         ->exists();
        //     if ($exist) {
        //         Wallet::where('user_id', auth()->id())->update([
        //             'balance' => auth()->user()->old_balance,
        //             'deposit' => auth()->user()->old_balance,
        //         ]);

        //         // Update user record
        //         User::where('id', auth()->id())->update(['has_moved' => 1]);
        //     }
        // }

        return view('user.dashboard', [
            'kycPending' => $kycPending,
            'status' =>   $status
        ]);
    }
}
