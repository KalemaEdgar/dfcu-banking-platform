<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function signIn(Request $request)
    {
        info('Request:', [
            'login request for' => $request->email,
            'received_at' => now()->format('c'),
        ]);

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|alpha_num',
        ]);

        if ($validator->fails()) {
            $response = [
                'status' => 'Failed',
                'message' => $validator->errors()->first(),
                'responded_at' => now()->format('c'),
            ];
            Log::debug('Response:', $response);
            // return response($response, 422);
            return back()->with('error', $response['message']);
        }

        $attributes = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (! Auth::attempt($attributes)) {
            $response = [
                'status' => 'Failed',
                'message' => 'Invalid credentials',
                'responded_at' => now()->format('c'),
            ];
            info('Response:', $response);
            // return response($response, 422);
            return back()->with('error', $response['message']);
        }

        session()->regenerate();

        $response = [
            'status' => 'success',
            'message' => 'Welcome, ' . auth()->user()->first_name . ' ' . auth()->user()->last_name,
            'responded_at' => now()->format('c'),
        ];

        info('Response:', $response);

        return redirect()->route('dashboard');
        // return response($response, 200);
    }

    public function signOut(Request $request)
    {
        info('Request:', ['Logout request', 'received_at' => now()->format('c')]);

        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $response = [
            'status' => 'success',
            'message' => 'Logged out successfully.',
            'responded_at' => now()->format('c'),
        ];

        info('Response:', $response);

        // return response($response, 200);
        return redirect()->route('home')->with('success', 'Logged out successfully');
    }
}
