<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;

class ConsultationController extends Controller
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
        $consultation = \App\Consultation::all();
        $data = ['consultation'=>$consultation];
        return view('admin/consultation/index')->with($data);
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
        $factory = (new Factory())
            ->withServiceAccount(__DIR__.'/FirebaseKey.json')->create();

        $database = $factory->getDatabase();
        $ref = $database->getReference('Chats');
        
        $chats = $ref->getValue();

        // $data = \App\Consultation::where('chat_id', $id)->get();
        // $d = ['data'=>$data];

        foreach ($chats as $chat){
            if($chat['id_konsultasi'] == $id){
                $all_chats[] = $chat;
            }
        }

        //return $all_chats;
        return view('admin.consultation.show', compact('all_chats'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
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
        // $product=\App\::find($id);
 
        // $product->name=$request->get('name');
        // $product->condition=$request->get('condition');
        // $product->price=$request->get('price');
        // $product->description=$request->get('description');
        // $product->image=$image;
        // $product->save();
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
