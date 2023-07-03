<?php

namespace App\Http\Controllers;

use App\Models\DeliveryInfo;
use Illuminate\Http\Request;

class DeliveryInfoController extends Controller
{
    public function index()
    {
        $deliveryInfos = DeliveryInfo::all();

        return response()->json($deliveryInfos);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'order_id' => 'required|exists:orders,id',
            'note' => 'nullable',
            'address' => 'required',
            'delivery_fee' => 'required|numeric',
            'total' => 'required|numeric',
        ]);

        $deliveryInfo = DeliveryInfo::create($validatedData);

        return response()->json($deliveryInfo, 201);
    }

    public function show(DeliveryInfo $deliveryInfo)
    {
        return response()->json($deliveryInfo);
    }

    public function update(Request $request, DeliveryInfo $deliveryInfo)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'order_id' => 'required|exists:orders,id',
            'note' => 'nullable',
            'address' => 'required',
            'delivery_fee' => 'required|numeric',
            'total' => 'required|numeric',
        ]);

        $deliveryInfo->update($validatedData);

        return response()->json($deliveryInfo);
    }

    public function destroy(DeliveryInfo $deliveryInfo)
    {
        $deliveryInfo->delete();

        return response()->json(null, 204);
    }
}
