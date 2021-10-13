<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DynamicContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class DynamicContentController extends Controller
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
        if(in_array("dynamic-content",$menu_write)){
            $action_display = 1;
        }else{
            $action_display = 0;
        }
        $content = DB::table('dynamic_contents')->latest('id')->paginate(10);
        return view('admin.dynamicContentManagement.dynamicContentList',compact('content','action_display'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.dynamicContentManagement.dynamicContentAdd');
        
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
                'file'=>'required|image|mimes:jpg,png,jpeg,svg',
                'menu_name'=>'required|max:200',
                'menu'=>'required',
                'title'=>'required|max:200',
                'subtitle'=>'required|max:1000',
                'description'=>'required|max:5000'
                
        ]); 

        if($request->file('file')){
            $img_path=uploadImage($request->file('file'));
            $dynamicContent = new DynamicContent;
            $dynamicContent->title = $request->title;
            $dynamicContent->subtitle = $request->subtitle;
            $dynamicContent->image = $img_path;
            $dynamicContent->description = $request->description;
            $dynamicContent->menu = $request->menu;
            $dynamicContent->menu_name = $request->menu_name;
            $dynamicContent->save();
            return redirect()->route('dynamic-content.index')->with('successMsg',"Content Added Successfully");
        }      

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DynamicContent  $dynamicContent
     * @return \Illuminate\Http\Response
     */
    public function show(DynamicContent $dynamicContent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DynamicContent  $dynamicContent
     * @return \Illuminate\Http\Response
     */
    public function edit(DynamicContent $dynamicContent)
    {
        return view('admin.dynamicContentManagement.dynamicContentEdit',compact('dynamicContent'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DynamicContent  $dynamicContent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DynamicContent $dynamicContent)
    {
        
        $validatedData = $request->validate([
                'file'=>'image|mimes:jpg,png,jpeg,svg',
                'menu_name'=>'required|max:200',
                'menu'=>'required',
                'title'=>'required|max:200',
                'subtitle'=>'required|max:1000',
                'description'=>'required',
                'status'=>'required|max:5000'
        ]);

        if($request->file('file')!=''){

            $img_path=uploadImage($request->file('file'));
            unlinkImage($request->old_image);
            
        }else{

                $img_path=$request->old_image;
        }

        $dynamicContent->menu_name = $request->menu_name;
        $dynamicContent->menu = $request->menu;
        $dynamicContent->title = $request->title;
        $dynamicContent->subtitle = $request->subtitle;
        $dynamicContent->image = $img_path;
        $dynamicContent->description = $request->description;
        $dynamicContent->status = $request->status;
        $dynamicContent->update();
        return redirect()->route('dynamic-content.index')->with('successMsg',"Content Updated Successfully");

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DynamicContent  $dynamicContent
     * @return \Illuminate\Http\Response
     */
    public function destroy(DynamicContent $dynamicContent)
    {
        $dynamicContent->delete();
        return redirect()->route('dynamic-content.index')->with('successMsg',"Content Deleted Successfully.");

    }
}
