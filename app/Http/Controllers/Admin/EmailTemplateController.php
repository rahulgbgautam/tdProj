<?php

namespace App\Http\Controllers\Admin;

use App\EmailTemplate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmailTemplateController extends Controller
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
        if(in_array("email-management",$menu_write)){
            $action_display = 1;
        }else{
            $action_display = 0;
        }
        $EmailData = DB::table('email_templates')->get();
        return view('admin.emailTemplate.emailList',compact('EmailData','action_display'));
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
     * @param  \App\EmailTemplate  $emailTemplate
     * @return \Illuminate\Http\Response
     */
    public function show(EmailTemplate $emailTemplate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EmailTemplate  $emailTemplate
     * @return \Illuminate\Http\Response
     */
    public function edit(EmailTemplate $emailTemplate,$id)
    {   
        $editemailtemp = EmailTemplate::find($id);
        return view('admin.emailTemplate.emailEdit',compact('editemailtemp'));

        // echo "<pre>";
        // print_r($editemailtemp);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EmailTemplate  $emailTemplate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmailTemplate $emailTemplate,$id)
    {      

            $validatedData = $request->validate([
                'subject'=>'required',
                'message'=>'required'                
            ]);
            $data = EmailTemplate::find($id);
            $data->title = $request->subject;
            $data->description = $request->message;
            $data->save();
            return redirect()->route('email-management.index')->with('successMsg',"Record Updated Successfully.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EmailTemplate  $emailTemplate
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmailTemplate $emailTemplate)
    {
        //
    }
}
