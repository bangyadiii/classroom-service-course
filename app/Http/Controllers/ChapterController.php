<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

        $chapters = Chapter::query();

        $result = $chapters->when($coursesId, function ($query) use ($coursesId) {
            return $query->where("course_id", "=", $coursesId);
        })->get();

        return \response()->json([
            "status" => "success",
            "message" => "berhasil mendapatkan data",
            "data" => $result,
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $rules = [
            "name" => "required|string",
            "course_id" => "required|integer",
        ];

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return \response()->json([
                "status" => "error",
                "message" => $validator->errors()
            ], 400);
        }
        $course_id = $request->input("course_id");
        $course = Course::find($course_id);

        if (!$course) {
            return \response()->json([
                "status" => "error",
                "message" => "Course not found.",
            ], 404);
        }

        $newChapter = Chapter::create($validator->validated());

        return \response()->json([
            "status" => "success",
            "message" => "Chapter created successfully.",
            "data" => $newChapter,
        ], 201);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Chapter  $chapter
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $chapter = Chapter::find($id);

        if (!$chapter) {

            return \response()->json([
                "status" => "error",
                "message" => "Chapter not found.",
            ], 404);
        }

        return \response()->json([
            "status" => "success",
            "message" => "Berhasil mendapatkan data.",
            "data" => $chapter
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Chapter  $chapter
     * @return \Illuminate\Http\Response
     */
    public function edit(Chapter $chapter)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Chapter  $chapter
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $chapter = Chapter::find($id);

        if (!$chapter) {
            return \response()->json([
                "status" => "error",
                "message" => "Chapter not found"
            ], 404);
        }
        $rules = [
            "name" => "string",
            "course_id" => "integer",
        ];

        $data = $request->all();
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return \response()->json([
                "status" => "error",
                "message" => $validator->errors()
            ], 400);
        }

        $chapter->fill($validator->validated());
        $chapter->save();

        return \response()->json([

            "status" => "success",
            "message" => "Chapters has been updated"
        ], 200);
    }


    public function destroy(Request $request, $id)
    {
        $chapter = Chapter::find($id);
        if (!$chapter) {
            return \response()->json([
                "status" => "error",
                "message" => "Chapter not found.",
            ], 404);
        }

        $chapter->delete();

        return \response()->json([
            "status" => "success",
            "message" => "berhasil menghapus Chapter",
        ], 200);
    }
}
