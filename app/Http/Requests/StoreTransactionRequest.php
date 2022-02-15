<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'amount' => 'required|numeric|min:1',
            'payer' => 'required|exists:users,id',
            'payee' => 'required|exists:users,id',
        ];
    }

    public function messages()
    {
        return [
            'numeric' => 'o campo :attribute nao é um valor válido',
            'required' => 'o campo :attribute é obrigatorio',
            'exists' => 'o campo :attribute é um valor inválido',
        ];
    }
}
