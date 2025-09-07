<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showForm()
    {
        $countries = Country::all();
        $cities    = [];

        if (old('country_id')) {
            $cities = City::where('country_id', old('country_id'))->get();
        }

        return view('auth.register', compact('countries', 'cities'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|min:6|confirmed',
            'gender'     => 'required|in:male,female,other',
            'country_id' => 'required|exists:countries,id',
            'city_id'    => 'required|exists:cities,id',
            'role'       => 'required|in:user,admin',
            'terms'      => 'accepted',
        ]);

        try {
            $user = User::create([
                'name'       => $validated['name'],
                'email'      => $validated['email'],
                'password'   => $validated['password'], // assume auto-hashed via mutator or cast
                'gender'     => $validated['gender'],
                'country_id' => $validated['country_id'],
                'city_id'    => $validated['city_id'],
                'role'       => $validated['role'],
                'terms'      => true,
            ]);

            Auth::login($user);

            return redirect()->route('/')->with('success', 'User registered successfully!');
        } catch (\Exception $e) {
            // You can log error here too if needed
            // Log::error($e->getMessage());
            // \Log::error('Register Error: '.$e->getMessage());

            return redirect()->back()->withInput()->with('error', 'Something went wrong while registering the user.');
        }
    }

    public function getCities($country_id)
    {
        $cities = City::where('country_id', $country_id)->orderBy('name')->get();

        return response()->json($cities);
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended()->with('success', 'login successful!');
            
        }

        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Logout successful!');
    }

    public function userDashboard()
    {
        return view('user.dashboard');
    }

    public function adminDashboard()
    {
        return view('admin.dashboard');
    }

}
