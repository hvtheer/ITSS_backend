<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    public function index()
    {
        $sellers = Seller::all();
        return response()->json($sellers);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'user_id' => 'required|exists:users,id',
            'address' => 'required',
            'phone' => 'required',
            'logo' => 'nullable|image',
            'status' => 'required|in:pending,accepted,not_accepted',
        ]);

        // Handle the logo image upload if necessary
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('seller_logos', 'public');
            $validatedData['logo'] = $logoPath;
        }

        $seller = Seller::create($validatedData);
        return response()->json($seller, 201);
    }

    public function show(Seller $seller)
    {
        return response()->json($seller);
    }

    public function update(Request $request, Seller $seller)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'user_id' => 'required|exists:users,id',
            'address' => 'required',
            'phone' => 'required',
            'logo' => 'nullable|image',
            'status' => 'required|in:pending,accepted,not_accepted',
        ]);

        // Handle the logo image upload if necessary
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('seller_logos', 'public');
            $validatedData['logo'] = $logoPath;
        }

        $seller->update($validatedData);
        return response()->json($seller);
    }

    public function destroy(Seller $seller)
    {
        $seller->delete();
        return response()->json(null, 204);
    }
}
