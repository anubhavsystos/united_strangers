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

    public function __construct(Event $event, SleepListing $sleepListing, WorkListing $workListing, PlayListing $playListing)
    {
        $this->event = $event;
        $this->sleepListing = $sleepListing;
        $this->workListing = $workListing;
        $this->playListing = $playListing;
    }


    public function index(){
        try{           
            $events = $this->event->get()->map(function ($item) {
                return $item->eventformatted();
            }); 
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
        $request['to_date'] = date('Y-m-d', strtotime($request->to_date));
        $request['from_date'] =  date('Y-m-d', strtotime($request->from_date));
        $listing_image = [];      
        if ($request->hasFile('listing_image')) {
            foreach ($request->file('listing_image') as $key => $image) {
                $imageName = $key.'-'.time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/event-images'), $imageName);
                array_push($listing_image, $imageName);
            }
        }
        $request['image'] = json_encode($listing_image);        
        $request['user_id'] = auth()->user()->id;        
        $event = $this->event->create($request->except(['_token', 'listing_image']));
        return redirect()->route('admin.event')->with('success', 'event created successfully.');        
    }

    public function event_type(Request $request){
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
   
    public function event_edit( $id){ 
        $event = $this->event->find($id);
        $segmentName = $event->segment?->title ?? '';
        return view('admin.event.create', compact('event', 'segmentName'));
    }

    

   public function event_update(Request $request, $id){
        $request->validate([
            'title'        => 'required|string|max:255',
            'from_date'    => 'required|date',
            'to_date'      => 'required|date',
            'listing_image'=> 'nullable|array',
            'listing_image.*' => 'image|mimes:jpg,jpeg,png|max:2048'
        ]);
        $event = $this->event->findOrFail($id);
        $request['from_date'] = date('Y-m-d', strtotime($request->from_date));
        $request['to_date']   = date('Y-m-d', strtotime($request->to_date));
        $listing_image = json_decode($event->image, true) ?? []; 
        if ($request->hasFile('listing_image')) {
            foreach ($request->file('listing_image') as $key => $image) {
                $imageName = $key.'-'.time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/event-images'), $imageName);
                $listing_image[] = $imageName;
            }
        }
        $request['image'] = json_encode($listing_image);
        $event->update($request->except(['_token', 'listing_image']));
        return redirect()->route('admin.event')->with('success', 'event updated successfully.');
    }
   
    public function event_delete($id){
        $event = $this->event->where('id', $id);       
        foreach(json_decode($event->first()->image) as $listImage){
            if(file_exists('public/uploads/event-images/'.$listImage)){
                unlink('public/uploads/event-images/'.$listImage);
            }
        }
        $event->delete();
        Session::flash('success', get_phrase('event deleted successfully!'));
        return redirect()->back();
    }

    public function event_image_delete($id, $image){        
        $listing = $this->event->where('id', $id);
        
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

}