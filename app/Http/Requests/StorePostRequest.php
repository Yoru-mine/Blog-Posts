<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:20000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:5120|dimensions:max_width=6000,max_height=6000',
        ];
    }
}
