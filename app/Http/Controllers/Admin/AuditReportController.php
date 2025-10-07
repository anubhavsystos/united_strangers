<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\Appointment;

class AuditReportController extends Controller{

    protected $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;     
    }

    public function index(Request $request)
    {
        try {
            $query = $this->appointment->newQuery();
            if (auth()->user()->type === 'agent') {
                $query->where('customer_id', auth()->user()->id);
            }
            if ($request->filled('from_date')) {
                $query->whereDate('date', '>=', $request->from_date);
            }
            if ($request->filled('to_date')) {
                $query->whereDate('date', '<=', $request->to_date);
            }
            $auditreports = $query->get();
            $totalAmount = $auditreports->sum('total_price');
            $totalPaid = $auditreports->where('status', 1)->sum('total_price');
            $totalRemaining = $auditreports->where('status', 0)->sum('total_price') +
                            $auditreports->whereNull('status')->sum('total_price');
            $totalCancelled = $auditreports->where('status', 3)->sum('total_price');
            $auditreports = $auditreports->map(function ($item) {
                return $item->appointmentCustomerFormatted();
            });
            return view('admin.appointment.list', compact(
                'auditreports', 'totalAmount', 'totalPaid', 'totalRemaining', 'totalCancelled'
            ));

        } catch (Exception $e) {
            \Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }



}