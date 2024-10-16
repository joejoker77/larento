<?php

namespace App\Http\Requests\Site;


use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    public function authorize():bool
    {
        return true;
    }

    public function rules():array
    {
        return [
            'user_name'    => 'required|string',
            'vote'         => 'required|integer',
            'comment'      => 'required|string'
        ];
    }
}
