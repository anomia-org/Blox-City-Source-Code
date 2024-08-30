<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Token;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\UsernameHistory;
use Closure;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        if(Setting::where('register_enabled', '0')->get()->first())
        {
            return abort('403');
        }
        
        if (UsernameHistory::where('username', '=', $data['username'])->exists()) {
            return back()->with('error', 'Username has already been taken.');
        }

        return Validator::make($data, [
            'username' => ['required', 'strictly_profane', 'string', 'min:3', 'max:20', 'regex:/\\A[a-z\\d]+(?:[.-][a-z\\d]+)*\\z/i', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'birthday' => ['required', 'date'],
            'tos_agree' => ['required'],
            'h-captcha-response' => ['hcaptcha'],
        ],
        [
            'regex' => 'The :attribute format is invalid.',
            'strictly_profane' => 'The :attribute contains a profane word.',
            'required' => 'The :attribute field is required.',
            'unique' => 'The :attribute has already been taken.',
            'string' => 'The :attribute must be a string.',
            'date' => 'The :attribute is not a valid date.',
            'confirmed' => 'The :attribute confirmation does not match.',
            'email' => 'The :attribute must be a valid email address.',
            'max' => [
                    'array' => 'The :attribute must not have more than :max items.',
                    'file' => 'The :attribute must not be greater than :max kilobytes.',
                    'numeric' => 'The :attribute must not be greater than :max.',
                    'string' => 'The :attribute must not be greater than :max characters.',
                ],
            'min' => [
                    'array' => 'The :attribute must have at least :min items.',
                    'file' => 'The :attribute must be at least :min kilobytes.',
                    'numeric' => 'The :attribute must be at least :min.',
                    'string' => 'The :attribute must be at least :min characters.',
                ],
            'h-captcha-response' => [
                'h-captcha-response' => 'Failed to verify user is human. Please try again.',
            ]

        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User 
     */
    protected function create(array $data)
    {
        if(Setting::where('register_enabled', '0')->get()->first())
        {
            return abort('403');
        }



        return User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'birthday' => $data['birthday'],
        ]);
    }
}
