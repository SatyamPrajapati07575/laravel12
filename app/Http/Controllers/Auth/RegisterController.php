<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\BulkUserUploadJob;
use App\Models\BulkUpload;
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
        $users = User::all();
        return view('admin.dashboard', compact('users'));
    }

    public function bulkUploadPage()
    {
        $uploads = BulkUpload::latest()->paginate(10);
        return view('admin.bulk_uploads', compact('uploads'));
    }

    public function bulkUpload(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt|max:20480',
        ]);

        $path = $request->file('csv_file')->store('uploads');

        $bulkUpload = BulkUpload::create([
            'file_path' => $path,
            'status'    => 'pending',
        ]);

        BulkUserUploadJob::dispatch($bulkUpload->id);
        return back()->with('success', 'CSV uploaded! Processing started.');
    }
    public function downloadSample()
    {
        $filename = "sample_users.csv";
        $headers  = [
            "Content-Type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
        ];

        $columns = ['name', 'email', 'password', 'gender', 'role'];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            // Header
            fputcsv($file, $columns);

            // Example rows
            fputcsv($file, ['Rohan', 'rohan@example.com', 'password123', 'male', 'user']);
            fputcsv($file, ['Admin User', 'admin@example.com', 'admin123', 'male', 'admin']);
            fputcsv($file, ['Rohan', 'rohan@example.com', 'password123', 'male', 'user']);
            fputcsv($file, ['Admin User', 'admin@example.com', 'admin123', 'male', 'admin']);
            fputcsv($file, ['Neha Sharma', 'neha1@example.com', 'pass123', 'female', 'user']);
            fputcsv($file, ['Amit Singh', 'amit2@example.com', 'pass234', 'male', 'user']);
            fputcsv($file, ['Priya Verma', 'priya3@example.com', 'pass345', 'female', 'user']);
            fputcsv($file, ['Deepak Yadav', 'deepak4@example.com', 'pass456', 'male', 'user']);
            fputcsv($file, ['Anjali Mehra', 'anjali5@example.com', 'pass567', 'female', 'admin']);
            fputcsv($file, ['Vikram Joshi', 'vikram6@example.com', 'pass678', 'male', 'user']);
            fputcsv($file, ['Kriti Kapoor', 'kriti7@example.com', 'pass789', 'female', 'user']);
            fputcsv($file, ['Suman Mishra', 'suman8@example.com', 'pass890', 'female', 'admin']);
            fputcsv($file, ['Rahul Rathi', 'rahul9@example.com', 'pass901', 'male', 'user']);
            fputcsv($file, ['Pooja Sinha', 'pooja10@example.com', 'pass012', 'female', 'user']);
            fputcsv($file, ['Rajiv Bhat', 'rajiv11@example.com', 'pass123', 'male', 'user']);
            fputcsv($file, ['Meena Gupta', 'meena12@example.com', 'pass234', 'female', 'user']);
            fputcsv($file, ['Tarun Jain', 'tarun13@example.com', 'pass345', 'male', 'admin']);
            fputcsv($file, ['Ritu Das', 'ritu14@example.com', 'pass456', 'female', 'user']);
            fputcsv($file, ['Mohit Khanna', 'mohit15@example.com', 'pass567', 'male', 'user']);
            fputcsv($file, ['Sneha Rawat', 'sneha16@example.com', 'pass678', 'female', 'user']);
            fputcsv($file, ['Nikhil Chauhan', 'nikhil17@example.com', 'pass789', 'male', 'admin']);
            fputcsv($file, ['Simran Kaur', 'simran18@example.com', 'pass890', 'female', 'user']);
            fputcsv($file, ['Ramesh Patel', 'ramesh19@example.com', 'pass901', 'male', 'user']);
            fputcsv($file, ['Sunita Rani', 'sunita20@example.com', 'pass012', 'female', 'user']);
            fputcsv($file, ['Kunal Arora', 'kunal21@example.com', 'pass123', 'male', 'user']);
            fputcsv($file, ['Divya Jain', 'divya22@example.com', 'pass234', 'female', 'admin']);
            fputcsv($file, ['Harsh Singh', 'harsh23@example.com', 'pass345', 'male', 'user']);
            fputcsv($file, ['Nisha Yadav', 'nisha24@example.com', 'pass456', 'female', 'user']);
            fputcsv($file, ['Manoj Tiwari', 'manoj25@example.com', 'pass567', 'male', 'user']);
            fputcsv($file, ['Swati Desai', 'swati26@example.com', 'pass678', 'female', 'admin']);
            fputcsv($file, ['Gaurav Rana', 'gaurav27@example.com', 'pass789', 'male', 'user']);
            fputcsv($file, ['Isha Kapoor', 'isha28@example.com', 'pass890', 'female', 'user']);
            fputcsv($file, ['Ankur Mehta', 'ankur29@example.com', 'pass901', 'male', 'user']);
            fputcsv($file, ['Pallavi Rao', 'pallavi30@example.com', 'pass012', 'female', 'admin']);
            fputcsv($file, ['Alok Pandey', 'alok31@example.com', 'pass123', 'male', 'user']);
            fputcsv($file, ['Tanya Bansal', 'tanya32@example.com', 'pass234', 'female', 'user']);
            fputcsv($file, ['Dinesh Joshi', 'dinesh33@example.com', 'pass345', 'male', 'user']);
            fputcsv($file, ['Rekha Sharma', 'rekha34@example.com', 'pass456', 'female', 'user']);
            fputcsv($file, ['Ajay Bhatt', 'ajay35@example.com', 'pass567', 'male', 'admin']);
            fputcsv($file, ['Mansi Garg', 'mansi36@example.com', 'pass678', 'female', 'user']);
            fputcsv($file, ['Karan Malhotra', 'karan37@example.com', 'pass789', 'male', 'user']);
            fputcsv($file, ['Preeti Nair', 'preeti38@example.com', 'pass890', 'female', 'user']);
            fputcsv($file, ['Saurabh Singh', 'saurabh39@example.com', 'pass901', 'male', 'admin']);
            fputcsv($file, ['Shruti Jain', 'shruti40@example.com', 'pass012', 'female', 'user']);
            fputcsv($file, ['Yogesh Deshmukh', 'yogesh41@example.com', 'pass123', 'male', 'user']);
            fputcsv($file, ['Tanvi Patel', 'tanvi42@example.com', 'pass234', 'female', 'user']);
            fputcsv($file, ['Ravi Shankar', 'ravi43@example.com', 'pass345', 'male', 'user']);
            fputcsv($file, ['Bhavna Saini', 'bhavna44@example.com', 'pass456', 'female', 'admin']);
            fputcsv($file, ['Hemant Thakur', 'hemant45@example.com', 'pass567', 'male', 'user']);
            fputcsv($file, ['Payal Mehra', 'payal46@example.com', 'pass678', 'female', 'user']);
            fputcsv($file, ['Suraj Singh', 'suraj47@example.com', 'pass789', 'male', 'user']);
            fputcsv($file, ['Ishita Sen', 'ishita48@example.com', 'pass890', 'female', 'admin']);
            fputcsv($file, ['Ritik Bansal', 'ritik49@example.com', 'pass901', 'male', 'user']);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

}
