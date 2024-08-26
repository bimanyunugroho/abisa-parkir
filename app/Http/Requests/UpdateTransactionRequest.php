<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionRequest extends FormRequest
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
            'parking_session_id' => [$this->isUpdate() ? 'required' : 'sometimes', 'exists:parking_sessions,id'],
            'amount'    => [$this->isUpdate() ? 'required' : 'sometimes', 'integer', 'min:0'],
            'user_id'   => [$this->isUpdate() ? 'required' : 'sometimes', 'exists:users,id'],
            'payment_method' => [$this->isUpdate() ? 'required' : 'sometimes', 'string']
        ];
    }

    private function isUpdate(): bool
    {
        return $this->isMethod('PUT') || $this->isMethod('PATCH');
    }
}
