<?php

namespace App\Http\Requests\Administrator;

use App\Models\Admin;
use App\Rules\PasswordHistoryRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class AdministratorRolePermissionRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'role' => ['required'],
            'permissions' => ['required'],
        ];
    }
}
