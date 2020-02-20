<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        // Tampil data
        $data = Role::all();
        $user = auth()->user();
        return view('role.index', compact('data','user'));
    }

    public function create() {
        //
        return view('role.create');
    }

    public function store(Request $request) {
        //
        $data = new Role();
        $data->nama = $request->nama;
        $data->active = $request->active;
        $data->created_by = $request->created_by;
        $data->updated_by = $request->updated_by;
        $data->save();
        return redirect()->route('role.index');
    }

    public function edit($id) {

        $data = Role::where('id', $id)->get();
        return view('role.edit', compact('data'));
    }

    public function update(Request $request, $id) {

        $data = Role::where('id', $id)->first();
        $data->nama = $request->nama;
        $data->active = $request->active;
        $data->created_by = $request->created_by;
        $data->updated_by = $request->updated_by;
        $data->save();
        return redirect()->route('role.index')->with('alert-success', 'Data berhasil diubah!');
    }


    public function show($id) {
        $data = Role::where('id', $id)->first();
        return view('role.lihat', ['data' => $data]);
    }

    public function destroy($id) {
        // delete
        $data = Role::where('id', $id)->first();
        $data->delete();
        return redirect()->route('role.index');
    }

}
