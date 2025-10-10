<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Message;
use App\Models\Message_thread;
use App\Models\Pricing;
use App\Models\User;
use App\Models\Wishlist;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    protected $appointment; protected $message; protected $thread; protected $pricing; protected $wishlist; protected $user;

    public function __construct( Appointment $appointment, Message $message, Message_thread $thread, Pricing $pricing, Wishlist $wishlist, User $user) {
        $this->appointment = $appointment ?? new Appointment();
        $this->message     = $message ?? new Message();
        $this->thread      = $thread ?? new Message_thread();
        $this->pricing     = $pricing ?? new Pricing();
        $this->wishlist    = $wishlist ?? new Wishlist();
        $this->user        = $user ?? new User();
    }


    public function appointment()
    {
        $page_data['active'] = 'userAppointment';
 
        $appointments = $this->appointment->orderBy('date', 'desc')->where("customer_id", user('id'))->get()->map(function ($item) {
            return $item->appointmentCustomerFormatted(); 
        });

        $page_data['appointments'] = $appointments;
        return view('user.customer.appointment.index', $page_data);
    }

    public function wallet()
    {
        $page_data['active'] = 'userWallet'; 
        // $page_data['appointments'] = $appointments;
        // return $page_data['appointments'];
        return view('user.customer.wallet', $page_data);
    }

    // Example for DTP style query (your given snippet)
    public function appointmentByType($id, $type)
    {
        $page_data['appointments'] = $this->appointment
            ->where("listing_type", $type)
            ->where("listing_id", $id)
            ->orderBy('date', 'desc')
            ->get()
            ->map(function ($item) use ($type) {
                return $item->appointmentFormatted($type);
            });

        return view('user.customer.appointment.index', $page_data);
    }

   
    public function user_messages($prefix = "", $id = "", $code = "")
    {
        $page_data['active'] = 'message';

        if ($id) {
            if ($code) {
                $threads = $this->thread->where('message_thread_code', $code);
                $page_data['messages'] = $this->message->where('message_thread_code', $code)->get();
            } else {
                if (user('is_agent')) {
                    $threads = $this->thread->where('sender', user('id'))->where('receiver', $id);
                } else {
                    $threads = $this->thread->where('sender', $id)->where('receiver', user('id'));
                }

                $thread_code = $this->generateUniqueCode();
                if (!$threads->first()) {
                    $thread = [
                        'message_thread_code' => $thread_code,
                        'sender' => user('id'),
                        'receiver' => $id,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ];
                    $this->thread->insert($thread);
                }

                $threads = $this->thread->where('message_thread_code', $thread_code);
                $page_data['messages'] = $this->message->where('message_thread_code', $thread_code)->get();
            }

            $thread_details = $threads->first();
            $page_data['thread_details'] = $thread_details;
            $page_data['code'] = ($code == '' && !$code) ? ($thread_details->message_thread_code ?? '') : $code;
        } else {
            $page_data['code'] = '';
        }

        $page_data['all_threads'] = $this->thread
            ->where('sender', user('id'))
            ->orWhere('receiver', user('id'))
            ->get();

        return view('user.message.index', $page_data);
    }

    public function send_message(Request $request, $prefix, $code)
    {
        $mes = [
            'message_thread_code' => $code,
            'message' => sanitize($request->message),
            'sender' => user('id'),
            'read_status' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ];

        $this->message->insert($mes);
        return redirect()->back();
    }

    public function pay(Request $request){
        $appointment = $this->appointment->find($request->appointment_id);
        if (!$appointment) {
            return redirect()->back()->with('error', 'Appointment not found');
        }


        $appointment->status = 1; 
        $appointment->save();

        return redirect()->route('customer.thankyou');       
    }

    public function thankyou()
    {
        return view('user.customer.thankyou');
    }

    public function visitProperty($id)
    {
        $appointment = $this->appointment->find($id);

        if (!$appointment) {
            return redirect()->back()->with('error', 'Property not found');
        }
         $appointment->status = 1; 
        $appointment->save();


        return redirect()->route('customer.appointment');
    }
    
    public function cancelAppointment($id)
    {
        $appointment = Appointment::find($id);
        
        if (!$appointment || $appointment->customer_id != user('id')) {
            return redirect()->back()->with('error', 'Appointment not found.');
        }

        if ($appointment->status == 0) {
            $appointment->status = 3; 
            $appointment->save();
            return redirect()->back()->with('success', 'Appointment cancelled successfully.');
        }

        return redirect()->back()->with('error', 'Cannot cancel this appointment.');
    }


}
