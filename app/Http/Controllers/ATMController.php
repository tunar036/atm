<?php

namespace App\Http\Controllers;

use App\UseCases\DeleteTransactionUseCase;
use App\UseCases\WithdrawUseCase;
use App\UseCases\GetTransactionHistoryUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ATMController extends Controller
{
    protected WithdrawUseCase $withdrawUseCase;
    protected GetTransactionHistoryUseCase $transactionHistoryUseCase;
    protected DeleteTransactionUseCase $deleteTransactionUseCase;

    public function __construct(WithdrawUseCase $withdrawUseCase, GetTransactionHistoryUseCase $transactionHistoryUseCase, DeleteTransactionUseCase $deleteTransactionUseCase)
    {
        $this->withdrawUseCase = $withdrawUseCase;
        $this->transactionHistoryUseCase = $transactionHistoryUseCase;
        $this->deleteTransactionUseCase = $deleteTransactionUseCase;
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

    public function deleteTransaction(int $id): JsonResponse
    {
        $result = $this->deleteTransactionUseCase->execute($id);
        if (!$result) {
            if (!Gate::allows('delete-transactions')) {
                return response()->json(['error' => 'Bu əməliyyatı yerinə yetirmək üçün icazəniz yoxdur.'], 403);
            } else {
                return response()->json(['error' => 'Tranzaksiya tapılmadı.'], 404);
            }
        }

        return response()->json(['success' => 'Tranzaksiya silindi']);
    }
}
