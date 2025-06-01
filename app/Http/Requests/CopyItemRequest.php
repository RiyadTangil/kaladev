<?php

namespace App\Http\Requests;

use App\Rules\IniAmount;
use Illuminate\Validation\Rule;
use App\Traits\DefaultAccessModelTrait;
use Illuminate\Foundation\Http\FormRequest;

class CopyItemRequest extends FormRequest
{
    use DefaultAccessModelTrait;
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
            'branch_from'           => ['required', 'numeric', 'not_in:0'],
            'branch_to'             => ['required', 'numeric', 'not_in:0'],
            'items'                 => ['required', 'array'],
            'is_checked_all'        => ['required', 'bool'],
            'excepts'               => ['nullable', 'array']
        ];
    }
}