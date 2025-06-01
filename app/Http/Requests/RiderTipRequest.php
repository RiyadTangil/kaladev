<?php

namespace App\Http\Requests;

use App\Rules\IniAmount;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class RiderTipRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'label'  => ['required', 'string', 'max:190', Rule::unique("rider_tips", "label")->ignore($this->route('riderTip.id'))],
            'amount' => ['required', 'numeric'],
            'type'   => ['required', Rule::in(['fixed', 'percentage'])],
        ];
    }
}
