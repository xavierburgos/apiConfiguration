<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\appointments;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = User::where('role', 'employee')->get();
        return view('appointments.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
 
    public function store(Request $request)
    {
        $appointment = new Appointment();
        $appointment->client_id = Auth::user()->id;
        $appointment->employee_id = $request->input('employee_id');
        $appointment->date = $request->input('date');
        $appointment->time = $request->input('time');
        $appointment->save();
        return redirect()->route('appointments.show', $appointment);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Appointment $appointment)
    {
        if (Auth::user()->id != $appointment->client_id && Auth::user()->role != 'admin') {
            abort(403, 'Unauthorized action.');
        }
        return view('appointments.show', compact('appointment'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Appointment $appointment)
    {
        if (Auth::user()->id != $appointment->client_id && Auth::user()->role != 'admin') {
            abort(403, 'Unauthorized action.');
        }
        $employees = User::where('role', 'employee')->get();
        return view('appointments.edit', compact('appointment', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Appointment $appointment)
    {
        if (Auth::user()->id != $appointment->client_id && Auth::user()->role != 'admin') {
            abort(403, 'Unauthorized action.');
        }
        $appointment->employee_id = $request->input('employee_id');
        $appointment->date = $request->input('date');
        $appointment->time = $request->input('time');
        $appointment->save();
        return redirect()->route('appointments.show', $appointment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Appointment $appointment)
    {
        if (Auth::user()->id != $appointment->client_id && Auth::user()->role != 'admin') {
            abort(403, 'Unauthorized action.');
        }
        $appointment->delete();
        return redirect()->route('appointments.index');
    }
}
