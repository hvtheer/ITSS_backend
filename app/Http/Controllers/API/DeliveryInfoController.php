<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DeliveryInfo;
use Illuminate\Http\Request;

class DeliveryInfoController extends Controller
{
    public function index()
    {
        try {
            $deliveryInfos = DeliveryInfo::all();

            if ($deliveryInfos->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No delivery infos found']);
            }

            return response()->json(['success' => true, 'data' => $deliveryInfos]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'receiver_name' => 'required',
                'numberPhone' => 'required',
                'note' => 'nullable',
                'address' => 'required',
                'shipping_fee' => 'required',
            ]);

            $deliveryInfo = DeliveryInfo::create($validatedData);
            return response()->json(['success' => true, 'data' => $deliveryInfo], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function show(DeliveryInfo $deliveryInfo)
    {
        try {
            return response()->json(['success' => true, 'data' => $deliveryInfo]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, DeliveryInfo $deliveryInfo)
    {
        try {
            $validatedData = $request->validate([
                'receiver_name' => 'required',
                'numberPhone' => 'required',
                'note' => 'nullable',
                'address' => 'required',
                'shipping_fee' => 'required',
            ]);

            $deliveryInfo->update($validatedData);
            return response()->json(['success' => true, 'data' => $deliveryInfo]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function destroy(DeliveryInfo $deliveryInfo)
    {
        try {
            $deliveryInfo->delete();
            return response()->json(['success' => true], 204);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
