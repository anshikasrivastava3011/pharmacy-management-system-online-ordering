<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Area;
use App\Models\Client;
use App\Models\Doctor;
use App\Models\Medicine;
use App\Models\Pharmacy;
use App\Models\Prescription;
use App\Models\User;
use Illuminate\Http\Request;
use App\DataTables\OrdersDataTable;
use App\Http\Requests\StoreOrderRequest;
use App\Jobs\OrderConfirmationJob;
use App\Models\Order;
use App\Models\OrderMedicine;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmation;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(OrdersDataTable $dataTable)
    {
        return $dataTable->render('order.index', ['pharmacies' => Pharmacy::all(), 'doctors' => Doctor::all(), 'medicines' => Medicine::all(), 'users' => User::all(), 'clients' => Client::all(), 'addresses' => Address::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }


    public function store(StoreOrderRequest $request)
    {
        $client = Client::where("user_id", "=", $request->user_id)->first();
        $pharmacy = Pharmacy::find($request->pharmacy_id);
        if ($client->address->find($request->delivering_address_id)) {
            if ($request->doctor_id == null || $pharmacy->doctors->find($request->doctor_id)) {
                $quantity = array_map('intval', $request->quantity);
                $orderMedicine = $request->medicine_id;
                $order = Order::create([
                    'user_id' => $request->user_id,
                    'pharmacy_id' => $request->pharmacy_id,
                    'doctor_id' => $request->doctor_id,
                    'creator_type' => $request->creator_type,
                    'status' => $request->status,
                    'is_insured' => $request->has('is_insured') ? 1 : 0,
                    'delivering_address_id' => $request->delivering_address_id,
                    'price' => 0,
                ]);
                try {
                    Order::createOrderMedicine($order, $quantity, $orderMedicine);
                    $order->price = Order::totalPrice($quantity, $orderMedicine);
                    $order->save();
                    if ($order->status == "WaitingForUserConfirmation") {
                        $user = User::where('id', '=', $order->user_id)->first();
                        dispatch(new OrderConfirmationJob($user, $order));
                    }
                } catch (\ErrorException $e) {
                    return redirect()->route('orders.index')->with('error', 'Error there is no medicine with this name !')->with('timeout', 5000);
                }
                return to_route('orders.index')->with('success', 'Order created successfully!')->with('timeout', 5000);
            } else {
                return to_route('orders.index')->with('error', 'The selected doctor does not work in this pharmacy, Please choose the correct doctor!');
            }
        } else {
            return to_route('orders.index')->with('error', 'Delivery address doesn\'t belong to this client!');
        }
    }





    public function show($id)
    {
        $order = Order::with('medicines')->find($id);
        $user = User::find($order->user_id);
        $pharmacy = Pharmacy::find($order->pharmacy_id);
        $doctor = Doctor::find($order->doctor_id);
        $doctor_name = $doctor ? User::find($doctor->user_id) : null;
        $address = Address::find($order->delivering_address_id);
        $area = $address ? Area::find($address->area_id) : null;
        $prescriptions = Prescription::where('order_id', $order->id)->get();

        return response()->json([
            'order'       => $order,
            'user'        => $user,
            'pharmacy'    => $pharmacy,
            'doctor'      => $doctor,
            'doctor_name' => $doctor_name,
            'address'     => $address,
            'area'        => $area,
            'prescriptions' => $prescriptions,
        ]);
    }

    public function edit($id)
    {
        $order = Order::with('medicines')->find($id);
        $user = User::find($order->user_id);
        $pharmacy = Pharmacy::find($order->pharmacy_id);
        $doctor = Doctor::find($order->doctor_id);
        return view('order.edit', ['order' => $order, 'user' => $user, 'pharmacy' => $pharmacy, 'doctor' => $doctor]);
    }

    public function update(StoreOrderRequest $request, $id)
{
    if (is_numeric($id)) {

        $order = Order::find($id);

        if (!is_null($request->quantity)) {
            $editedQuantity = array_map('intval', $request->quantity);
        } else {
            $editedQuantity = [];
        }

        $editedOrderMedicine = $request->medicine_id;

        try {

            // UPDATE ORDER DETAILS
            $order->update([
                'doctor_id' => $request->doctor_id,
                'creator_type' => $request->creator_type,
                'status' => $request->status,
                'is_insured' => $request->has('is_insured') ? 1 : 0,
            ]);

            // UPDATE MEDICINES ONLY IF PROVIDED
            if (!empty($editedOrderMedicine) && !empty($editedQuantity)) {

                Order::updateOrderMedicine(
                    $order,
                    $editedQuantity,
                    $editedOrderMedicine
                );

                $order->price = Order::totalPrice(
                    $editedQuantity,
                    $editedOrderMedicine
                );

                $order->save();
            }

            // SEND EMAIL ONLY FOR WAITING STATUS
            if ($order->status == "WaitingForUserConfirmation") {

                $user = User::where('id', '=', $order->user_id)->first();

                dispatch(new OrderConfirmationJob($user, $order));
            }

        } catch (\Exception $e) {

            return redirect()
                ->route('orders.index')
                ->with('error', 'Error in Updating Order!')
                ->with('timeout', 5000);
        }

        return redirect()
            ->route('orders.index')
            ->with('success', 'Orders has been updated successfully!')
            ->with('timeout', 5000);
    }
}

    public function destroy($id)
    {
        $order = Order::with('medicines')->find($id);
        $order->medicines()->detach();
        Prescription::where("order_id", $id)->delete();
        $order->delete();
        return to_route('orders.index')->with('success', 'order deleted successfully!')->with('timeout', 5000);
    }

    public function confirm($order_id)
    {
        if (!is_numeric($order_id)) {
            abort(404);
        }

        $order = Order::where('id', $order_id)->firstOrFail();

        if ($order->status === 'WaitingForUserConfirmation') {
            $order->update(['status' => 'Confirmed']);
            return view('actions.confirm', ['order' => $order, 'state' => 'Confirmednow']);
        }

        return view('actions.confirm', ['order' => $order, 'state' => $order->status]);
    }

    public function updatestatus($order_id)
    {
        if (is_numeric($order_id)) {

            $order = Order::where('id', $order_id)->first();
            if ($order->status == "WaitingForUserConfirmation") {
                $order->update([
                    "status" =>  "Canceled"
                ]);
                return view('actions.cancel', ['order' => $order, 'state' => "WaitingForUserConfirmation"]);
            } elseif ($order->status == "Canceled") {
                return view('actions.cancel', ['order' => $order, 'state' => "Canceled"]);
            } elseif ($order->status == "Confirmed" || $order->status == "Delivered") {
                return view('actions.cancel', ['order' => $order, 'state' => "Confirmed"]);
            }
        }
    }
public function clientCreateOrder($medicineId)
{
    $medicine = Medicine::findOrFail($medicineId);

    return view('client.create_order', compact('medicine'));
}

public function clientStoreOrder(Request $request)
{
    $request->validate([
        'medicine_id' => 'required|exists:medicines,id',
        'quantity' => 'required|integer|min:1',
    ]);

    $user = Auth::user();
    $client = Client::where('user_id', $user->id)->firstOrFail();

    $medicine = Medicine::findOrFail($request->medicine_id);
    $pharmacy = Pharmacy::first();

    if (!$pharmacy) {
        return redirect()->route('client.medicines')
            ->with('error', 'No pharmacy available.');
    }

    $address = Address::where('client_id', $client->id)
        ->where('is_main', 1)
        ->first();

    if (!$address) {
        return redirect()->route('client.profile')
            ->with('error', 'Please complete your profile address first.');
    }

    $totalPrice = $medicine->price * $request->quantity;

    $order = Order::create([
        'user_id' => $user->id,
        'pharmacy_id' => $pharmacy->id,
        'doctor_id' => null,
        'delivering_address_id' => $address->id,
        'creator_type' => 'client',
        'status' => 'Processing',
        'is_insured' => 0,
        'price' => $totalPrice,
    ]);

    OrderMedicine::create([
        'order_id' => $order->id,
        'medicine_id' => $medicine->id,
        'quantity' => $request->quantity,
    ]);

    return redirect()->route('client.orders')
        ->with('success', 'Order placed successfully!');
}

public function clientOrders()
{
    $orders = Order::with('medicines', 'address')
        ->where('user_id', Auth::id())
        ->latest()
        ->get();

    return view('client.orders', compact('orders'));
}
public function addToCart($medicineId)
{
    $medicine = Medicine::findOrFail($medicineId);
    $cart = session()->get('cart', []);

    $currentCartQuantity = isset($cart[$medicineId]) ? $cart[$medicineId]['quantity'] : 0;

    if ($currentCartQuantity + 1 > $medicine->quantity) {
        return redirect()->route('client.medicines')
            ->with('error', 'Only ' . $medicine->quantity . ' quantity available for this medicine.');
    }

    if (isset($cart[$medicineId])) {
        $cart[$medicineId]['quantity']++;
    } else {
        $cart[$medicineId] = [
            'id' => $medicine->id,
            'name' => $medicine->commercial_name ?? $medicine->scientific_name ?? $medicine->name ?? 'Medicine',
            'price' => $medicine->price,
            'quantity' => 1,
        ];
    }

    session()->put('cart', $cart);

    return redirect()->route('client.medicines')->with('success', 'Medicine added to cart!');
}

public function clientCart()
{
    $cart = session()->get('cart', []);
    return view('client.cart', compact('cart'));
}

public function updateCart(Request $request)
{
    $cart = session()->get('cart', []);

    if ($request->quantities) {
        foreach ($request->quantities as $medicineId => $quantity) {
            $medicine = Medicine::findOrFail($medicineId);
            $quantity = max(1, (int)$quantity);

            if ($quantity > $medicine->quantity) {
                return redirect()->route('client.cart')
                    ->with('error', 'Only ' . $medicine->quantity . ' quantity available for ' . ($medicine->commercial_name ?? $medicine->scientific_name ?? $medicine->name));
            }

            if (isset($cart[$medicineId])) {
                $cart[$medicineId]['quantity'] = $quantity;
            }
        }
    }

    session()->put('cart', $cart);

    return redirect()->route('client.cart')->with('success', 'Cart updated successfully!');
}

public function removeFromCart($medicineId)
{
    $cart = session()->get('cart', []);

    if (isset($cart[$medicineId])) {
        unset($cart[$medicineId]);
    }

    session()->put('cart', $cart);

    return redirect()->route('client.cart')->with('success', 'Medicine removed from cart!');
}

public function placeCartOrder()
{
    $cart = session()->get('cart', []);

    if (empty($cart)) {
        return redirect()->route('client.cart')->with('error', 'Your cart is empty.');
    }

    $user = Auth::user();
    $client = Client::where('user_id', $user->id)->firstOrFail();

    $address = Address::where('client_id', $client->id)
        ->where('is_main', 1)
        ->first();

    if (!$address) {
        return redirect()->route('client.profile')
            ->with('error', 'Please complete your profile address first.');
    }

    $pharmacy = Pharmacy::first();

    if (!$pharmacy) {
        return redirect()->route('client.cart')->with('error', 'No pharmacy available.');
    }

    $totalPrice = 0;

    foreach ($cart as $item) {
        $medicine = Medicine::findOrFail($item['id']);

        if ($item['quantity'] > $medicine->quantity) {
            return redirect()->route('client.cart')
                ->with('error', 'Only ' . $medicine->quantity . ' quantity available for ' . ($medicine->commercial_name ?? $medicine->scientific_name ?? $medicine->name));
        }

        $totalPrice += $medicine->price * $item['quantity'];
    }

    $order = Order::create([
        'user_id' => $user->id,
        'pharmacy_id' => $pharmacy->id,
        'doctor_id' => null,
        'delivering_address_id' => $address->id,
        'creator_type' => 'client',
        'status' => 'Processing',
        'is_insured' => 0,
        'price' => $totalPrice,
    ]);

    foreach ($cart as $item) {
        $medicine = Medicine::findOrFail($item['id']);

        OrderMedicine::create([
            'order_id' => $order->id,
            'medicine_id' => $medicine->id,
            'quantity' => $item['quantity'],
        ]);

        $medicine->quantity = $medicine->quantity - $item['quantity'];
        $medicine->save();
    }
    $order->load('medicines', 'address.area', 'pharmacy');

    Mail::to($user->email)->send(new OrderConfirmation($order, $user));

    session()->forget('cart');

    return redirect()->route('client.orders')->with('success', 'Order placed successfully! Confirmation email sent.');
}
}
