<?php

namespace App\Http\Requests\Api\Admins;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceRequest extends FormRequest
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
        $rules['price'] = [
            'required',
            'numeric'
        ];
        $rules['name'] = [
            'required',
            'string',
            'max:255',
            Rule::unique('services')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })->ignore($this->route('service'))
        ];
        return $rules;
    }
}
