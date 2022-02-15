<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Str;
use App\Notifications\AuthorizedTransactionNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

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

    /**
     * Set the Payer and Payee and check rules
     *
     * @param array $payload
     * @return void
     */
    public function newUserTransaction(array $payload)
    {
        $this->payer = $this->userRepository->find($payload['payer']);
        $this->payee = $this->userRepository->find($payload['payee']);
        $this->amount = $payload['amount'];

        $this->checkIfPayerAvailableToTransfer();
        $this->checkIfPayerHasBalance();

        return  $this->createTransaction();
    }

    /**
     * Create User Transaction
     *
     * @return void
     */
    public function createTransaction()
    {
        DB::beginTransaction();

        $this->payer->wallet->subtractBalance($this->amount);

        $transaction = $this->transactionRepository->create(
            [
                'payer_id' => $this->payer->id,
                'payee_id' => $this->payee->id,
                'amount' => $this->amount,
                'uuid' => Str::uuid()
            ]
        );

        if (!$this->authorizeTransaction()) {
            DB::rollBack();

            abort(400, "transaction cancelled");
        } else {

            $this->payee->wallet->addBalance($transaction->amount);

            $this->payer->notify(new AuthorizedTransactionNotification($transaction, $this->payer));

            DB::commit();
        }
    }

    /**
     * Check if payer is a user type available to transfer
     *
     * @return void
     */
    public function checkIfPayerAvailableToTransfer()
    {
        if ($this->payer->user_type == User::TYPE_STORE) {
            abort(400, 'you cannot perform this operation');
        }
        return true;
    }


    /**
     * Check if payer have sufficient balance
     *
     * @return bool
     */
    public function checkIfPayerHasBalance()
    {
        if ($this->payer->wallet->amount < $this->amount) {
            abort(400, 'insuficient balance');
        }
        return true;
    }

    /**
     * Authorize transaction
     *
     * @return bool
     */
    private function authorizeTransaction(): bool
    {
        $authUrl = env('AUTHORIZATION_URL');

        if (!Config::get('transactions.force_fails')) {
            try {
                $response = Http::acceptJson()->get($authUrl);

                Log::info($response->json());

                if ($response->status() == Response::HTTP_OK && $response->object()->message == Transaction::AUTH_MESSAGE_SUCCESS) {

                    return true;
                }

                return false;
            } catch (Exception $e) {

                Log::alert('Unable to Authorize Transaction: ' . $e->getMessage());
            }
        } else {
            return false;
        }
    }
}
