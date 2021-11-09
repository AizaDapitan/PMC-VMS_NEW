<?php

namespace App\Http\Controllers;

use App\Drivers;
use App\Exports\VehicleRequestExportDriver;
use Illuminate\Http\Request;

class VehicleRequestDriverController extends Controller
{
    public function drivers(Request $request)
    {

        $types = array('GSD Driver', 'Motor Pool Driver', 'Mine Driver');
        $drivers = Drivers::all();
        $drivers_ = $drivers->toArray();

        foreach ($drivers_ as $value) {
            if (!in_array($value['type'], $types) && strlen($value['type']) > 1)
            {
                array_push($types,$value['type']);
            }
        }
       
        return view('admin.utilization.drivers', compact('types','drivers'));
    }

    public function submitdrivers(Request $request)
    {

        if($request->input('act') == "submit")
        {

            $name = $request->dname;
            $type = $request->dtype;
            if( strlen($request->dtype2) > 1)
            {
                $type = $request->dtype2;
            }

            $insert = Drivers::create([
                'driver_name' => $name,
                'type' => $type
            ]);

            return redirect()->route('vehicle.drivers', ['added' => 1]);
        }

        if($request->input('act') == "update")
        {
            $id = $request->edid;
            $type = $request->edtype;
            if(strlen($request->edtype2) > 1) {
                $type = $request->edtype2;
            }
            
            Drivers::where('id', $id)
                    ->update([
                        'driver_name' => $request->edname,
                        'type' => $type
                    ]);

            return redirect()->route('vehicle.drivers', ['updated' => 1]);
        }

        if($request->input('act') == "activate")
        {
            $id = $request->input('id');
            Drivers::where('id', $id)
                    ->update([
                        'isActive' => 1
                    ]);

            return redirect()->route('vehicle.drivers', ['activated' => 1]);
        }

        if($request->input('act') == "deactivate")
        {
            $id = $request->input('id');
            Drivers::where('id', $id)
                    ->update([
                        'isActive' => 0
                    ]);

            return redirect()->route('vehicle.drivers', ['deactivated' => 1]);
        }

        return;
    }

   
}
