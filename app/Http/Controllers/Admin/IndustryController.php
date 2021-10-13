<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Industry;
use Illuminate\Http\Request;

class IndustryController extends Controller
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
        if(in_array("manage-industry",$menu_write)){
            $action_display = 1;
        }else{
            $action_display = 0;
        }
        $data=Industry::where('is_deleted','0')->latest('id')->paginate(10);
        return view('admin.industryManagement.industryList',compact('data','action_display'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.industryManagement.industryAdd');
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
                'industry_name'=>'required|unique:industries,industry_name|max:200',
        ]);

        $data = new Industry;
        $data->industry_name=$request->industry_name;
        $data->save();
        return redirect()->route('industry.index')->with('successMsg',"Industry Added Successfully.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Industry  $industry
     * @return \Illuminate\Http\Response
     */
    public function show(Industry $industry)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Industry  $industry
     * @return \Illuminate\Http\Response
     */
    public function edit(Industry $industry)
    {
        return view('admin.industryManagement.industryEdit',compact('industry'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Industry  $industry
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Industry $industry)
    {   

        if($request->industry_name != $request->industry_name_old && $request->industry_name != ''){
               $validatedData = $request->validate([
                    'industry_name'=>'required|unique:industries,industry_name|max:200',
                ]);
        }else{

            $validatedData = $request->validate([
                'industry_name'=>'required|max:200'
            ]);
        }
        $industry->industry_name = $request->industry_name;
        $industry->status = $request->status;
        $industry->update();
        return redirect()->route('industry.index')->with('successMsg',"Industry Updated Successfully."); 
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Industry  $industry
     * @return \Illuminate\Http\Response
     */
    public function destroy(Industry $industry)
    {   
        $industry->is_deleted = "1";
        $industry->industry_name = $industry->industry_name.'-'.date("YmdHis");
        $industry->update();
        return redirect()->route('industry.index')->with('successMsg',"Industry Deleted Successfully.");
    }
}
