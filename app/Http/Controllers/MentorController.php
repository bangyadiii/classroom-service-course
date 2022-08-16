<?php

namespace App\Http\Controllers;

use App\Models\Mentors;
use Illuminate\Http\Request;
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
            "email" => "required|email"
        ];

        $data = $request->all();
        $validated = Validator::make($data, $rules);

        if($validated->fails()){
            return \response()->json([
                "status" => "error",
                "message" => $validated->errors()
            ]);
        }

        $newMentors = Mentors::create($data);

        return response()->json([
            "status" => "success",
            "message" => "Mentors has been created.",
            "data" => $newMentors
        ]);
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

        if(!$mentors) {
            return response()->json([
                "status" => "error",
                "message" => "Mentor not found.",
                "data" => []
            ]);

        }


        return response()->json([
            "status" => "success",
            "message" => "berhasil mendapatkan data mentor",
            "data" => $mentors
        ]);
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
    public function update(Request $request, Mentors $mentors)
    {
        $rules = [
            "name" => "required|string",
            "profile" => "required|url",
        ];

        $data = $request->all();
        $validated = Validator::make($data, $rules);

        if($validated->fails()){
            return \response()->json([
                "status" => "error",
                "message" => $validated->errors()
            ]);
        }

        $updatedMentors = $mentors->fill($data);
        $updatedMentors->save();

        return response()->json([
            "status" => "success",
            "message" => "Mentors has been updated.",
            "data" => $updatedMentors
        ]);

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
        if(!$mentors){
            return \response()->json([
                "status" => "error",
                "message" => "Delete mentor failed"
            ]);
        }

        $mentors->delete();

        return \response()->json([
            "status" => "success",
            "message" => "Mentor has been deleted"
        ]);
    }
}