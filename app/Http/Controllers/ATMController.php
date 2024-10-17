<?php

namespace App\Http\Controllers;

use App\UseCases\WithdrawUseCase;
use App\UseCases\GetTransactionHistoryUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ATMController extends Controller
{
    protected WithdrawUseCase $withdrawUseCase;
    protected GetTransactionHistoryUseCase $transactionHistoryUseCase;
    public function __construct(WithdrawUseCase $withdrawUseCase, GetTransactionHistoryUseCase $transactionHistoryUseCase)
    {
        $this->withdrawUseCase = $withdrawUseCase;
        $this->transactionHistoryUseCase = $transactionHistoryUseCase;
    }

    public function withdraw(Request $request): JsonResponse
    {
        $userId = auth()->id();
        $amount = (int) $request->input('amount');

        $result = $this->withdrawUseCase->execute($userId, $amount);

        if (isset($result['error'])) {
            return response()->json(['message' => $result['error']], 400);
        };

        return response()->json($result);
    }

    public function transactionHistory(): JsonResponse
    {
        $history = $this->transactionHistoryUseCase->execute();
        return response()->json($history);
    }
}
