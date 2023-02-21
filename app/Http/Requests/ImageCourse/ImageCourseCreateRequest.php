<?php

namespace App\Http\Requests\ImageCourse;

use Illuminate\Foundation\Http\FormRequest;

class ImageCourseCreateRequest extends FormRequest
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
            "course_id" => "required|integer",
            "image" => "url",
            "image_base64" => "required"
        ];
    }
}
