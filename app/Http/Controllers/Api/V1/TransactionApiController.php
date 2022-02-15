<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransactionRequest;
use App\Services\TransactionService;
use App\User;
use Exception;
use Illuminate\Http\Response;

class TransactionApiController extends Controller
{
    private $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function store(StoreTransactionRequest $request)
    {
        try {

            $this->transactionService->newUserTransaction($request->all());

            return response()->json([
                'success' => true,
                'message' => "success",
            ], Response::HTTP_OK);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
