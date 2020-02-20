<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MNotarisModels;
use Illuminate\Support\Facades\Auth;
use DB;

class NewDashboardControllers extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->isMethod('post')){
            $id = $request->id_jancuk;
            $result = $this->getNotarisValue($id);
            
            if($result > 0){
                $userId = Auth::id();
                $setNotaris = DB::table('mst_employee')->where('id', $userId)
                            ->update(['is_notaris' => $id ]);

            }
        }

        $model = MNotarisModels::all();
        return view('dashboard.index', compact('model'));
    }

    public function getNotarisValue($id)
    {
        $result = DB::table('m_notaris as a')
                ->where('a.is_deleted', 0)
                ->where('a.id', $id)
                ->count();

        return $result;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
