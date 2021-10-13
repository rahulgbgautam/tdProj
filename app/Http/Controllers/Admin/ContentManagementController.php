<?php

namespace App\Http\Controllers\Admin;

use App\ContentManagement;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContentManagementController extends Controller
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
        if(in_array("content-management",$menu_write)){
            $action_display = 1;
        }else{
            $action_display = 0;
        }
        $content = DB::table('content_managements')->get();
        return view('admin.contentManagement.contentList',compact('content','action_display'));
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
     * @param  \App\ContentManagement  $contentManagement
     * @return \Illuminate\Http\Response
     */
    public function show(ContentManagement $contentManagement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ContentManagement  $contentManagement
     * @return \Illuminate\Http\Response
     */
    public function edit(ContentManagement $contentManagement)
    {

        return view('admin.contentManagement.contentEdit',compact('contentManagement'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ContentManagement  $contentManagement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ContentManagement $contentManagement)
    {
        
        $validatedData = $request->validate([
                'title'=>'required|max:50',
                'subtitle'=>'required|max:100',
                'description'=>'required',
        ]);

        $contentManagement->title = $request->title;
        $contentManagement->subtitle = $request->subtitle;
        $contentManagement->description = $request->description;
        $contentManagement->update();
        return redirect()->route('content-management.index')->with('successMsg',"Content Updated Successfully");

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ContentManagement  $contentManagement
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContentManagement $contentManagement)
    {
        //
    }
}
