<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;

class CourseUpdateRequest extends FormRequest
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
            "name" => "string",
            "description" => "string",
            "certificate" => "boolean",
            "type" => "string|in:free,premium",
            "thumbnail" => "url",
            "price" => "integer",
            "status" => "in:draft,published",
            "level" => "in:all-level,beginner,intermediate,advanced",
            "mentor_id" => "integer",
        ];
    }
}
