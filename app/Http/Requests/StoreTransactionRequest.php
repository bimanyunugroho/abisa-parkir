<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
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
            'parking_session_id' => ['required', 'exists:parking_sessions,id'],
            'amount'    => ['required', 'integer', 'min:0'],
            'user_id'   => ['required', 'exists:users,id'],
            'payment_method' => ['required', 'string']
        ];
    }
}
