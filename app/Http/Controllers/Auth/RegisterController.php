<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/client/dashboard';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20'],
            'gender' => ['required'],
            'date_of_birth' => ['required', 'date'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->assignRole('client');

        $clientId = time();

        $client = Client::create([
            'id' => $clientId,
            'gender' => $data['gender'],
            'date_of_birth' => $data['date_of_birth'],
            'avatar_image' => 'default-avatar.jpg',
            'phone' => $data['phone'],
            'area_id' => 144401,
            'street_name' => 'Not Added',
            'building_no' => 0,
            'floor_number' => 0,
            'flat_number' => 0,
            'is_main' => 1,
            'user_id' => $user->id,
        ]);

        Address::create([
            'client_id' => $clientId,
            'area_id' => 144401,
            'street_name' => 'Not Added',
            'building_number' => 0,
            'floor_number' => 0,
            'flat_number' => 0,
            'is_main' => 1,
        ]);

        return $user;
    }
}