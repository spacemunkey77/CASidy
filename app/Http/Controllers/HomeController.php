<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Auth;

use App\Models\Notify;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function apikey()
    {
        $token = Str::random(80);

        $user = Auth::user();

        $user->forceFill([
            'api_token' => hash('sha256', $token),
        ])->save();

        return view('api', compact('token'));

    }

    public function optin() {
        return view('optin');
    }

    public function optout() {
        return view('optout');
    }

    public function saveoptin(Request $request) {

        $validatedData = $request->validate([
            'phone_number' => 
                array(
                    'required',
                    'regex:/\+1[\d{10}/'
                ),
            'optin' => 'required|integer',
        ]);

        $user = Auth::user();

        $notify = Notify::where('user_id',$user->id)->first();

        if ($notify) {
            $notify->sms = $validatedData['phone_number'];
            $notify->optin = 1;
            $notify->save();
        } else {
            $newNotify = [
                'sms' => $validatedData['phone_number'],
                'optin' => 1,
                'email' => $user->email,
                'user_id' => $user->id,
            ];
            Notify::create($newNotify);
        }

        return view('optin')->with('success', 'Opted In Successfully!');

    }
    
    public function saveoptout(Request $request) {

        $validatedData = $request->validate([
            'phone_number' => 
                array(
                    'required',
                    'regex:/\+1[\d{10}/'
                ),
            'optout' => 'required|integer',
        ]);
        
        $user = Auth::user();
        $notify = Notify::where('user_id',$user->id)->first();

        if ($notify->sms === $validatedData['phone_number']) {

            $notify->sms = null;
            $notify->optin = 0;
            $notify->save();

            return view('optout')->with('success', 'Opted Out Successfully!');

        } else {

            return view('optout')->with('success', 'Failed to Opt Out. Mobile Number Mis-Match!');

        }

        
    }

}
