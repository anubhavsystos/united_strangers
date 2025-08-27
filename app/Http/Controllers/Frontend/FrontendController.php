<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BeautyListing;
use App\Models\Blog;
use App\Models\CarListing;
use App\Models\Category;
use App\Models\SleepListing;
use App\Models\Pricing;
use App\Models\WorkListing;
use App\Models\PlayListing;
use App\Models\User;
use App\Models\Message_thread;
use App\Models\Message;
use App\Models\Review;
use App\Models\Wishlist;
use App\Models\City;
use App\Models\Country;
use App\Models\Menu;
use App\Models\Appointment;
use App\Models\Amenities;
use App\Models\Newsletter;
use App\Models\Newsletter_subscriber;
use App\Models\ClaimedListing;
use App\Models\ReportedListing;
use App\Models\Contact;
use App\Models\Offer;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
class FrontendController extends Controller
{
    protected $blog;
    protected $blog_category;

   public function __construct(BeautyListing $beautyListing,CarListing $carListing,SleepListing $sleepListing,WorkListing $workListing,PlayListing $playListing,User $user,Message_thread $messageThread,Message $message,Review $review,Wishlist $wishlist,City $city,Country $country,Menu $menu,Appointment $appointment,Amenities $amenities,Newsletter $newsletter,Newsletter_subscriber $newsletterSubscriber,ClaimedListing $claimedListing,ReportedListing $reportedListing,Contact $contact,Pricing $pricing,Category $category,Offer $offer, Event $event, Blog $blog) {       
        $this->sleepListing = $sleepListing;
        $this->workListing = $workListing;
        $this->playListing = $playListing;
        $this->user = $user;
        $this->wishlist = $wishlist;
        $this->city = $city;
        $this->country = $country;
        $this->appointment = $appointment;
        $this->amenities = $amenities;
        $this->newsletter = $newsletter;
        $this->newsletterSubscriber = $newsletterSubscriber;
        $this->claimedListing = $claimedListing;
        $this->reportedListing = $reportedListing;
        $this->contact = $contact;
        $this->pricing = $pricing;
        $this->category = $category;
        $this->offer = $offer;
        $this->event = $event;
        $this->blog = $blog;
    }


    public function index(){
        $sleeplistings = $this->sleepListing->where('visibility', 'visible')->orderBy('created_at', 'desc')->limit(15)->get()->map(function ($item) {
            return $item->productFormattedArray();
        });

        $worklistings = $this->workListing->where('visibility', 'visible')->orderBy('created_at', 'desc')->limit(15)->get()->map(function ($item) {
            return $item->productFormattedArray();
        });

        $playlistings = $this->playListing->where('visibility', 'visible')->orderBy('created_at', 'desc')->limit(15)->get()->map(function ($item) {
            return $item->productFormattedArray();
        });

        $today = now()->toDateString();

        $offers = $this->offer->whereDate('to_date', '<=', $today)->whereDate('from_date', '>=', $today)->get()->map(function ($item) {
            return $item->offerformatted();
        });

        $events = $this->event->whereDate('to_date', '<=', $today)->whereDate('from_date', '>=', $today)->get()->map(function ($item) {
            return $item->eventformatted();
        });

        $blogs = $this->blog->orderBy('created_at', 'desc')->limit(15)->get()->map(function ($item) {
            return $item->blogformatted();
        });

        return view('frontend.index', compact('sleeplistings','worklistings','playlistings','offers','events','blogs'));
    }

   

    public function details(){
        $sleepListing_data = [];
        $workListing_data = [];
        $playListing_data = [];
        $sleepListing = $this->sleepListing->where('visibility', 'visible')->with(['countryDetail', 'cityDetail', 'categoryDetail'])->get();
        $sleepcountries = $sleepListing->map(function ($item) {
            return $item->countryDetail ? [ 'id' => $item->countryDetail->id, 'name' => $item->countryDetail->name ] : null;
        })->filter()->unique('id')->values();
        $sleepcitys = $sleepListing->map(function ($item) {
            return $item->cityDetail ? ['id' => $item->cityDetail->id,'name' => $item->cityDetail->name ] : null;
        })->filter()->unique('id')->values();
        $sleepcategorys = $sleepListing->map(function ($item) {
            return $item->categoryDetail ? ['id' => $item->categoryDetail->id,'name' => $item->categoryDetail->name ] : null;
        })->filter()->unique('id')->values();
        $sleepListing_data['sleepcountries'] = $sleepcountries;
        $sleepListing_data['sleepcitys'] = $sleepcitys;
        $sleepListing_data['sleepcategorys'] = $sleepcategorys;
        $workListing = $this->workListing->where('visibility', 'visible')->with(['countryDetail', 'cityDetail', 'categoryDetail'])->get();
        $workcountries = $workListing->map(function ($item) {
            return $item->countryDetail ? [ 'id' => $item->countryDetail->id, 'name' => $item->countryDetail->name ] : null;
        })->filter()->unique('id')->values();
        $workcitys = $workListing->map(function ($item) {
            return $item->cityDetail ? ['id' => $item->cityDetail->id,'name' => $item->cityDetail->name ] : null;
        })->filter()->unique('id')->values();
        $workcategorys = $workListing->map(function ($item) {
            return $item->categoryDetail ? ['id' => $item->categoryDetail->id,'name' => $item->categoryDetail->name ] : null;
        })->filter()->unique('id')->values();
        $workpricerange = $workListing->pluck('price')->filter()->sort()->values();

        $min = $workpricerange->first();
        $max = $workpricerange->last();
        $step = ceil(($max - $min) / 4); 
        $ranges = [];
        for ($i = 0; $i < 4; $i++) {
            $start = $min + $step * $i;
            $end = ($i == 3) ? $max : ($start + $step - 1);
            $ranges[] = ['range' => "{$start} - {$end}"];
        }

        $workListing_data['workcountries'] = $workcountries;
        $workListing_data['workcitys'] = $workcitys;
        $workListing_data['workcategorys'] = $workcategorys;
        $workListing_data['workpricerange'] = collect($ranges);
        // workListing end

         // playListing
        $playListing = $this->playListing->where('visibility', 'visible')->with(['countryDetail', 'cityDetail', 'categoryDetail'])->get();

        $playcountries = $playListing->map(function ($item) {
            return $item->countryDetail ? [ 'id' => $item->countryDetail->id, 'name' => $item->countryDetail->name ] : null;
        })->filter()->unique('id')->values();
        $playcitys = $playListing->map(function ($item) {
            return $item->cityDetail ? ['id' => $item->cityDetail->id,'name' => $item->cityDetail->name ] : null;
        })->filter()->unique('id')->values();
        $playcategorys = $playListing->map(function ($item) {
            return $item->categoryDetail ? ['id' => $item->categoryDetail->id,'name' => $item->categoryDetail->name ] : null;
        })->filter()->unique('id')->values();
        $playListing_data['playcountries'] = $playcountries;
        $playListing_data['playcitys'] = $playcitys;
        $playListing_data['playcategorys'] = $playcategorys;
        // playListing end

        // return $sleepListing_data ;
        return view('frontend.details', compact('sleepListing_data','workListing_data','playListing_data'));
            
    }

    public function show_products_segment(Request $request)
    {
        $type = $request->type;
        $category = $request->category;
       
        $hasMoreThanSeven = 0; 
        if ($type == 'sleep') {           
            $products = $this->sleepListing->with(['countryDetail','cityDetail','claimed','reviews'])->where('visibility', 'visible')->latest()->take(8)->get()->map(function ($item) {
                return $item->productFormattedArray();
            });
            $hasMoreThanSeven = $products->count() > 7;
            
        } elseif ($type == 'work') {
            $products = $this->workListing->with(['countryDetail','cityDetail','claimed','reviews'])->where('visibility', 'visible')->latest()->take(8)->get()->map(function ($item) {
                return $item->productFormattedArray();
            });
            $hasMoreThanSeven = $products->count() > 7;
           
        } elseif ($type == 'play') {
            $products = $this->playListing->with(['countryDetail','cityDetail','claimed','reviews'])->where('visibility', 'visible')->latest()->take(8)->get()->map(function ($item) {
                return $item->productFormattedArray();
            });
            $hasMoreThanSeven = $products->count() > 7;
        } else {
            return response('Invalid type', 400);
        }

        return response()->json([
            'type' => $type,
            'products' => $products,
            'show_view_more' => $hasMoreThanSeven
        ]);            
        return response()->json($products);
    }



    // public function data_injection(){   
    //     $data = "sleep";
    //      $images = json_decode($sleepList->image) ?? [];
    //                     $image = isset($images[0]) ? $images[0] : null;
    //                     $countryName = App\Models\Country::where('id', $sleepList->country)->first();
    //                     $cityName = App\Models\City::where('id', $sleepList->city)->first();

    //                     $claimStatus = App\Models\ClaimedListing::where('listing_id', $sleepList->id)->where('listing_type', 'sleep')->first();  
    //     return $data ;
    // }

    public function sleep_home(){
        $page_data['cities'] = City::get();
        $uniqueCountryIds = City::distinct()->pluck('country');
        $page_data['listing_countries'] = Country::whereIn('id', $uniqueCountryIds)->take(6)->get();
        $page_data['categories'] = Category::where('type','sleep')->get();
        $page_data['top_listings'] = SleepListing::orderBy('id', 'desc')->where('visibility', 'visible')->get();
        $page_data['directory'] = 'sleep';
        return view('frontend.sleep.home', $page_data);
    }    

    public function car_home(){
        $page_data['categories'] = Category::where('type','car')->get();
        $page_data['top_listings'] = CarListing::orderBy('id', 'desc')->where('visibility', 'visible')->get();
        $page_data['directory'] = 'car';
        return view('frontend.car.home', $page_data);
    }

    public function beauty_home(){
        $page_data['BeautyPopular'] = BeautyListing::orderBy('id', 'desc')->where('visibility', 'visible')->where('is_popular','popular')->limit(4)->get();
        $page_data['BeautyBest'] = BeautyListing::orderBy('id', 'desc')->where('visibility', 'visible')->where('is_popular','best')->limit(4)->get();
        $page_data['BeautyWellness'] = BeautyListing::orderBy('id', 'desc')->where('visibility', 'visible')->where('is_popular','wellness')->limit(4)->get();
        $page_data['directory'] = 'beauty';
        return view('frontend.beauty.home', $page_data);
    }

    public function doctor_home(){
        $page_data['top_listings'] = SleepListing::orderBy('id', 'desc')->where('visibility', 'visible')->limit(4)->get();
        $page_data['directory'] = 'doctor';
        return view('frontend.doctor.home', $page_data);
    }
    public function realestate_home(){
        $page_data['categories'] = Category::where('type','work')->get();
        $cityIdsWithListings = WorkListing::distinct()->pluck('city');
        $page_data['listing_cities'] = City::whereIn('id', $cityIdsWithListings)->take(4)->get();
        $page_data['top_listings'] = WorkListing::orderBy('id', 'desc')->where('visibility', 'visible')->get();
        $page_data['directory'] = 'work';
        return view('frontend.work.home', $page_data);
    }
    public function play_home(){
        $countryIdsWithListings = PlayListing::distinct()->pluck('country');
        $page_data['countries'] = Country::whereIn('id', $countryIdsWithListings)->take(4)->get();

        $cityIdsWithListings = PlayListing::distinct()->pluck('city');
        $page_data['cities'] = City::whereIn('id', $cityIdsWithListings)->take(4)->get();

        $page_data['categories'] = Category::where('type','work')->get();
        $page_data['top_listings'] = PlayListing::orderBy('id', 'desc')->where('visibility', 'visible')->get();
        $page_data['directory'] = 'play';
        return view('frontend.playhome', $page_data);
    }

    public function listing_view($type, $view){
        if($type == 'car'){
            $page_data['listings'] = CarListing::where('visibility', 'visible')->paginate(9);
            $page_data['directory'] = 'car';
        }elseif($type == 'beauty'){
            $page_data['listings'] = BeautyListing::where('visibility', 'visible')->paginate(9);
            $page_data['directory'] = 'beauty';
        }elseif($type == 'sleep'){
            $page_data['listings'] = SleepListing::where('visibility', 'visible')->paginate(9);
            $page_data['directory'] = 'sleep';
        }elseif($type == 'work'){
            $page_data['listings'] = WorkListing::where('visibility', 'visible')->paginate(9);
            $page_data['directory'] = 'work';
        }elseif($type == 'play'){
            $page_data['listings'] = PlayListing::where('visibility', 'visible')->paginate(9);
            $page_data['directory'] = 'play';
        }elseif($type == 'doctor') {
            $page_data['listings'] = User::where('type', 'doctor')->paginate(9);
        }
        
        $page_data['categories'] = Category::where('type', $type)->get();
        $page_data['type'] = $type;
        $page_data['view'] = $view;
        return view('frontend.'.$type.'.'.$view.'_listing', $page_data);
    }

    public function listing_details($type, $id, $slug){
        if($type == 'car'){
            $page_data['listing'] = CarListing::where('id', $id)->first();
            $page_data['directory'] = 'car';
        }elseif($type == 'beauty'){
            $page_data['listing'] = BeautyListing::where('id', $id)->first();
            $page_data['directory'] = 'beauty';
        }elseif($type == 'sleep'){
            $page_data['listing'] = SleepListing::where('id', $id)->first();
            $page_data['directory'] = 'sleep';
        }elseif($type == 'work'){
            $page_data['listing'] = WorkListing::where('id', $id)->first();
            $page_data['directory'] = 'work';
        }elseif($type == 'play'){
            $page_data['listing'] = PlayListing::where('id', $id)->first();
            $page_data['directory'] = 'play';
        }
        $page_data['type'] = $type;
        $page_data['listing_id'] = $id;
        return view('frontend.'.$type.'.details_'.$type, $page_data);
    }

    public function pricing(){
        $page_data['packages'] = Pricing::get();
        return view('frontend.pricing', $page_data);
    }

    public function blogs(){
        $page_data['blogs'] = $this->blog->where('status', 1)->paginate(10);
        return view('frontend.blogs', $page_data);
    }

    public function blog_details($id, $slug){
        $page_data['blog'] = $this->blog->where('id', $id)->first();
        return view('frontend.blog_details', $page_data);
    }
    public function blog_category($category, $slug){
        $page_data['blogs'] = $this->blog->where('category', $category)->where('status', 1)->paginate(10);
        return view('frontend.blogs', $page_data);
    }
    public function blog_search(Request $request){
        $request->validate([
            'search' => 'required|string|max:255',
        ]);
    
        $page_data['blogs'] = $this->blog->where('title', 'like', '%' . $request->search . '%')->where('status', 1)->paginate(10);
        return view('frontend.blogs', $page_data);
    }



    // Reviews System 
    public function ListingReviews(Request $request, $listing_id)
{
    if (!Auth::check()) {
        Session::flash('warning', get_phrase('Please Login First!'));
        return redirect()->route('login');
    }
    
    // Validate input
    $request->validate([
        'rating' => 'required|integer', 
        'review' => 'required|string',
    ]);

    // Create new review
    $review = new Review;
    $review->rating = $request->rating; 
    $review->review = sanitize($request->review);
    $review->type = sanitize($request->listing_type);
    $review->agent_id = sanitize($request->agent_id);
    $review->user_id = auth()->user()->id;
    $review->listing_id = $listing_id;
    $review->save();

    Session::flash('success', get_phrase('Your review was successfully submitted!'));
    return redirect()->back();
}


    public function ListingReviewsUpdate(Request $request, $listing_id)
    {
       

        $data=$request->all();
        $review=Review::where('user_id',auth()->user()->id)->where('listing_id',$listing_id)->first();
        $editReview=Review::find($review->id);
        $editReview->rating=sanitize($data['rating']);
        $editReview->review=sanitize($data['review']);
        $editReview->save();
        Session::flash('success', get_phrase('Your review was update successfully!'));
        return redirect()->back();

    }

    public function ListingReviewsReply(Request $request, $listing_id){

        $data=$request->all();
        $review= new Review;
        $review['review']=sanitize($data['review']);
        $review['type']=sanitize($data['listing_types']);
        $review['agent_id']=sanitize($data['agent_id']);
        $review['user_id']= auth()->user()->id;
        $review['listing_id']=$listing_id;
        $review['reply_id']=sanitize($data['reply_id']);
        $review->save();
        Session::flash('success', get_phrase('Your review was successfully submitted!'));
        return redirect()->back();
    }

    public function ListingReviewsEdit($id){
        $page_data['ReviewEdit'] = Review::where('id', $id)->first();
        return view('frontend.edit-review', $page_data);
    }

    public function ListingOwnReviewsUpdated(Request $request, $id)
    {
        $data = $request->all();
        $review = Review::findOrFail($id);
        $review->review = sanitize($data['update_review']);
        $review->save();
        Session::flash('success', get_phrase('Your review was successfully submitted!'));
        return redirect()->back();
    }

    public function ListingOwnReviewsDelete($id){
        $review = Review::where('id', $id)->first();
        review::where('id', $id)->delete(); 
        Session::flash('success', get_phrase('Review deleted successfully!'));
        return redirect()->back();
    }

    // Wishlist 

    public function updateWishlist(Request $request)
    {
        $validated = $request->validate([
            'listing_id' => 'required|integer',
            'type' => 'required|string',
            'user_id' => 'required|integer',
        ]);
        try {
            $wishlist = Wishlist::where('listing_id', $validated['listing_id'])
                ->where('user_id', $validated['user_id'])
                ->first();
            if ($wishlist) {
                $wishlist->delete(); 
                $message = 'Wishlist removed';
                $status = 'success'; 
            } else {
                Wishlist::create($validated); 
                $message = 'Wishlist added!';
                $status = 'success'; 
            }
            if ($request->ajax()) {
                return response()->json(['status' => $status, 'message' => $message]);
            }
            return redirect()->back();
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            if ($request->ajax()) {
                return response()->json(['status' => 'error', 'message' => $errorMessage], 500);
            }
            return redirect()->back()->withErrors(['error' => $errorMessage]);
        }
    }


    public function followUnfollow(Request $request)
    {
        $loggedInUser = auth()->user(); 
        $followedUserId = $request->input('agent_id'); 
        if (!$loggedInUser || !$followedUserId) {
            return response()->json(['status' => 0, 'message' => 'Invalid request']);
        }
        $following = json_decode($loggedInUser->following_agent, true) ?? [];
        if (in_array($followedUserId, $following)) {
            $following = array_filter($following, function ($id) use ($followedUserId) {
                return $id != $followedUserId;
            });
            $status = 0; 
            $message = 'Unfollowed';
        } else {
            $following[] = $followedUserId;
            $status = 1; 
            $message = 'Followed';
        }
        $loggedInUser->following_agent = json_encode(array_values($following));
        $loggedInUser->save();
        return response()->json(['status' => $status, 'message' => $message, 'followed_user_id' => $followedUserId]);
    }
    
    

    public function customerMessage(Request $request)
    {
        $data = $request->all();
        $message    = sanitize($data['message']);
        $receiver   = $data['agent_id'];
        $sender     = auth()->user()->id;
        $check = Message_thread::where('sender', $sender)->where('receiver', $receiver)->count();
        if ($check == 0) {
            $data_message_thread= new Message_thread();
            $message_thread_code                        = substr(md5(rand(100000000, 20000000000)), 0, 15);
            $data_message_thread['message_thread_code'] = $message_thread_code;
            $data_message_thread['sender']              = $sender;
            $data_message_thread['receiver']            = $receiver;
            $data_message_thread->save();
        }
        if ($check > 0) {
            $message_thread_code = Message_thread::where('sender', $sender)->where('receiver', $receiver)->value('message_thread_code');
        }
        $data_message= new Message();
        $data_message['message_thread_code']    = $message_thread_code;
        $data_message['message']                = $message;
        $data_message['sender']                 = $sender;
        $data_message['read_status']            = 0;
        $data_message->save();
        $response['code'] = $message_thread_code;
        $response['status'] = 'success';
        return $response;
    }


    public function customerBookAppointment(Request $request){

        $request->validate([
            'date' => 'required|date_format:Y-m-d H:i:s',
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
        ]);

        $data = $request->all();
        if (!Auth::check()) {
            Session::flash('warning', get_phrase('Please Login First!'));
            return redirect()->back();
        }
        if (auth()->user()->id == $data['agent_id']) {
            Session::flash('warning', get_phrase("You can't book your own business!"));
            return redirect()->back();
        }
    
        $appointment_date_time = Carbon::createFromFormat('Y-m-d H:i:s', $data['date']);
        $appointment = new Appointment();
        $appointment->date = sanitize($appointment_date_time->format('Y-m-d')); 
        $appointment->time = sanitize($appointment_date_time->format('H:i:s')); 
        $appointment->listing_type = sanitize($data['listing_type']);
        $appointment->listing_id = sanitize($data['listing_id']);
        $appointment->agent_id = sanitize($data['agent_id']);
        $appointment->name = sanitize($data['name']);
        $appointment->phone = sanitize($data['phone']);
        $appointment->email = sanitize($data['email']);
        $appointment->message =sanitize($data['message'] ?? 0);
        
        $appointment->status = ($data['listing_type'] == 'play') ? 1 : 0;

        $appointment->customer_id = auth()->user()->id;

        $additionalInfo = [
            'adults' => $data['adults'] ?? 0,
            'children' => $data['children'] ?? 0
        ];
        $appointment->aditional_information = json_encode($additionalInfo);
        $appointment->save();
        $payment_method = "paypal";
        // return redirect()->route('payment.create', [
        //     'identifier' => $payment_method,
        //     'appointment_id' => $appointment->id
        // ]);
    
        Session::flash('success', get_phrase('Appointment placed successfully!'));
        return redirect()->back();
    }
    

    function get_amenity_filtered_ids($min_price, $max_price){
        $min_price = preg_replace('/[^0-9]/', '', $min_price);
        $max_price = preg_replace('/[^0-9]/', '', $max_price);
        $amenities_id = [];
        $amenities_id = Amenities::whereBetween('price', [$min_price, $max_price])->pluck('id')->toArray();
        $amenities_id = array_map('strval', $amenities_id);
        return $amenities_id;
    }

    function get_menu_filtered_ids($min_price, $max_price){
        $min_price = preg_replace('/[^0-9]/', '', $min_price);
        $max_price = preg_replace('/[^0-9]/', '', $max_price);
        $amenities_id = [];
        $amenities_id = Menu::whereBetween('price', [$min_price, $max_price])->pluck('id')->toArray();
        $amenities_id = array_map('strval', $amenities_id);
        return $amenities_id;
    }
    

    // All Filter
    public function ListingsFilter(Request $request)
    {
        $listing_type = $request->type ?? 'beauty';
        $listings = null; 
      
        if ($listing_type == 'car') {
            $listings = CarListing::where('visibility', 'visible');
            $page_data['directory'] = 'car';
            if (isset($request->category) && $request->category != 'all') {
                $listings = $listings->where('category', $request->category);
                $page_data['category_type'] = sanitize($request->category);
                $page_data['activeMenu '] = sanitize($request->category);
            }
            if (isset($request->car_type) && $request->car_type != 'all') {
                $listings = $listings->where('car_type', $request->car_type);
                $page_data['car_type'] = sanitize($request->car_type);
                $page_data['activeMenu '] = sanitize($request->car_type);
            }
            if (isset($request->model) && $request->model != 'all') {
                $listings = $listings->where('model', $request->model);
                $page_data['model_type'] = sanitize($request->model);
                $page_data['activeMenu '] = sanitize($request->model);
            }
            if (isset($request->brand) && $request->brand != 'all') {
                $listings = $listings->where('brand', $request->brand);
                $page_data['brand_type'] = sanitize($request->brand);
            }
            if (isset($request->year) && $request->year != 'all') {
                $listings = $listings->where('year', $request->year);
                $page_data['year_type'] = sanitize($request->year);
            }
            if (isset($request->color) && $request->color != 'all') {
                $listings = $listings->where('exterior_color', $request->color);
                $page_data['color_type'] = sanitize($request->color);
            }
            if($request->min_price || $request->max_price ){
                $minPrice = sanitize($request->min_price);
                $maxPrice = sanitize($request->max_price);
                $listings = $listings->whereBetween('price', [$minPrice, $maxPrice]);
            }
            if (isset($request->title) && $request->title != 'all') {
                $listings = $listings->where('title', 'like', '%' . sanitize($request->title) . '%');
            }
        } elseif ($listing_type == 'beauty') {
            $listings = BeautyListing::where('visibility', 'visible');
            $page_data['directory'] = 'beauty';
            
            if (isset($request->category) && $request->category != 'all') {
                $listings = $listings->where('category', $request->category);
                $page_data['category_type'] = sanitize($request->category);
                $page_data['activeMenu '] = sanitize($request->category);
            }
           
            // Listing pricing
            $amenity_ids = $this->get_amenity_filtered_ids($request->min_price, $request->max_price);
            $listings->where(function ($query) use ($amenity_ids) {
                foreach ($amenity_ids as $id_key => $amenity_id) {
                    if($id_key == 0)
                        $query->whereJsonContains('service', $amenity_id);
                    else
                        $query->orWhereJsonContains('service', $amenity_id);
                }
            });
            // Listing pricing end
            if (isset($request->city) && $request->city != 'all') {
                $listings = $listings->where('city', $request->city);
                $page_data['city_type'] = sanitize($request->city);
            }
            if (isset($request->country) && $request->country != 'all') {
                $listings = $listings->where('country', $request->country);
                $page_data['country_type'] = sanitize($request->country);
            }
            if (isset($request->title) && $request->title != 'all') {
                $listings = $listings->where('title', 'like', '%' . sanitize($request->title) . '%');
            }

        } elseif ($listing_type == 'sleep') {
            $listings = SleepListing::where('visibility', 'visible');
            $page_data['directory'] = 'sleep';
            if (isset($request->category) && $request->category != 'all') {
                $listings = $listings->where('category', $request->category);
                $page_data['category_type'] = sanitize($request->category);
                $page_data['activeMenu '] = sanitize($request->category);
            }
            if($request->min_price || $request->max_price ){
                $minPrice = sanitize($request->min_price);
                $maxPrice = sanitize($request->max_price);
                $listings = $listings->whereBetween('price', [$minPrice, $maxPrice]);
            }
            if (isset($request->is_popular) && $request->is_popular != 'all') {
                $listings = $listings->where('is_popular', sanitize($request->is_popular));
                $page_data['status_type'] = sanitize($request->is_popular);
            }
            if (isset($request->city) && $request->city != 'all') {
                $listings = $listings->where('city', $request->city);
                $page_data['city_type'] = sanitize($request->city);
            }
            if (isset($request->country) && $request->country != 'all') {
                $listings = $listings->where('country', $request->country);
                $page_data['country_type'] = sanitize($request->country);
            }
            if (isset($request->bed) && $request->bed != 'all') {
                $listings = $listings->where('bed', $request->bed);
                $page_data['searched_bedroom'] = sanitize($request->bed);
            }
            if (isset($request->bath) && $request->bath != 'all') {
                $listings = $listings->where('bath', $request->bath);
                $page_data['searched_bathroom'] = sanitize($request->bath);
            }

            if (isset($request->title) && $request->title != 'all') {
                $listings = $listings->where('title', 'like', '%' . sanitize($request->title) . '%');
            }
           

        } elseif ($listing_type == 'work') {
            $listings = WorkListing::where('visibility', 'visible');
            $page_data['directory'] = 'work';

            if (isset($request->category) && $request->category != 'all') {
                $listings = $listings->where('category', $request->category);
                $page_data['category_type'] = sanitize($request->category);
                $page_data['activeMenu '] = sanitize($request->category);
            }
            if($request->min_price || $request->max_price ){
                $minPrice = sanitize($request->min_price);
                $maxPrice = sanitize($request->max_price);
                $listings = $listings->whereBetween('price', [$minPrice, $maxPrice]);
            }
            if (isset($request->status) && $request->status != 'all') {
                $listings = $listings->where('status', $request->status);
                $page_data['status_type'] = sanitize($request->status);
            }
            if (isset($request->bed) && $request->bed != 'all') {
                $listings = $listings->where('bed', $request->bed);
                $page_data['searched_bedroom'] = sanitize($request->bed);
            }
            if (isset($request->bath) && $request->bath != 'all') {
                $listings = $listings->where('bath', $request->bath);
                $page_data['searched_bathroom'] = sanitize($request->bath);
            }
            if (isset($request->garage) && $request->garage != 'all') {
                $listings = $listings->where('garage', $request->garage);
                $page_data['searched_garage'] = sanitize($request->bath);
            }
            if (isset($request->city) && $request->city != 'all') {
                $listings = $listings->where('city', $request->city);
                $page_data['city_type'] = sanitize($request->city);
            }
            if (isset($request->country) && $request->country != 'all') {
                $listings = $listings->where('country', sanitize($request->country));
                $page_data['country_type'] = sanitize($request->country);
            }
            
            if (isset($request->title) && $request->title != 'all') {
                $listings = $listings->where('title', 'like', '%' . sanitize($request->title) . '%');
            }


        } elseif ($listing_type == 'play') {
            $listings = PlayListing::where('visibility', 'visible');
            $page_data['directory'] = 'play';

            if (isset($request->category) && $request->category != 'all') {
                $listings = $listings->where('category', $request->category);
                $page_data['category_type'] = sanitize($request->category);
                $page_data['activeMenu '] = sanitize($request->category);
            }
            if (isset($request->city) && $request->city != 'all') {
                $listings = $listings->where('city', $request->city);
                $page_data['city_type'] = sanitize($request->city);
            }
            if (isset($request->country) && $request->country != 'all') {
                $listings = $listings->where('country', $request->country);
                $page_data['country_type'] = sanitize($request->country);
            }
             // Listing pricing
             $amenity_ids = $this->get_menu_filtered_ids($request->min_price, $request->max_price);
             $listings->where(function ($query) use ($amenity_ids) {
                 foreach ($amenity_ids as $id_key => $amenity_id) {
                     if($id_key == 0)
                         $query->whereJsonContains('menu', $amenity_id);
                     else
                         $query->orWhereJsonContains('menu', $amenity_id);
                 }
             }); 
             // Listing pricing end

             if (isset($request->title) && $request->title != 'all') {
                $listings = $listings->where('title', 'like', '%' . $request->title . '%');
            }
         

            } 
        $page_data['categories'] = Category::where('type', $request->type)->get();
       
        $page_data['listings'] = $listings->paginate(9); 
        $page_data['type'] = $request->type ?? 'beauty';
        $page_data['view'] = $request->view;
    
        return view('frontend.' . $page_data['type'] . '.' . $request->view . '_listing', $page_data);
    }
    

    // Agent Details
    public function agent_details($id, $slug){
        $page_data['users'] = User::where('id', $id)->firstOrNew();
        return view('frontend.agent_details', $page_data);
    }


    // Newsletter
    public function newslater_subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        $exists = Newsletter_subscriber::where('email', $request->email)->exists();
    
        if ($exists) { 
            Session::flash('warning', get_phrase('You are already subscribed!'));
            return redirect()->back();
        }
        Newsletter_subscriber::create([
            'email' => $request->email,
        ]); 
        Session::flash('success', get_phrase('Newsletter Subscribe successfully'));
        return redirect()->back();
    }
    
    
   //   Privacy policy
   public function privacy_policy(){
       return view('frontend.privacy-policy');
   }
   public function refund_policy(){
       return view('frontend.refund-policy');
   }
   public function about_us(){
       return view('frontend.about-us');
   }
   public function terms_and_condition(){
       return view('frontend.terms-and-condition');
   }
   public function contact_us(){
       return view('frontend.contact_us');
   }

   public function contact_store(Request $request)
   {
       $request->validate([
           'name' => 'required|string|max:255',
           'email' => 'required|email|max:255',
           'number' => 'required|numeric',
           'address' => 'required|string|max:255',
           'message' => 'required|string',
       ]);
       Contact::create([
           'name' => sanitize($request->name),
           'email' => sanitize($request->email),
           'phone' => sanitize($request->number),
           'address' => sanitize($request->address),
           'message' => sanitize($request->message),
           'has_read' => 0,
           'replied' => 0,
       ]);  
       Session::flash('success', get_phrase('Email Send successfully')); 
       return redirect()->back();
   }
   
    // Claim Listing
    public function claimListingForm($type, $id){
        $page_data['listing_id'] = $id;
        $page_data['type'] = $type;
        return view('frontend.claimed-form',$page_data);
    }
    public function claimListingStore(Request $request) {
        $request->validate([
            'user_name' => 'required|string|max:255',
            'user_phone' => 'required|numeric',
            'additional_info' => 'required|string',
        ]);
    
        $data = $request->all();
        $userId = auth()->user()->id;
      
        $claimListing = new ClaimedListing();
        $claimListing->listing_type = $data['claim_listing_type'];
        $claimListing->listing_id = $data['claim_listing_id'];
        $claimListing->user_id = $userId;
        $claimListing->user_name = $data['user_name'];
        $claimListing->user_phone = $data['user_phone']; 
        $claimListing->additional_info = $data['additional_info'];
        $claimListing->status = 0;
        $claimListing->save();
    
        Session::flash('success', 'Your claim request has been submitted successfully. Waiting for admin approval.');
        return redirect()->back();
    }
    
    // Report Listing Form
    public function reportListingForm($type, $id){
        $page_data['listing_id'] = $id;
        $page_data['type'] = $type;
        return view('frontend.report-form',$page_data);
    }

    public function reportListingStore(Request $request) {
        $request->validate([
            'user_name' => 'required|string|max:255',
            'user_phone' => 'required|numeric',
            'user_email' => 'required|string',
            'report' => 'required|string',
            'report_type' => 'required|string',
        ]);
    
        $data = $request->all();
        $userId = auth()->user()->id;
      
        $reportListing = new ReportedListing();
        $reportListing->type = $data['report_listing_type'];
        $reportListing->listing_id = $data['report_listing_id'];
        $reportListing->reporter_id = $userId;
        $reportListing->user_name = $data['user_name'];
        $reportListing->user_phone = $data['user_phone']; 
        $reportListing->user_email = $data['user_email']; 
        $reportListing->report = $data['report'];
        $reportListing->report_type = $data['report_type'];
        $reportListing->status = 0;
        $reportListing->save();
    
        Session::flash('success', 'Your Report request has been submitted successfully. Waiting for admin approval.');
        return redirect()->back();
    }




    

 

}
