<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OverallRatingMessages;

class OverallRatingController extends Controller
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
        if(in_array("manage-avg-rating-text",$menu_write)){
            $action_display = 1;
        }else{
            $action_display = 0;
        }
        $overallRating=OverallRatingMessages::all();
        return view('admin.overallRating.overallRatingList',compact('overallRating','action_display'));
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
        $overallRating=OverallRatingMessages::find($id);
        return view('admin.overallRating.overallRatingEdit',compact('overallRating'));
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
        $validatedData = $request->validate([
                'performance'=>'required|max:1000',
                'message'=>'required|max:5000',
                'min_score'=>'required|numeric|min:0|max:100'
        ]);
        $overallRating=OverallRatingMessages::find($id);
        $overallRating->performance = $request->performance;
        $overallRating->message = $request->message;
        $overallRating->min_score = $request->min_score;
        $overallRating->update();
        return redirect()->route('overall-rating.index')->with('successMsg',"Rating Updated Successfully.");

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
