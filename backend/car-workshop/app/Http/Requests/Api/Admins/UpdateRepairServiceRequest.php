<?php

namespace App\Http\Requests\Api\Admins;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRepairServiceRequest extends FormRequest
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
        return [
            'repair_service_id' => 'required|exists:repair_services,id'
        ];
    }
}
