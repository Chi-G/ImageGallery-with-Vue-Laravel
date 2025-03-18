<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ImageController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            Log::error('Unauthenticated request to /api/images');
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $query = Image::where('user_id', $user->id);

        // Filter by name
        if ($request->has('name') && !empty($request->name)) {
            $query->where('path', 'like', '%' . $request->name . '%');
        }
        // Filter by label
        if ($request->has('label') && !empty($request->label)) {
            $query->where('label', 'like', '%' . $request->label . '%');
        }

        // Filter by date
        if ($request->has('date') && !empty($request->date)) {
            $query->whereDate('uploaded_at', $request->date);
        }

        $images = $query->latest()
            ->get()
            ->map(function ($image) {
                return [
                    'id' => $image->id,
                    'name' => basename($image->path ?? 'unknown'),
                    'url' => asset('storage/' . $image->path),
                    'label' => $image->label,
                ];
            });

        $dates = Image::where('user_id', $user->id)
            ->selectRaw('DATE(uploaded_at) as date')
            ->distinct()
            ->pluck('date');

        return response()->json([
            'images' => $images,
            'dates' => $dates,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => ['required', 'file', 'image', 'mimes:jpeg,png,jpg', 'max:10240'],
            'label' => ['nullable', 'string', 'max:255'],
        ]);

        $file = $request->file('image');
        $date = now()->format('Y-m-d');
        $path = $file->store("images/{$date}", 'public');

        $image = Image::create([
            'user_id' => $request->user()->id,
            'path' => $path,
            'label' => $request->label,
            'uploaded_at' => now(),
        ]);

        return response()->json([
            'id' => $image->id,
            'name' => basename($path),
            'url' => asset('storage/' . $path),
            'label' => $image->label,
        ], 201);
    }

    public function byDate(Request $request, string $date)
    {
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return response()->json(['error' => 'Invalid date format'], 400);
        }

        $images = Image::where('user_id', $request->user()->id)
            ->whereDate('uploaded_at', $date)
            ->latest()
            ->get()
            ->map(function ($image) {
                return [
                    'id' => $image->id,
                    'name' => basename($image->path),
                    'url' => asset('storage/' . $image->path),
                    'label' => $image->label,
                ];
            });

        return response()->json([
            'images' => $images,
        ]);
    }

    public function destroy(Request $request, Image $image)
    {
        if ($image->user_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        Storage::disk('public')->delete($image->path);
        $image->delete();

        return response()->json(null, 204);
    }
}
