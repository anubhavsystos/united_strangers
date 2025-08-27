<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\Offer;
use App\Models\SleepListing;
use App\Models\WorkListing;
use App\Models\PlayListing;

class OfferController extends Controller
{
    protected $offer;
    protected $sleepListing;
    protected $workListing;
    protected $playListing;

    public function __construct(Offer $offer, SleepListing $sleepListing, WorkListing $workListing, PlayListing $playListing)
    {
        $this->offer = $offer;
        $this->sleepListing = $sleepListing;
        $this->workListing = $workListing;
        $this->playListing = $playListing;
    }


    public function index(){
        try{           
            $offers = $this->offer->get()->map(function ($item) {
                return $item->offerformatted();
            }); 
            return view('admin.offer.list',compact('offers'));
        }catch (Exception $e){
            \Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    public function offer_create(){
        return view('admin.offer.create');
    }

    public function store(Request $request){
        $request['to_date'] = date('Y-m-d', strtotime($request->to_date));
        $request['from_date'] =  date('Y-m-d', strtotime($request->from_date));
        $listing_image = [];      
        if ($request->hasFile('listing_image')) {
            foreach ($request->file('listing_image') as $key => $image) {
                $imageName = $key.'-'.time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/offer-images'), $imageName);
                array_push($listing_image, $imageName);
            }
        }
        $request['image'] = json_encode($listing_image);        
        $offer = $this->offer->create($request->except(['_token', 'listing_image']));
        return redirect()->route('admin.offers')->with('success', 'Offer created successfully.');        
    }

    public function offer_type(Request $request){
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
   
    public function offer_edit( $id){ 
        $offer = $this->offer->find($id);
        $segmentName = $offer->segment?->title ?? '';
        return view('admin.offer.create', compact('offer', 'segmentName'));
    }

    

   public function offer_update(Request $request, $id){
        $request->validate([
            'title'        => 'required|string|max:255',
            'from_date'    => 'required|date',
            'to_date'      => 'required|date',
            'listing_image'=> 'nullable|array',
            'listing_image.*' => 'image|mimes:jpg,jpeg,png|max:2048'
        ]);
        $offer = $this->offer->findOrFail($id);
        $request['from_date'] = date('Y-m-d', strtotime($request->from_date));
        $request['to_date']   = date('Y-m-d', strtotime($request->to_date));
        $listing_image = json_decode($offer->image, true) ?? []; 
        if ($request->hasFile('listing_image')) {
            foreach ($request->file('listing_image') as $key => $image) {
                $imageName = $key.'-'.time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/offer-images'), $imageName);
                $listing_image[] = $imageName;
            }
        }
        $request['image'] = json_encode($listing_image);
        $offer->update($request->except(['_token', 'listing_image']));
        return redirect()->route('admin.offers')->with('success', 'Offer updated successfully.');
    }
   
    public function offer_delete($id){
        $offer = $this->offer->where('id', $id);       
        foreach(json_decode($offer->first()->image) as $listImage){
            if(file_exists('public/uploads/offer-images/'.$listImage)){
                unlink('public/uploads/offer-images/'.$listImage);
            }
        }
        $offer->delete();
        Session::flash('success', get_phrase('offer deleted successfully!'));
        return redirect()->back();
    }

    public function offer_image_delete($id, $image){        
        $listing = $this->offer->where('id', $id);
        
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