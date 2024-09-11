<?php

namespace App\Http\Requests;

use App\Enums\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'exit_time' => ['nullable', 'date_format:Y-m-d H:i:s', 'after:entry_time'],
            'duration' => ['nullable', 'integer', 'min:0'],
            'total_cost' => ['nullable', 'numeric', 'min:0', 'decimal:0,2'],
            'payment' => ['nullable', 'numeric', 'min:0', 'decimal:0,2'],
            'change_pay' => ['nullable', 'numeric', 'min:0', 'decimal:0,2'],
            'status' => [$this->isUpdate() ? 'required' : 'sometimes', Rule::in(StatusEnum::values())],
        ];
    }

    private function isUpdate(): bool
    {
        return $this->isMethod('PUT') || $this->isMethod('PATCH');
    }
}
