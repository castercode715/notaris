<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MstVoucher;
use App\Models\MstAsset;
use App\Models\MstVoucherLang;
use App\Models\MstLanguage;
use Illuminate\Support\Facades\Auth;
use Validator;
use DataTables;
use DB;
use App\Utility;

class MstVoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('master.voucher.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new MstVoucher();
        $page = DB::table('mst_asset as a')
                ->join('mst_asset_lang as c', 'c.asset_id', 'a.id')
                ->where('is_deleted','0')
                ->where('active','1')
                ->where('c.code','IND')
                ->pluck('c.asset_name', 'c.asset_id')
                ->all();

        $lang = DB::table('mst_language as a')
                ->select('a.*')
                ->get();

        return view('master.voucher.create', compact(['model','page','lang']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title.*'  => 'required',
            'asset_id'  => 'required',
            'desc.*'  => 'required',
            'date_start'  => 'required',
            'date_expired'  => 'required',
            'long_promo'  => 'required',
            'number_interest'  => 'required',
            'quota'  => 'required',
            'status'  => 'required',
            'image.*'  => 'required|image|mimes:jpg,jpeg,png|max:5048',
            'iframe.*'  => 'required',
            'code.*'  => 'required'

        ]);

        $userId = Auth::user()->id;
        $active = 1;
        $titles = $request->title;
        $desc = $request->desc;
        $iframe = $request->iframe;
        $code = $request->code;
        $image = $request->file('image');


        $parent = [
            'asset_id' => $request->asset_id,
            'date_start' => date('Y-m-d', strtotime($request->date_start)),
            'date_end' => date('Y-m-d', strtotime($request->date_expired)),
            'long_promo' => $request->long_promo,
            'number_interest' => $request->number_interest,
            'quota' => $request->quota,
            'status' => $request->status,
            'active' => $active,
            'created_by'=> $userId,
            'updated_by'=> $userId
        ];
        // dd($desc);
        $parent_s = MstVoucher::create($parent);
        $image_filename = '';
        if($parent_s){
            foreach ($titles as $key => $title) {

                
                if($image[$key] != null){
                    //save gambar
                    $image_filename = time().$image[$key]->getClientOriginalName();
                    $path = base_path().'/public/images/voucher/';
                    $image[$key]->move($path, $image_filename);

                }

                $child = [
                    'voucher_id' => $parent_s->id,
                    'code' => $code[$key],
                    'title' => $title,
                    'desc' => $desc[$key],
                    'iframe' => $iframe[$key],
                    'image' => $image_filename,
                ];

                MstVoucherLang::create($child);

            }

            return redirect('master/voucher/'.$parent_s->id)->with('success','Voucher successfully created');

        }else{
            Redirect::back()->withErrors(['error', 'Failed']);
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
         $row = DB::table('mst_voucher as a')
                ->join('mst_voucher_lang as b', 'b.voucher_id', 'a.id')
                ->join('mst_asset as c','c.id','a.asset_id')
                ->join('mst_asset_lang as d','d.asset_id','c.id')
                ->join('mst_language as e','e.code','b.code')
                ->select('a.*', 'b.*', 'd.asset_name','e.language')
                ->where('a.is_deleted','0')
                ->where('a.active','1')
                ->where('b.code','IND')
                ->where('a.id',$id)
                // ->pluck('c.asset_name', 'c.asset_id')
                ->first();

        $page = DB::table('mst_asset as a')
                ->join('mst_asset_lang as c', 'c.asset_id', 'a.id')
                ->where('is_deleted','0')
                ->where('active','1')
                ->where('c.code','IND')
                ->pluck('c.asset_name', 'c.asset_id')
                ->all();

        $lang = DB::table('mst_voucher_lang as a')
                ->join('mst_voucher as b', 'a.voucher_id', 'b.id')
                ->join('mst_language as c', 'c.code', 'a.code')
                ->select('a.*', 'b.*','c.language', 'a.id as no')
                ->where('b.id',$id)
                ->get();

        $model = MstVoucher::findOrFail($id);
        $uti = new Utility();
        return view('master.voucher.detail', compact(['lang','page','row','model','uti']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = MstVoucher::findOrFail($id);
        $page = MstAsset::where('active','1')
                    ->where('is_deleted','0')
                    ->pluck('asset_name', 'id')
                    ->all();
        
        return view('master.voucher.update', compact(['model','page']));
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
        $this->validate($request, [
            'title'  => 'required',
            'desc'  => 'required',
            'iframe'  => 'required',
            'code'  => 'required',
        ]);

        $model = MstVoucherLang::findOrFail($id);
        $userId = Auth::user()->id;
        $active = $request->get('active') ? 1 : 0;        
        $code = $request->code;        

        $description = str_replace('<pre>', '<p>', $request->desc);
        $description = str_replace('</pre>', '</p>', $description);

        if($request->file('image'))
        {
            $image = $request->file('image');
            $filename = $code.time().$image->getClientOriginalName();
            $path = base_path().'/public/images/voucher/';
            $image->move($path, $filename);

            

            $data = [
            'code'   => $request->code,
            'title'      => $request->title,
            'desc'   => $description,
            'image'        => $filename,
            'iframe'     => $request->iframe,
            ];



            if($model->image != null)
                unlink(base_path().'/public/images/voucher/'.$model->image);            
        }
        else
        {
           $data = [
            'title'      => $request->title,
            'desc'   => $description,
            'iframe'     => $request->iframe
        ];

        }

        $model->update($data);
        return redirect('master/voucher/'.$model->voucher_id)->with('success','Successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted_date   = date('Y-m-d H:i:s');
        $deleted_by     = Auth::user()->id;

        DB::update("update mst_voucher set 
            deleted_at='".$deleted_date."',
            deleted_by = ".$deleted_by.",
            is_deleted = 1
            where id = ".$id."");
    }

    public function delete($id)
    {
        $deleted_date   = date('Y-m-d H:i:s');
        $deleted_by     = Auth::user()->id;

        $delete = DB::update("update mst_voucher set 
            deleted_at = '".$deleted_date."',
            deleted_by = ".$deleted_by.",
            is_deleted = 1
            where id = ".$id."");

        if($delete)
            return redirect('master/voucher')->with('success', 'Deleted');
        else
            return redirect('master/voucher/show/'.$id)->with('error', 'Failed');
    }


    public function dataTable()
    {
        // $model = MstVoucher::query();
        // $model->where('is_deleted','<>','1');

        $model = DB::table('mst_voucher as a')
                    ->join('mst_voucher_lang as b','a.id','b.voucher_id')
                    ->join('mst_language as c','b.code','c.code')
                    ->where('a.is_deleted','0')
                    ->where('b.code','IND')
                    ->select('a.id','b.title','a.created_at','a.created_by')
                    ->get();
        
        return DataTables::of($model)
            // ->addColumn('checkbox', '<input type="checkbox" id="'.$model->sec_role_id.'" name="checkbox">' )
            ->addColumn('action', function($model){
                return view('master.voucher.action', [
                    'model' => $model,
                    'url_show'=> route('voucher.show', $model->id),
                    'url_edit'=> route('voucher.edit', $model->id),
                    'url_destroy'=> route('voucher.destroy', $model->id)
                ]);
            })
            ->editColumn('created_at', function($model){
                return date('d-m-Y H:i:s', strtotime($model->created_at));
            })
            ->editColumn('created_by', function($model){
                $uti = new utility();
                return $uti->getUser($model->created_by);
            })
           
            ->addIndexColumn()
            ->rawColumns(['action', 'active', 'created_at', 'created_by'])
            ->make(true);
    }


     public function detail($id)
    {
        // $id = base64_decode($id);
        $row = DB::table('mst_voucher_lang as a')
                ->join('mst_voucher as c', 'c.id', 'a.voucher_id')
                ->join('mst_language as b', 'b.code', 'a.code')
                ->select('c.*','a.*', 'b.language' )
                ->where('a.id','=',$id)
                ->first();
          $uti = new Utility();
        return view('master.voucher.detail-lang', compact(['row','uti']));
    }

    public function edit_detail($id)
    {
        // $id = base64_decode($id);
        $model = DB::table('mst_voucher_lang as a')
                ->join('mst_voucher as c', 'c.id', 'a.voucher_id')
                ->join('mst_language as b', 'b.code', 'a.code')
                ->select('c.*','a.*', 'b.language' )
                ->where('a.id','=',$id)
                ->first();

        

        $uti = new Utility();
        return view('master.voucher.update', compact(['page','model','uti']));
    }

    public function show_detail($id)
    {
        // $id = base64_decode($id);
        $lang = DB::table('mst_voucher_lang as a')
                ->join('mst_language as b', 'a.code', 'b.code')
                ->select('a.*','b.*')
                ->where('a.id', $id)
                ->first();
        $model = MstVoucherLang::findOrFail($id);
        $uti = new Utility();
        return view('master.voucher.update', compact(['lang','model','uti']));
    }

    public function update_satu(Request $request, $id)
    {
        $this->validate($request, [
            'asset_id'  => 'required',
            'date_start'  => 'required',
            'number_interest'  => 'required',
            'long_promo'  => 'required',
            'date_expired'  => 'required',
            'quota'  => 'required',
            'status'  => 'required',
        ]);

        $userId = Auth::user()->id;
        $active = $request->get('active') ? 1 : 0;   
        $model = MstVoucher::findOrFail($id);


        $data = [
                'asset_id'      => $request->asset_id,
                'date_start'    => date('Y-m-d', strtotime($request->date_start)),
                'date_end'=> date('Y-m-d', strtotime($request->date_expired)),
                'long_promo'=> $request->long_promo,
                'number_interest'=> $request->number_interest,
                'quota'=> $request->quota,
                'status'=> $request->status,
                'active'=> $active,
                'created_by'=> $userId,
                'updated_by'=> $userId
            ];  
            
        $model->update($data);
        return redirect('master/voucher/'.$model->id)->with('success','Successfully updated');
        
    }


}
