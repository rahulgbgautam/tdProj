<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Domains;
use App\Models\DomainsUser;
use App\Models\ScanData;
use App\Models\User;
use App\Models\Industry;
use App\Exports\DomainsExport;
use Maatwebsite\Excel\Facades\Excel;
use Validator;


class DomainsController extends Controller
{
    
    public function index(Request $request){
     
        $search = trim($request->search);
        $id = session('admin')['id'];     
        $menu_write = menuPermissionByType($id,"write");
        if(in_array("domains",$menu_write)){
            $action_display = 1;
        }else{
            $action_display = 0;
        }
        $query = Domains::leftJoin('industries','ds_domains.industry','=','industries.id')
                        ->select('ds_domains.*','industries.industry_name')
                        ->groupBy('ds_domains.id')
                        ->latest('id');
        if($search){
            $query->where('domain_name','LIKE','%'.$search.'%');
        }
        $content = $query->paginate(25);
        return view('admin.domains.domainsList',compact('content','search','action_display'));


    }

    public function create()
    {   
        $industry_data=Industry::where('is_deleted','0')
                        ->where('status',"Active")
                        ->get(); 
        return view('admin.domains.domainsAdd',compact('industry_data'));
        
    }

    public function store(Request $request)
    {   
        $validatedData = $request->validate([
                'domains'=>'required|max:10000',
                'industry'=>'required',
        ]);

        $industry = $request->industry;
        $domain_not_exists = [];
        $domain_presents = [];
        $lines = explode(PHP_EOL, $request->domains);
        foreach ($lines as $data) {
            $domain = trim($data);
            if(!empty($domain)){
                if(!checkdnsrr($domain,"MX")){
                    $domain_not_exists[] = $domain;
                }else{
                    $domain = refineDomain($domain);
                    $domaininfo = checkAndAddDomain($domain, $industry);
                    $domain_in_table = checkDomainInTable($domain);
                    if($domain_in_table){
                        $domain_presents[] = $domain;
                    }
                }
            }
        }
        // return $domain_presents;
        if($domain_not_exists||$domain_presents){
            return redirect('admin/domains/create')->with([
                                        'domain_not_exists'=>$domain_not_exists,
                                        'domain_presents'=>$domain_presents,
                                        ]);
        }else{
            return redirect('admin/domains')->with('successMsg',"Domain Added Successfully.");
        }

    }

    public function AssociateUser($id)
    {   
        $domain_id=$id;
        $domain_data=Domains::leftJoin('ds_domain_users','ds_domains.id','=','ds_domain_users.domain_id')
                                ->leftJoin('industries','ds_domain_users.industry','=','industries.id')
                                ->select('ds_domains.id','ds_domains.domain_name','ds_domain_users.industry','industries.industry_name')
                                ->where('ds_domains.id',$domain_id)
                                ->groupBy('ds_domains.id')
                                ->get(); 

        $user_data=User::where('type',"user")
                        ->where('status',"Active")
                        ->where('user_verified','1')
                        ->orderBy('name','ASC')
                        ->get();

        $industry_data=Industry::where('is_deleted','0')
                        ->where('status',"Active")
                        ->get();                                             

        return view('admin.domains.domainsAssociate',compact('user_data','domain_data','industry_data','domain_id'));
        
    }

    public function BlockDomain($id)
    {   

        $data = Domains::find($id);
        $data->status = "Inactive";
        $data->update();
        return redirect('admin/domains')->with('successMsg',"Domain Blocked Successfully.");
    }

    public function UnblockDomain($id)
    {   

        $data = Domains::find($id);
        $data->status = "Active";
        $data->update();
        return redirect('admin/domains')->with('successMsg',"Domain Unblocked Successfully.");
    }

    public function ExportDomain(Request $request)
    {   
          return Excel::download(new DomainsExport, 'ds_domains.xlsx');
    }

    public function EditDomain(Request $request,$id)
    {   
        $domain = Domains::find($id);
        $industry_data=Industry::where('is_deleted','0')
                        ->where('status',"Active")
                        ->get();
        // return $domain;                
        return view('admin.domains.domainsEdit',compact('domain','industry_data'));     
    }

     public function UpdateDomain(Request $request,$id)
    {   
        $validatedData = $request->validate([
                'industry'=>'required',
        ]);
        $Domains=Domains::find($id);
        $Domains->industry = $request->industry;
        $Domains->update();
        return redirect('admin/domains')->with('successMsg',"Domain Updated Successfully.");
            
    }


    public function AssociateUserProcess(Request $request)
    {
        $validatedData = $request->validate([
                'domain_name'=>'required',
                'user_name'=>'required',
                'type'=>'required',
        ]);
        $users_id = $request->user_name;
        $domain_id = $request->domain_id;
        $domainInfo = Domains::find($domain_id);
        $value['type'] = $request->type;
        $value['industry'] = $domainInfo->industry;
        foreach($users_id as $user_id) {
            checkAndAddDomainUser($domain_id,$user_id,"month",$value);
        }
        return redirect('admin/domains')->with('successMsg',"Domain Associated Successfully");
    }

    public function destroy($id)
    {
        $data=Domains::find($id);
        $data->is_deleted="1";
        $data->update();
        return redirect('admin/domains')->with('successMsg',"Domain Deleted Successfully.");

    }
    public function exportCsv(Request $request,$search='')
    {   
        $fileName = 'domains '.date('d F Y').'.csv';
        $records = Domains::leftJoin('industries','ds_domains.industry','=','industries.id')
                        ->select('ds_domains.*','industries.industry_name')
                        ->groupBy('ds_domains.id');
        if($search){
            $records->where('domain_name',$search);
        }
        $records = $records->get();                
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );
        $columns = array('Domain Name', 'Industry Name', 'Grade', 'Average Score', 'Latest Scan Date', 'Status');
        $callback = function() use($records, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($records as $res) {
                $row['Domain Name']  = $res->domain_name;
                $row['Industry Name'] = $res->industry_name;
                $row['Grade'] = getRating($res->average_score);
                $row['Average Score'] = $res->average_score;
                $row['Latest Scan Date'] = date('d-m-Y',strtotime($res->last_scan_date));
                $row['Status'] = $res->status;
                fputcsv($file, array($row['Domain Name'],$row['Industry Name'],$row['Grade'],$row['Average Score'],$row['Latest Scan Date'],$row['Status']));
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    } 
}
