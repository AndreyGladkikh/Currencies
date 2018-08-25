<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConvertCurrencyRequest extends FormRequest
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
            'price' => 'required|digits_between:0, 10',
            'from' => 'required',
            'to' => 'required',
        ];
    }

    public function formatErrors()
    {
        return response()->json([
            'status' => 400,
            'message' => 'Поле должно содержать не более 10 цифр',
        ]);
    }

    public function messages()
    {
        return [
            'price.required' => 'Поле должно содержать не более 10 цифр',
        ];
    }
}
