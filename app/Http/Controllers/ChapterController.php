<?php

namespace App\Http\Controllers;

use App\Http\Requests\Chapter\ChapterCreateRequest;
use App\Http\Requests\Chapter\ChapterUpdateRequest;
use App\Models\Chapter;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ChapterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $coursesId = $request->query("course_id");
        $limit = $request->query("limit");

        $chapters = Chapter::query();
        if ($coursesId) {
            $chapters->where("course_id", $coursesId);
        }

        $result = $chapters->simplePaginate($limit ?? 20);

        return $this->success(200, "Berhasil mendapatkan data", $result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ChapterCreateRequest $request)
    {
        $course_id = $request->input("course_id");
        $course = Course::find($course_id);

        if (!$course) {
            abort(Response::HTTP_NOT_FOUND, "NOT FOUND");
        }

        $newChapter = Chapter::create($request->validated());

        return $this->success(201, "Chapter created successfully", $newChapter);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Chapter  $chapter
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $chapter = Chapter::find($id);

        if (!$chapter) {
            \abort(Response::HTTP_NOT_FOUND, "NOT_FOUND");
        }

        return $this->success(200, "Berhasil mendapatkan data", $chapter);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Chapter  $chapter
     * @return \Illuminate\Http\Response
     */
    public function update(ChapterUpdateRequest $request, $id)
    {
        $chapter = Chapter::find($id);

        if (!$chapter) {
            \abort(Response::HTTP_NOT_FOUND, "NOT_FOUND");
        }
        $chapter->fill($request->validated());
        $chapter->save();

        return $this->success(200, "Chapter has been updated", $chapter->fresh());
    }


    public function destroy($id)
    {
        $chapter = Chapter::find($id);
        if (!$chapter) {
            \abort(Response::HTTP_NOT_FOUND, "NOT_FOUND");
        }

        $chapter->delete();

        return $this->success(200, "Berhasil menghapus chapter");
    }
}
