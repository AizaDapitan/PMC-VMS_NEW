<?php

namespace App\Http\Controllers;

use App\Unit;
use App\Department;
use App\HRISAgusanDepartment;
use Illuminate\Http\Request;
use DB;

class SysMaintenanceUnitsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */    

    public function fms_index() 
    {
        $units = Unit::where('isECS',0)->get(); 

        $localDept = Department::get();
        $hrisDept = HRISAgusanDepartment::select(DB::raw('DISTINCT DeptDesc as name'))->orderBy('DeptDesc', 'asc')->get();
        $departments = array_merge($localDept->toArray(), $hrisDept->toArray());
        $dept = $departments; 
        
        return view('admin.requests.reports.fms-vehicle-report',compact('dept','units'));  

    }

    public function fms_vehicles(Request $request)
    {        
        $localDept = Department::get();
        $hrisDept = HRISAgusanDepartment::select(DB::raw('DISTINCT DeptDesc as name'))->orderBy('DeptDesc', 'asc')->get();
        $departments = array_merge($localDept->toArray(), $hrisDept->toArray());
        $dept = $departments; 

                   
        if ($request != null) {            
                                               
            $units = Unit::where('isECS',0)->where('dept',$request->dept)->get();                           
                                            
        }

        return view('admin.requests.reports.fms-vehicle-report',compact('dept','units'));  
        
        
    }

    public function vms_vehicles()
    {
        $units = Unit::where('isECS',null)->get();  
        return view('admin.requests.reports.vms-vehicle-report',compact('units'));
    }
}
