<?php

namespace App\Http\Controllers;

use App\Http\Requests\Review\ReviewCreateRequest;
use App\Http\Requests\Review\ReviewUpdateRequest;
use App\Models\Course;
use App\Models\MyCourse;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
    public function store(ReviewCreateRequest $request)
    {
        $Course = Course::find($request->input("course_id"));

        if (!$Course) {
            return $this->error(Response::HTTP_NOT_FOUND, "Course not found");
        }
        $anyUser = \getUser($request->input("user_id"));

        if (!$anyUser["meta"]["success"]) {
            return $this->error($anyUser["meta"]["http_code"], $anyUser["meta"]["message"]);
        }
        $course_id = $request->input("course_id");
        $user_id = $request->input("user_id");

        $ifReviewed = Review::where("course_id", "=", $course_id)->where("user_id", "=", $user_id)->exists();

        if ($ifReviewed) {
            return $this->error(Response::HTTP_CONFLICT, "This course has been reviewed before.");
        }

        $review = Review::create($request->validated());

        return $this->success(Response::HTTP_OK, "Berhasil menambahkan review.", $review);
    }


    public function update(ReviewUpdateRequest $request, $id)
    {
        $review = Review::findOrFail($id);

        $Course = Course::find($request->input("course_id"));

        if (!$Course) {
            return $this->error(Response::HTTP_NOT_FOUND, "course not found.");
        }

        $anyUser = \getUser($request->input("user_id"));

        if ($anyUser["status"] === "error") {
            return $this->error($anyUser["http_code"], $anyUser["meta"]["message"]);
        }

        $review->fill($request->safe()->except(['course_id', "user_id"]));
        $review->save();

        return $this->success(Response::HTTP_OK, "Review has been updated", $review);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MyCourse  $myCourse
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $review = Review::find($id);

        if (!$review) {
            return $this->error(Response::HTTP_NOT_FOUND, "Review not found");
        }

        $review->delete();

        return $this->success(Response::HTTP_OK, "Berhasil menghapus review");
    }
}
