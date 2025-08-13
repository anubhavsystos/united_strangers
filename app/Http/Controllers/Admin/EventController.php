<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\Event;
use App\Models\SleepListing;
use App\Models\WorkListing;
use App\Models\PlayListing;

class EventController extends Controller
{
    protected $event;
    protected $sleepListing;
    protected $workListing;
    protected $playListing;

    public function __construct(event $event, SleepListing $sleepListing, WorkListing $workListing, PlayListing $playListing)
    {
        $this->event = $event;
        $this->sleepListing = $sleepListing;
        $this->workListing = $workListing;
        $this->playListing = $playListing;
    }


    public function index(){
        try{           
            $events = $this->event->get();
            return view('admin.event.list',compact('events'));
        }catch (Exception $e){
            \Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    public function event_create(){
        return view('admin.event.create');
    }

    public function store(Request $request){
        $request['to_date'] = date('Y-m-d', strtotime($request->from_date));
        $request['from_date'] =  date('Y-m-d', strtotime($request->to_date));
        $listing_image = [];
        if ($request->hasFile('listing_image')) {
            foreach ($request->file('listing_image') as $key => $image) {
                $imageName = $key.'-'.time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/event-images'), $imageName);
                array_push($listing_image, $imageName);
            }
        }
        $request['image'] = json_encode($listing_image);        
        $event = $this->event->create($request->except(['_token', 'listing_image']));
        return redirect()->route('admin.event')->with('success', 'event created successfully.');        
    }

    public function event_type(Request $request)
    {
        $type = $request->type;
        if ($type === 'sleep') {
            $segment = $this->sleepListing->select('id', 'title')->get();
        } elseif ($type === 'work') {
            $segment = $this->workListing->select('id', 'title')->get();
        } elseif ($type === 'play') {
            $segment = $this->playListing->select('id', 'title')->get();
        } else {
            $segment = [];
        } 

        return response()->json($segment);
    }


   
    public function event_edit($type, $id, $tab = ""){  
        if($type == 'work'){
            $page_data['event'] =  $this->workevent->where('id', $id)->first();
        }elseif($type == 'sleep'){
            $page_data['event'] = $this->sleepevent->where('id', $id)->first();
        }elseif($type == 'play'){
            $page_data['event'] = $this->playevent->where('id', $id)->first();
        }
        $page_data['categories'] = $this->category->where('type', $type)->get();
        $page_data['tab'] = $tab;
        $page_data['type'] = $type;
         
        return view('admin.event.'.$type.'_edit', $page_data);
    }

    public function event_update(Request $request, $type, $id){
        
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

      

        if($type == 'car'){
            $data['sub_title'] = sanitize($request->sub_title);
            $data['brand'] = sanitize($request->brand);
            $data['model'] = sanitize($request->model);
            $data['year'] = sanitize($request->year);
            $data['car_type'] = sanitize($request->car_type);
            $data['transmission'] = sanitize($request->transmission);
            $data['fuel_type'] = sanitize($request->fuel_type);
            $data['engine_size'] = sanitize($request->engine_size);
            $data['cylinder'] = sanitize($request->cylinder);
            $data['interior_color'] = sanitize($request->interior_color);
            $data['exterior_color'] = sanitize($request->exterior_color);
            $data['drive_train'] = sanitize($request->drive_train);
            $data['trim'] = sanitize($request->trim);
            $data['mileage'] = sanitize($request->mileage);
            $data['vin'] = sanitize($request->vin);
            $data['price'] = sanitize($request->price);
            $data['discount_price'] = $request->discount_price;
            $data['feature'] = sanitize($request->feature);
            $data['specification'] = sanitize($request->specification);
            $data['is_popular'] = $request->is_popular ?? 0;
            $data['status'] = sanitize($request->status);
            $data['stock'] =sanitize($request->stock);

            $event_image = json_decode(Carevent::where('id', $id)->pluck('image')->toArray()[0])??[];

            if ($request->hasFile('event_image')) {
                foreach ($request->file('event_image') as $key => $image) {
                    $imageName = $key.'-'.time() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('uploads/event-images'), $imageName);
                    array_push($event_image, $imageName);
                }
                $data['image'] = json_encode($event_image);
            }else{
                $data['image'] = $event_image;
            }
            
            Carevent::where('id', $id)->update($data);
            Session::flash('success', get_phrase('event Update successfully!'));
            if(isset($request->is_agent) && $request->is_agent == 1){
                return redirect('agent/my-events');
            }else{
                return redirect('admin/events/car');
            }
        }elseif($type == 'beauty'){

            $opening_times = [
                'saturday' => ['open' => sanitize($request->saturday_open), 'close' => sanitize($request->saturday_close)],
                'sunday' => ['open' =>sanitize( $request->sunday_open), 'close' => sanitize($request->sunday_close)],
                'monday' => ['open' => sanitize($request->monday_open), 'close' => sanitize($request->monday_close)],
                'tuesday' => ['open' => sanitize($request->tuesday_open), 'close' => sanitize($request->tuesday_close)],
                'wednesday' => ['open' => sanitize($request->wednesday_open), 'close' => sanitize($request->wednesday_close)],
                'thursday' => ['open' => sanitize($request->thursday_open), 'close' => sanitize($request->thursday_close)],
                'friday' => ['open' => sanitize($request->friday_open), 'close' => sanitize($request->friday_close)],
            ];
            
            // Encode the array into JSON format
            $data['opening_time'] = json_encode($opening_times);

            $data['video'] = sanitize($request->video);

            $data['is_popular'] = $request->is_popular ?? 0;
            
            $data['team'] = json_encode($request->team)??[];
            
            $data['service'] = json_encode($request->service)??[];


            $event_image = json_decode(Beautyevent::where('id', $id)->pluck('image')->toArray()[0])??[];

            if ($request->hasFile('event_image')) {
                foreach ($request->file('event_image') as $key => $image) {
                    $imageName = $key.'-'.time() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('uploads/event-images'), $imageName);
                    array_push($event_image, $imageName);
                }
                $data['image'] = json_encode($event_image);
            }else{
                $data['image'] = $event_image;
            }
            Session::flash('success', get_phrase('event Update successfully!'));
            Beautyevent::where('id', $id)->update($data);

            if(isset($request->is_agent) && $request->is_agent == 1){
                return redirect('agent/my-events');
            }else{
                return redirect('admin/events/beauty');
            }
            
        }elseif($type == 'sleep'){
            $data['price'] = sanitize($request->price);
            $data['bed'] = sanitize($request->bed);
            $data['bath'] = sanitize($request->bath);
            $data['size'] = sanitize($request->size);
            $data['dimension'] = sanitize($request->dimension);
            $data['feature'] = json_encode($request->feature)??[];
            $data['room'] = json_encode($request->room)??[];
            $data['is_popular'] = $request->is_popular ?? 0;
          
            
            $event_image = json_decode($this->sleepevent->where('id', $id)->pluck('image')->toArray()[0])??[];

            if ($request->hasFile('event_image')) {
                foreach ($request->file('event_image') as $key => $image) {
                    $imageName = $key.'-'.time() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('uploads/event-images'), $imageName);
                    array_push($event_image, $imageName);
                }
                $data['image'] = json_encode($event_image);
            }else{
                $data['image'] = $event_image;
            }
            
            $this->sleepevent->where('id', $id)->update($data);
            Session::flash('success', get_phrase('event Update successfully!'));
            if(isset($request->is_agent) && $request->is_agent == 1){
                return redirect('agent/my-events');
            }else{
                return redirect('admin/events/sleep');
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


            $event_image = json_decode( $this->workevent->where('id', $id)->pluck('image')->toArray()[0])??[];

            if ($request->hasFile('event_image')) {
                foreach ($request->file('event_image') as $key => $image) {
                    $imageName = $key.'-'.time() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('uploads/event-images'), $imageName);
                    array_push($event_image, $imageName);
                }
                $data['image'] = json_encode($event_image);
            }else{
                $data['image'] = json_encode($event_image);
            }

            // Model
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
        
            $event_event_floor_plan = json_decode( $this->workevent->where('id', $id)->pluck('floor_plan')->toArray()[0])??[];

            if ($request->hasFile('event_floor_plan')) {
                foreach ($request->file('event_floor_plan') as $key => $image) {
                    $floorImage = $key.'-'.time() . '.' . $image->getClientOriginalExtension();
                    // $image->storeAs('public/floor-plan', $floorImage);
                    $image->move(public_path('uploads/floor-plan'), $floorImage);
                    array_push($event_event_floor_plan, $floorImage);
                }
                $data['floor_plan'] = json_encode($event_event_floor_plan);
            }else{
                $data['floor_plan'] = json_encode($event_event_floor_plan);
            }

            Session::flash('success', get_phrase('event Update successfully!'));
             $this->workevent->where('id', $id)->update($data);
            if(isset($request->is_agent) && $request->is_agent == 1){
                return redirect('agent/my-events');
            }else{
                return redirect('admin/events/work');
            }
        }elseif($type == 'play'){
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
            
            // Encode the array into JSON format
            $data['opening_time'] = json_encode($opening_times);
            $data['amenities'] = json_encode($request->feature)??[];
            $event_image = json_decode($this->playevent->where('id', $id)->pluck('image')->toArray()[0])??[];

            if ($request->hasFile('event_image')) {
                foreach ($request->file('event_image') as $key => $image) {
                    $imageName = $key.'-'.time() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('uploads/event-images'), $imageName);
                    array_push($event_image, $imageName);
                }
                $data['image'] = json_encode($event_image);
            }else{
                $data['image'] = json_encode($event_image);
            }
            $data['menu'] = json_encode($request->menu)??[];
            $this->playevent->where('id', $id)->update($data);
            Session::flash('success', get_phrase('event Update successfully!'));
            if(isset($request->is_agent) && $request->is_agent == 1){
                return redirect('agent/my-events');
            }else{
                return redirect('admin/events/play');
            }
        }
    }


    public function event_image_delete($type, $id, $image){
        if($type == 'car'){
            $event = Carevent::where('id', $id);
        }elseif($type == 'beauty'){
            $event = Beautyevent::where('id', $id);
        }elseif($type == 'sleep'){
            $event = $this->sleepevent->where('id', $id);
        }elseif($type == 'work'){
            $event =  $this->workevent->where('id', $id);
        }elseif($type == 'play'){
            $event = $this->playevent->where('id', $id);
        }
        $imageToRemove = $image;
        $imageArray = json_decode($event->first()->image);
        $key = array_search($imageToRemove, $imageArray);
        if ($key !== false) {
            unset($imageArray[$key]);
        }
        $imageArray = array_values($imageArray);
        $resultJson = json_encode($imageArray);
        $event->update(['image'=>$resultJson]);
        if(file_exists('public/uploads/event-images/'.$image)){
            unlink('public/uploads/event-images/'.$image);
        }
        return 1;
    }
    public function event_floor_image_delete($type, $id, $image){
        if($type == 'car'){
            $event = Carevent::where('id', $id);
        }elseif($type == 'beauty'){
            $event = Beautyevent::where('id', $id);
        }elseif($type == 'sleep'){
            $event = $this->sleepevent->where('id', $id);
        }elseif($type == 'work'){
            $event =  $this->workevent->where('id', $id);
        }elseif($type == 'play'){
            $event = $this->playevent->where('id', $id);
        }
        $imageToRemove = $image;
        $imageArray = json_decode($event->first()->floor_plan);
        $key = array_search($imageToRemove, $imageArray);
        if ($key !== false) {
            unset($imageArray[$key]);
        }
        $imageArray = array_values($imageArray);
        $resultJson = json_encode($imageArray);
        $event->update(['floor_plan'=>$resultJson]);
        if(file_exists('public/uploads/floor-plan/'.$image)){
            unlink('public/uploads/floor-plan/'.$image);
        }
        return 1;
    }

    public function event_status($type, $id, $status){
        $status = $status == 'visible'?'hidden':'visible';
       
        if($type == 'car'){
            $event = Carevent::where('id', $id);
        }elseif($type == 'beauty'){
            $event = Beautyevent::where('id', $id);
        }elseif($type == 'sleep'){
            $event = $this->sleepevent->where('id', $id);
        }elseif($type == 'work'){
            $event =  $this->workevent->where('id', $id);
        }elseif($type == 'play'){
            $event = $this->playevent->where('id', $id);
        }       
        $event->update(['visibility'=>$status]);
        Session::flash('success', get_phrase('event Update successfully!'));
        return redirect()->back();
    }

    public function event_delete($type, $id){
        if($type == 'car'){
            $event = Carevent::where('id', $id);
        }elseif($type == 'beauty'){
            $event = Beautyevent::where('id', $id);
        }elseif($type == 'sleep'){
            $event = $this->sleepevent->where('id', $id);
        }elseif($type == 'work'){
            $event =  $this->workevent->where('id', $id);
        }elseif($type == 'play'){
            $event = $this->playevent->where('id', $id);
        }
        foreach(json_decode($event->first()->image) as $listImage){
            if(file_exists('public/uploads/event-images/'.$listImage)){
                unlink('public/uploads/event-images/'.$listImage);
            }
        }
        $event->delete();
        Session::flash('success', get_phrase('event deleted successfully!'));
        return redirect()->back();
    }

}