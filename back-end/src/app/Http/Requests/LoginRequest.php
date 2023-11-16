<?php

namespace App\Http\Requests;

use App\Http\Controllers\BaseController;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use \Illuminate\Contracts\Validation\Validator;
use \Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
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
            'email' => [
                'required',
                'string',
                'email',
                'min:5',
                'max:191',
            ],

            'password' => [
                'required',
                'min:6',
                'max:255',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'O E-mail deve ser informado',
            'email.string'   => 'E-mail não condizente com os parâmetros estabelecidos',
            'email.email'    => 'E-mail não condizente com os parâmetros estabelecidos',
            'email.min'      => 'E-mail não condizente com os parâmetros estabelecidos',
            'email.max'      => 'E-mail não condizente com os parâmetros estabelecidos',

            'password.required' => 'A senha deve ser informada',
            'password.min'      => 'Senha não condizente com os parâmetros estabelecidos',
            'password.max'      => 'Senha não condizente com os parâmetros estabelecidos',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $response = (new BaseController)->sendError('Erro ao tentar realizar login. Verifique os dados e tente novamente.', data: $validator->errors()->all(), code: $bad_request = 400);

        throw new ValidationException($validator, $response);
    }
}
