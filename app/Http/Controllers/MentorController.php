<?php

namespace App\Http\Controllers;

use App\Models\Mentors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ValidatedInput;

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

        return \response()->json([
            "status" => "success",
            "message" => "berhasil mendapatkan data Mentor",
            "data" => $allMentor

        ]);
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
            "name" => "required|string",
            "profile" => "required|url",
            "profession" => "string",
            "email" => "required|email|unique:mentors"
        ];

        $data = $request->all();
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return \response()->json([
                "status" => "error",
                "message" => $validator->errors()
            ], 400);
        }
        $newMentors = Mentors::create($validator->validated());

        return response()->json([
            "status" => "success",
            "message" => "Mentors has been created.",
            "data" => $newMentors
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Mentors  $mentors
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $mentors = Mentors::find($id);

        if (!$mentors) {
            return response()->json([
                "status" => "error",
                "message" => "Mentor not found.",
                "data" => []
            ], 404);
        }


        return response()->json([
            "status" => "success",
            "message" => "berhasil mendapatkan data mentor",
            "data" => $mentors
        ], 200);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Mentors  $mentors
     * @return \Illuminate\Http\Response
     */
    public function edit(Mentors $mentors)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Mentors  $mentors
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        $mentors = Mentors::find($id);
        if (!$mentors) {
            return \response()->json([
                "status" => "error",
                "message" => "Mentor not found"
            ], 404);
        }

        $rules = [
            "name" => "string",
            "profession" => "string",
            "profile" => "url",
        ];

        $data = $request->all();
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return \response()->json([
                "status" => "error",
                "message" => $validator->errors()
            ], 400);
        }

        $updatedMentors = $mentors->fill($validator->validated());
        $updatedMentors->save();

        return response()->json([
            "status" => "success",
            "message" => "Mentors has been updated.",
            "data" => $updatedMentors
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Mentors  $mentors
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $mentors = Mentors::find($id);
        if (!$mentors) {
            return \response()->json([
                "status" => "error",
                "message" => "Mentor not found"
            ], 404);
        }

        $mentors->delete();

        return \response()->json([
            "status" => "success",
            "message" => "Mentor has been deleted"
        ], 200);
    }
}
