<?php

namespace App\Jobs;

use App\Models\Transaction;
use App\Models\User;
use App\Services\TransactionService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AuthorizeTransaction implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    private $transaction;
    private $payer;

    public function __construct(Transaction $transaction, User $payer)
    {
        $this->transaction = $transaction;
        $this->payer = $payer;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $authUrl = env('AUTHORIZATION_URL');

        try {
            $response = Http::acceptJson()->get($authUrl);

            Log::info($response->json());

            if ($response->status() == Response::HTTP_OK && $response->object()->message == Transaction::AUTH_MESSAGE_SUCCESS) {
                TransactionService::commitTransaction($this->transaction, $this->payer);
            } else {

                TransactionService::rollbackTransaction($this->transaction, $this->payer);
            }
        } catch (Exception $e) {

            Log::alert('Não foi possivel authorizar a transação: ' . $e->getMessage());
        }
    }
}
