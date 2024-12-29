<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterUser extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => 'required |unique:users,email',
            'password' => 'required'
        ];
    }

    // retourner les erreurs

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'succes' => false,
            'status_code' => 422,
            'message' => 'Erreur de validation',
            'errorsList' => $validator->errors()

        ]));
    }

    public function messages()
    {
        return [
            'name.required' => 'Un nom doit être fourni',
            'email.required' => 'Un email doit être fourni',
            'email.unique' => 'Cet adresse email est déjà utilisée',
            'password.required' => 'Un mot de passe doit être fourni'
        ];
    }
}
