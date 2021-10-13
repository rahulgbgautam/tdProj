<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use Illuminate\Http\Request;
use Validator;

class PromoCodeController extends Controller
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
        if(in_array("promo-code",$menu_write)){
            $action_display = 1;
        }else{
            $action_display = 0;
        }
        $promoCode = PromoCode::where('is_deleted','0')
                               ->latest('id')
                               ->paginate(10); 
        return view('admin.promoCode.promoCodeList',compact('promoCode','action_display'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.promoCode.promoCodeAdd');
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
                'promo_code'=>'required|unique:promo_codes,promo_code|max:50|alpha_dash',
                'available_quantity'=>'required|numeric|min:0|max:999999999',
                'expiry_date'=>'required|date',
                'discount'=>'required|numeric|min:1|max:99'
        ]);
        $promocode = new PromoCode;
        $promocode->promo_code = $request->promo_code;
        $promocode->available = $request->available_quantity;
        $promocode->expire_date = $request->expiry_date;
        $promocode->discount = $request->discount;
        $promocode->save();
        return redirect()->route('promo-code.index')->with('successMsg',"Promo Code Added Successfully.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PromoCode  $promoCode
     * @return \Illuminate\Http\Response
     */
    public function show(PromoCode $promoCode)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PromoCode  $promoCode
     * @return \Illuminate\Http\Response
     */
    public function edit(PromoCode $promoCode)
    {
        return view('admin.promoCode.promoCodeEdit',compact('promoCode'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PromoCode  $promoCode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PromoCode $promoCode)
    {   
        // return $request->all();
        if($request->promo_code != $request->promo_code_old && $request->promo_code != ''){

            $validatedData = $request->validate([
                'promo_code'=>'required|unique:promo_codes,promo_code|max:50|alpha_dash',
                'available_quantity'=>'required|numeric|min:0|max:999999999',
                'expiry_date'=>'required|date',
                'discount'=>'required|numeric|min:1|max:99'
            ]);

        }else{

            $validatedData = $request->validate([
                'promo_code'=>'required|max:500|alpha_dash',
                'available_quantity'=>'required|numeric|min:0',
                'expiry_date'=>'required|date',
                'discount'=>'required|numeric|min:1|max:99'
            ]);

        }

        $promoCode->promo_code = $request->promo_code;
        $promoCode->available = $request->available_quantity;
        $promoCode->expire_date = $request->expiry_date;
        $promoCode->discount = $request->discount;
        $promoCode->status = $request->status;
        $promoCode->update();
        return redirect()->route('promo-code.index')->with('successMsg',"Promo Code Updated Successfully.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PromoCode  $promoCode
     * @return \Illuminate\Http\Response
     */
    public function destroy(PromoCode $promoCode)
    {
        $promoCode->is_deleted = "1";
        $promoCode->promo_code = $promoCode->promo_code.'-'.date("YmdHis");
        $promoCode->update();
        return redirect()->route('promo-code.index')->with('successMsg',"Promo Code Deleted Successfully.");
    }
}
