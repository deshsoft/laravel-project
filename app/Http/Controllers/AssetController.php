<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $assets = Asset::when($search, function ($query, $search) {
            return $query->where('asset_type', 'like', "%{$search}%")
                ->orWhere('mode', 'like', "%{$search}%")
                ->orWhere('available_quantity', 'like', "%{$search}%")
                ->orWhere('rental_value', 'like', "%{$search}%");
        })->paginate(10);

        return view('assets.index', compact('assets'));
    }

    public function create()
    {
        return view('assets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'asset_type' => 'required|string',
            'mode' => 'required|string',
            'asset_size' => 'nullable|integer',
            'available_quantity' => 'nullable|integer',
            'rental_value' => 'required|numeric|between:0,99999.99',
            'fixed_hourly' => 'required|string',
        ]);

        Asset::create($request->all());
        return redirect()->route('assets.index')->with('success', 'Asset created successfully.');
    }

    public function edit(Asset $asset)
    {
        return view('assets.edit', compact('asset'));
    }

    public function update(Request $request, Asset $asset)
    {
        $request->validate([

            'mode' => 'required|string',
            'asset_size' => 'nullable|integer',
            'available_quantity' => 'nullable|integer',
            'rental_value' => 'required|numeric|between:0,99999.99',
            'fixed_hourly' => 'required|string',
        ]);

        $asset->update($request->all());
        return redirect()->route('assets.index')->with('success', 'Asset updated successfully.');
    }

    public function destroy(Asset $asset)
    {
        $asset->delete();
        return redirect()->route('assets.index')->with('success', 'Asset deleted successfully.');
    }
}

