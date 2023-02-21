<?php

namespace App\Http\Controllers;

use App\Http\Requests\MyCourse\MyCourseCreateRequest;
use App\Models\Course;
use App\Models\MyCourse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MyCourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $userId = $request->query("user_id");

        $query = MyCourse::query()->with("course");

        if ($userId) {
            $query->where("user_id", $userId);
        }

        $courses = $query->simplePaginate();

        return $this->success(200, "Get all my course", $courses);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createPremiumAccess(Request $request)
    {
        $data = $request->all();
        $createdCourse = MyCourse::create([
            'course_id' => $data['course_id'],
            'user_id' => $data['user_id']
        ]);


        return $this->success(Response::HTTP_CREATED, "Premium Access has been added to current user", $createdCourse);
    }

    public function store(MyCourseCreateRequest $request)
    {
        $course = Course::find($request->input("course_id"));

        if (!$course) {
            return $this->error(Response::HTTP_NOT_FOUND, "Course not found", $request->course_id);
        }
        $user = \getUser($request->input("user_id"));

        if (!$user["meta"]["success"]) {
            return $this->error($user["meta"]["http_code"], $user["meta"]["message"]);
        }
        $courseId =  $request->input("course_id");
        $userId = $request->input("user_id");

        $isExistCourse = MyCourse::where("course_id", "=", $courseId)
            ->where("user_id", "=", $userId)
            ->exists();

        if ($isExistCourse) {
            return $this->error(Response::HTTP_CONFLICT, "This class has been taken before");
        }

        if ($course["type"] === "premium" && $course["price"] > 0) {
            $res = createOrder([
                "user" => $user["data"],
                "course" => $course
            ]);

            if (!$res["meta"]["success"]) {
                return $this->error($res["meta"]["http_code"], $res["meta"]["message"]);
            }

            return $this->success($res["meta"]["http_code"], $res["meta"]["message"], $res["data"]);
        } else {
            $mycourse = MyCourse::create($request->validated());
            return $this->success(Response::HTTP_OK, "Berhasil menambahkan kelas", $mycourse);
        }
    }
}
