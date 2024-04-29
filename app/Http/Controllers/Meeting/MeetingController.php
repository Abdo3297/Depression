<?php

namespace App\Http\Controllers\Meeting;

use Carbon\Carbon;
use App\Models\Meeting;
use Illuminate\Http\Request;
use Jubaer\Zoom\Facades\Zoom;
use App\Http\Controllers\Controller;
use App\Http\Resources\AcceptMeetingResource;

class MeetingController extends Controller
{
    public function index()
    {
        if (Meeting::exists()) {
            $meeting = Meeting::where('doctor_id',auth('doctor_api')->user()->id)->paginate();
            return $this->paginateResponse(AcceptMeetingResource::collection($meeting));
        }
        return $this->errorResponse('data not found');
    }
    public function show($id)
    {
        $meeting = Meeting::where('doctor_id',auth('doctor_api')->user()->id)->find($id);
        if ($meeting) {
            return $this->okResponse('data fetched successfully',AcceptMeetingResource::make($meeting));
        }
        return $this->errorResponse('data not found');
    }
    public function acceptMeeting(Request $request)
    {
        $meeting = Meeting::where('doctor_id',auth('doctor_api')->user()->id)->first();
        $meetings = Zoom::createMeeting([
            "agenda" => $meeting->topic,
            "topic" => $meeting->topic,
            "type" => 1, // 1 => instant, 2 => scheduled, 3 => recurring with no fixed time, 8 => recurring with fixed time
            "duration" => $meeting->duration, // in minutes
            "timezone" => 'Africa/Cairo', // set your timezone
            "password" => '123',
            "start_time" => Carbon::now(), // set your start time
            "template_id" => 'set your template id', // set your template id  Ex: "Dv4YdINdTk+Z5RToadh5ug==" from https://marketplace.zoom.us/docs/api-reference/zoom-api/meetings/meetingtemplates
            "pre_schedule" => true,  // set true if you want to create a pre-scheduled meeting
            "schedule_for" => 'ahasn8391@gmail.com', // set your schedule for
            "settings" => [
                'join_before_host' => false, // if you want to join before host set true otherwise set false
                'host_video' => false, // if you want to start video when host join set true otherwise set false
                'participant_video' => false, // if you want to start video when participants join set true otherwise set false
                'mute_upon_entry' => false, // if you want to mute participants when they join the meeting set true otherwise set false
                'waiting_room' => false, // if you want to use waiting room for participants set true otherwise set false
                'audio' => 'both', // values are 'both', 'telephony', 'voip'. default is both.
                'auto_recording' => 'none', // values are 'none', 'local', 'cloud'. default is none.
                'approval_type' => 0, // 0 => Automatically Approve, 1 => Manually Approve, 2 => No Registration Required
            ],
        ]);
        Meeting::where('doctor_id',auth('doctor_api')->user()->id)->first()->update([
            'topic' => $meetings['data']['topic'],
            'meeting_id' => $meetings['data']['id'],
            'start_url' => $meetings['data']['start_url'],
            'join_url' => $meetings['data']['join_url'],
            'status' => 'accepted',
        ]);
        $getMeeting = Meeting::where('doctor_id',auth('doctor_api')->user()->id)->first();
        return $this->okResponse('meeting accepted successfully',AcceptMeetingResource::make($getMeeting));
    }
    public function rejectMeeting(Request $request) {
        Meeting::where('doctor_id',auth('doctor_api')->user()->id)->first()->update([
            'status'=>'rejected'
        ]);
        return response()->json([
            'message'=>'rejectMeeting successfully'
        ]);
    }
    public function update(Request $request, $id)
    {
        //
    }
    public function destroy($id)
    {
        //
    }
    
}
