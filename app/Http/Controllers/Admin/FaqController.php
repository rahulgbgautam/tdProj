<?php

namespace App\Http\Controllers\Admin;

use App\Faq;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $id = session('admin')['id'];     
        $menu_write = menuPermissionByType($id,"write");
        if(in_array("faq",$menu_write)){
            $action_display = 1;
        }else{
            $action_display = 0;
        }
        $Faq=Faq::latest('id')->paginate(10); 
        return view('admin.faq.faq_list',compact('Faq','action_display'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        return view('admin.faq.faq_add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
         $validatedData = $request->validate([

            'question'=>'required|max:100',
            'answer'=>'required|max:1000'
        ]);

        $data = new Faq;
        $data->question = $request->question;
        $data->answer = $request->answer;
        $data->status = "Active";
        $data->save();
        $request->session()->flash('successMsg','FAQ Added Successfully.');
        return redirect()->route('faq.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function show(Faq $faq)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function edit(Faq $faq)
    {

        return view('admin.faq.faq_edit',compact('faq'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Faq $faq)
    {   
         $validatedData = $request->validate([
            'question'=>'required|max:100',
            'answer'=>'required|max:1000'
        ]);

        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->status = $request->status;
        $faq->update();
        $request->session()->flash('successMsg','FAQ Updated Successfully.');
        return redirect()->route('faq.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function destroy(Faq $faq)
    {
        
        $faq->delete();
        return redirect()->route('faq.index')->with('successMsg',"FAQ Deleted Successfully.");

    }


}
