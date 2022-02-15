<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
            'name' => 'required|string|min:3|max:100',
            'last_name' => 'required|string|min:3|max:100',
            'cpf_cnpj' => 'required|string|unique:users,cpf_cnpj',
            'phone' => 'required|string|min:3|max:100',
            'email' => 'required|email|unique:users,email|',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'string' => 'O campo :attribute não é texto ',
            'min' => 'O campo :attribute é muito curto',
            'max' => 'O campo :attribute é muito longo',
            'email' => 'O campo :attribute não é um e-mail válido',
            'unique' => 'O campo :attribute já está em uso',
        ];
    }
}
