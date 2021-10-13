<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProbsSubCategory;
use App\Models\ProbsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ProbsSubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) 
    {   
        $id = $request->category_id;
        $admin_id = session('admin')['id'];     
        $menu_write = menuPermissionByType($admin_id,"write");
        if(in_array("probs-sub-category",$menu_write)){
            $action_display = 1;
        }else{
            $action_display = 0;
        }
        if($id){
            $content = ProbsSubCategory::where('ds_probs_sub_category.is_deleted','0')
            ->select('ds_probs_sub_category.*','ds_probs_category.category_name','ds_probs_category.status')
            ->leftJoin('ds_probs_category','ds_probs_category.id','=','ds_probs_sub_category.category_id')
            ->where('ds_probs_sub_category.category_id','=',$id)
            ->where('ds_probs_category.status','=',"Active")
            ->orderBy('category_name','ASC')
            ->paginate(10);
            if(count($content)<1){
                $category = ProbsCategory::where('is_deleted','0')
                            ->where('status','Active')
                            ->orderBy('category_name','ASC')
                            ->get();    
                return view('admin.probsSubCategory.probsSubCategoryList',compact('content','category','id','action_display'));
            }  
        }else{
            $content = ProbsSubCategory::where('ds_probs_sub_category.is_deleted','0')
            ->select('ds_probs_sub_category.*','ds_probs_category.category_name')
            ->leftJoin('ds_probs_category','ds_probs_category.id','=','ds_probs_sub_category.category_id')
            ->where('ds_probs_category.status','=',"Active")
            ->orderBy('category_name','ASC')
            // ->orderBy('sub_category_name','ASC')
            ->paginate(10);
        }

        $category = ProbsCategory::where('is_deleted','0')
                    ->where('status','Active')
                    ->orderBy('id','ASC')
                    ->get();

        // return $category;                  
        return view('admin.probsSubCategory.probsSubCategoryList',compact('content','category','id','action_display'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
       $categoryList = DB::table('ds_probs_category')
                        ->where('is_deleted','0')
                        ->orderBy('category_name','ASC')
                        ->where('status','Active')
                        ->get(); 
       return view('admin.probsSubCategory.probsSubCategoryAdd',compact('categoryList'));
        
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
                'category_id'=>'required',
                'sub_category_name'=>'required|max:50',
                'sub_category_display_name'=>'required|max:50',
                'pass_message'=>'required|max:1000',
                'fail_message'=>'required|max:1000',
                'remediation_message'=>'required|max:1000',
                'pass_code'=>'required',
                'fail_code'=>'required',
                'max_score'=>'required|numeric|min:0|max:99'
        ]);
            $data = new ProbsSubCategory;
            $data->category_id=$request->category_id;
            $data->sub_category_name=$request->sub_category_name;
            $data->sub_category_display_name=$request->sub_category_display_name;
            $data->pass_message=$request->pass_message;
            $data->fail_message=$request->fail_message;
            $data->remediation_message=$request->remediation_message;
            $data->pass_code=$request->pass_code;
            $data->fail_code=$request->fail_code;
            $data->max_score=$request->max_score;
            $data->save();
            return redirect()->route('probs-sub-category.index')->with('successMsg',"Sub Category Added Successfully.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProbsSubCategory  $probsSubCategory
     * @return \Illuminate\Http\Response
     */
    public function show(ProbsSubCategory $probsSubCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProbsSubCategory  $probsSubCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(ProbsSubCategory $probsSubCategory)
    {   
        $selectedCategory = DB::table('ds_probs_category')
                        ->where('is_deleted','0')
                        ->where('status','Active')
                        ->where('id',$probsSubCategory->category_id)
                        ->get();

        $categoryList = DB::table('ds_probs_category')
                        ->where('is_deleted','0')
                        ->where('status','Active')
                        ->orderBy('category_name','ASC')
                        ->get(); 

        return view('admin.probsSubCategory.probsSubCategoryEdit',compact('probsSubCategory','categoryList','selectedCategory'));
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProbsSubCategory  $probsSubCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProbsSubCategory $probsSubCategory)
    {
        
        $validatedData = $request->validate([
                'category_id'=>'required',
                'sub_category_name'=>'required|max:50',
                'sub_category_display_name'=>'required|max:50',
                'pass_message'=>'required|max:1000',
                'fail_message'=>'required|max:1000',
                'remediation_message'=>'required|max:1000',
                'pass_code'=>'required',
                'fail_code'=>'required',
                'status'=>'required',
                'max_score'=>'required|numeric|min:0|max:99'
        ]);

            $probsSubCategory->category_id=$request->category_id;
            $probsSubCategory->sub_category_name=$request->sub_category_name;
            $probsSubCategory->sub_category_display_name=$request->sub_category_display_name;
            $probsSubCategory->pass_message=$request->pass_message;
            $probsSubCategory->fail_message=$request->fail_message;
            $probsSubCategory->remediation_message=$request->remediation_message;
            $probsSubCategory->pass_code=$request->pass_code;
            $probsSubCategory->fail_code=$request->fail_code;
            $probsSubCategory->status=$request->status;
            $probsSubCategory->max_score=$request->max_score;
            $probsSubCategory->update();
            return redirect()->route('probs-sub-category.index')->with('successMsg',"Sub Category Updated Successfully.");

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProbsSubCategory  $probsSubCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProbsSubCategory $probsSubCategory)
    {
        $probsSubCategory->is_deleted="1";
        $probsSubCategory->update();
        return redirect()->route('probs-sub-category.index')->with('successMsg',"Sub Category Deleted Successfully.");
    }
}
