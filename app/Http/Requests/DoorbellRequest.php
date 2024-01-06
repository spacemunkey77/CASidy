<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DoorbellRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'status' => [
                'required',
                'regex:/^[A-Za-z0-9\s]+$/i'],
            'doorbell' => [
                'required',
                'regex:/^[A-Za-z0-9\s]+$/i'],
            'object' => [
                'required',
                'regex:/^[A-Za-z0-9\s]+$/i'],
            'timestamp' => 'required|string',
        ];
    }
}
