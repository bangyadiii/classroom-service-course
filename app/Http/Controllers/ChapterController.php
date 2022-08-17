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

        $result = $chapters->when($coursesId, function($query) use ($coursesId){
            return $query->where("course_id", "=", $coursesId);

        })->get();

        return \response()->json([
            "status" => "success",
            "message" => "berhasil mendapatkan data",
            "data" => $result,
        ]);

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

        $validated = Validator::make($data, $rules);

        if($validated->fails()){
            return \response()->json([
                "status" => "error",
                "message" => $validated->errors(),
            ], 400);
        }

        $newChapter = Chapter::create($validated);

        $course_id = $request->input("course_id");
        $course = Course::find($course_id);

        if(!$course){
            return \response()->json([
                "status" => "error",
                "message" => "Course not found.",
            ], 400);


        }


        return \response()->json([
                "status" => "success",
                "message" => "Chapter created successfully.",
                "data" => $newChapter,
        ],200);

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

        if(!$chapter){

            return \response()->json([
                "status" => "error",
                "message" => "Chapter not found.",
            ], 400);

        }
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
    public function update(Request $request, Chapter $chapter)
    {
        $rules = [
            "name" => "required|string",
            "course_id" => "required|integer",
        ];

        $data = $request->all();
        $validated = Validator::make( $data, $rules);

        if($validated->fails()){
            return \response()->json([
                "status" => "error",
                "message" => $validated->errors()
            ], 400);
        }

        $chapter->fill($data);
        $chapter->save();

        return \response()->json([

            "status" => "success",
            "message" => "Chapters has been updated"
        ], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Chapter  $chapter
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $chapter = Chapter::find($id);

        if(!$chapter){

            return \response()->json([
                "status" => "error",
                "message" => "Chapter not found.",
            ], 400);

        }

        $chapter->delete();

        return \response()->json([
            "status" => "success",
            "message" => "berhasil menghapus Chapter",
        ], 200);


    }
}
