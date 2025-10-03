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

    public function fetchAppointments(Request $request)
    {
        $segment_type = $request->input('segment_type');
        $segment_id   = $request->input('segment_id');
        $appointments = [];

        $appointment_rows = $this->appointment
            ->where("listing_type", $segment_type)
            ->where("listing_id", $segment_id)
            ->get();

        foreach ($appointment_rows as $app) {
            $formatted = $app->appointmentFormatted($segment_type);
            $title = '';
            if (!empty($formatted['in_time']) && !empty($formatted['out_time'])) {             
                $title .= $formatted['in_time'] . ' - ' . $formatted['out_time'];
            }
            $appointments[] = [
                'title' => $formatted['room_name'] . '<br>' .$title,
                'start' => date("Y-m-d", strtotime($formatted['date'])),
            ];
        }
        return response()->json($appointments);
    }



   public function appointment_store(Request $request){
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
        return redirect()->back();
    }

}
