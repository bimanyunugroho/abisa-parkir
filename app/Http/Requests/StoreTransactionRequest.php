<?php

namespace App\Http\Requests;

use App\Enums\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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
            'parking_area_id' => ['required', 'exists:parking_areas,id'],
            'parking_rate_id' => ['required', 'exists:parking_rates,id'],
            'user_id' => ['required', 'exists:users,id'],
            'license_plate' => ['required', 'string', 'max:255'],
            'entry_time' => ['required', 'date_format:Y-m-d H:i:s'],
            'exit_time' => ['nullable', 'date_format:Y-m-d H:i:s', 'after:entry_time'],
            'duration' => ['nullable', 'integer', 'min:0'],
            'total_cost' => ['nullable', 'numeric', 'min:0', 'decimal:0,2'],
            'status' => ['required', Rule::in(StatusEnum::values())],
        ];
    }
}
