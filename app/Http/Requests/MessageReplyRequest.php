<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessageReplyRequest extends FormRequest
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
            "message_id" => ["required", "integer"],
            "body" => ["required", "max:5000", "regex:/^[a-z0-9 .\-!,\':;<>?()\[\]*+=\/#$&\t\n\r]+/i", "strictly_profane"],
        ];
    }
}
