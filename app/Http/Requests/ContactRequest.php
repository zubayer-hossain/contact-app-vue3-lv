<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string'],
            'email' => ['required', 'email'],
            "phone_numbers" => ['required','array','min:1'],
            'phone_numbers.*.phone_type_id' => ['required'],
            'phone_numbers.*.phone_number' => ['required'],
            "addresses" => ['required','array','min:1'],
            'addresses.*.address_line' => ['required'],
            'addresses.*.pincode' => ['required'],
        ];
    }
}
