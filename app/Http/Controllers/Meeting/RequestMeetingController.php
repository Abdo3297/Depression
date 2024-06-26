<?php

namespace App\Http\Controllers\Meeting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Meeting\RequestMeetingRequest;
use App\Http\Resources\RequestMeetingResource;
use App\Models\Meeting;

class RequestMeetingController extends Controller
{
    public function requestMeeting(RequestMeetingRequest $request)
    {  
        $data = $request->validated();
        $meeting = Meeting::create($data);
        return $this->okResponse('request sent successfully',RequestMeetingResource::make($meeting));
    }
    public function responseMeetings()
    {
        $meeting = Meeting::where('patient_id',auth('patient_api')->user()->id)->paginate(5);
        if ($meeting) {
            return $this->okResponse('data fetched successfully',RequestMeetingResource::collection($meeting));
        }
        return $this->errorResponse('data not found');
    }
}
