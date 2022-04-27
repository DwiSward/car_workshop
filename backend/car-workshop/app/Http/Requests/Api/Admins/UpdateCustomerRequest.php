<?php

namespace App\Http\Requests\Api\Admins;

use App\Models\Customer;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
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
        // dd(Customer::findOrFail($this->route('customer')));
        $rules = [];
        $rules['name'] = [
            'required',
            'string',
            'max:255'
        ];
        $rules['email'] = [
            'required',
            'string',
            'email',
            'max:255',
            Rule::unique('users')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })->ignore(Customer::findOrFail($this->route('customer'))->user->id)
        ];
        return $rules;
    }
}
