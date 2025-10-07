<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
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
use App\Models\Room;
use App\Models\NearbyLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
class FrontendController extends Controller
{
    protected $blog;
    protected $blog_category;

   public function __construct(SleepListing $sleepListing,WorkListing $workListing,PlayListing $playListing,User $user,Message_thread $messageThread,Message $message,Review $review,Wishlist $wishlist,City $city,Country $country,Menu $menu,Appointment $appointment,Amenities $amenities,Newsletter $newsletter,Newsletter_subscriber $newsletterSubscriber,ClaimedListing $claimedListing,ReportedListing $reportedListing,Contact $contact,Pricing $pricing,Category $category,Offer $offer, Event $event, Blog $blog,Room $room,NearbyLocation $nearbylocation) {
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
        $this->room = $room;
        $this->review = $review;
        $this->menu = $menu;
        $this->nearbylocation = $nearbylocation;
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
        $Totalsleeps = $this->sleepListing->where('visibility','visible')->take(8)->get();
        $Totalplay =  $this->playListing->where('visibility','visible')->take(8)->get();         
        $Totalwork =  $this->workListing->where('visibility','visible')->take(8)->get();
        $reviews = $this->review->whereNull('reply_id')->where('rating',5)->orderBy('created_at', 'DESC')->take(50)->get();
        return view('frontend.index', compact('sleeplistings','worklistings','playlistings','offers','events','blogs','reviews'));
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

    public function sleep_home(){
        $page_data['cities'] = City::get();
        $uniqueCountryIds = City::distinct()->pluck('country');
        $page_data['listing_countries'] = Country::whereIn('id', $uniqueCountryIds)->take(6)->get();
        $page_data['categories'] = Category::where('type','sleep')->get();
        $page_data['top_listings'] = $this->sleepListing->orderBy('id', 'desc')->where('visibility', 'visible')->get();
        $page_data['directory'] = 'sleep';
        return view('frontend.sleep.home', $page_data);
    } 

    public function realestate_home(){
        $page_data['categories'] = Category::where('type','work')->get();
        $cityIdsWithListings = $this->workListing->distinct()->pluck('city');
        $page_data['listing_cities'] = City::whereIn('id', $cityIdsWithListings)->take(4)->get();
        $page_data['top_listings'] = $this->workListing->orderBy('id', 'desc')->where('visibility', 'visible')->get();
        $page_data['directory'] = 'work';
        return view('frontend.work.home', $page_data);
    }

    public function play_home(){
        $countryIdsWithListings = $this->playListing->distinct()->pluck('country');
        $page_data['countries'] = Country::whereIn('id', $countryIdsWithListings)->take(4)->get();
        $cityIdsWithListings = $this->playListing->distinct()->pluck('city');
        $page_data['cities'] = City::whereIn('id', $cityIdsWithListings)->take(4)->get();
        $page_data['categories'] = Category::where('type','work')->get();
        $page_data['top_listings'] = $this->playListing->orderBy('id', 'desc')->where('visibility', 'visible')->get();
        $page_data['directory'] = 'play';
        return view('frontend.playhome', $page_data);
    }

    public function listing_view($type, $view){   
        $count  = 25;
        if($type == 'sleep'){
            $page_data['listings'] = $this->sleepListing->where('visibility', 'visible')->latest()->paginate($count);            
            $page_data['directory'] = 'sleep';
        }elseif($type == 'work'){
            $page_data['listings'] = $this->workListing->where('visibility', 'visible')->latest()->paginate($count);
            $page_data['directory'] = 'work';
        }elseif($type == 'play'){
            $page_data['listings'] = $this->playListing->where('visibility', 'visible')->latest()->paginate($count);
            $page_data['directory'] = 'play';
        }

        $page_data['listings']->getCollection()->transform(function ($item) {
            return $item->productFormattedArray();
        });
        
        $page_data['categories'] = Category::where('type', $type)->get();
        $page_data['type'] = $type;
        $page_data['view'] = $view;
        
        return view('frontend.'.$type.'.'.$view.'_listing', $page_data);
    }

    public function listing_details($type, $id, $slug){       
        if($type == 'sleep'){            
            $page_data['listing'] = $this->sleepListing->where('id', $id)->first();
            $page_data['directory'] = 'sleep';
        }elseif($type == 'work'){
            $page_data['listing'] = $this->workListing->where('id', $id)->first();
            $page_data['directory'] = 'work';
        }elseif($type == 'play'){
            $page_data['listing'] = $this->playListing->where('id', $id)->first();
            $page_data['directory'] = 'play';
        }
        $nearbyLocations = $this->nearbylocation
        ->where('listing_id', $id)
        ->where('listing_type', $type)
        ->get();

        $coordinates = $nearbyLocations->map(function ($location) {
            return [
                'latitude' => $location->latitude,
                'longitude'=> $location->longitude,
                'name'     => $location->name,
                'type'     => $location->type,
            ];
        });

        $page_data['nearbyLocations'] = $nearbyLocations;
        $page_data['coordinates'] = $coordinates;

        $page_data['rooms'] = $this->room->where('listing_id', $id)->get()->map(function ($item) {
            return $item->roomFormattedArray();
        }); 
        $page_data['menus'] = $this->menu->where('listing_id', $id)->get();
        $today = now()->toDateString();

        $page_data['offers'] = $this->offer->where('segment_id',$id)->where('segment_type',$type)->whereDate('to_date', '<=', $today)->whereDate('from_date', '>=', $today)->get()->map(function ($item) {
            return $item->offerformatted();
        });

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

    public function ListingReviews(Request $request, $listing_id)
    {
        if (!Auth::check()) {
            Session::flash('warning', get_phrase('Please Login First!'));
            return redirect()->route('login');
        }
        
        $request->validate([
            'rating' => 'required|integer', 
            'review' => 'required|string',
        ]);

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
        $request['date'] = date("Y-m-d", strtotime($request['date']));
        $request['in_time'] = date("H:i:s", strtotime($request['in_time']));
        $request['out_time'] = date("H:i:s", strtotime($request['out_time']));
        $request['customer_id'] = auth()->user()->id ?? 1;
       
        if (is_array($request->room_id)) {
            $request['room_id'] = json_encode($request->room_id); 
        }
        if (is_array($request->menu_id)) {
            $request['menu_id'] = json_encode($request->menu_id); 
        }
        if (is_array($request->menu_qty)) {
            $request['menu_qty'] = json_encode($request->menu_qty);
        }
        if (is_array($request->menu_summary)) {
            $request['menu_summary'] = implode(', ', $request->menu_summary); 
        }
        
        $appointment = $this->appointment->create($request->except('_token'));
        
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
        $listing_type = $request->input('type', 'sleep');
        $perPage = 25;

        $page_data = [];
        $page_data['type'] = $listing_type;
        $page_data['view'] = $request->input('view', '');
        $query = null;
        if ($listing_type === 'sleep') {
            $query = $this->sleepListing
                ->with(['countryDetail','cityDetail','claimed','reviews'])
                ->where('visibility', 'visible')
                ->latest();

            $page_data['directory'] = 'sleep';

            if ($request->filled('category') && $request->category !== 'all') {
                $query->where('category', sanitize($request->category));
                $page_data['category_type'] = sanitize($request->category);
                $page_data['activeMenu'] = sanitize($request->category);
            }

            if ($request->filled('min_price') || $request->filled('max_price')) {
                $min = sanitize($request->min_price ?? 0);
                $max = sanitize($request->max_price ?? 9999999);
                $query->whereBetween('price', [$min, $max]);
            }

            if ($request->filled('is_popular') && $request->is_popular !== 'all') {
                $query->where('is_popular', sanitize($request->is_popular));
                $page_data['status_type'] = sanitize($request->is_popular);
            }

            if ($request->filled('city') && $request->city !== 'all') {
                $query->where('city', sanitize($request->city));
                $page_data['city_type'] = sanitize($request->city);
            }

            if ($request->filled('country') && $request->country !== 'all') {
                $query->where('country', sanitize($request->country));
                $page_data['country_type'] = sanitize($request->country);
            }

            if ($request->filled('bed') && $request->bed !== 'all') {
                $query->where('bed', sanitize($request->bed));
                $page_data['searched_bedroom'] = sanitize($request->bed);
            }

            if ($request->filled('bath') && $request->bath !== 'all') {
                $query->where('bath', sanitize($request->bath));
                $page_data['searched_bathroom'] = sanitize($request->bath);
            }

            if ($request->filled('title') && $request->title !== 'all') {
                $query->where('title', 'like', '%' . sanitize($request->title) . '%');
            }
        } elseif ($listing_type === 'work') {
            $query = $this->workListing
                ->with(['countryDetail','cityDetail','claimed','reviews'])
                ->where('visibility', 'visible')
                ->latest();

            $page_data['directory'] = 'work';

            if ($request->filled('category') && $request->category !== 'all') {
                $query->where('category', sanitize($request->category));
                $page_data['category_type'] = sanitize($request->category);
                $page_data['activeMenu'] = sanitize($request->category);
            }

            if ($request->filled('min_price') || $request->filled('max_price')) {
                $minPrice = sanitize($request->min_price ?? 0);
                $maxPrice = sanitize($request->max_price ?? 9999999);
                $query->whereBetween('price', [$minPrice, $maxPrice]);
            }

            if ($request->filled('status') && $request->status !== 'all') {
                $query->where('status', sanitize($request->status));
                $page_data['status_type'] = sanitize($request->status);
            }

            if ($request->filled('bed') && $request->bed !== 'all') {
                $query->where('bed', sanitize($request->bed));
                $page_data['searched_bedroom'] = sanitize($request->bed);
            }

            if ($request->filled('bath') && $request->bath !== 'all') {
                $query->where('bath', sanitize($request->bath));
                $page_data['searched_bathroom'] = sanitize($request->bath);
            }

            if ($request->filled('garage') && $request->garage !== 'all') {
                $query->where('garage', sanitize($request->garage));
                $page_data['searched_garage'] = sanitize($request->garage);
            }

            if ($request->filled('city') && $request->city !== 'all') {
                $query->where('city', sanitize($request->city));
                $page_data['city_type'] = sanitize($request->city);
            }

            if ($request->filled('country') && $request->country !== 'all') {
                $query->where('country', sanitize($request->country));
                $page_data['country_type'] = sanitize($request->country);
            }

            if ($request->filled('title') && $request->title !== 'all') {
                $query->where('title', 'like', '%' . sanitize($request->title) . '%');
            }

        } elseif ($listing_type === 'play') {
            $query = $this->playListing
                ->with(['countryDetail','cityDetail','claimed','reviews'])
                ->where('visibility', 'visible')
                ->latest();

            $page_data['directory'] = 'play';

            if ($request->filled('category') && $request->category !== 'all') {
                $query->where('category', sanitize($request->category));
                $page_data['category_type'] = sanitize($request->category);
                $page_data['activeMenu'] = sanitize($request->category);
            }

            if ($request->filled('city') && $request->city !== 'all') {
                $query->where('city', sanitize($request->city));
                $page_data['city_type'] = sanitize($request->city);
            }

            if ($request->filled('country') && $request->country !== 'all') {
                $query->where('country', sanitize($request->country));
                $page_data['country_type'] = sanitize($request->country);
            }

            // $amenity_ids = $this->get_menu_filtered_ids($request->min_price ?? null, $request->max_price ?? null);
            // if (!empty($amenity_ids)) {
            //     $query->where(function ($q) use ($amenity_ids) {
            //         foreach ($amenity_ids as $id_key => $amenity_id) {
            //             if ($id_key === 0) {
            //                 $q->whereJsonContains('menu', $amenity_id);
            //             } else {
            //                 $q->orWhereJsonContains('menu', $amenity_id);
            //             }
            //         }
            //     });
            // }

            if ($request->filled('title') && $request->title !== 'all') {
                $query->where('title', 'like', '%' . sanitize($request->title) . '%');
            }

        } else {
            $query = $this->sleepListing
                ->with(['countryDetail','cityDetail','claimed','reviews'])
                ->where('visibility', 'visible')
                ->latest();

            $page_data['directory'] = 'sleep';
        }

        // ensure $query is set (extra safety)
        if (! $query) {
            $query = $this->sleepListing
                ->with(['countryDetail','cityDetail','claimed','reviews'])
                ->where('visibility', 'visible')
                ->latest();
            $page_data['directory'] = $page_data['directory'] ?? 'sleep';
        }

        // categories for the given type
        $page_data['categories'] = Category::where('type', $listing_type)->get();

        // paginate + preserve query string
        $page_data['listings'] = $query->paginate($perPage)->appends($request->all());

        // transform each item to productFormattedArray (keeps paginator structure)
        $page_data['listings']->getCollection()->transform(function ($item) {
            return $item->productFormattedArray();
        });

        // return the view
        return view('frontend.' . $page_data['type'] . '.grid_listing', $page_data);
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

    public function getAvailableRooms(Request $request){
        $listing_id = $request->input('listing_id');
        $date       = $request->input('date');
        $in_time    = $request->input('in_time');
        $out_time   = $request->input('out_time');
    
        $bookedRoomIds = $this->appointment->where('listing_type', 'sleep')->where('listing_id', $listing_id)
            ->whereDate('from_date', '<=', $date)
            ->whereDate('to_date', '>=', $date)
            ->where(function ($query) use ($in_time, $out_time) {
                $query->where('in_time', '<', $out_time)->where('out_time', '>', $in_time);
            })->pluck('room_id') 
            ->map(function ($item) {
                return json_decode($item, true); 
            })->flatten()->unique()->values()->toArray();
        $rooms = $this->room
            ->where('listing_id', $listing_id)
            ->whereNotIn('id', $bookedRoomIds)
            ->get()
            ->map(function ($item) {
                return $item->roomFormattedArray();
            });

        return response()->json([
            'rooms' => $rooms
        ]);
    }

}
