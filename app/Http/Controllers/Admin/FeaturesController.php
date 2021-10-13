<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Features;
use Illuminate\Http\Request;

class FeaturesController extends Controller
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
        if(in_array("features-management",$menu_write)){
            $action_display = 1;
        }else{
            $action_display = 0;
        }
        $FeatureData=features::latest('id')->paginate(10);
        return view('admin.featureManagement.featureList',compact('FeatureData','action_display'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.featureManagement.featureAdd');
        
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
            'description'=>'required|max:1000',
            'icon_image'=>'required|image|mimes:jpg,png,jpeg'

        ]);

         if($request->file('icon_image')){

            $img_path=uploadImage($request->file('icon_image'));
        }

        $data = new features;
        $data->title = $request->title;
        $data->discription = $request->description;
        $data->icon_image = $img_path;
        $data->save();
        $request->session()->flash('successMsg','Feature Added Successfully.');
        return redirect()->route('features-management.index');


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\features  $features
     * @return \Illuminate\Http\Response
     */
    public function show(features $features)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\features  $features
     * @return \Illuminate\Http\Response
     */
    public function edit(features $features,$id)
    {

       $Feature=features::find($id);
        return view('admin.featureManagement.featureEdit',compact('Feature'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\features  $features
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, features $features,$id)
    {
        
         $validatedData = $request->validate([

            'title'=>'required|max:50',
            'description'=>'required|max:1000'

        ]);

        if($request->file('icon_image')!=''){

            $img_path=uploadImage($request->file('icon_image'));
            unlinkImage($request->old_icon_image);
            
        }else{

                $img_path=$request->old_icon_image;
        }


        $feature=features::find($id);
        $feature->title = $request->title;
        $feature->discription = $request->description;
        $feature->icon_image = $img_path;
        $feature->status = $request->status;
        $feature->update();
        $request->session()->flash('successMsg','Feature Updated Successfully.');
        return redirect()->route('features-management.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\features  $features
     * @return \Illuminate\Http\Response
     */
    public function destroy(features $features,$id)
    {
        $Feature=features::find($id);
        $Feature->delete();
        return redirect()->route('features-management.index')->with('successMsg',"Feature Record Deleted Successfully.");
    }
}
