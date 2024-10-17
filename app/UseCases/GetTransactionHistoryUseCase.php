<?php
namespace App\UseCases;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class GetTransactionHistoryUseCase{
    public function execute():array{
        $userId = Auth::id();
        return Transaction::whereHas('account',function($query) use($userId){
            $query->where('user_id',$userId);
        })->get()->toArray();
    }
}