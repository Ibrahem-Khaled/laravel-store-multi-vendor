<?php

namespace App\Http\Requests\dashboard;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage-users');
    }


    public function rules(): array
    {
        $id = $this->route('user');
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $id],
            'phone' => ['nullable', 'string', 'max:30', 'unique:users,phone,' . $id],
            'role' => ['required', 'in:admin,moderator,user,trader'],
            'status' => ['required', 'in:pending,active,inactive,banned'],
            'gender' => ['nullable', 'in:male,female'],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'coins' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
