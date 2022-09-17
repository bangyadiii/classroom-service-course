<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\MyCourse;
use App\Models\Review;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $rules = [
            "user_id" => "required|integer",
            "course_id" => "required|integer",
            "review" => "required|integer|min:1|max:5",
            "note" => "string"
        ];
        $data = $request->all();
        $validated = Validator::make($data, $rules);

        if ($validated->fails()) {
            return \response()->json([
                'status' => 'error',
                "message" => $validated->errors()
            ], 400);
        }

        $Course = Course::find($request->input("course_id"));

        if (!$Course) {
            return \response()->json([
                'status' => 'error',
                "message" => "Course not found"
            ], 404);
        }
        $anyUser = \getUser($request->input("user_id"));

        if ($anyUser["status"] === "error") {
            return \response()->json([
                "status" => $anyUser["status"],
                "message" => $anyUser["message"]
            ], $anyUser["http_code"]);
        }
        $course_id = $request->input("course_id");
        $user_id = $request->input("user_id");

        $ifReviewed = Review::where("course_id", "=", $course_id)->where("user_id", "=", $user_id)->exists();

        if ($ifReviewed) {
            return \response([
                "status" => "error",
                "message" => "This course has been reviewed before."
            ], 409);
        }

        $review = Review::create($data);

        return response([
            "status" => "success",
            "message" => "Berhasil menambahkan review",
            "data" => $review
        ], 200);
    }

    public function show(Review $review)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $rules = [
            "user_id" => "integer",
            "course_id" => "integer",
            "review" => "integer|min:1|max:5",
            "note" => "string"
        ];
        $data = $request->all();
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return \response()->json([
                'status' => 'error',
                "message" => $validator->errors()
            ], 400);
        }

        $review = Review::findOrFail($id);

        $Course = Course::find($request->input("course_id"));

        if (!$Course) {
            return \response()->json([
                'status' => 'error',
                "message" => "Course not found"
            ], 404);
        }
        $anyUser = \getUser($request->input("user_id"));

        if ($anyUser["status"] === "error") {
            return \response()->json([
                "status" => $anyUser["status"],
                "message" => $anyUser["message"]
            ], $anyUser["http_code"]);
        }

        $review->fill($validator->safe()->except(['course_id', "user_id"]));
        $review->save();

        return \response()->json([
            "status" => "success",
            "message" => "Berhasil mengedit review",
            "data" => $review
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MyCourse  $myCourse
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $review = Review::find($id);

        if (!$review) {
            return \response()->json([
                "status" => "error",
                "message" => "Review not found."
            ], 404);
        }

        $review->delete();
        return \response()->json([
            "status" => "success",
            "message" => "Berhasil menghapus review",
        ], 200);
    }
}
