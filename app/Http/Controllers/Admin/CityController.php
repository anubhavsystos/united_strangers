<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Exception;

class CityController extends Controller
{
    protected $city;
    protected $country;

    public function __construct(City $city, Country $country)
    {
        $this->city = $city;
        $this->country = $country;
    }

    public function index()
    {
        try {
            $cities = $this->city->get();
            $countryIds = $this->city->distinct()->pluck('country');
            $countries = $this->country->whereIn('id', $countryIds)->get();

            return view('admin.city.index', compact('cities', 'countries'));
        } catch (Exception $e) {
            Session::flash('error', 'Failed to load cities.');
            return redirect()->back();
        }
    }

    public function add_city()
    {
        try {
            $countries = $this->country->get();
            return view('admin.city.create', compact('countries'));
        } catch (Exception $e) {
            Session::flash('error', 'Failed to load country list.');
            return redirect()->back();
        }
    }

    public function store_city(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'country' => 'required|max:255',
        ]);

        try {
            $data = [
                'name' => sanitize($request->name),
                'country' => sanitize($request->country),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/city'), $filename);
                $data['image'] = $filename;
            }

            $this->city->create($data);
            Session::flash('success', get_phrase('City added successfully!'));
        } catch (Exception $e) {
            Session::flash('error', 'Failed to add city.');
        }

        return redirect()->back();
    }

    public function edit_city($id)
    {
        try {
            $city_details = $this->city->findOrFail($id);
            $countries = $this->country->get();
            return view('admin.city.edit', compact('city_details', 'countries'));
        } catch (Exception $e) {
            Session::flash('error', 'City not found.');
            return redirect()->back();
        }
    }

    public function update_city(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'country' => 'required|max:255',
        ]);

        try {
            $city = $this->city->findOrFail($id);
            $city->name = sanitize($request->name);
            $city->country = sanitize($request->country);

            if ($request->hasFile('image')) {
                if ($city->image && File::exists(public_path('uploads/city/' . $city->image))) {
                    File::delete(public_path('uploads/city/' . $city->image));
                }

                $file = $request->file('image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/city'), $filename);
                $city->image = $filename;
            }

            $city->save();
            Session::flash('success', get_phrase('City updated successfully!'));
        } catch (Exception $e) {
            Session::flash('error', 'Failed to update city.');
        }

        return redirect()->back();
    }

    public function delete_city($id)
    {
        try {
            $city = $this->city->findOrFail($id);

            if ($city->image && File::exists(public_path('uploads/city/' . $city->image))) {
                File::delete(public_path('uploads/city/' . $city->image));
            }

            $city->delete();
            Session::flash('success', get_phrase('City deleted successfully!'));
        } catch (Exception $e) {
            Session::flash('error', 'Failed to delete city.');
        }

        return redirect()->back();
    }

    public function country_city($id)
    {
        try {
            $cities = $this->city->where('country', $id)->get();
            return response()->json($cities);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to fetch cities'], 500);
        }
    }

    public function edit_country($id)
    {
        try {
            $country_details = $this->country->findOrFail($id);
            return view('admin.city.edit_country', compact('country_details'));
        } catch (Exception $e) {
            Session::flash('error', 'Country not found.');
            return redirect()->back();
        }
    }

    public function update_country(Request $request, $id)
    {
        try {
            $country = $this->country->findOrFail($id);
            if ($request->hasFile('thumbnail')) {
                if ($country->thumbnail && File::exists(public_path('uploads/country-thumbnails/' . $country->thumbnail))) {
                    File::delete(public_path('uploads/country-thumbnails/' . $country->thumbnail));
                }

                $thumbnail = $request->file('thumbnail');
                $filename = time() . '.' . $thumbnail->getClientOriginalExtension();
                $thumbnail->move(public_path('uploads/country-thumbnails'), $filename);
                $country->thumbnail = $filename;
            }

            $country->save();
            Session::flash('success', get_phrase('Country thumbnail updated successfully!'));
        } catch (Exception $e) {
            Session::flash('error', 'Failed to update country.');
        }

        return redirect()->back();
    }
}
