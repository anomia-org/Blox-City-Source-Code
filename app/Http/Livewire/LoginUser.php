<?php

namespace App\Http\Livewire;

use App\Http\Controllers\UserController;
use App\Models\Privacy;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LoginUser extends Component
{

    public $username;
    public $password;

    protected $rules = [
        'username' => ['required', 'string', 'min:3', 'max:20', 'regex:/^[0-9A-Za-z.\-_]+$/'],
        'password' => ['required', 'string'],
    ];

    public function username()
    {
        return 'username';
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function login()
    {
        $validatedData = $this->validate();

        $user = Auth::attempt($validatedData);

        return $this->redirect('/dashboard');
    }


    public function render()
    {
        return view('livewire.login-user');
    }
}
