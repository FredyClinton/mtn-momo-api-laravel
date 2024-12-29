<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestToPay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Exception;

class MtnMomoController extends Controller
{
    protected string $secondaryKey = '36c28f395e404e47b72c2fa12113b53e';
    protected string $referenceId = '8fa63fc7-d78a-43ec-a956-5ffd897ad635';
    protected string $apiKey = '0983da937d7d40aeb4a0ccca5e842eca';

    /**
     * Generate access token
     *
     * @return string
     * @throws Exception
     */
    public function createAccessToken(): string
    {
        $url = 'https://sandbox.momodeveloper.mtn.com/collection/token/';

        $headers = [
            'Authorization' => 'Basic ' . base64_encode($this->referenceId . ':' . $this->apiKey),
            'Ocp-Apim-Subscription-Key' => $this->secondaryKey,
        ];

        $response = Http::withHeaders($headers)->post($url);

        if ($response->failed()) {
            throw new Exception('Failed to generate access token: ' . $response->body());
        }

        return $response->json('access_token');
    }

    /**
     * Handle the request-to-pay process
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function requestToPay(RequestToPay $requestToPay)
    {

        try {
            $accessToken = $this->createAccessToken();
            $referenceId = Str::uuid()->toString();

            $url = 'https://sandbox.momodeveloper.mtn.com/collection/v1_0/requesttopay';
            $body = [
                'amount' => $requestToPay->amount,
                'currency' => 'EUR',
                'externalId' => (string)rand(10000000, 99999999),
                'payer' => [
                    'partyIdType' => 'MSISDN',
                    'partyId' => $requestToPay->phone,
                ],
                'payerMessage' => 'Taillor-mate Laravel MTN Payment',
                'payeeNote' => 'Thank you for using Taillor mate Softwares MTN Payment',
            ];

            $headers = [
                'Authorization' => 'Bearer ' . $accessToken,
                'X-Reference-Id' => $referenceId,
                'X-Target-Environment' => 'sandbox',
                'Ocp-Apim-Subscription-Key' => $this->secondaryKey,
                'Content-Type' => 'application/json',
            ];

            $response = Http::withHeaders($headers)->post($url, $body);

            if ($response->failed()) {
                return response()->json([
                    'message' => 'Failed to process request-to-pay',
                    'details' => $response->json(),
                ], $response->status());
            }

            $status = $this->checkRequestToPayStatus($referenceId);


            return  response()->json([
                'message' => 'Request-to-pay sent successfully',
                'reference_id' => $referenceId,
                'status_code' => $response->status(),
                'details' => $status,

            ], 202);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred during the request-to-pay process',
                'details' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Check the status of a request-to-pay transaction
     *
     * @param string $referenceId
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkRequestToPayStatus(string $referenceId)
    {
        try {
            $accessToken = $this->createAccessToken();

            $url = "https://sandbox.momodeveloper.mtn.com/collection/v1_0/requesttopay/" . $referenceId;
            $headers = [
                'Authorization' => 'Bearer ' . $accessToken,
                'X-Target-Environment' => 'sandbox',
                'Ocp-Apim-Subscription-Key' => $this->secondaryKey,
            ];

            $response = Http::withHeaders($headers)->get($url);

            if ($response->failed()) {
                return response()->json([
                    'message' => 'Failed to fetch request-to-pay status',
                    'details' => $response->json(),
                ], $response->status());
            }

            return response()->json([
                'message' => 'Request-to-pay status fetched successfully',
                'details' => $response->json(),
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while checking the request-to-pay status',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}
