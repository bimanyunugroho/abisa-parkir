<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreParkingSessionRequest extends FormRequest
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
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'parking_area_id' => ['required', 'exists:parking_areas,id'],
            'user_id' => ['required', 'exists:users,id'],
            'entry_time' => ['required'],
            'exit_time' => ['required'],
            'duration' => ['required'],
            'total_cost' => ['required'],
            'status' => ['required']
        ];
    }
}
