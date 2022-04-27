<?php

namespace App\Http\Requests\Api\Admins;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['brand'] = [
            'required',
            'string',
            'max:255'
        ];
        $rules['color'] = [
            'required',
            'string',
            'max:255'
        ];
        $rules['type'] = [
            'required',
            'string',
            'max:255'
        ];
        $rules['license_plate'] = [
            'required',
            'string',
            'max:255',
            Rule::unique('cars')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })->ignore($this->route('car'))
        ];
        $rules['customer_id'] = [
            'required',
            'string',
            'max:255',
            Rule::exists('customers', 'id')
        ];
        return $rules;
    }
}
