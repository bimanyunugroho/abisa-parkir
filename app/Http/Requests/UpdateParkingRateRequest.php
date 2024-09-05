<?php

namespace App\Http\Requests;

use App\Models\ParkingRate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateParkingRateRequest extends FormRequest
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
            'vehicle_id'    => [$this->isUpdate() ? 'required' : 'sometimes', 'exists:vehicles,id', Rule::unique(ParkingRate::class)->ignore($this->route('parking_rate')->id)],
            'time_interval' => [$this->isUpdate() ? 'required' : 'sometimes', 'numeric'],
            'cost'  => [$this->isUpdate() ? 'required' : 'sometimes', 'numeric']
        ];
    }

    private function isUpdate(): bool
    {
        return $this->isMethod('PUT') ?? $this->isMethod('PATCH');
    }
}
