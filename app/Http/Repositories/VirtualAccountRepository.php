<?php

namespace App\Http\Repositories;

use Exception;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VirtualAccountRepository
{

    public function createVirtualAccount($loginUserId)
    {
        $accessToken =  $this->getAccessToken();

        $exist = User::where('id', $loginUserId)
            ->where('vwallet_is_created', 0)
            ->exists();
        if ($exist) {

            $userDetails = User::where('id', $loginUserId)->first();

            $customer_name = trim($userDetails->name);
            $refno = md5(uniqid($userDetails->email));

            $bankCode1 = env('BANKCODE1');
            $bankCode2 = env('BANKCODE2');

            try {

                $data = [
                    "accountReference"     => $refno,
                    "accountName"          => $userDetails->name,
                    "currencyCode"         => "NGN",
                    "contractCode"         => env('MONNIFYCONTRACT'),
                    "customerEmail"        => $userDetails->email,
                    "customerName"         => $userDetails->name,
                    "bvn"                  => '22192051259',
                    "getAllAvailableBanks" => false,
                    "preferredBanks"       => [$bankCode1, $bankCode2],
                ];

                Log::info($data);

                $url = env('MONNIFY_BASE_URL') . '/v2/bank-transfer/reserved-accounts';

                $headers = [
                    "Authorization: Bearer $accessToken",
                    'Content-Type: application/json',
                ];

                // Initialize cURL
                $ch = curl_init();

                // Set cURL options
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

                // Execute request
                $response = curl_exec($ch);

                Log::info($response);
                // Check for cURL errors
                if (curl_errno($ch)) {
                    throw new \Exception('cURL Error: ' . curl_error($ch));
                }

                // Close cURL session
                curl_close($ch);

                // Decode the JSON response to an associative array
                $retrieveData = json_decode($response, true);

                // $retrieveData = [
                //     "requestSuccessful" => true,
                //     "responseMessage" => "success",
                //     "responseCode" => "0",
                //     "responseBody" => [
                //         "contractCode" => "324191543790",
                //         "accountReference" => "e519a3b0568ed95135abfbc883152393",
                //         "accountName" => "HAS",
                //         "currencyCode" => "NGN",
                //         "customerEmail" => "sani.m38@gmail.com",
                //         "customerName" => "Test User",
                //         "accounts" => [
                //             [
                //                 "bankCode" => "232",
                //                 "bankName" => "Sterling bank",
                //                 "accountNumber" => "5271360263",
                //                 "accountName" => "HAS"
                //             ],
                //             [
                //                 "bankCode" => "50515",
                //                 "bankName" => "Moniepoint Microfinance Bank",
                //                 "accountNumber" => "6059140435",
                //                 "accountName" => "HAS"
                //             ]
                //         ],
                //         "collectionChannel" => "RESERVED_ACCOUNT",
                //         "reservationReference" => "17JVS876SSDTN6U07060",
                //         "reservedAccountType" => "GENERAL",
                //         "status" => "ACTIVE",
                //         "createdOn" => "2025-05-30 10:39:27.997",
                //         "incomeSplitConfig" => [],
                //         "bvn" => "22192051259",
                //         "restrictPaymentSource" => false,
                //         "metaData" => new \stdClass()
                //     ]
                // ];


                // Proceed only if the request was successful
                if (! $retrieveData['requestSuccessful']) {
                    throw new Exception('Request was not successful.');
                }

                $responseBody = $retrieveData['responseBody'];
                $account_name = 'MFY/Champion technology-' . $responseBody['accountName'];
                $accountReference = $responseBody['accountReference'];
                $accounts = $responseBody['accounts'];

                $insertData = [];

                // Iterate through accounts and prepare data for insertion
                foreach ($accounts as $account) {
                    if (in_array($account['bankCode'], [$bankCode1, $bankCode2])) {
                        $insertData[] = [
                            'user_id' => $loginUserId,
                            'accountReference' => $accountReference,
                            'accountNo' => $account['accountNumber'],
                            'accountName' => $account_name,
                            'bankName' => $account['bankName'],
                            'status' => '1',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }

                // Perform batch insert if there is data to insert
                if (! empty($insertData)) {
                    DB::table('virtual_accounts')->insert($insertData);
                }

                // Update user to indicate virtual account creation
                User::where('id', $loginUserId)->update(['vwallet_is_created' => 1]);
                return true;
            } catch (\Exception $e) {
                Log::error('Error creating virtual account for user ' . $loginUserId . ': ' . $e->getMessage());

                return response()->json(['error' => 'Failed to create virtual account.'], 500);
            }
        }
    }

    public function getAccessToken()
    {

        try {

            $AccessKey = env('MONNIFYAPI') . ':' . env('MONNIFYSECRET');
            $ApiKey = base64_encode($AccessKey);

            $url =  env('MONNIFY_BASE_URL') . '/v1/auth/login/';

            $headers = [
                'Accept: application/json, text/plain, */*',
                'Content-Type: application/json',
                "Authorization: Basic {$ApiKey}",
            ];

            // Initialize cURL
            $ch = curl_init();

            // Set cURL options
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            // Execute request
            $response = curl_exec($ch);

            // Check for cURL errors
            if (curl_errno($ch)) {
                throw new \Exception('cURL Error: ' . curl_error($ch));
            }

            // Close cURL session
            curl_close($ch);


            $response = json_decode($response, true);
            return $response['responseBody']['accessToken'];
        } catch (\Exception $e) {
            Log::error('Error Authentication Monnify ' . auth()->user()->id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while making the User BVN Verification');
        }
    }
}
