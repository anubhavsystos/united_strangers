<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenities;
use App\Models\BeautyListing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class AmenitiesController extends Controller
{
    protected $amenities;
    protected $beautyListing;

    public function __construct(Amenities $amenities, BeautyListing $beautyListing)
    {
        $this->amenities = $amenities;
        $this->beautyListing = $beautyListing;
    }

    public function amenities_list($type)
    {
        try {
            $page_data['type'] = $type;
            $page_data['amenities'] = $this->amenities->where('type', $type)->get();
            return view('admin.amenities.index', $page_data);
        } catch (\Exception $e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    public function amenities_item($type, $item)
    {
        try {
            $page_data['type'] = $type;
            $page_data['item'] = $item;

            if ($item == 'service') {
                $page_data['amenities'] = $this->amenities->where('type', $type)->where('identifier', 'service')->get();
            } elseif (in_array($item, ['work', 'sleep'])) {
                $page_data['amenities'] = $this->amenities->where('type', $type)->where('identifier', 'feature')->get();
            } else {
                $page_data['amenities'] = $this->amenities->where('type', $type)->get();
            }

            return view('admin.amenities.index', $page_data);
        } catch (\Exception $e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    public function amenities_add($prefix, $type, $item = "", $page = "", $listing_id = "")
    {
        $page_data = compact('prefix', 'type', 'item', 'page', 'listing_id');
        return view('admin.amenities.create', $page_data);
    }

    public function amenities_create(Request $request, $prefix, $type)
    {
        try {
            $data['type'] = $type;

            if (in_array($type, ['car', 'work', 'sleep', 'play'])) {
                $request->validate([
                    'name' => 'required|max:50',
                ]);

                $data['name'] = sanitize($request->name);
                $data['identifier'] = sanitize($request->item);

                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $imageName = time() . '_' . $image->getClientOriginalName();
                    $image->move(public_path('uploads/amenities'), $imageName);
                    $data['image'] = 'uploads/amenities/' . $imageName;
                }

            } elseif ($type == 'beauty') {
                if ($request->item == 'service') {
                    $request->validate([
                        'name' => 'required|max:50',
                        'time' => 'required|max:50',
                        'price' => 'required|max:50',
                    ]);

                    $data['name'] = sanitize($request->name);
                    $data['user_id'] = auth()->user()->id;
                    $data['time'] = sanitize($request->time);
                    $data['price'] = sanitize($request->price);
                    $data['identifier'] = 'service';

                } else {
                    $request->validate([
                        'name' => 'required|max:50',
                        'designation' => 'required|max:50',
                        'image' => 'required',
                        'rating' => 'required|max:50',
                    ]);

                    $data['identifier'] = 'team';
                    $data['name'] = sanitize($request->name);
                    $data['user_id'] = auth()->user()->id;
                    $data['designation'] = sanitize($request->designation);
                    $data['rating'] = sanitize($request->rating);

                    if ($request->hasFile('image')) {
                        $image = $request->file('image');
                        $imageName = time() . '.' . $image->getClientOriginalExtension();
                        $image->move(public_path('uploads/team'), $imageName);
                        $data['image'] = $imageName;
                    }
                }
            }

            $data['created_at'] = Carbon::now();
            $data['updated_at'] = Carbon::now();
            $this->amenities->insert($data);

            if ($request->page == 'listing') {
                Session::flash('success', get_phrase('Listing ' . $request->item . ' created successfully!'));

                return $prefix === 'admin'
                    ? redirect()->route('admin.listing.edit', [
                        'type' => $type,
                        'id' => $request->listing_id,
                        'tab' => $request->item
                    ])
                    : redirect()->back();
            }

            Session::flash('success', get_phrase('Amenity created successfully!'));
            return redirect()->back();

        } catch (\Exception $e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    public function amenities_edit($type, $id)
    {
        try {
            $page_data['amenities'] = $this->amenities->findOrFail($id);
            return view('admin.amenities.edit', $page_data);
        } catch (\Exception $e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    public function amenities_update(Request $request, $type, $id)
    {
        try {
            $amenity = $this->amenities->findOrFail($id);
            $data = ['updated_at' => Carbon::now()];

            if ($amenity->type == 'beauty') {
                $data['name'] = sanitize($request->name);
                if ($amenity->identifier == 'service') {
                    $data['time'] = sanitize($request->time);
                    $data['price'] = sanitize($request->price);
                } else {
                    $request->validate([
                        'name' => 'required|max:50',
                        'designation' => 'required|max:50',
                        'rating' => 'required|max:50',
                    ]);

                    $data['designation'] = sanitize($request->designation);
                    $data['rating'] = sanitize($request->rating);

                    if ($request->hasFile('image')) {
                        $image = $request->file('image');
                        $imageName = time() . '.' . $image->getClientOriginalExtension();
                        $image->move(public_path('uploads/team'), $imageName);

                        if ($amenity->image && file_exists(public_path('uploads/team/' . $amenity->image))) {
                            unlink(public_path('uploads/team/' . $amenity->image));
                        }

                        $data['image'] = $imageName;
                    }
                }

            } else {
                $request->validate([
                    'name' => 'required|max:50',
                ]);

                $data['type'] = sanitize($request->type);
                $data['name'] = sanitize($request->name);
                $data['identifier'] = sanitize($request->item);

                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $imageName = time() . '_' . $image->getClientOriginalName();
                    $image->move(public_path('uploads/amenities'), $imageName);
                    $data['image'] = 'uploads/amenities/' . $imageName;

                    if ($amenity->image && file_exists(public_path($amenity->image))) {
                        unlink(public_path($amenity->image));
                    }
                }
            }

            $amenity->update($data);

            Session::flash('success', get_phrase('Amenity updated successfully!'));
            return redirect()->back();

        } catch (\Exception $e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    public function amenities_delete($prefix, $id)
    {
        try {
            $amenity = $this->amenities->findOrFail($id);

            // Delete related images
            if (in_array($amenity->type, ['car', 'work', 'sleep', 'play']) && $amenity->image) {
                $path = public_path($amenity->image);
                if (file_exists($path)) unlink($path);
            }

            if ($amenity->type == 'beauty' && $amenity->image) {
                $path = public_path('uploads/team/' . $amenity->image);
                if (file_exists($path)) unlink($path);
            }

            $amenity->delete();

            // Remove from any beauty listings
            $beautyListings = $this->beautyListing->where('service', 'like', '%' . $id . '%')->get();
            foreach ($beautyListings as $listing) {
                $services = json_decode($listing->service);
                if (($key = array_search($id, $services)) !== false) {
                    unset($services[$key]);
                    $listing->service = json_encode(array_values($services));
                    $listing->save();
                }
            }

            Session::flash('success', get_phrase('Amenity deleted successfully!'));
            return redirect()->back();

        } catch (\Exception $e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }
}
