<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class AmenitiesController extends Controller
{
    protected $amenities;

    public function __construct(Amenities $amenities)
    {
        $this->amenities = $amenities;
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

            if (in_array($type, ['work', 'sleep', 'play'])) {
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
            if ($amenity->image) {
                $path = public_path($amenity->image);
                if (file_exists($path)) unlink($path);
            }
            $amenity->delete();

            Session::flash('success', get_phrase('Amenity deleted successfully!'));
            return redirect()->back();

        } catch (\Exception $e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }
}
