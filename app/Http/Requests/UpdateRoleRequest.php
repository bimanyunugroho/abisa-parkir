<?php

namespace App\Http\Requests;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
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
            'name'  => [$this->isUpdate() ? 'required' : 'sometimes', 'string', 'max:100', Rule::unique(Role::class)->ignore($this->route('role')->id)]
        ];
    }

    private function isUpdate(): bool
    {
        return $this->isMethod('PUT') || $this->isMethod('PATCH');
    }
}
