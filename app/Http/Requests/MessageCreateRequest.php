<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessageCreateRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "to" => ["required", "integer"],
            "subject" => ["required", "string", "max:255", "regex:/^[a-z0-9 .\-!,\':;<>?()\[\]*+=\/#$&]+$/i", "strictly_profane"],
            "body" => ["required", "max:5000", "regex:/^[a-z0-9 .\-!,\':;<>?()\[\]*+=\/#$&\t\n\r]+/i", "strictly_profane"],
        ];
    }
}
