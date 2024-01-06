<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ButtonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'action' => [
                'required',
                'regex:/^[A-Za-z0-9\s]+$/i'],
            'timestamp' => 'required|string',
        ];
    }
}
