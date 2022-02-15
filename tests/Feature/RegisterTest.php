<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testErrorCreateUser()
    {
        $user = User::factory()->make();

        $response = $this->postJson('/api/v1/user', [
            'name' => $user->name,
            'email' => $user->email,
            'cpf_cnpj' => $user->cpf_cnpj
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }


    public function testErrorDuplicatedUser()
    {
        $user = User::factory()->make();

        $response = $this->postJson('/api/v1/user', [
            'name' => $user->name,
            'email' => $user->email,
            'cpf_cnpj' => $user->cpf_cnpj,
            'user_type' => $user->user_type,
            'phone' => $user->phone
        ]);

        $response->assertStatus(Response::HTTP_CREATED);

        $response = $this->postJson('/api/v1/user', [
            'name' => $user->name,
            'email' => $user->email,
            'cpf_cnpj' => $user->cpf_cnpj,
            'user_type' => $user->user_type,
            'phone' => $user->phone
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testSuccessCreateUserTypeUser()
    {
        $user = User::factory()->make([
            'user_type' => User::TYPE_USER
        ]);

        $response = $this->postJson('/api/v1/user', [
            'name' => $user->name,
            'email' => $user->email,
            'cpf_cnpj' => $user->cpf_cnpj,
            'user_type' => $user->user_type,
            'phone' => $user->phone
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function testSuccessCreateUserTypeStore()
    {
        $user = User::factory()->make([
            'user_type' => User::TYPE_STORE
        ]);

        $response = $this->postJson('/api/v1/user', [
            'name' => $user->name,
            'email' => $user->email,
            'cpf_cnpj' => $user->cpf_cnpj,
            'user_type' => $user->user_type,
            'phone' => $user->phone
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
    }
}
