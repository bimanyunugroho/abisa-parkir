<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateParkingSessionRequest extends FormRequest
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
            'vehicle_id' => [$this->isUpdate() ? 'required' : 'sometimes', 'exists:vehicles,id'],
            'parking_area_id' => [$this->isUpdate() ? 'required' : 'sometimes', 'exists:parking_areas,id'],
            'user_id' => [$this->isUpdate() ? 'required' : 'sometimes', 'exists:users,id'],
            'entry_time' => [$this->isUpdate() ? 'required' : 'sometimes'],
            'exit_time' => [$this->isUpdate() ? 'required' : 'sometimes'],
            'duration' => [$this->isUpdate() ? 'required' : 'sometimes'],
            'total_cost' => [$this->isUpdate() ? 'required' : 'sometimes'],
            'status' => [$this->isUpdate() ? 'required' : 'sometimes']
        ];
    }

    private function isUpdate(): bool
    {
        return $this->isMethod('PUT') || $this->isMethod('PATCH');
    }
}
