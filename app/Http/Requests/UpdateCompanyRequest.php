<?php

namespace App\Http\Requests;

use App\Models\Company;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyRequest extends FormRequest
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
            'name' => [$this->isUpdate() ? 'required' : 'sometimes', 'string', Rule::unique(Company::class)->ignore($this->route('company')->id)],
            'address' => [$this->isUpdate() ? 'required' : 'sometimes', 'string'],
            'phone_number' => [$this->isUpdate() ? 'required' : 'sometimes'],
            'email' => [$this->isUpdate() ? 'required' : 'sometimes', 'email']
        ];
    }

    private function isUpdate(): bool
    {
        return $this->isMethod('PUT') || $this->isMethod('PATCH');
    }
}
