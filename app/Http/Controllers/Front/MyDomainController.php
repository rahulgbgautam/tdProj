<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use auth;
use App\Models\Domains;
use App\Models\DomainsUser;
use App\Models\DomainScanScore;
use App\Models\ProbsCategory;
use App\Models\subscription;
use Session; 

class MyDomainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function MyBrands(Request $request)
    {
        return $this->showDomains(1);
    }

    public function MyPortfolio(Request $request)
    {
       return $this->showDomains(2);
    }

    public function showDomains($type)
    {
        ### code to store is user subscribed
        $userid=auth::id(); 
        $subscription = new subscription();     
        $subscription->getCurrentSubscription($userid); 
        
        $data['domain_data'] = Domains::select('ds_domain_users.*','ds_domains.last_scan_date','ds_domains.average_score','domain_name','ds_domains.status','ds_domain_users.subscription_id','ds_domains.cdn_network')
            ->join('ds_domain_users','ds_domain_users.domain_id','=','ds_domains.id')
            ->where('ds_domain_users.type', $type)
            ->where('ds_domain_users.user_id', $userid)
            ->where('ds_domains.status', 'Active')
            ->orderBy('ds_domain_users.expiry_date','ASC')
            ->paginate(20);

        foreach ($data['domain_data'] as $key => $value) {
            $checksubscription = subscription::where('user_id',$userid)->count();
            // dd($checksubscription);
            if($value['subscription_id']=='0' || $value['subscription_id']=='' || $value['subscription_id']==NULL){
                if($checksubscription>0){
                    $data['domain_data'][$key]['firstdomain'] = 'no';
                }else{
                    $data['domain_data'][$key]['firstdomain'] = 'yes';
                }
            }else{
                $data['domain_data'][$key]['firstdomain'] = 'no';
            }
        }

        // $activeCatCount = ProbsCategory::where('ds_probs_category.status', 'Active')
        //     ->count();  
        // $data['activeCatCount'] = $activeCatCount;
        $data['activeCatCount'] = 5;

        return view('front.myDomain',compact('data'));
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
        //
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
        //
    }

    public function domainAutoRenewal($domain_id)
    {
        $user_id = auth()->user()->id;
        $getSubscription = DomainsUser::select('ds_domain_users.type', 'ds_domain_users.auto_payment', 'ds_domain_users.id as domain_user_id')
            ->join('ds_domains','ds_domain_users.domain_id','=','ds_domains.id')
            ->join('subscriptions','ds_domain_users.subscription_id','=','subscriptions.id')
            ->where('ds_domains.status', 'Active')
            ->where('ds_domain_users.user_id', $user_id)
            ->where('subscriptions.user_id', $user_id)
            ->where('ds_domain_users.domain_id', $domain_id)
            ->groupBy('ds_domain_users.id')
            ->first();

        $type = 1;
        if(isset($getSubscription->type) == 1){
            $type = $getSubscription->type;
        }

        if($type == 1) {
            $routeURL = 'my-brands';
        }
        else{
            $routeURL = 'my-portfolio';
        }

        if($getSubscription) {
            $auto_payment = ($getSubscription->auto_payment == 'Yes')?'No':'Yes';
            $domainsUser = DomainsUser::find($getSubscription->domain_user_id);
            $domainsUser->auto_payment = $auto_payment;
            $domainsUser->update();

            $message = "Selected domain auto payment has been updated.";
            return redirect($routeURL)->with('success',$message);
        }
        else{
            $message = "You are not authorized to access this domain.";
            return redirect($routeURL)->with('error',$message);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function domainDetail($id)
    {
        $user_id = auth()->user()->id;

        $domaindata = Domains::select('domain_name', 'ds_domains.average_score', 'ds_domain_users.expiry_date', 'ds_domain_users.type', 'ds_domains.scan_status')
            ->join('ds_domain_users','ds_domain_users.domain_id','=','ds_domains.id')
            ->where('ds_domains.id',$id)
            ->where('ds_domain_users.user_id',$user_id)
            ->first();

        $errorMessage = '';
        $domainType = 1;
        if(empty($domaindata)){
            $errorMessage = "You are not authorized to access this domain.";
        }
        else {
            $domainType = $domaindata->type;
            if($domaindata->scan_status == 'Processing'){
                $errorMessage = "Domain is under scanning. Please try after some time.";
            }
            elseif($domaindata->expiry_date < date('Y-m-d')){
                $errorMessage = "You are not authorized to access this domain.";
            }
            elseif(session('subscription') != 'yes') {
                $errorMessage = "You need to purchase subscription plan to view full report.";
            }
            elseif($domaindata->average_score < 1){
                $errorMessage = "You need to scan domain at least once before check domain details.";
            }
        }

        if($errorMessage) {
            if($domainType == 2) {
                return redirect('my-portfolio')->with('error',$errorMessage);
            }
            else{
                return redirect('my-brands')->with('error',$errorMessage);
            }
        }

        $data['domainsubcategories'] = array();
        $data['domaincategorydata'] = Domains::select('ds_domains.domain_name','ds_probs_category.category_name', 'ds_probs_category.id as category_id','ds_score_by_category.average_score','ds_probs_category.icon')
            ->join('ds_score_by_category','ds_score_by_category.domain_id','=','ds_domains.id')
            ->join('ds_probs_category','ds_probs_category.id','=','ds_score_by_category.probs_category_id')
            ->where('ds_domains.id',$id)
            ->where('ds_probs_category.status', 'Active')
            ->orderBy('ds_probs_category.id', 'ASC')
            ->groupBy('ds_probs_category.id')
            ->get();

        foreach ($data['domaincategorydata'] as $key => $value) {
            // code to get subcategories data from ds_domain_scan_score on basis of the category ID and domain ID.
            $data['domainsubcategories'][$value['category_name']] = DomainScanScore::select('ds_probs_sub_category.*', 'ds_domain_scan_score.score','ds_domain_scan_score.status','ds_domain_scan_score.message')
                ->join('ds_probs_sub_category','ds_probs_sub_category.id','=','ds_domain_scan_score.probs_sub_category_id')
                ->where('ds_domain_scan_score.domain_id', $id)
                ->where('ds_probs_sub_category.is_deleted', '0')
                ->where('ds_probs_sub_category.category_id', $value['category_id'])
                ->orderBy('ds_probs_sub_category.id', 'ASC')
                ->get();

        }
        $data['domain_id']= $id;
       return view('front.domainDetail',compact('data'));
    }

    public function domainSummary($id)
    {
        $user_id = auth()->user()->id; 
        $domaindata = Domains::select('domain_name', 'ds_domains.average_score', 'ds_domain_users.expiry_date', 'ds_domain_users.type', 'ds_domains.scan_status')
            ->join('ds_domain_users','ds_domain_users.domain_id','=','ds_domains.id')
            ->where('ds_domains.id',$id)
            ->where('ds_domain_users.user_id',$user_id)
            ->first();

        $errorMessage = '';
        $domainType = 1; 
        if(empty($domaindata)){
            $errorMessage = "You are not authorized to access this domain.";
        }
        else {
            $domainType = $domaindata->type;
            if($domaindata->scan_status == 'Processing'){
                $errorMessage = "Domain is under scanning. Please try after some time.";
            }
            elseif($domaindata->average_score < 1){
                $errorMessage = "You need to scan domain at least once before check domain details.";
            }
        }

        if($errorMessage) {
            if($domainType == 2) {
                return redirect('my-portfolio')->with('error',$errorMessage);
            }
            else{
                return redirect('my-brands')->with('error',$errorMessage);
            }
        }

        $domainRating = getRating($domaindata['average_score']);
        $getMessageByGrade = getMessageByGrade($domainRating);
        $domainRatingMessage = trim($getMessageByGrade['message']);
        
        $speedMeterImage = speedMeterImage($domaindata['average_score']);

        $data['domaindata'] = $domaindata;       
        // $data['domainRating'] = $domainRating;       
        // $data['domainRatingValue'] = $domainRatingValue;       
        // $data['speedMeterImage'] = $speedMeterImage;       
        // $data['domainRatingMessage'] = trim($domainRatingMessage);

        $query = ProbsCategory::select('ds_domains.domain_name', 'ds_probs_category.category_name', 'ds_score_by_category.average_score')
            ->leftjoin('ds_score_by_category','ds_probs_category.id','=','ds_score_by_category.probs_category_id')
            ->leftjoin('ds_domains','ds_score_by_category.domain_id','=','ds_domains.id')
            ->leftjoin('ds_domain_users','ds_domain_users.domain_id','=','ds_domains.id')
            ->where('ds_domains.id',$id)
            ->where('ds_probs_category.status', 'Active')
            ->orderBy('ds_probs_category.id', 'ASC')
            ->groupBy('ds_probs_category.id');
            
        $data['domaincategorydata'] = $query->get(); 

        ## code to get tranding chart data
        $chartData = getTrandingChartData($user_id, $id);
        $chartData['speedMeterImage'] = $speedMeterImage;       
        $chartData['domainRatingMessage'] = trim($domainRatingMessage);
        $chartData['type'] = 1;

        $data['chart'] = $chartData;

        $data['trandingChart'] = view('front.trandingChart', $data)->render();

        return view('front.brandViewSummary', $data);
    }

    public function DeepWebTool()
    {
        if(session('subscription') != 'yes') {
            return redirect('my-brands')->with('success',"To use the Deepweb Tool Box, you need to purchase the subscription first.");
        }

        $webToolsArray = array();
        $i = 0;
        $i++;
        $webToolsArray[] = array(
            'id' => $i,
            'tootbox' => 'Website Documents', 
            'description' => 'Search any website for publicly accessible documents.', 
            'how_to_use' => 'Enter the <b>Website Domain e.g. example.com</b> in the search box above. <br>Next step, simply press the search button for this toolbox. The system will then search and list all <b>Publicly Accessible</b> documents and system files found on the Website e.g. PDF, Word or Excel, PowerPoint, Zip, Mpeg, Backups, SQL, etc.. all told 20 formats ', 
            'critaria' => 'site:target AND filetype:txt OR filetype:doc OR filetype:xls OR filetype:PDF OR filetype:docx OR filetype:ppt OR filetype:mpeg OR filetype:zip OR filetype:dat OR filetype:log OR filetype:mdb OR filetype:sql OR filetype:ods OR filetype:back OR filetype:tmp  OR filetype:odp OR filetype:pem OR filetype:csr  OR filetype:key'
        );
        $i++;
        $webToolsArray[] = array(
            'id' => $i,
            'tootbox' => 'Deepweb Documents', 
            'description' => 'Search the Deepweb internet for publicly accessible common documents', 
            'how_to_use' => "Enter the <b>Document or File Name  e.g. Secret</b> in the search box above. You can add <b>OR</b> and <b>AND</b> statements to include other words in the search e.g. apple AND ipad will return all docs found across the Deepweb internet with the name 'apple and ipad' in the document title. <br>Next step, simply press the search button for this toolbox. The system will then return a list of all <b>Publicly Accessible</b> document found in the <b>Deepweb</b> internet  e.g. documents with common formats such as PDF, Word, Excel, PowerPoint, Zip, Mpeg etc.. all told, 10 common formats will be searched. ", 
            'critaria' => 'allintitle:target AND filetype:txt OR filetype:doc OR filetype:xls OR filetype:PDF OR filetype:docx OR filetype:ppt OR filetype:mpeg OR filetype:zip'
        );
        $i++;
        $webToolsArray[] = array(
            'id' => $i,
            'tootbox' => 'Deepweb System Files', 
            'description' => 'Search the Deepweb internet for publicly accessible system file documents', 
            'how_to_use' => "Enter the <b>System File Name  e.g. backupdbase-SQL</b> in the search box above. You can add <b>OR</b> and <b>AND</b> statements to include other words in the search e.g. <b>backup123 AND dbase123</b> will return all system files found across the Deepweb internet with the name 'backup123 and sql123' in the system file title. <br>Next step, simply press the search button for this toolbox. The system will then return a list all <b>Publicly Accessible</b> system files found in the <b>Deepweb</b> internet e.g. system files such as sql, mdb, tmp, odp, backup etc... all told 10 common formats  ", 
            'critaria' => 'allintitle:target AND filetype:dat OR filetype:log OR filetype:mdb OR filetype:sql OR filetype:ods OR filetype:back OR filetype:tmp  OR filetype:odp OR filetype:pem OR filetype:csr  OR filetype:key'
        );
        $i++;
        $webToolsArray[] = array(
            'id' => $i,
            'tootbox' => 'Google Shares ', 
            'description' => 'Search for publicly open shared Google documents  ', 
            'how_to_use' => "Enter the <b>Document or File Name  e.g. Secret</b> in the search box above. You can add <b>OR</b> and <b>AND</b> statements to include other words in the search e.g. <b>apple AND ipad</b> will return all docs found across the publicly shared Google drives with the name 'apple and ipad' in the document title. <br>Next step, simply press the search button for this toolbox. The system will then return a list of all <b>Publicly Accessible</b> document found in <b>Google Shares</b>  e.g. any document with your search name as reference in the doc title ", 
            'critaria' => 'site:drive.google.com [target]'
        );
        $i++;
        $webToolsArray[] = array(
            'id' => $i,
            'tootbox' => 'Paste Scan ', 
            'description' => 'Search for publicly accessible PasteBin pages with key words ', 
            'how_to_use' => "Enter the <b>Word or File Name e.g. Secret</b> in the search box above. You can add <b>OR</b> and <b>AND</b> statements to include other words in the search e.g. <b>apple AND ipad</b> will return all pages found across 30+ Paste bin sites with the words 'apple and ipad'. <br>Next step, simply press the search button for this toolbox. The system will then return a list of all publicly available pages found on over 30+ pastebin sites with your search term words.", 
            'critaria' => 'site:pastebin.com | site:paste2.org | site:pastehtml.com | site:slexy.org | site:snipplr.com | site:snipt.net | site:textsnip.com | site:bitpaste.app | site:justpaste.it | site:heypasteit.com | site:hastebin.com | site:dpaste.org | site:dpaste.com | site:codepad.org | site:jsitor.com | site:codepen.io | site:jsfiddle.net | site:dotnetfiddle.net | site:phpfiddle.org | site:ide.geeksforgeeks.org | site:repl.it | site:ideone.com | site:paste.debian.net | site:paste.org | site:paste.org.ru | site:codebeautify.org  | site:codeshare.io | site:trello.com "target"'
        );
        $i++;
        $webToolsArray[] = array(
            'id' => $i,
            'tootbox' => 'Code Repositories  ', 
            'description' => 'Search for application codes, API keys, passwords in publicly accessible code repositories ', 
            'how_to_use' => "Enter the <b>Application Name, Project Name, Company or Code e.g. SecretAppcode</b> in the search box above. You can add <b>OR</b> and <b>AND</b> statements to include other words in the search e.g. <b>App1 AND key1234</b> will return all pages found across 2 common application development repositories with the words 'App1 and Key123 found on pages'. <br>Next step, simply press the search button for this toolbox. The system will then return a list of all publicly available pages found in the code development repositories. ", 
            'critaria' => 'site:github.com | site:gitlab.com "target"'
        );
        $i++;
        $webToolsArray[] = array(
            'id' => $i,
            'tootbox' => 'Website FTP Shares', 
            'description' => 'Search any website for publicly accessible FTP file shares ', 
            'how_to_use' => 'Enter the <b>Website Domain e.g. example.com</b> in the search box above. <br>Next step, simply press the search button for this toolbox. The system will then search for any <b>Publicly Accessible</b> File Share repository for that website.  ', 
            'critaria' => 'site:"target" intitle:"index of" inurl:ftp'
        );
        
        $data['webTools'] = $webToolsArray;
        return view('front.deepWebTool', $data);
    }
}
