<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ApotekerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $apoteker = \App\Apoteker::all();
        $data = ['apoteker' => $apoteker];
        return view('admin/apoteker/index')->with($data);
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
        //$apoteker = \App\Apoteker::where('apoteker_id', $id)->get();
        $apoteker = DB::table('tb_apoteker')
            ->where('tb_apoteker.apoteker_id', '=', $id)
            ->get();
        $array = array(0, 0, 0);
        if ($apoteker[0]->apoteker_sipa1 != null && $apoteker[0]->apotek_sipa1 != null) {
            $array[0] = $apoteker[0]->apotek_sipa1;
        }
        if ($apoteker[0]->apoteker_sipa2 != null && $apoteker[0]->apotek_sipa2 != null) {
            $array[1] = $apoteker[0]->apotek_sipa2;
        }
        if ($apoteker[0]->apoteker_sipa3 != null && $apoteker[0]->apotek_sipa3 != null) {
            $array[2] = $apoteker[0]->apotek_sipa3;
        }
        $apotek = DB::table('tb_apotik')
            ->whereIn('apotik_id', $array)
            ->get();

        $data = [
            'data' => $apoteker,
            'apotek' => $apotek
        ];

        //$str = implode(',', $data);
        //return print($str);
        return view('admin.apoteker.show')->with($data);
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
    public function update(Request $request)
    {
        DB::table('tb_apoteker')->where('apoteker_id', $request->id)->update([
            'status' => $request->status
        ]);
        return redirect('/admin/apoteker');
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
