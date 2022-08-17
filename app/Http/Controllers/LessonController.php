<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function PHPSTORM_META\map;

class LessonController extends Controller
{
    public function index(Request $request)
    {
        $chapterId = $request->query("chapter_id");

        $lesson = Lesson::query();

        $result = $lesson->when($chapterId, function($query) use ($chapterId){
            return $query->where("chapter_id", "=", "$chapterId");
        })->get();

        return \response()->json([
            "status" => "success",
            "message" => "Berhasil mendapatkan data",
            "data" => $result
        ]);

    }

    public function store(Request $request)
    {
        $rules = [
            "name" => "required|string",
            "video" => "required|string",
            "chapter_id" => "required|integer",

        ];

        $data = $request->all();

        $validated = Validator::make($data, $rules);

        if($validated->fails()){
            return \response()->json([
                "status" => "error",
                "message" => $validated->errors()
            ], 400);
        }

        $chapter = Chapter::find($request->input("chapter_id"));

        if(!$chapter) {
            return \response()->json([
                "status" => "error",
                "message" => "Chapter not found",
            ], 400);
        }

        $newLesson = Lesson::create($validated);

        return \response()->json([
            "status" => "success",
            "message" => "Lesson has been created",
            "data" => $newLesson
        ]);
    }

    public function show(Lesson $lesson)
    {
        return \response()->json([
            "status" => "success",
            "message" => "Berhasil mendapatkan data lesson",
            "data" => $lesson
        ]);

    }


    public function update(Request $request, Lesson $lesson)
    {
        $rules = [
            "name" => "string",
            "video" => "string",
            "chapter_id" => "integer",

        ];

        $data = $request->all();

        $validated = Validator::make($data, $rules);

        if($validated->fails()){
            return \response()->json([
                "status" => "error",
                "message" => $validated->errors()
            ], 400);
        }

        $chapter = Chapter::find($request->input("chapter_id"));

        if(!$chapter) {
            return \response()->json([
                "status" => "error",
                "message" => "Chapter not found",
            ], 400);
        }

        $lesson->fill($data);

        $lesson->save();

        return \response()->json([
            "status" => "success",
            "message" => "Berhasil update",
            "data" => $lesson
        ]);

    }

   public function destroy(Lesson $lesson)
    {
       $lesson->deleteOrFail();

        return \response()->json([
            "status" => "success",
            "message" => "berhasil menghapus lesson"
        ]);

    }
}
