<?php

namespace App\Http\Controllers;

use App\Models\UserCode;
use Illuminate\Http\Request;
use MongoDB\Driver\Session;

class TwoFAController extends Controller
{
    public function index()
    {
        return view('2fa');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required'
        ]);
        $find = UserCode::where('user_id', auth()->user()->id)
            ->where('code', $request->code)
            ->where('updated_at', '>=', now()->subMinute(2))
            ->first();
        if (!is_null($find)) {
            \Illuminate\Support\Facades\Session::put('user_2fa', auth()->user()->id);
            return redirect()->route('home');
        }
        return back()->with('error', 'you entered wrong code');
    }

    public function resend()
    {
        auth()->user()->generateCode();
        return back()->with('success', 'We sent you code on your email.');
    }
}
