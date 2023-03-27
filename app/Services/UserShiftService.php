<?php

namespace App\Services;

use App\Models\Estate;
use App\Models\User;
use App\Models\UserShift;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class UserShiftService {

    private Carbon $date;

    public function __construct() {
        $this->date = Carbon::now();
    }

    public function checkUserShift()
    {
        foreach($this->getActualUserShifts() as $userShift)
        {
            if($userShift->date_from->lte($this->date) && $userShift->date_to->gt($this->date) && empty($userShift->temp_changes))
            {
                $this->processUserShift($userShift);
            }
            elseif($userShift->date_to->lte($this->date))
            {
                $this->rollbackUserShift($userShift);
            }
        }
    }

    private function rollbackUserShift(UserShift $userShift)
    {
        foreach($userShift->estates as $estate)
        {
            $this->changeEstateSupervisor($estate, $userShift->user);
        }
    }

    private function processUserShift(UserShift $userShift)
    {
        $substituteUser = $userShift->substituteUser;
        $estates = $userShift->user->estates;

        foreach($estates as $estate)
        {
            $this->changeEstateSupervisor($estate, $substituteUser);
        }
        $this->userShiftSetChanges($userShift, $estates->pluck('id')->toArray());
    }

    private function userShiftSetChanges(UserShift $userShift, array $estatesIds)
    {
        $userShift->temp_changes = $estatesIds;
        $userShift->save();
    }

    private function changeEstateSupervisor(Estate $estate, User $substituteUser)
    {
        $estate->supervisor_user_id = $substituteUser->user_id;
        $estate->save();
    }

    private function getActualUserShifts() : Collection
    {
        $userShifts = UserShift::whereDate('date_from', $this->date)->orWhereDate('date_to', $this->date)->orderBy('date_from')->get();
        return $userShifts;
    }
}
