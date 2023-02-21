<?php

namespace App\Http\Controllers;

use App\Http\Requests\Mentor\MentorCreateRequest;
use App\Http\Requests\Mentor\MentorUpdateRequest;
use App\Models\Mentors;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class MentorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allMentor = Mentors::all();

        return $this->success(Response::HTTP_OK, "OK", $allMentor);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MentorCreateRequest $request)
    {
        $newMentors = Mentors::create($request->validated());

        return $this->success(Response::HTTP_CREATED, "Mentors has been created.", $newMentors);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Mentors  $mentors
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $mentors = Mentors::find($id);

        if (!$mentors) {
            \abort(Response::HTTP_NOT_FOUND, "Mentor not found");
        }

        return $this->success(Response::HTTP_OK, "Berhasil mendapatkan data mentor", $mentors);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Mentors  $mentors
     * @return \Illuminate\Http\Response
     */
    public function update(MentorUpdateRequest $request,  $id)
    {
        $mentors = Mentors::find($id);
        if (!$mentors) {
            return $this->error(Response::HTTP_NOT_FOUND, "Mentor not found");
        }

        $updatedMentors = $mentors->fill($request->validated());
        $updatedMentors->save();

        return $this->success(200, "Mentors has been updated", $updatedMentors);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Mentors  $mentors
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $mentors = Mentors::find($id);
        if (!$mentors) {
            return $this->error(Response::HTTP_NOT_FOUND, "Mentor not found");
        }

        $mentors->delete();

        return $this->success(Response::HTTP_OK, "Mentor has been deleted");
    }
}
