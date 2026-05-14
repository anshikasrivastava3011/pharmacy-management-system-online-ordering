<?php

namespace App\Http\Controllers;

use App\DataTables\ClientsDataTable;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Address;
use App\Models\Area;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function index(ClientsDataTable $dataTable)
    {
        return $dataTable->render('clients.index', ['areas' => Area::all()]);
    }

    public function show($national_id)
    {
        $client = Client::where('id', '=', $national_id)->first();
        $user = User::where('id', $client->user_id)->first();
        $addresses = Address::where('client_id', $client->id)->get();
        foreach ($addresses as $address) {
            $address->area_name = $address->area->name;
        }
        return response()->json(['client' => $client, 'user' => $user, 'addresses' => $addresses]);
    }

public function update(UpdateClientRequest $request, $national_id)
{
    if (is_numeric($national_id)) {
        try {
            $client = Client::where('id', '=', $national_id)->first();

            if (!$client) {
                return to_route('clients.index')
                    ->with('error', 'Client not found.')
                    ->with('timeout', 3000);
            }

            $userData = [];
            $userData['name'] = $request->name;
            $userData['email'] = $request->email;

            User::where('id', $client->user_id)->update($userData);

            $clientData = [];
            $clientData['id'] = $request->id;
            $clientData['date_of_birth'] = $request->date_of_birth;
            $clientData['gender'] = $request->gender;
            $clientData['phone'] = $request->phone;

            if ($request->hasFile('avatar_image')) {
                $avatar = $request->file('avatar_image');
                $avatarName = time() . '_' . $avatar->getClientOriginalName();
                $avatar->storeAs('public/clients_Images', $avatarName);
                $clientData['avatar_image'] = $avatarName;
            }

            $client->update($clientData);

        } catch (\Illuminate\Database\QueryException $exception) {
            return to_route('clients.index')
                ->with('error', 'Error in Updating Client Record.')
                ->with('timeout', 3000);
        }

        return to_route('clients.index')
            ->with('success', 'Client Record Updated Successfully.')
            ->with('timeout', 3000);
    }
}

    public function store(StoreClientRequest $request)
    {
        try {

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            if ($request->hasFile('avatar_image')) {
                $avatar = $request->file('avatar_image');
                $avatar_name = $avatar->getClientOriginalName();
                $avatar->storeAs('public/clients_Images', $avatar_name);
            } else {
                $avatar_name = 'default-avatar.jpg';
            }

            $isChecked = 0;
            if ($request->has('is_main')) {
                $isChecked = 1;
            }

            Client::create([
                'id' => $request->id,
                'user_id' => $user->id,
                'avatar_image' => $avatar_name,
                'gender' => $request->gender,
                'date_of_birth' => $request->date_of_birth,
                'phone' => $request->phone,
            ]);

            $user->assignRole('client');
        } catch (\Illuminate\Database\QueryException $exception) {
            return to_route('clients.index')->with('error', 'Error in Creating Client.')->with('timeout', 3000);
        }
        return to_route('clients.index')->with('success', 'Client has been created successfully!')->with('timeout', 3000);
    }

    public function destroy($national_id)
    {
        if (is_numeric($national_id)) {
            try {
                $client = Client::where('id', '=', $national_id)->first();
                $user = User::where('id', $client->user_id)->first();
                Client::destroy($national_id);
                User::destroy($user->id);
            } catch (\Illuminate\Database\QueryException $exception) {
                return to_route('clients.index')->with('error', 'Error in Deleting Client Record Please Delete Related Records First.')->with('timeout', 3000);
            }
            return to_route('clients.index')->with('success', 'Client Record Deleted Successfully.')->with('timeout', 3000);
        }
    }
    public function clientProfile()
{
    $user = Auth::user();

    $client = Client::where('user_id', $user->id)->firstOrFail();
    $address = Address::where('client_id', $client->id)->where('is_main', 1)->first();
    $areas = Area::all();

    return view('client.profile', compact('user', 'client', 'address', 'areas'));
}

public function updateClientProfile(Request $request)
{
    $request->validate([
        'phone' => 'required|string|max:20',
        'area_id' => 'required|exists:areas,id',
        'street_name' => 'required|string|max:255',
        'building_number' => 'required|integer|min:0',
        'floor_number' => 'required|integer|min:0',
        'flat_number' => 'required|integer|min:0',
    ]);

    $user = Auth::user();
    $client = Client::where('user_id', $user->id)->firstOrFail();

    $client->update([
        'phone' => $request->phone,
        'area_id' => $request->area_id,
        'street_name' => $request->street_name,
        'building_no' => $request->building_number,
        'floor_number' => $request->floor_number,
        'flat_number' => $request->flat_number,
        'is_main' => 1,
    ]);

    Address::updateOrCreate(
        [
            'client_id' => $client->id,
            'is_main' => 1,
        ],
        [
            'area_id' => $request->area_id,
            'street_name' => $request->street_name,
            'building_number' => $request->building_number,
            'floor_number' => $request->floor_number,
            'flat_number' => $request->flat_number,
        ]
    );

    return redirect()->route('client.profile')->with('success', 'Profile and address updated successfully!');
}
}