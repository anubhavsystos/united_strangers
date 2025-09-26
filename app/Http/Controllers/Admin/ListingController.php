<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Listing_Feature;
use App\Models\Listing_Specification;
use App\Models\SleepListing;
use App\Models\WorkListing;
use App\Models\PlayListing;
use App\Models\Room;
use App\Models\Menu;
use App\Models\ClaimedListing;
use App\Models\ReportedListing;
use App\Models\Appointment;
use App\Models\City;
use App\Models\Country;
use App\Models\Amenities;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\NearByLocation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ListingController extends Controller
{
    public function __construct(Category $category,SleepListing $sleepListing,Listing_Feature $listingFeature,Listing_Specification $listingSpecification,WorkListing $workListing,PlayListing $playListing,Room $room,Menu $menu,ClaimedListing $claimedListing,ReportedListing $reportedListing,NearByLocation $nearByLocation, Amenities $amenities, Appointment $appointment, City $city, Country $country) {

        $this->category = $category;
        $this->sleepListing = $sleepListing;
        $this->listingFeature = $listingFeature;
        $this->listingSpecification = $listingSpecification;
        $this->workListing = $workListing;
        $this->playListing = $playListing;
        $this->room = $room;
        $this->menu = $menu;
        $this->claimedListing = $claimedListing;
        $this->reportedListing = $reportedListing;
        $this->nearByLocation = $nearByLocation;
        $this->appointment = $appointment;
        $this->amenities = $amenities;
        $this->city = $city;
        $this->country = $country;
    }


    public function listing_list($type){
        if($type == 'work'){
            $page_data['listings'] =  $this->workListing->get();
        }elseif($type == 'sleep'){
            $page_data['listings'] = $this->sleepListing->get();
        }elseif($type == 'play'){
            $page_data['listings'] = $this->playListing->get();
        }
        $page_data['type'] = $type;
        return view('admin.listing.list', $page_data);
    }

    public function listing_create(){
        return view('admin.listing.create');
    }

    public function create_type($type){
        return view('admin.listing.'.$type);
    }

    public function listing_category($type){
        $categories = $this->category->where('type', $type)->get();
        return response()->json($categories);
    }

    public function listing_store(Request $request, $type){

        $data['title'] = sanitize($request->title);
        $data['category'] = sanitize($request->category);
        $data['description'] = $request->description;
        $data['visibility'] = sanitize($request->visibility);
        $data['type'] = $type;
        
        $data['meta_title'] = sanitize($request->meta_title);
        $data['meta_keyword'] = sanitize($request->keyword);
        $data['meta_description'] = sanitize($request->meta_description);
        $data['og_title'] = sanitize($request->og_title);
        $data['og_description'] = sanitize($request->og_description);
        
        $data['canonical_url'] = sanitize($request->canonical_url);
        $data['json_id'] = $request->json_id;
        $data['country'] = sanitize($request->country);
        $data['city'] = sanitize($request->city);
        $data['area'] = $request->country.':@:'.$request->city.':@:'.$request->address;
        $data['address'] = sanitize($request->address);
        $data['postal_code'] = sanitize($request->post_code);
        $data['Latitude'] = sanitize($request->latitude);
        $data['Longitude'] = sanitize($request->longitude);
        $data['created_at'] = Carbon::now();
        $data['updated_at'] = Carbon::now();

        $data['user_id'] = user('id');

        $listing_image = [];
        if ($request->hasFile('listing_image')) {
            foreach ($request->file('listing_image') as $key => $image) {
                $imageName = $key.'-'.time() . '.' . $image->getClientOriginalExtension();
                // $image->storeAs('public/listing-images', $imageName);
                $image->move(public_path('uploads/listing-images'), $imageName);
                array_push($listing_image, $imageName);
            }
        }
        $data['image'] = json_encode($listing_image);

        if($type == 'sleep'){
            $data['price'] = sanitize($request->price);
            $data['bed'] = sanitize($request->bed);
            $data['bath'] = sanitize($request->bath);
            $data['size'] = sanitize($request->size);
            $data['dimension'] = sanitize($request->dimension);
            $data['is_popular'] = $request->is_popular ?? 0;
            $this->sleepListing->insert($data);
        }elseif($type == 'work'){
            $data['property_id'] = sanitize($request->property_id);
            $data['price'] = $request->price;
            $data['discount'] = $request->discount;
            $data['bed'] = sanitize($request->bed);
            $data['bath'] = sanitize($request->bath);
            $data['garage'] = sanitize($request->garage);
            $data['size'] = sanitize($request->size);
            $data['year'] = sanitize($request->year);
            $data['dimension'] = sanitize($request->dimension);
            $data['sub_dimension'] = sanitize($request->sub_dimension);
            $data['status'] = sanitize($request->status);
            $data['near_by']='{"0":"school","1":"hospital","2":"shopping_center"}';
             $this->workListing->insert($data);
        }elseif($type == 'play'){
            $data['tables'] = sanitizenumber($request->tables);            
            $data['is_popular'] = $request->is_popular ?? 0;
            $this->playListing->insert($data);
        }
        if ($request->hasFile('og_image')) {
            $image = $request->file('og_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension(); 
            // $image->storeAs('public\og_image', $imageName);
            $image->move(public_path('uploads/og_image'), $imageName);
            $data['og_image'] = $imageName;
        }
        Session::flash('success', get_phrase('Listing Created successfully!'));
        if(isset($request->is_agent) && $request->is_agent == 1){
            return redirect('agent/my-listings');
        }else{
            return redirect('admin/listings/'.$type);
        }

    }

    public function listing_edit($type, $id, $tab = ""){  
        if($type == 'work'){
            $page_data['listing'] =  $this->workListing->where('id', $id)->first();
        }elseif($type == 'sleep'){
            $page_data['listing'] = $this->sleepListing->where('id', $id)->first();
        }elseif($type == 'play'){            
            $page_data['listing'] = $this->playListing->where('id', $id)->first();
            $page_data['menus'] = $this->menu->where('listing_id', $id)->get();            
        }
        $page_data['appointments'] =   $this->appointment->where("listing_type", $type)->orderBy('date', 'desc')->where("listing_id", $id)->get()->map(function ($item) use ($type) {
            return $item->appointmentFormatted($type);
        });   
        $page_data['tab'] = $tab;
        $page_data['type'] = $type;
        $page_data['rooms'] = $this->room->where('listing_id', $id)->where('segment_type', $type)->get(); 
        $page_data['features'] = $this->amenities->get(); 
        $page_data['city'] = $this->city->get();
        $page_data['country'] = $this->country->get();
        $page_data['categories'] = $this->category->get();
        return view('admin.listing.'.$type.'_edit', $page_data);
    }

    public function listing_update(Request $request, $type, $id){
        
        $data['title'] = sanitize($request->title);
        $data['category'] = sanitize($request->category);
        $data['description'] = sanitize($request->description);
        $data['visibility'] = sanitize($request->visibility);
        $data['updated_at'] = Carbon::now();
       
        $data['meta_title'] = sanitize($request->meta_title);
        $data['meta_keyword'] = sanitize($request->keyword);
        $data['meta_description'] = sanitize($request->meta_description);
        $data['og_title'] = sanitize($request->og_title);
        $data['og_description'] = sanitize($request->og_description);
      
        $data['canonical_url'] = sanitize($request->canonical_url);
        $data['json_id'] = $request->json_id;
        $data['country'] = sanitize($request->country);

        if ($request->hasFile('og_image')) {
            $image = $request->file('og_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/og_image'), $imageName);
            $data['og_image'] = $imageName;
        }
    
        if($request->city){
            $data['city'] = sanitize($request->city);
        }
        $data['area'] = $request->country.':@:'.$request->city.':@:'.$request->address;
        $data['address'] = sanitize($request->address);
        $data['postal_code'] = sanitize($request->post_code);
        $data['Latitude'] = sanitize($request->latitude);
        $data['Longitude'] = sanitize($request->longitude);

      

      if($type == 'sleep'){
            $data['price'] = sanitize($request->price);
            $data['bed'] = sanitize($request->bed);
            $data['bath'] = sanitize($request->bath);
            $data['size'] = sanitize($request->size);
            $data['dimension'] = sanitize($request->dimension);
            $data['feature'] = json_encode($request->feature)??[];
            $data['room'] = json_encode($request->room)??[];
            $data['is_popular'] = $request->is_popular ?? 0;
            $listing_image = json_decode($this->sleepListing->where('id', $id)->pluck('image')->toArray()[0])??[];
            if ($request->hasFile('listing_image')) {
                foreach ($request->file('listing_image') as $key => $image) {
                    $imageName = $key.'-'.time() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('uploads/listing-images'), $imageName);
                    array_push($listing_image, $imageName);
                }
                $data['image'] = json_encode($listing_image);
            }else{
                $data['image'] = $listing_image;
            }
            
            $this->sleepListing->where('id', $id)->update($data);
            Session::flash('success', get_phrase('Listing Update successfully!'));
            if(isset($request->is_agent) && $request->is_agent == 1){
                return redirect('agent/my-listings');
            }else{
                return redirect('admin/listings/sleep');
            }
        }elseif($type == 'work'){
            $data['property_id'] = sanitize($request->property_id);
            $data['price'] = $request->price;
            $data['discount'] = $request->discount;
            $data['bed'] = sanitize($request->bed);
            $data['bath'] = sanitize($request->bath);
            $data['garage'] = sanitize($request->garage);
            $data['size'] = sanitize($request->size);
            $data['year'] = sanitize($request->year);
            $data['dimension'] = sanitize($request->dimension);
            $data['video'] = sanitize($request->video);
            $data['sub_dimension'] = sanitize($request->sub_dimension);
            $data['feature'] = json_encode($request->feature)??[];
            $data['status'] = sanitize($request->status);
            $listing_image = json_decode( $this->workListing->where('id', $id)->pluck('image')->toArray()[0])??[];
            if ($request->hasFile('listing_image')) {
                foreach ($request->file('listing_image') as $key => $image) {
                    $imageName = $key.'-'.time() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('uploads/listing-images'), $imageName);
                    array_push($listing_image, $imageName);
                }
                $data['image'] = json_encode($listing_image);
            }else{
                $data['image'] = json_encode($listing_image);
            }
            if ($request->model) {
                $random_name = rand();
                $attachment = $random_name . '.' . $request->model->getClientOriginalExtension();
                $request->model->move(public_path('uploads/3d'), $attachment);
                if (!empty($request->old_model) && file_exists(public_path('uploads/3d/' . $request->old_model))) {
                    unlink(public_path('uploads/3d/' . $request->old_model));
                }
                $data['model'] = $attachment;
            } else {
                $data['model'] = $request->old_model;
            }
        
            $listing_listing_floor_plan = json_decode( $this->workListing->where('id', $id)->pluck('floor_plan')->toArray()[0])??[];

            if ($request->hasFile('listing_floor_plan')) {
                foreach ($request->file('listing_floor_plan') as $key => $image) {
                    $floorImage = $key.'-'.time() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('uploads/floor-plan'), $floorImage);
                    array_push($listing_listing_floor_plan, $floorImage);
                }
                $data['floor_plan'] = json_encode($listing_listing_floor_plan);
            }else{
                $data['floor_plan'] = json_encode($listing_listing_floor_plan);
            }

            Session::flash('success', get_phrase('Listing Update successfully!'));
             $this->workListing->where('id', $id)->update($data);
            if(isset($request->is_agent) && $request->is_agent == 1){
                return redirect('agent/my-listings');
            }else{
                return redirect('admin/listings/work');
            }
        }elseif($type == 'play'){
            $data['tables'] = sanitizenumber($request->tables);
            $data['is_popular'] = $request->is_popular;
            $opening_times = [
                'saturday' => ['open' => sanitize($request->saturday_open), 'close' => sanitize($request->saturday_close)],
                'sunday' => ['open' => sanitize($request->sunday_open), 'close' => sanitize($request->sunday_close)],
                'monday' => ['open' => sanitize($request->monday_open), 'close' => sanitize($request->monday_close)],
                'tuesday' => ['open' => sanitize($request->tuesday_open), 'close' => sanitize($request->tuesday_close)],
                'wednesday' => ['open' => sanitize($request->wednesday_open), 'close' => sanitize($request->wednesday_close)],
                'thursday' => ['open' => sanitize($request->thursday_open), 'close' => sanitize($request->thursday_close)],
                'friday' => ['open' => sanitize($request->friday_open), 'close' => sanitize($request->friday_close)],
            ];
            $data['opening_time'] = json_encode($opening_times);
            $data['amenities'] = json_encode($request->feature)??[];
            $listing_image = json_decode($this->playListing->where('id', $id)->pluck('image')->toArray()[0])??[];

            if ($request->hasFile('listing_image')) {
                foreach ($request->file('listing_image') as $key => $image) {
                    $imageName = $key.'-'.time() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('uploads/listing-images'), $imageName);
                    array_push($listing_image, $imageName);
                }
                $data['image'] = json_encode($listing_image);
            }else{
                $data['image'] = json_encode($listing_image);
            }
            $data['menu'] = json_encode($request->menu)??[];
            $this->playListing->where('id', $id)->update($data);
            Session::flash('success', get_phrase('Listing Update successfully!'));
            if(isset($request->is_agent) && $request->is_agent == 1){
                return redirect('agent/my-listings');
            }else{
                return redirect('admin/listings/play');
            }
        }
    }


    public function listing_image_delete($type, $id, $image){
        if($type == 'sleep'){
            $listing = $this->sleepListing->where('id', $id);
        }elseif($type == 'work'){
            $listing =  $this->workListing->where('id', $id);
        }elseif($type == 'play'){
            $listing = $this->playListing->where('id', $id);
        }
        $imageToRemove = $image;
        $imageArray = json_decode($listing->first()->image);
        $key = array_search($imageToRemove, $imageArray);
        if ($key !== false) {
            unset($imageArray[$key]);
        }
        $imageArray = array_values($imageArray);
        $resultJson = json_encode($imageArray);
        $listing->update(['image'=>$resultJson]);
        if(file_exists('public/uploads/listing-images/'.$image)){
            unlink('public/uploads/listing-images/'.$image);
        }
        return 1;
    }
    public function listing_floor_image_delete($type, $id, $image){
        if($type == 'sleep'){
            $listing = $this->sleepListing->where('id', $id);
        }elseif($type == 'work'){
            $listing =  $this->workListing->where('id', $id);
        }elseif($type == 'play'){
            $listing = $this->playListing->where('id', $id);
        }
        $imageToRemove = $image;
        $imageArray = json_decode($listing->first()->floor_plan);
        $key = array_search($imageToRemove, $imageArray);
        if ($key !== false) {
            unset($imageArray[$key]);
        }
        $imageArray = array_values($imageArray);
        $resultJson = json_encode($imageArray);
        $listing->update(['floor_plan'=>$resultJson]);
        if(file_exists('public/uploads/floor-plan/'.$image)){
            unlink('public/uploads/floor-plan/'.$image);
        }
        return 1;
    }

    public function listing_status($type, $id, $status){
        $status = $status == 'visible'?'hidden':'visible';
       
        if($type == 'sleep'){
            $listing = $this->sleepListing->where('id', $id);
        }elseif($type == 'work'){
            $listing =  $this->workListing->where('id', $id);
        }elseif($type == 'play'){
            $listing = $this->playListing->where('id', $id);
        }       
        $listing->update(['visibility'=>$status]);
        Session::flash('success', get_phrase('Listing Update successfully!'));
        return redirect()->back();
    }

    public function listing_delete($type, $id){
        if($type == 'sleep'){
            $listing = $this->sleepListing->where('id', $id);
        }elseif($type == 'work'){
            $listing =  $this->workListing->where('id', $id);
        }elseif($type == 'play'){
            $listing = $this->playListing->where('id', $id);
        }
        foreach(json_decode($listing->first()->image) as $listImage){
            if(file_exists('public/uploads/listing-images/'.$listImage)){
                unlink('public/uploads/listing-images/'.$listImage);
            }
        }
        $listing->delete();
        Session::flash('success', get_phrase('Listing deleted successfully!'));
        return redirect()->back();
    }

    public function listing_feature_add($prefix, $id){
        $page_data['id'] = $id;
        $page_data['page'] = 'add';
        return view('admin.listing.feature', $page_data);
    }

    public function listing_feature_edit($prefix, $id, $feature_id){
        $page_data['id'] = $id;
        $page_data['feature_id'] = $feature_id;
        $page_data['page'] = 'edit';
        $page_data['feature'] = Listing_Feature::where('id', $feature_id)->first();
        return view('admin.listing.feature', $page_data);
    }

    public function listing_feature_store(Request $request, $prefix, $id){
       
        $validated = $request->validate([
            'title' => 'required|max:500',
        ]);
        $data['title'] = sanitize($request->title);
        $data['listing_id'] = $id;
        $data['feature_id'] = 0;
        $data['created_at'] = Carbon::now();
        $data['updated_at'] = Carbon::now();
        Listing_Feature::insert($data);
        $type = 'car';
        $page_data['listing'] = CarListing::where('id', $id)->first();
        $page_data['type'] = $type;
        $page_data['tab'] = 'feature';
        Session::flash('success', get_phrase('Listing feature add successful!'));
        if(user('role') == 1){
            return redirect()->route('admin.listing.edit', ['type' => 'car', 'id' => $id, 'tab'=>'feature']);
        }else{
            return redirect()->route('user.listing.edit',['id'=>$id, 'type'=>'car','tab'=>'feature']);
        }
    }

    public function listing_feature_update(Request $request, $prefix, $id, $feature_id){
        $validated = $request->validate([
            'title' => 'required|max:500',
        ]);
        $data['title'] = sanitize($request->title);
        $data['updated_at'] = Carbon::now();
        Listing_Feature::where('id', $feature_id)->update($data);
        Session::flash('success', get_phrase('Listing feature update successful!'));

        if(user('role') == 1){
            return redirect()->route('admin.listing.edit', ['type' => 'car', 'id' => $id, 'tab'=>'feature']);
        }else{
            return redirect()->route('user.listing.edit',['id'=>$id, 'type'=>'car','tab'=>'feature']);
        }
    }

    public function listing_feature_delete($prefix, $id, $feature_id){
        Listing_Feature::where('id', $feature_id)->delete();
        Session::flash('success', get_phrase('Listing feature delete successful!'));
        if(user('role') == 1){
            return redirect()->route('admin.listing.edit', ['type' => 'car', 'id' => $id, 'tab'=>'feature']);
        }else{
            return redirect()->route('user.listing.edit',['id'=>$id, 'type'=>'car','tab'=>'feature']);
        }
    }

    public function listing_sub_feature_add($prefix, $id, $feature_id){
        $page_data['id'] = $id;
        $page_data['feature_id'] = $feature_id;
        $page_data['page'] = 'sub_add';
        return view('admin.listing.feature', $page_data);
    }

    public function listing_sub_feature_store(Request $request, $prefix, $id, $feature_id){
        $validated = $request->validate([
            'title' => 'required|max:500',
        ]);
        $data['title'] = sanitize($request->title);
        $data['listing_id'] = $id;
        $data['feature_id'] = $feature_id;
        $data['created_at'] = Carbon::now();
        $data['updated_at'] = Carbon::now();
        Listing_Feature::insert($data);
        Session::flash('success', get_phrase('Listing sub feature add successful!'));
        if(user('role') == 1){
            return redirect()->route('admin.listing.edit', ['type' => 'car', 'id' => $id, 'tab'=>'feature']);
        }else{
            return redirect()->route('user.listing.edit',['id'=>$id, 'type'=>'car','tab'=>'feature']);
        }
    }

    public function listing_specification_add($prefix, $id){
        $page_data['id'] = $id;
        $page_data['page'] = 'add';
        return view('admin.listing.specification', $page_data);
    }

    public function listing_specification_store(Request $request, $prefix, $id){
        $validated = $request->validate([
            'title' => 'required|max:500',
        ]);
        $data['title'] = sanitize($request->title);
        $data['listing_id'] = $id;
        $data['specification_id'] = 0;
        $data['created_at'] = Carbon::now();
        $data['updated_at'] = Carbon::now();
        Listing_Specification::insert($data);
        Session::flash('success', get_phrase('Listing specification add successful!'));
        if(user('role') == 1){
            return redirect()->route('admin.listing.edit', ['type' => 'car', 'id' => $id, 'tab'=>'specification']);
        }else{
            return redirect()->route('user.listing.edit',['id'=>$id, 'type'=>'car','tab'=>'specification']);
        }
    }

    public function listing_specification_edit($prefix, $id, $specification_id){
        $page_data['id'] = $id;
        $page_data['specification_id'] = $specification_id;
        $page_data['specification'] = Listing_Specification::where('id', $specification_id)->first();
        $page_data['page'] = 'edit';
        return view('admin.listing.specification', $page_data);
    }

    public function listing_specification_update(Request $request, $prefix, $id, $specification_id){
        $validated = $request->validate([
            'title' => 'required|max:500',
        ]);
        $data['title'] = sanitize($request->title);
        $data['updated_at'] = Carbon::now();
        Listing_Specification::where('id', $specification_id)->update($data);
        Session::flash('success', get_phrase('Listing specification update successful!'));
        if(user('role') == 1){
            return redirect()->route('admin.listing.edit', ['type' => 'car', 'id' => $id, 'tab'=>'specification']);
        }else{
            return redirect()->route('user.listing.edit',['id'=>$id, 'type'=>'car','tab'=>'specification']);
        }
    }

    public function listing_specification_delete($prefix, $id, $specification_id){
        Listing_Specification::where('id', $specification_id)->delete();
        Toastr::success(get_phrase('Listing specification delete successful!'), get_phrase('Success'));
        if(user('role') == 1){
            return redirect()->route('admin.listing.edit', ['type' => 'car', 'id' => $id, 'tab'=>'specification']);
        }else{
            return redirect()->route('user.listing.edit',['id'=>$id, 'type'=>'car','tab'=>'specification']);
        }    }

    public function listing_sub_specification_add($prefix, $id, $specification_id){
        $page_data['id'] = $id;
        $page_data['specification_id'] = $specification_id;
        $page_data['page'] = 'sub_add';
        return view('admin.listing.specification', $page_data);
    }

    public function listing_sub_specification_update(Request $request, $prefix, $id, $specification_id, $parent) {
        $validated = $request->validate([
            'title' => 'required|max:50',
            'value' => 'required|max:50',
        ]);
        $data['title'] = sanitize($request->title);
        $data['value'] = sanitize($request->value);
        $data['listing_id'] = $id;
        $data['specification_id'] = $parent;
        $data['updated_at'] = Carbon::now();

        Listing_Specification::where('id', $specification_id)->update($data);
        Session::flash('success', get_phrase('Listing specification update successful!'));
        if(user('role') == 1){
            return redirect()->route('admin.listing.edit', ['type' => 'car', 'id' => $id, 'tab'=>'specification']);
        }else{
            return redirect()->route('user.listing.edit',['id'=>$id, 'type'=>'car','tab'=>'specification']);
        }
    }

    public function listing_sub_specification_edit($prefix, $id, $specification_id, $parent){
        $page_data['id'] = $id;
        $page_data['specification_id'] = $specification_id;
        $page_data['parent'] = $parent;
        $page_data['page'] = 'sub_edit';
        $page_data['specification'] = Listing_Specification::where('id',$specification_id)->first();
        return view('admin.listing.specification', $page_data);
    }

    public function listing_sub_specification_store(Request $request, $prefix, $id, $specification_id){
        $validated = $request->validate([
            'title' => 'required|max:50',
            'value' => 'required|max:50',
        ]);
        $data['title'] = sanitize($request->title);
        $data['value'] = sanitize($request->value);
        $data['listing_id'] = $id;
        $data['specification_id'] = $specification_id;
        $data['created_at'] = Carbon::now();
        $data['updated_at'] = Carbon::now();
        Listing_Specification::insert($data);
        Session::flash('success', get_phrase('Listing specification add successful!'));
        if(user('role') == 1){
            return redirect()->route('admin.listing.edit', ['type' => 'car', 'id' => $id, 'tab'=>'specification']);
        }else{
            return redirect()->route('user.listing.edit',['id'=>$id, 'type'=>'car','tab'=>'specification']);
        }
    }

    public function listing_add_room($prefix, $id, $room_id, $action){
        $page_data['id'] = $id;
        $page_data['action'] = $action;
        if ($action == 'edit') {
            $page_data['room'] = $this->room->where('id', $room_id)->where('segment_type', 'sleep')->first();
        }
        $page_data['features'] = $this->amenities->get();
        $page_data['page'] = 'room';
        return view('admin.listing.room', $page_data);
    }

    public function listing_add_property($prefix, $id, $room_id, $action){
        $page_data['id'] = $id;
        $page_data['action'] = $action;
        if ($action == 'edit') {
            $page_data['property'] = $this->room->where('id', $room_id)->where('segment_type', 'work')->first();
        }
        $page_data['features'] = $this->amenities->get();
        $page_data['page'] = 'property';
        // return $page_data;
        return view('admin.listing.property', $page_data);
    }


    public function listing_store_room(Request $request, $prefix, $id){
        $validated = $request->validate([
            'title' => 'required|max:50',
            'person' => 'required|max:100',
            'price' => 'required|max:50',
            'feature' => 'required',
            'image' => 'required',
        ]);
        $data['title'] = sanitize($request->title);
        $data['person'] = sanitize($request->person);
        $data['child'] = sanitize($request->child);
        $data['segment_type'] = sanitize($request->segment_type);
        $data['listing_id'] = $id;
        $data['user_id'] = user('id');
        $data['price'] = sanitize($request->price);
        $data['feature'] = json_encode($request->feature);
        if ($request->has('room_type')) {
            $data['room_type'] = implode(',', $request->room_type);
        } else {
            $data['room_type'] = null;
        }
        $room_image = [];
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $key => $image) {
                $imageName = $key.'-'.time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/room-images'), $imageName);
                array_push($room_image, $imageName);
            }
        }
        $data['image'] = json_encode($room_image);
        Room::insert($data);
        Session::flash('success', get_phrase('Sleep room create successful!'));
        if(user('role') == 2) {
            return redirect()->route('user.listing.edit',['id'=>$id, 'type'=>'sleep','tab'=>'room']);
        }else{
            return redirect()->route('admin.listing.edit', ['id'=> $id, 'type' =>'sleep','tab'=>'room']);
        }
    }

    public function listing_update_room(Request $request, $prefix, $id, $room_id){
        $validated = $request->validate([
            'title' => 'required|max:50',
            'person' => 'required|max:100',
            'price' => 'required|max:50',
            'feature' => 'required',
        ]);
        $data['title'] = sanitize($request->title);
        $data['person'] = sanitize($request->person);
        $data['child'] = sanitize($request->child);
        $data['listing_id'] = $id;
        $data['price'] = sanitize($request->price);
        $data['feature'] = json_encode($request->feature);
        if ($request->has('room_type')) {
            $data['room_type'] = implode(',', $request->room_type);
        } else {
            $data['room_type'] = null;
        }
        $room_image = [];
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $key => $image) {
                $imageName = $key.'-'.time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/room-images'), $imageName);
                array_push($room_image, $imageName);
            }
            $data['image'] = json_encode($room_image);
        }
        Room::where('id', $room_id)->update($data);
        Session::flash('success', get_phrase('Sleep room create successful!'));
        if(user('role') == 2) {
            return redirect()->route('user.listing.edit',['id'=>$id, 'type'=>'sleep','tab'=>'room']);
        }else{
            return redirect()->route('admin.listing.edit', ['type' => 'sleep', 'id' => $id, 'tab'=>'feature']);
        }
    }

    public function listing_update_property(Request $request, $prefix, $id, $property_id){
        $validated = $request->validate([
            'title' => 'required|max:50',
            'person' => 'required|max:100',
            'price' => 'required|max:50',
            'feature' => 'required',
        ]);
        $data['title'] = sanitize($request->title);
        $data['person'] = sanitize($request->person);
        $data['child'] = sanitize($request->child);
        $data['listing_id'] = $id;
        $data['price'] = sanitize($request->price);
        $data['feature'] = json_encode($request->feature);
        if ($request->has('room_type')) {
            $data['room_type'] = implode(',', $request->room_type);
        } else {
            $data['room_type'] = null;
        }
        $room_image = [];
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $key => $image) {
                $imageName = $key.'-'.time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/room-images'), $imageName);
                array_push($room_image, $imageName);
            }
            $data['image'] = json_encode($room_image);
        }
        Room::where('id', $property_id)->update($data);
        Session::flash('success', get_phrase('Sleep Property Update successful!'));
        if(user('role') == 2) {
            return redirect()->route('user.listing.edit',['id'=>$id, 'type'=>'work','tab'=>'property']);
        }else{
            return redirect()->route('admin.listing.edit', ['type' => 'work', 'id' => $id, 'tab'=>'property']);
        }
    }
    public function listing_store_property(Request $request, $prefix, $id){      
        $validated = $request->validate([
            'title' => 'required|max:50',
            'person' => 'required|max:100',
            'price' => 'required|max:50',
            'feature' => 'required',
            'image' => 'required',
        ]);
        $data['title'] = sanitize($request->title);
        $data['person'] = sanitize($request->person);
        $data['child'] = sanitize($request->child);
        $data['segment_type'] = sanitize($request->segment_type);
        $data['listing_id'] = $id;
        $data['user_id'] = user('id');
        $data['price'] = sanitize($request->price);
        $data['feature'] = json_encode($request->feature);
        if ($request->has('room_type')) {
            $data['room_type'] = implode(',', $request->room_type);
        } else {
            $data['room_type'] = null;
        }
        $room_image = [];
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $key => $image) {
                $imageName = $key.'-'.time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/room-images'), $imageName);
                array_push($room_image, $imageName);
            }
        }
        $data['image'] = json_encode($room_image);
        $this->room->insert($data);
        Session::flash('success', get_phrase('Work Property Create successful!'));
        if(user('role') == 2) {
            return redirect()->route('user.listing.edit',['id'=>$id, 'type'=>'work','tab'=>'property']);
        }else{
            return redirect()->route('admin.listing.edit', ['id'=> $id, 'type' =>'work','tab'=>'property']);
        }
    }
    
    public function listing_room($prefix, $id, $listing_id){
        Room::where('id', $id)->delete();
        Session::flash('success', get_phrase('Sleep room delete successful!'));
        if(user('role') == 2) {
            return redirect()->route('user.listing.edit',['id'=>$listing_id, 'type'=>'sleep','tab'=>'room']);
        }else{
            return redirect()->route('admin.listing.edit', ['type' => 'sleep', 'id' => $listing_id, 'tab'=>'feature']);
        }
    }

    public function listing_property($prefix, $id, $listing_id){
        Room::where('id', $id)->delete();
        Session::flash('success', get_phrase('Sleep Property delete successful!'));
        if(user('role') == 2) {
            return redirect()->route('user.listing.edit',['id'=>$listing_id, 'type'=>'work','tab'=>'property']);
        }else{
            return redirect()->route('admin.listing.edit', ['type' => 'work', 'id' => $listing_id, 'tab'=>'property']);
        }
    }

    public function listing_menu_add($prefix, $id){
        $page_data['id'] = $id;
        $page_data['page'] = 'add';
        return view('admin.listing.menu', $page_data);
    }

    public function listing_menu_store(Request $request, $prefix, $id) {
        $validated = $request->validate([
            'title' => 'required|max:50',
            'sub_title' => 'required|max:100',
            'price' => 'required|max:50',
            'image' => 'required',
        ]);
        $data['title'] = sanitize($request->title);
        $data['sub_title'] = sanitize($request->sub_title);
        $data['price'] = $request->price;
        $data['listing_id'] = $id;
        $data['user_id'] = user('id');
        $data['dis_price'] = $request->dis_price;
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            // $request->image->storeAs('public/menu', $imageName);
            $request->image->move(public_path('uploads/menu'), $imageName);
            $data['image'] = $imageName;
        }
        Menu::insert($data);
        Session::flash('success', get_phrase('Listing menu create successful!'));
        if(user('role') == 1){
            return redirect()->route('admin.listing.edit', ['type' => 'play', 'id' => $id, 'tab'=>'menu']);
        }else{
            return redirect()->route('user.listing.edit',['id'=>$id, 'type'=>'play','tab'=>'menu']);
        }
    }

    public function listing_menu_edit($prefix, $id, $listing_id, $page){
        $page_data['id'] = $id;
        $page_data['page'] = $page;
        $page_data['listing_id'] = $listing_id;
        $page_data['menu'] = Menu::where('id', $id)->first();
        return view('admin.listing.menu', $page_data);
    }

    public function listing_menu_update(Request $request, $prefix, $id, $listing_id){
        $validated = $request->validate([
            'title' => 'required|max:50',
            'sub_title' => 'required|max:100',
            'price' => 'required|max:50',
        ]);
        $data['title'] = sanitize($request->title);
        $data['sub_title'] = sanitize($request->sub_title);
        $data['price'] = $request->price;
        $data['listing_id'] = $listing_id;
        $data['dis_price'] = $request->dis_price;
        $menu = Menu::where('id', $id);
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('uploads/menu'), $imageName);
            $data['image'] = $imageName;
            if(file_exists('public/uploads/menu/'.$menu->first()->image)){
                unlink('public/uploads/menu/'.$menu->first()->image);
            }
        }
        $menu->update($data);
        Session::flash('success', get_phrase('Listing menu update successful!'));
        if(user('role') == 1){
            return redirect()->route('admin.listing.edit', ['type' => 'play', 'id' => $listing_id, 'tab'=>'menu']);
        }else{
            return redirect()->route('user.listing.edit',['id'=>$listing_id, 'type'=>'play','tab'=>'menu']);
        }
    }

    public function listing_menu_delete($prefix, $id, $listing_id){
        $menu = Menu::where('id', $id);
        if(file_exists('public/uploads/menu/'.$menu->first()->image)){
            unlink('public/uploads/menu/'.$menu->first()->image);
        }
        $menu->delete();
        Session::flash('success', get_phrase('Listing menu delete successful!'));
        if(user('role') == 1){
            return redirect()->route('admin.listing.edit', ['type' => 'play', 'id' => $listing_id, 'tab'=>'menu']);
        }else{
            return redirect()->route('user.listing.edit',['id'=>$listing_id, 'type'=>'play','tab'=>'menu']);
        }
    }

   


    // Listing NearBy
  
    public function listing_nearBY($prefix, $id){
        $page_data['id'] = $id;
        $page_data['page'] = 'add';
        return view('admin.listing.nearby_add', $page_data);
    }


    public function saveNearByLocation(Request $request)
    {
        $data= $request->all();


        $listing= $this->workListing->find($data['nearby_listing_id']);
        $nearby=json_decode($listing->near_by,true);
        $type=$nearby[$data['nearby_id']];

        $insertNearbyLocation = new NearByLocation();
        $insertNearbyLocation->name=$data['nearbyname'];
        $insertNearbyLocation->type=$type;
        $insertNearbyLocation->nearby_id=$data['nearby_id'];
        $insertNearbyLocation->listing_type=$data['listing_type'];
        $insertNearbyLocation->latitude=$data['nearby-latitude'];
        $insertNearbyLocation->longitude=$data['nearby-longitude'];
        $insertNearbyLocation->listing_id=$listing->id;
        $insertNearbyLocation->save();
        Session::flash('success', get_phrase('NearBy Location Add successful!'));
        return redirect()->back();

    }

    public function edit_listing_nearBY($prefix, $id, $page = null){
        $page_data['id'] = $id;
        $page_data['page'] = $page;
        $page_data['NearBy'] = NearByLocation::where('id', $id)->first();
        return view('admin.listing.nearby_edit', $page_data);
    }


    public function updateNearByLocation(Request $request,$prefix, $id)
    {
        $data = $request->all();
      
        $updateNearByLocation = NearByLocation::find($id);
        $updateNearByLocation->name = $data['nearbyname'];
        $updateNearByLocation->nearby_id = $data['nearby_id'];
        $updateNearByLocation->latitude = $data['nearby-latitude'];
        $updateNearByLocation->longitude = $data['nearby-longitude'];
    
        $updateNearByLocation->save();
        Session::flash('success', 'NearBy Location updated successfully!');
        return redirect()->back();
    }
    


    public function deleteNearByLocation($prefix, $id)
    {
        $deleteNearbyLocation =NearByLocation::find($id);
        $deleteNearbyLocation->delete();
        Session::flash('success', get_phrase('Location has been deleted'));
        return redirect()->back();
    }

    // Admin Claim Add
    public function claimed_listing_form($type, $id){
        $page_data['claimType']= $type;
        $page_data['claimId']= $id;
        return view('admin.listing.claimed_listings_form', $page_data);
    }
    
    public function claimed_validity_approve(Request $request, $listing_type, $listing_id){
        $request->validate([
            'user_name' => 'required|string|max:255',
            'user_phone' => 'required|numeric',
            'additional_info' => 'required|string',
        ]);
        $data = $request->except('_token');
        $data = $request->all();
        if ($listing_type == 'sleep') {
            $listing = $this->sleepListing->where('id', $listing_id)->first();
        } elseif ($listing_type == 'work') {
            $listing = $this->workListing->where('id', $listing_id)->first();
        } elseif ($listing_type == 'play') {
            $listing = $this->playListing->where('id', $listing_id)->first();
        }
        
        $data['user_name'] = $request->user_name;
        $data['user_phone'] = $request->user_phone;
        $data['additional_info'] = $request->additional_info;
        $data['listing_id'] = $listing->id;
        $data['listing_type'] = $listing->type;
        $data['user_id'] = $listing->user_id;
        $data['status'] = 1;
        ClaimedListing::create($data);
        return redirect()->back()->with('success', get_phrase('The claimed listing has been approved successfully!'));
        
    }

    public function claimed_listings()
    {
        $listings = $this->claimedListing->where('status', 0)->with(['sleep', 'play', 'work'])->get()
            ->map(function ($item) {
                return $item->toFormattedArray();
            });
        return view('admin.listing.claimed_listings', compact('listings'));
    }

    public function claimed_listings_approve($type, $listing_id)
    {
        $claimListing = ClaimedListing::find($listing_id);
        $claimListing->status = 1;
        $claimListing->save();
    
        Session::flash('success', get_phrase('The claimed listing has been approved successfully!'));
        return redirect()->back();
    }
    
    
    public function claimed_listings_delete($id){
        $claimListing = ClaimedListing::find($id);
        $claimListing->delete();
        Session::flash('success', get_phrase('Claimed listing has been remove successfully!'));
        return redirect()->back();
    }
    


    public function reported_listings()
    {
        $reported_listings = $this->reportedListing->where('status', 0)->with(['sleep', 'play', 'work'])->get()
            ->map(function ($item) {
                return $item->toFormattedArray();
            });
        return view('admin.listing.reported_listings', compact('reported_listings'));
    }

    public function report_listings_delete($id){
        $reportListing = $this->reportedListing->find($id);
        $reportListing->delete();
        Session::flash('success', get_phrase('Report listing has been remove successfully!'));
        return redirect()->back();
    }

    public function report_listings_approve($type, $listing_id)
    {
        $reportListing = $this->reportedListing->find($listing_id);
        $reportListing->status = 1;
        $reportListing->save();
        Session::flash('success', get_phrase('The Report listing has been approved successfully!'));
        return redirect()->back();
    }
    
    // Report To Listing Delete
    public function report_global_listings_delete($type, $listing_id){
        if($type == 'sleep'){
            $listing = $this->sleepListing->where('id', $listing_id);
        }elseif($type == 'work'){
            $listing =  $this->workListing->where('id', $listing_id);
        }elseif($type == 'play'){
            $listing = $this->playListing->where('id', $listing_id);
        }
        $listingData = $listing->first();
        if ($listingData) {
            foreach (json_decode($listingData->image) as $listImage) {
                $imagePath = public_path('uploads/listing-images/'.$listImage);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            $listing->delete();
            $this->reportedListing->where('type', $type)->where('listing_id', $listing_id)->delete();
            Session::flash('success', get_phrase('Listing deleted successfully!'));
        } else {
            Session::flash('error', get_phrase('Listing not found!'));
        }
    
        return redirect()->back();
    }
}