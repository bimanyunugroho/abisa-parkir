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
            'parking_area_id' => [$this->isUpdate() ? 'required' : 'sometimes', 'exists:parking_areas,id'],
            'parking_rate_id' => [$this->isUpdate() ? 'required' : 'sometimes', 'exists:parking_rates,id'],
            'user_id' => [$this->isUpdate() ? 'required' : 'sometimes', 'exists:users,id'],
            'no_ticket' => [$this->isUpdate() ? 'required' : 'sometimes', 'string', 'max:255'],
            'license_plate' => [$this->isUpdate() ? 'required' : 'sometimes', 'string', 'max:255'],
            'entry_time' => [$this->isUpdate() ? 'required' : 'sometimes', 'date_format:Y-m-d H:i:s'],
            'exit_time' => ['nullable', 'date_format:Y-m-d H:i:s', 'after:entry_time'],
            'duration' => ['nullable', 'integer', 'min:0'],
            'total_cost' => ['nullable', 'numeric', 'min:0', 'decimal:0,2'],
            'status' => [$this->isUpdate() ? 'required' : 'sometimes', Rule::in(StatusEnum::values())],
        ];
    }

    private function isUpdate(): bool
    {
        return $this->isMethod('PUT') || $this->isMethod('PATCH');
    }
}
