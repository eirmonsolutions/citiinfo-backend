<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FeatureController extends Controller
{
    public function index()
    {
        $features = Feature::orderBy('id', 'desc')->paginate(10);
        return view('superadmin.feature', compact('features'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'icon_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active'  => ['nullable', 'in:0,1'],
        ]);

        $path = null;
        if ($request->hasFile('icon_image')) {
            $path = $request->file('icon_image')->store('features', 'public');
        }

        Feature::create([
            'name'       => $data['name'],
            'icon_image' => $path,
            'is_active'  => (bool)($data['is_active'] ?? 0),
        ]);

        return back()->with('success', 'Feature added successfully!');
    }

    public function update(Request $request, Feature $feature)
    {
        $data = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'icon_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active'  => ['nullable', 'in:0,1'],
        ]);

        $path = $feature->icon_image;

        if ($request->hasFile('icon_image')) {
            // delete old
            if ($feature->icon_image && Storage::disk('public')->exists($feature->icon_image)) {
                Storage::disk('public')->delete($feature->icon_image);
            }

            $path = $request->file('icon_image')->store('features', 'public');
        }

        $feature->update([
            'name'       => $data['name'],
            'icon_image' => $path,
            'is_active'  => (bool)($data['is_active'] ?? 0),
        ]);

        return back()->with('success', 'Feature updated successfully!');
    }

    public function destroy(Feature $feature)
    {
        if ($feature->icon_image && Storage::disk('public')->exists($feature->icon_image)) {
            Storage::disk('public')->delete($feature->icon_image);
        }

        $feature->delete();
        return back()->with('success', 'Feature deleted successfully!');
    }

    public function toggleStatus(Feature $feature)
    {
        $feature->is_active = !$feature->is_active;
        $feature->save();

        return back()->with('success', 'Status updated!');
    }
}
