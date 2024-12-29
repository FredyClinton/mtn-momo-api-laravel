<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;


class CreatePostRequest extends FormRequest
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
            'title' => 'required'
        ];
    }

    // retourner les erreurs

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'succes' => false,
            'error' => true,
            'message' => 'Erreur de validation',
            'errorsList' => $validator->errors()

        ]));
    }

    public function messages()
    {
        return [
            'titre.required' => 'Un titre doit être fourni'
        ];
    }
}
