<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Appointment;
class AppointmentController extends Controller
{
    public function appointments(){
        return view('admin.appointment.index');
    }

    public function __construct( Appointment $appointment) {    
        $this->appointment = $appointment;
    }

    public function fetchAppointments(Request $request){     

        $segment_type = $request->input('segment_type');
        $segment_id   = $request->input('segment_id');
        $appointments = [];
        $appointment_row = $this->appointment->where("listing_type", $segment_type)->where("listing_id", $segment_id)->get();
        foreach ($appointment_row as $app_mt) {
            $appointments[] = [
                'title' => $app_mt->message,
                'start' =>  date("Y-m-d", strtotime($app_mt->date))
            ];
        }
        return response()->json($appointments);
    }

    public function appointment_store(Request $request){
        $request['date'] = date("Y-m-d", strtotime($request['date']));
        $request['customer_id'] = auth()->user()->id ?? 1;        
        $appointment = $this->appointment->create($request->except('_token'));        
        return response()->json($appointment);
    }
}
