<?php

namespace App\Http\Controllers\Availability;

use Carbon\Carbon;
use App\Models\Availability;
use App\Http\Controllers\Controller;
use App\Http\Resources\AvailabilityResource;
use App\Http\Requests\Availability\StoreAvailabilityRequest;
use App\Http\Requests\Availability\UpdateAvailabilityRequest;

class AvailabilityController extends Controller
{
    public function index()
    {
        if (Availability::exists()) {
            $availabilities = Availability::where('doctor_id', auth()->user()->id)->paginate();
            return $this->paginateResponse(AvailabilityResource::collection($availabilities));
        }
        return $this->errorResponse('data not found');
    }
    public function show($id)
    {
        $availabilities = Availability::where('doctor_id', auth()->user()->id)->find($id);
        if ($availabilities) {
            return $this->okResponse('data fetched successfully', AvailabilityResource::make($availabilities));
        }
        return $this->errorResponse('data not found');
    }
    public function store(StoreAvailabilityRequest $request)
    {
        $data = $request->validated();

        $from = Carbon::createFromFormat('g:i A', $data['from']);
        $to = Carbon::createFromFormat('g:i A', $data['to']);


        if ($to->lte($from)) {
            $to->addDay();
        }

        $diffMinutes = $to->diffInMinutes($from);
        if ($diffMinutes != 60) {
            return $this->errorResponse('The time range exceeds 60 minutes.');
        }

        $availability = Availability::create($data);

        return $this->createResponse(AvailabilityResource::make($availability));
    }
    public function update(StoreAvailabilityRequest $request, $id)
    {
        $data = $request->validated();

        $from = Carbon::createFromFormat('g:i A', $data['from']);
        $to = Carbon::createFromFormat('g:i A', $data['to']);

        if ($to->lte($from)) {
            $to->addDay();
        }

        $diffMinutes = $to->diffInMinutes($from);
        if ($diffMinutes != 60) {
            return $this->errorResponse('The time range must be exactly 60 minutes.');
        }

        $availability = Availability::findOrFail($id);
        $availability->update($data);

        return $this->okResponse('record updated',AvailabilityResource::make($availability));

    }

    public function destroy($id)
    {
        $availabilities = Availability::where('doctor_id', auth()->user()->id)->find($id);
        if ($availabilities) {
            $availabilities->delete();
            return $this->okResponse('record deleted', []);
        }
        return $this->errorResponse('record not found');
    }
}
