<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Department;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $departments = Department::all();

        return view('admin.home.index', [
            'departments' => $departments
        ]);
    }

    public function dashboard(Request $request)
    {

        $query = "
        select
            d.dateStart AS ds,
            d.dateEnd AS de,
            d.*,
            u.name AS uni,
            u.type
        from
            downtime d
        left join unit u on u.id = d.unitId
            WHERE
            (
                (
                    d.dateStart >= " . "'" . $request->query("start") . "'" . "
                    and d.dateEnd <= " . "'" . $request->query("end") . " 23:59:59". "'" ."
                )
                OR (
                    d.dateEnd >= " . "'" .  $request->query("start") . "'" . "
                    and d.dateEnd <= " . "'" . $request->query("end") . " 23:59:59" . "'" . "
                )
            )
            order by
            d.id desc";

        $result = DB::select($query);
       
        return view('admin.dashboard.index', compact('result'));
    }

    public function maintenance()
    {
        return view('admin.maintenance.index');
    }

    public function downtime()
    {
        return redirect('/dashboard');
    }

    
}
