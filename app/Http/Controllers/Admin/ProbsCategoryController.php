<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProbsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ProbsCategoryController extends Controller
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
        if(in_array("probs-category",$menu_write)){
            $action_display = 1;
        }else{
            $action_display = 0;
        }
        $content = ProbsCategory::where('is_deleted','0')
                    ->orderBy('category_name','ASC')->paginate(10);
        return view('admin.probsCategory.probsCategoryList',compact('content','action_display'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
     return view('admin.probsCategory.probsCategoryAdd');
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
            'category_name'=>'required|unique:ds_probs_category,category_name|max:200',
            'Grade_A'=>'required|max:10000',
            'Grade_B'=>'required|max:10000',
            'Grade_C'=>'required|max:10000',
            'Grade_D'=>'required|max:10000',
            'Grade_E'=>'required|max:10000',
        ]);
        // return $request->all();
        $data = new ProbsCategory;
        $data->category_name = $request->category_name;
        $data->grade_a = $request->Grade_A;
        $data->grade_b = $request->Grade_B;
        $data->grade_c = $request->Grade_C;
        $data->grade_d = $request->Grade_D;
        $data->grade_e = $request->Grade_E;
        $data->save();
        return redirect()->route('probs-category.index')->with('successMsg',"Category Added Successfully.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProbsCategory  $probsCategory
     * @return \Illuminate\Http\Response
     */
    public function show(ProbsCategory $probsCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProbsCategory  $probsCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(ProbsCategory $probsCategory)
    {
        return view('admin.probsCategory.probsCategoryEdit',compact('probsCategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProbsCategory  $probsCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProbsCategory $probsCategory)
    {   
        if($request->category_name != $request->category_name_old && $request->category_name != ''){

            $validatedData = $request->validate([
                'category_name'=>'required|unique:ds_probs_category,category_name|max:50',
                'Grade_A'=>'required|max:10000',
                'Grade_B'=>'required|max:10000',
                'Grade_C'=>'required|max:10000',
                'Grade_D'=>'required|max:10000',
                'Grade_E'=>'required|max:10000',
            ]);

        }else{

            $validatedData = $request->validate([
                'category_name'=>'required|max:50',
                'Grade_A'=>'required|max:10000',
                'Grade_B'=>'required|max:10000',
                'Grade_C'=>'required|max:10000',
                'Grade_D'=>'required|max:10000',
                'Grade_E'=>'required|max:10000',
            ]);

        }
        
        $probsCategory->category_name=$request->category_name;
        $probsCategory->grade_a=$request->Grade_A;
        $probsCategory->grade_b=$request->Grade_B;
        $probsCategory->grade_c=$request->Grade_C;
        $probsCategory->grade_d=$request->Grade_D;
        $probsCategory->grade_e=$request->Grade_E;
        $probsCategory->status=$request->status;
        $probsCategory->update();
        return redirect()->route('probs-category.index')->with('successMsg',"Category Updated Successfully.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProbsCategory  $probsCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProbsCategory $probsCategory)
    {

        $probsCategory->is_deleted = "1";
        $probsCategory->category_name = $probsCategory->category_name.'-'.date("YmdHis");
        $probsCategory->update();
        return redirect()->route('probs-category.index')->with('successMsg',"Category Deleted Successfully.");

    }
}
