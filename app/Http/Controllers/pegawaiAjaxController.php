<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class pegawaiAjaxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Pegawai::orderBy('nama', 'asc');
        return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('aksi', function($data){
            return view('pegawai.tombol')->with('data', $data);
        })
        ->make(true);
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
        $validasi = Validator::make($request->all(),[
            'nama' => 'required',
            'email' => 'required|email',
        ],[
            'nama.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Email wajib benar',
        ]);

        if($validasi->fails()){
            return response()->json(['errors' => $validasi->errors()]);
        }else{
            $data = [
                'nama' => $request->nama,
                'email' => $request->email
            ];
    
            Pegawai::create($data);
            return response()->json(['success' => "Berhasil menyimpan data"]);

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
        $data = Pegawai::where('id', $id)->first();
        return response()->json(['result'=> $data]);
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
        $validasi = Validator::make($request->all(),[
            'nama' => 'required',
            'email' => 'required|email',
        ],[
            'nama.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Email wajib benar',
        ]);

        if($validasi->fails()){
            return response()->json(['errors' => $validasi->errors()]);
        }else{
            $data = [
                'nama' => $request->nama,
                'email' => $request->email
            ];
    
            Pegawai::where('id', $id)->update($data);
            return response()->json(['success' => "Berhasil update data"]);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Pegawai::where('id', $id)->delete();
    }
}
