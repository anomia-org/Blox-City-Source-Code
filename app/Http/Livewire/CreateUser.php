<?php

namespace App\Http\Livewire;

use App\Models\Privacy;
use App\Models\Setting;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Illuminate\Foundation\Auth\RegistersUsers;

class CreateUser extends Component
{
    use RegistersUsers;

    public $username;
    public $email;
    public $password;
    public $password_confirmation;
    public $birthday;
    public $tos_agree;

    protected $rules = [
        'username' => ['required', 'string', 'min:3', 'max:20', 'unique:users', 'regex:/^[0-9A-Za-z.\-_]+$/'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
        'birthday' => ['required', 'date'],
        'tos_agree' => ['required'],
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function create()
    {
        $validatedData = $this->validate();

        if(Setting::where('register_enabled', '0')->get()->first())
        {
            return abort('403');
        }

        // Creates and grabs new user model
        $user = User::create([
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'birthday' => $validatedData['birthday'],
            'avatar_url' => '1',
        ]);

        // Creates user privacy policy
        Privacy::create([
            'user_id' => $user->id,
        ]);

        // Creates user avatar


        return redirect('/login');
    }

    public function render()
    {
        return view('livewire.create-user');
    }
}
