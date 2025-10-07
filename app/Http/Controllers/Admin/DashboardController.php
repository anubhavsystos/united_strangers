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
use App\Models\Appointment;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
     public function __construct( User $user,SleepListing $sleeplisting,PlayListing $playListing,WorkListing $workListing,Newsletter_subscriber $newsletter_subscriber,Subscription $subscription,Appointment $appointment){
        $this->user = $user; 
        $this->sleeplisting = $sleeplisting; 
        $this->playListing = $playListing; 
        $this->workListing = $workListing; 
        $this->newsletter_subscriber = $newsletter_subscriber; 
        $this->subscription = $subscription; 
        $this->appointment = $appointment;
    } 

    public function index(){
        try {
            $user = auth()->user();
            $type = $user->type ?? '';
            $userId = $user->id ?? 0;
            $customer = $agent = 0;
            if ($type === 'admin') {
                $customer = $this->user->where('type', 'customer')->count();
                $agent = $this->user->where('type', 'agent')->count();
            }
            
            $sleep = $this->sleeplisting->where('visibility', 'visible')
                ->when($type === 'agent', fn($q) => $q->where('user_id', $userId))
                ->count();

            $play = $this->playListing->where('visibility', 'visible')
                ->when($type === 'agent', fn($q) => $q->where('user_id', $userId))
                ->count();

            $work = $this->workListing->where('visibility', 'visible')
                ->when($type === 'agent', fn($q) => $q->where('user_id', $userId))
                ->count();
            $sleep_appointment = $this->appointment->where('listing_type', 'sleep')
                ->when($type === 'agent', fn($q) => $q->where('agent_id', $userId))
                ->get();

            $work_appointment = $this->appointment->where('listing_type', 'work')
                ->when($type === 'agent', fn($q) => $q->where('agent_id', $userId))
                ->get();

            $play_appointment = $this->appointment->where('listing_type', 'play')
                ->when($type === 'agent', fn($q) => $q->where('agent_id', $userId))
                ->get();

            $totalListing = $sleep + $play + $work;
            $sleepPercentage = $totalListing ? ($sleep / $totalListing) * 100 : 0;
            $playPercentage  = $totalListing ? ($play / $totalListing) * 100 : 0;
            $workPercentage  = $totalListing ? ($work / $totalListing) * 100 : 0;

            $totalPaidAmount = $this->appointment
                ->when($type === 'agent', fn($q) => $q->where('agent_id', $userId))
                ->sum('total_price');

            $workData  = $this->mapMonthlyTotals($work_appointment);
            $sleepData = $this->mapMonthlyTotals($sleep_appointment);
            $playData  = $this->mapMonthlyTotals($play_appointment);

            return view('admin.dashboard', compact('customer','agent','sleep','play','work','totalListing','sleepPercentage','playPercentage','workPercentage','totalPaidAmount','workData','sleepData','playData','sleep_appointment','work_appointment','play_appointment'
            ));
        } catch (Exception $e) {
            \Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    
    private function mapMonthlyTotals($appointments)
    {
        $months = array_fill(1, 12, 0);
        foreach ($appointments as $a) {
            $month = (int) date('n', strtotime($a->date ?? ''));
            $price = (float) ($a->total_price ?? 0);
            if ($month >= 1 && $month <= 12) {
                $months[$month] += $price;
            }
        }

        return $months;
    }
}
