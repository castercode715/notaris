<?php

namespace App\Http\Controllers;

use App\Layout;
use Illuminate\Http\Request;

class LayoutController extends Controller
{
    public function index()
    {
        // Tampil data
        $data = Layout::all();
        $user = auth()->user();
        return view('layout.index', compact('data', 'user'));
    }

    public function create() {
        //
        return view('layout.create');
    }

    public function store(Request $request) {
        //
        $data = new Layout;
        $data->nama = $request->nama;
        $data->active = $request->active;
        $data->created_by = $request->created_by;
        $data->updated_by = $request->updated_by;
        $data->save();
        return redirect()->route('layout.index');
    }

    public function edit($id) {

        $data = Layout::where('id', $id)->get();
        return view('layout.edit', compact('data'));
    }

    public function update(Request $request, $id) {

        $data = Layout::where('id', $id)->first();
        $data->nama = $request->nama;
        $data->active = $request->active;
        $data->created_by = $request->created_by;
        $data->updated_by = $request->updated_by;
        $data->save();
        return redirect()->route('layout.index')->with('alert-success', 'Data berhasil diubah!');
    }


    public function show($id) {
        $data = Layout::where('id', $id)->first();
        return view('layout.lihat', ['data' => $data]);
    }

    public function destroy($id) {
        // delete
        $data = Layout::where('id', $id)->first();
        $data->delete();
        return redirect()->route('layout.index');
    }


}
