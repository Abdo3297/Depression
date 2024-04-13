<?php

namespace App\Http\Controllers\Statistics;

use App\Models\Patient;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StatisticsController extends Controller
{
    public function statistics() 
    {
        $numberOfUsers = Patient::count();
        $numberOfUsersHasFillForm = Patient::where('result', '!=', NULL)->count();
        $numberOfUsersHasDisease = Patient::where('result', '=', 'yes')->count();
        $numberOfUsersHasNotDisease = $numberOfUsersHasFillForm - $numberOfUsersHasDisease;
        
        $data = [
            'All Patient' => $numberOfUsers,
            'All Patient Has Fill Form' => $numberOfUsersHasFillForm,
            'Patient With Depression' => $numberOfUsersHasDisease,
            'Patient Without Depression' => $numberOfUsersHasNotDisease
        ];
        return $this->okResponse('some statistics for doctor about patients',$data);
    }
}
