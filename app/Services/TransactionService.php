<?php

namespace App\Services;

use App\Jobs\AuthorizeTransaction;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Str;
use App\Notifications\AuthorizedTransactionNotification;
use Illuminate\Support\Facades\Log;
use Exception;

class TransactionService
{

    protected $userRepository;

    protected $transactionRepository;

    protected $amount;

    protected $payer;

    protected $payee;

    public function __construct(User $user, Transaction $transaction)
    {
        $this->userRepository = $user;
        $this->transactionRepository = $transaction;
    }

    public function newUserTransaction(array $payload)
    {
        $this->payer = $this->userRepository->find($payload['payer']);
        $this->payee = $this->userRepository->find($payload['payee']);
        $this->amount = $payload['amount'];

        if ($this->checkIfPayerAvailableToTransfer() && $this->checkIfPayerHasBalance()) {

            $this->createTransaction();
        }
    }


    public function createTransaction()
    {
        $transaction = $this->transactionRepository->create(
            [
                'payer_id' => $this->payer->id,
                'payee_id' => $this->payee->id,
                'amount' => $this->amount,
                'uuid' => Str::uuid()
            ]
        );

        $this->payer->wallet->subtractBalance($this->amount);

        return AuthorizeTransaction::dispatch($transaction, $this->payer);
    }


    public function checkIfPayerAvailableToTransfer()
    {
        if ($this->payer->user_type == User::TYPE_STORE) {
            throw new Exception('Você não pode efetuar essa transação.');
        }
        return true;
    }


    public function checkIfPayerHasBalance()
    {
        if ($this->payer->wallet->amount >= $this->amount) {
            return true;
        }

        throw new Exception('Você não tem saldo suficiente');
    }

    public static function commitTransaction(Transaction $transaction, User $user)
    {
        Log::info('handle commit');
        Log::info(json_encode($transaction));
        Log::info(json_encode($user));

        $transaction->is_authorized = true;
        $transaction->save();

        $sender = User::find($transaction->payee_id);
        $sender->wallet->addBalance($transaction->amount);

        $user->notify(new AuthorizedTransactionNotification($transaction, $user));
    }


    public static function rollbackTransaction(Transaction $transaction, User $user)
    {
        Log::info('handle rollback');
        Log::info(json_encode($transaction));
        Log::info(json_encode($user));

        $transaction->is_authorized = false;
        $transaction->save();

        $user->wallet->addBalance($transaction->amount);
    }
}
