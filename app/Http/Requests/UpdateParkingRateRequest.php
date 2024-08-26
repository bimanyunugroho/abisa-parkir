<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'type' => [$this->isUpdate() ? 'required' : 'sometimes', 'string'],
            'rate_per_hor' => [$this->isUpdate() ? 'required' : 'sometimes']
        ];
    }

    private function isUpdate(): bool
    {
        return $this->isMethod('PUT') ?? $this->isMethod('PATCH');
    }
}
