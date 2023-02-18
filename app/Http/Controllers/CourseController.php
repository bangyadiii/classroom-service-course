<?php

namespace App\Http\Controllers;

use App\Http\Requests\Course\CourseCreateRequest;
use App\Http\Requests\Course\CourseUpdateRequest;
use App\Models\Course;
use App\Models\Mentors;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $courses = Course::query();
        $q = $request->query("q");
        $limit = $request->query("limit");
        $status = $request->query("status");

        if ($q) {
            $courses->whereRaw("name LIKE '%" . strtolower($q) . "%'");
        }

        if ($status) {
            $courses->where("status", "=", $status);
        }

        return $this->success(
            Response::HTTP_OK,
            "Getting data successful",
            $courses->simplePaginate($limit ?? 20)
        );
    }


    public function store(CourseCreateRequest $request)
    {
        $mentor  = Mentors::find($request->input("mentor_id"));
        if (!$mentor) {
            return $this->error(Response::HTTP_NOT_FOUND, "Mentor not found");
        }

        $validated = $request->validated();

        $newCourse = Course::create($validated);

        return $this->success(Response::HTTP_CREATED, "Course has been created", $newCourse);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $course = Course::with(["chapters.lessons", "reviews", "mentor",  "imageCourse"])
            ->withCount(['myCourse as total_student', "lessons as total_lessons"])
            ->findOrFail($id);

        return $this->success(Response::HTTP_OK, "Get course data", $course);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(CourseUpdateRequest $request, $id)
    {
        $course = Course::find($id);

        if (!$course) {
            \abort(Response::HTTP_NOT_FOUND, "Course not found");
        }

        if ($request->mentor_id) {
            $mentor  = Mentors::find($request->mentor_id);
            if (!$mentor) {
                \abort(Response::HTTP_NOT_FOUND, "Mentor not found");
            }
        }

        $data = $request->validated();

        $course->fill($data)->save();
        return $this->success(200, "Course has been updated", $course);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $course = Course::find($id);

        if (!$course) {
            \abort(Response::HTTP_NOT_FOUND, "course not found");
        }

        $course->delete();

        return $this->success(Response::HTTP_OK, "Course has been deleted");
    }
}
