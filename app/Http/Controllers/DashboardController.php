<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {

        $status = auth()->user()->kyc_status;

        $kycPending = session('kyc_pending', false);

        if ($status == 'Pending') {
            $kycPending = true;
        }

        return view('user.dashboard', [
            'kycPending' => $kycPending,
            'status' =>   $status
        ]);
    }
}
