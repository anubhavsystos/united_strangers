<?php

namespace App\Http\Controllers\Auth;

use App\Providers\RouteServiceProvider;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;


class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $message = get_phrase('Welcome back ____');
        $message = str_replace('____', user('name'), $message);
        Session::flash('success', $message);

        if (user('role') == 1) {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('customer.wishlist');
        }
    }

    /**
     * Redirect to Google for authentication
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
        // If you hit "state mismatch" errors in some setups, try ->stateless() as a last resort:
        // return Socialite::driver('google')->stateless()->redirect();
    }

    /**
     * Handle callback from Google
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            // stateless() can help with some session issues or when using APIs.
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            Session::flash('error', 'Google login failed: '.$e->getMessage());
            return redirect()->route('login');
        }

        // Try to find user by google_id OR by email (so existing accounts can be linked)
        $user = User::where('google_id', $googleUser->getId())
                    ->orWhere('email', $googleUser->getEmail())
                    ->first();

        if ($user) {
            // if user exists but google_id is empty, attach it
            if (!$user->google_id) {
                $user->google_id = $googleUser->getId();
                $user->save();
            }
        } else {
            // Create a new user (set role as per your app — here defaulted to 2/customer)
            $user = User::create([
                'name' => $googleUser->getName() ?? $googleUser->getNickname() ?? 'No name',
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'password' => Hash::make(Str::random(24)), // random password (not used)
                'role' => 2,
            ]);
        }

        // Log in and regenerate session
        Auth::login($user, true);
        $request->session()->regenerate();

        $message = get_phrase('Welcome back ____');
        $message = str_replace('____', $user->name, $message);
        Session::flash('success', $message);

        if ($user->role == 1) {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('customer.wishlist');
        }
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    // OTP login 
public function checkPhone(Request $request)
{
    $request->validate([
        'phone' => 'required|digits:10'
    ]);

    $exists = User::where('phone', $request->phone)->exists();

    return response()->json([
        'exists' => $exists
    ]);
}


    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|digits:10'
        ]);

        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            return response()->json(['status' => false, 'message' => 'Mobile number not found!']);
        }

        $otp = rand(100000, 999999);

        Session::put('otp', $otp);
        Session::put('otp_phone', $request->phone);

        return response()->json(['status' => true, 'message' => 'OTP sent successfully!', 'otp' => $otp]);
    }

   public function verifyOtp(Request $request)
{
    $request->validate([
        'otp' => 'required|digits:6'
    ]);

    if (Session::get('otp') == $request->otp) {
        $user = User::where('phone', Session::get('otp_phone'))->first();
        if ($user) {
            Auth::login($user);
            Session::forget(['otp', 'otp_phone']); 
            return response()->json([
                'status' => true,
                'redirect' => $user->role == 1 
                    ? route('admin.dashboard') 
                    : route('customer.wishlist')
            ]);
        }
    }

    return response()->json([
        'status' => false,
        'message' => 'Invalid OTP!'
    ]);
}


}
