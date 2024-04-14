<?php

namespace App\Http\Controllers\Meeting;

use App\Models\Meeting;
use App\Models\Availability;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class MeetingController extends Controller
{
    public function index()
    {
        //
    }
    public function show($id)
    {
        //
    }
    public function store(Request $request)
    {
        //
    }
    public function update(Request $request, $id)
    {
        //
    }
    public function destroy($id)
    {
        //
    }
    public function requestMeeting(Request $request)
    {
        // Validate the request data
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'available_id' => 'required|exists:availabilities,id',
            'topic' => 'required|string|max:255',
        ]);

        // Retrieve the availability record
        $availability = Availability::findOrFail($request->input('available_id'));

        // Ensure that the doctor_id in the availability matches the one provided in the request
        if ($availability->doctor_id != $request->input('doctor_id')) {
            throw ValidationException::withMessages([
                'doctor_id' => 'The selected doctor is not available at the provided time.',
            ]);
        }

        // Create a new meeting
        $meeting = new Meeting();
        $meeting->patient_id = auth()->user()->id;
        $meeting->doctor_id = $request->input('doctor_id');
        $meeting->available_id = $request->input('available_id');
        $meeting->topic = $request->input('topic');
        $meeting->save();

        // Return success response
        return response()->json(['message' => 'Meeting requested successfully'], 200);
    }
}
