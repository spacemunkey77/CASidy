<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LockRequest;

use App\Automation\SmartThings\Lock;

class DoorController extends Controller
{
    public function august(LockRequest $request) {

        $lock = new Lock();

        $lockmode = $request->lockmode;

        switch($lockmode) {
            case "lock":
                $r = $lock->operate("lock");

                if( $r["status"] == "locked") {
                    $status = True;
                } else {
                    $status = False;
                }
                break;
            case "unlock":
                $r = $lock->operate("unlock");

                if( $r["status"] == "unlocked" ) {
                    $status = True;
                } else {
                    $status = False;
                }
                break;
        }

        return response()->json(["status" => $status, "mode" => $lockmode]);

    }

}
