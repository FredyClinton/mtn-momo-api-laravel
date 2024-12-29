<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RequestToPay extends FormRequest
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
            'amount' => 'required|numeric|min:0.01',
            'phone' => 'required|regex:/^[0-9]{9}$/',
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
            'amount.required' => 'Un montant doit être fourni',
            'phone.required' => 'Le numero de téléphone du client doit être fourni',
            'amount.numeric' => 'Le format du montant n \'est pas correct',
        ];
    }
}
