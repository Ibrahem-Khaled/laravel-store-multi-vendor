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
        // استخراج معرف المستخدم بشكل صحيح من route model binding
        $user = $this->route('user');
        $userId = is_object($user) ? $user->id : $user;
        
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $userId],
            'phone' => ['nullable', 'string', 'max:30', 'unique:users,phone,' . $userId],
            'role' => ['required', 'in:admin,moderator,user,trader'],
            'role_ids' => ['nullable', 'array'],
            'role_ids.*' => ['exists:roles,id'],
            'status' => ['required', 'in:pending,active,inactive,banned'],
            'gender' => ['nullable', 'in:male,female'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'coins' => ['nullable', 'integer', 'min:0'],
            'address' => ['nullable', 'string', 'max:500'],
        ];
    }
}
