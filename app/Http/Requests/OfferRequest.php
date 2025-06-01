<?php

namespace App\Http\Requests;

use App\Rules\IniAmount;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OfferRequest extends FormRequest
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
            'name'        => [
                'required',
                'string',
                'max:190',
                Rule::unique("offers", "name")->ignore($this->route('offer.id'))
            ],
            'amount'     => ['required', 'numeric', 'max:100', new IniAmount()],
            'status'     => ['required', 'numeric', 'max:24'],
            'day'        => ['required', 'numeric', 'max:24'],
            'start_time' => ['required', 'string', 'max:190'],
            'end_time'   => ['required', 'string', 'max:190'],
            'image'      => $this->route('offer.id') ? ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'] : ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->isNotNull(request('start_time')) && strtotime(request('end_time')) < strtotime(request('start_time'))) {
                $validator->errors()->add('end_time', 'End Time can\'t be older than Start Time.');
            }
        });
    }


    private function isNotNull($value)
    {
        if ($value === 'null') {
            return false;
        }
        return true;
    }
}
