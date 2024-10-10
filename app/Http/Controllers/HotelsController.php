<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Hotels;
use App\Models\Country;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHotelRequest;
use App\Http\Requests\UpdateHotelRequest;

class HotelsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hotels = Hotels::with(['rooms'])->orderByDesc('id')->paginate(10);
        return view('admin.hotels.index', compact('hotels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = Country::orderByDesc('id')->get();
        $cities = City::orderByDesc('id')->get();
        return view('admin.hotels.create', compact('countries', 'cities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHotelRequest $request)
    {
        DB::transaction(function () use($request) {
            $validated = $request->validated();

            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = 
                $request->file('thumbnail')->store('thumbnails/' . date('Y/m/d'), 'public');
                $validated['thumbnail'] = $thumbnailPath;
            }

            $validated['slug'] = Str::slug($validated['name']);

            $hotels = Hotels::create($validated);

            if (request()->hasFile('photos')){
                foreach ($request->file('photos') as $photo ) {
                    $photopath = $photo->store('photo/' . date('Y/m/d'), 'public');
                    $hotels->photos()->create([
                        'photo' => $photopath
                    ]);
                }
            }
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Hotels $hotel)
    {
        $latestPhotos = $hotel->photos()->orderByDesc('id')->take(3)->get();
        return view('admin.hotels.show', compact('hotel', 'latestPhotos'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Hotels $hotel)
    {
        $latestPhotos = $hotel->photos()->orderByDesc('id')->take(3)->get();
        $countries = Country::orderByDesc('id')->get();
        $cities = City::orderByDesc('id')->get();
        return view('admin.hotels.edit', compact('hotel', 'latestPhotos', 'countries', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHotelRequest $request, Hotels $hotel)
    {
        DB::transaction(function () use($request, $hotel) {
            $validated = $request->validated();

            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = 
                $request->file('thumbnail')->store('thumbnails/' . date('Y/m/d'), 'public');
                $validated['thumbnail'] = $thumbnailPath;
            }

            $validated['slug'] = Str::slug($validated['name']);

            $hotel->update($validated);

            if (request()->hasFile('photos')){
                foreach ($request->file('photos') as $photo ) {
                    $photopath = $photo->store('photo/' . date('Y/m/d'), 'public');
                    $hotel->photos()->create([
                        'photo' => $photopath
                    ]);
                }
            }
        });

        return redirect()->route('admin.hotels.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Hotels $hotel)
    {
        DB::transaction(function() use ($hotel) {
            $hotel->delete();
        });
        
        return redirect()->route('admin.hotels.index')->with('success', 'Hotel deleted successfully');
    }
}
