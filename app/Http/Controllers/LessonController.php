<?php

namespace App\Http\Controllers;

use App\Http\Requests\Lesson\LessonCreateRequest;
use App\Http\Requests\Lesson\LessonUpdateRequest;
use App\Models\Chapter;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LessonController extends Controller
{
    public function index(Request $request)
    {
        $chapterId = $request->query("chapter_id");

        $lesson = Lesson::query();
        if ($chapterId) {
            $lesson->where("chapter_id", $chapterId);
        }

        $result = $lesson->simplePaginate();
        return $this->success(Response::HTTP_OK, "Berhasil mendapatkan data", $result);
    }

    public function store(LessonCreateRequest $request)
    {
        $chapter = Chapter::find($request->input("chapter_id"));

        if (!$chapter) {
            \abort(Response::HTTP_NOT_FOUND, "Chapter not found");
        }

        $newLesson = Lesson::create($request->validated());

        return $this->success(Response::HTTP_OK, "Lesson has been created", $newLesson);
    }

    public function show(Lesson $lesson)
    {
        return $this->success(Response::HTTP_OK, "Berhasil mendapatkan data lessons", $lesson);
    }


    public function update(LessonUpdateRequest $request, Lesson $lesson)
    {
        $chapter = Chapter::find($request->input("chapter_id"));

        if (!$chapter) {
            \abort(Response::HTTP_NOT_FOUND, "chapter not found");
        }

        $lesson->fill($request->validated());

        $lesson->save();

        return $this->success(Response::HTTP_OK, "Berhasil update lesson", $lesson);
    }

    public function destroy($id)
    {
        $lesson = Lesson::find($id);
        if (!$lesson) {
            \abort(Response::HTTP_NOT_FOUND, "Lesson not found");
        }

        $lesson->delete();

        return $this->success(Response::HTTP_OK, "Lesson has been deleted.");
    }
}
