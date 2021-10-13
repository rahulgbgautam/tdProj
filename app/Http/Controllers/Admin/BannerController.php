<?php

namespace App\Http\Controllers\Admin;

use App\Banner;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Helpers\Helper;

class BannerController extends Controller 
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
        if(in_array("banner-management",$menu_write)){
            $action_display = 1;
        }else{
            $action_display = 0;
        }
        $BannerData=Banner::latest('id')->paginate(10); 
        return view('admin.bannerManagement.bannerList',compact('BannerData','action_display'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.bannerManagement.bannerAdd');
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

            'title'=>'required|max:50',
            'subtitle'=>'required|max:100',
            'description'=>'required|max:1000',
            'banner_image'=>'required|image|mimes:jpg,png,jpeg,svg',
            'banner_type'=>'required'

        ]);

         if($request->file('banner_image')){

            $img_path=uploadImage($request->file('banner_image'));
            $data = new Banner;
            $data->title = $request->title;
            $data->subtitle = $request->subtitle;
            $data->discription = $request->description;
            $data->banner_image = $img_path;
            $data->banner_type = $request->banner_type;
            $data->save();
            $request->session()->flash('successMsg','Banner Added Successfully.');
            return redirect()->route('banner-management.index');

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function show(Banner $banner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function edit(Banner $banner,$id)
    {
        $Banner=Banner::find($id);
        return view('admin.bannerManagement.bannerEdit',compact('Banner'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Banner $banner,$id)
    {   
            $validatedData = $request->validate([

            'title'=>'required|max:50',
            'subtitle'=>'required|max:100',
            'description'=>'required|max:1000',
            'banner_type'=>'required'

        ]);

        if($request->file('banner_image')!=''){

            $img_path=uploadImage($request->file('banner_image'));
            unlinkImage($request->old_banner_image);
            
        }else{

                $img_path=$request->old_banner_image;
        }

        $Banner=Banner::find($id);
        $Banner->title = $request->title;
        $Banner->subtitle = $request->subtitle;
        $Banner->discription = $request->description;
        $Banner->banner_image = $img_path;
        $Banner->banner_type = $request->banner_type;
        $Banner->status = $request->status;
        $Banner->update();
        $request->session()->flash('successMsg','Banner Updated Successfully.');
        return redirect()->route('banner-management.index');

        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Banner $banner,$id)
    {   
        $Banner=Banner::find($id);
        $Banner->delete();
        return redirect()->route('banner-management.index')->with('successMsg',"Banner Record Deleted Successfully.");
    }
}
