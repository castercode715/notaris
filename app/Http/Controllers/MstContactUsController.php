<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MstContactUs; 
use App\Models\MstInvestor; 
use Illuminate\Support\Facades\Auth;
use DataTables;
use DB;
use App\Utility;


class MstContactUsController extends Controller
{
    const MODULE_NAME = 'Contact Us';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'R'))
            abort(401, 'Unauthorized action.');

        return view('master.contact_us.index');
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
        if(!$this->checkAccess(self::MODULE_NAME, 'C'))
            abort(401, 'Unauthorized action.');

        $validate = $this->validate($request, [
            'message' => 'required'
        ]);

        if($validate)
        {
            $data = [
                'parent_id' => $request->parent_id,
                'message' => $request->message,
                'flag' => 1,
                'active' => 1,
                'created_by' => Auth::user()->id,
                'created_at' => date('Y-m-d H:i:s')
            ];

            $path = base_path().'/public/files/inbox/';
            if($image = $request->file('attachment'))
            {
                $filename = time().$image->getClientOriginalName();
                $image->move($path, $filename);
                $data = array_merge($data, ['file'=>$filename]);
            }

            if($model = MstContactUs::create($data))
            {
                // add email queue
                $attachment1 = '';
                if($image)
                    $attachment1 = $path.$model->file;

                $contact = MstContactUs::findOrFail($model->parent_id);
                $investor = MstInvestor::findOrFail($contact->investor_id);
                $query = "call add_email_queue(
                            '".$investor->email."', 
                            '".$investor->full_name."', 
                            'Reply Admin Kop-Aja', 
                            '".$request->message."', 
                            '', 
                            '".$attachment1."', 
                            '', 
                            'ADMIN', 
                            '".Auth::user()->id."', 
                            @vret
                        );";
                DB::select($query);

                return redirect('master/inbox/reply/'. base64_encode($request->id))->with('success','Success');
            }
            else
                return redirect('master/inbox/reply/'.base64_encode($request->id))->with('error', 'Failed');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!$this->checkAccess(self::MODULE_NAME, 'S'))
            abort(401, 'Unauthorized action.');

        $model = MstContactUs::findOrFail($id);
        $uti = new Utility();
        return view('master.contact_us.detail', compact(['model','uti']));
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
        if(!$this->checkAccess(self::MODULE_NAME, 'D'))
            abort(401, 'Unauthorized action.');

        $deleted_date   = date('Y-m-d H:i:s');
		$deleted_by     = Auth::user()->id;

        DB::update("update mst_contact_us set 
            deleted_date='".$deleted_date."',
            deleted_by = ".$deleted_by.",
            is_deleted = 1
            where id = ".$id."");
    }
	
	public function dataTable()
    {
        $model = DB::table('v_inbox')
                ->where('created_by', null)
                ->where('active', 1)
                ->where('is_deleted', 0)
                ->where('flag',1)
                ->orderBy('created_at','desc');
		
        return DataTables::of($model)
            ->addColumn('action', function($model){
                return view('master.contact_us.action', [
                    'model' => $model,
                    'url_show'=> route('inbox.reply', base64_encode($model->id) ),
                ]);
            })
            ->editColumn('created_at', function($model){
                return date('d M Y H:i', strtotime($model->created_at)).' WIB';
            })
            ->editColumn('status', function($model){
                $status = '<span class="label label-primary">New</span>';
                if($model->status > 0)
                    $status = '<span class="label label-warning">Replied</span>';

                return $status;
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'active', 'created_at','status'])
            ->make(true);
    }

    public function reply($id)
    {
        $id = base64_decode($id);

        $model = MstContactUs::findOrFail($id);
        $data = [];
        $parent = [];
        if($model->parent_id)
        {
            $data = DB::table('mst_contact')
                    ->where('parent_id', $model->parent_id)
                    // ->whereNotIn('id', [$id])
                    ->orderBy('id', 'desc')
                    ->get();
            $parent = MstContactUs::findOrFail($model->parent_id);
        }
        else
        {
            $data = DB::table('mst_contact')
                    ->where('parent_id', $id)
                    ->orderBy('id', 'desc')
                    ->get(); 
        }

        $uti = new Utility;

        return view('master.contact_us.reply', compact('model','data','parent','uti'));
    }
}
