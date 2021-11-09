<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class RoleMaintenanceController extends Controller
{
    public function index(Request $request) 
    {
        $roles = $request->has('id') ? Role::find($request->input('id')) : null;
        $roleList = Role::orderBy('name')->get();
        
        return view('admin.maintenance.account.role', compact('roleList','roles'));
    }

    public function updateRole(Request $request) 
    {

        if( $request->has('a_role'))
        {                        
            $request->validate([
                'name' => 'required',
                'description' => 'required',
            ]);

            $name = $request->input('name');
            $description = $request->input('description');
            $active = 1;                        
            $id = $request->query('id');
           
            if (Role::where('name', strtoupper($name))
                ->exists()) 
            {                
                Session::flash('error'," Role Name! already exists.");
                return redirect()->back();
            } 
            else 
            {
                $role = Role::create([
                    'name' => strtoupper($name),
                    'description' => $description,
                    'active' => $active
                ]);

                Session::flash('success'," Role Created Successfully...");
                return redirect()->back();

            }    
        }

        if( $request->has('e_role'))
        {            
            $name = $request->input('name');
            $description = $request->input('description');

            $id = $request->query('id')?? $request->id;
            $role = Role::find($id);
    
            if(! $role)
            {
                Session::flash('error',"Role Update Failed...");
                return redirect()->back();
            }
            
            if (Role::where('name', strtoupper($name))
                ->where('id', '<>', $id)
                ->exists()
            )

            {                
                Session::flash('error'," Role Name! already exists.");
                return redirect()->back();                
            } 
            else 
            {
        
                Role::find($id)->update([
                    'name' => strtoupper($name),
                    'description' => $description
                ]);

                return redirect()->route('maintenance.role')->with('success', 'Role has been updated!!');

            }            
        }

        if( $request->has('activate'))
        {
            $id = $request->input('id');
            $role = Role::find($id);

            if(! $role)
            {
                Session::flash('errorMsg',"Role Activation Failed...");
                return redirect()->back();
            }

            $role->update(['active' => 1]);

            Session::flash('successMsg',"Role has been Activated...");
            return redirect()->back();
        }

        
        if( $request->has('deactivate'))
        {
            $id = $request->input('id');
            $role = Role::find($id);

            if(! $role)
            {
                Session::flash('errorMsg',"Role Deactivation Failed...");
                return redirect()->back();
            }

            $role->update(['active' => 0]);

            Session::flash('successMsg',"Role has been Deactivated...");
            return redirect()->back();
        }        
   
    }
}
