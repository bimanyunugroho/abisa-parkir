<?php

namespace App\Http\Requests;

use App\Models\ParkingArea;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateParkingAreaRequest extends FormRequest
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
            'name'  => [$this->isUpdate() ? 'required': 'sometimes', 'string', 'max:100', 'unique:parking_areas', Rule::unique(ParkingArea::class)->ignore($this->route('parking_area')->id)],
            'capacity'  => [$this->isUpdate() ? 'required': 'sometimes', 'string', 'numeric'],
            'current_occupancy' => [$this->isUpdate() ? 'required': 'sometimes', 'string']
        ];
    }

    private function isUpdate(): bool
    {
        return $this->isMethod('PUT') || $this->isMethod('PATCH');
    }
}
