<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SleepListing;
use App\Models\PlayListing;
use App\Models\WorkListing;
use App\Models\Newsletter_subscriber;
use App\Models\Subscription;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
     public function __construct( User $user,SleepListing $sleeplisting,PlayListing $playListing,WorkListing $workListing,Newsletter_subscriber $newsletter_subscriber,Subscription $subscription){
        $this->user = $user; 
        $this->sleeplisting = $sleeplisting; 
        $this->playListing = $playListing; 
        $this->workListing = $workListing; 
        $this->newsletter_subscriber = $newsletter_subscriber; 
        $this->subscription = $subscription; 
    } 

    public function index(){
        try{
            $users = $this->user->where('role',2)->count();
            $agent = $this->user->where('role',1)->count();
            
            $sleep = $this->sleeplisting->where('visibility', 'visible')->count();
            $play = $this->playListing->where('visibility', 'visible')->count();
            $work = $this->workListing->where('visibility', 'visible')->count();
            $subscriber = $this->newsletter_subscriber->count();
            $totalListing = $sleep + $play + $work  ;

            $sleepPercentage = $totalListing ? ($sleep / $totalListing) * 100 : 0;
            $playPercentage = $totalListing ? ($play / $totalListing) * 100 : 0;
            $workPercentage = $totalListing ? ($work / $totalListing) * 100 : 0;

            $totalPaidAmount = $this->subscription->sum('paid_amount');
            return view('admin.dashboard',compact('users','agent','sleep','play','work','totalListing','sleepPercentage','playPercentage','workPercentage','totalPaidAmount'));
        }catch (Exception $e){
            \Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }
}
