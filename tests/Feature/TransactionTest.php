<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_payee_not_exist_for_transaction()
    {
        $payload = [
            'amount' => 1,
            'payer' => 9999999999,
            'payee' => 1
        ];

        $response = $this->postJson('/api/v1/transactions', $payload);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertExactJson(
            [
                "message" => "The given data was invalid.",
                "errors" => [
                    "payer" => [
                        "o campo payer é um valor inválido"
                    ]
                ]
            ]
        );
    }

    public function test_amount_transaction()
    {
        $payload = [
            'amount' => 0,
            'payer' => 1,
            'payee' => 2
        ];

        $response = $this->postJson('/api/v1/transactions', $payload);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }


    public function testIfUserNotHaveBalanceToTransfer()
    {
        $user = User::factory()->create(['user_type' => User::TYPE_USER]);

        $payee = User::where('user_type', User::TYPE_STORE)->first();

        $response =  $this->postJson('/api/v1/transactions', [
            'amount' => 10000,
            'payer' => $user->id,
            'payee' => $payee->id
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);

        $response->assertExactJson([
            'success' => false,
            'message' => 'insuficient balance'
        ]);
    }

    public function testIfPayerNotUserStore()
    {
        $user = User::factory()->create(['user_type' => User::TYPE_STORE]);

        $payee = User::where('user_type', User::TYPE_STORE)->first();

        $response =  $this->postJson('/api/v1/transactions', [
            'amount' => 100,
            'payer' => $user->id,
            'payee' => $payee->id
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);

        $response->assertExactJson([
            'success' => false,
            'message' => 'you cannot perform this operation'
        ]);
    }

    public function testUnauthorizedTransaction()
    {
        Config::set('transactions.force_fails', true);

        $user = User::factory()->create(['user_type' => User::TYPE_USER]);

        $user->wallet->addBalance(5000000);

        $payee = User::where('user_type', User::TYPE_STORE)->first();

        $response =  $this->postJson('/api/v1/transactions', [
            'amount' => 100,
            'payer' => $user->id,
            'payee' => $payee->id
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);

        $response->assertExactJson([
            'success' => false,
            'message' => 'transaction cancelled'
        ]);
    }

    public function testMakeTransaction()
    {
        Config::set('transactions.force_fails', false);

        $user = User::factory()->create(['user_type' => User::TYPE_USER]);

        $user->wallet->addBalance(5000000);

        $payee = User::where('user_type', User::TYPE_STORE)->first();

        $response =  $this->postJson('/api/v1/transactions', [
            'amount' => 100,
            'payer' => $user->id,
            'payee' => $payee->id
        ]);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertExactJson([
            'success' => true,
            'message' => 'success'
        ]);
    }
}
