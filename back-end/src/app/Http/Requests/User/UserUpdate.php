<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Http\Controllers\BaseController;

class UserUpdate extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name'     => ['nullable', 'string', 'min:3', 'max:191'],
            'email'    => ['nullable', 'string', 'email', 'max:191', 'unique:users,email,' . request('id')],
            'password' => ['nullable', 'max:255', 'min:6', 'confirmed'],
            'role'     => ['nullable', 'exists:roles,name'],
        ];
    }

    public function messages(): array
    {
        return [
            'name'               => 'Informe corretamente o nome',
            'email'              => 'Informe corretamente o e-mail',
            'email.unique'       => 'E-mail já cadastrado',
            'password'           => 'A senha deve ser informada',
            'password.confirmed' => 'As senhas informadas não coicidem',
            'role'               => 'Selecione um nível',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $response = (new BaseController)->sendError('Dados incorretos', $validator->errors()->toArray(), 400);

        throw new HttpResponseException($response);
    }
}
