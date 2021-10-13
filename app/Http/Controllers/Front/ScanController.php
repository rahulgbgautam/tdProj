<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Domains;
use App\Models\DomainsUser;
use App\Models\ScanData;
use App\Models\DomainScan;
use auth;
use App\Models\DomainScoreCategory;
use App\Models\DomainScanScore;
use App\Models\StoreScan;
use App\Models\ProbsCategory;
use App\Models\ProbsSubCategory;
use Session;
use Validator;
use Carbon\Carbon;

class ScanController extends Controller
{
    public function emailBreach(Request $request)
    {
        $user_id = auth::id();

        if(session('subscription') != 'yes') {
            return redirect('email-breach')->with('success',"To use the Email Breach, you need to purchase the subscription first.");
        }

        $domain_name = '';
        $result = '';
        $dehashed_entire_db = array();
        $dehashed_total = 0;

        if ($request->isMethod('post')) {
          $validator = Validator::make($request->all(), [
            'domain_name' => 'required',
          ]);

          $validator->after(function ($validator) use ($request, $user_id) {
              $domain_name = $request->domain_name;
              if(!empty($domain_name)){
                ### to check last scan
                $scan = StoreScan::where('domain_name',$domain_name)
                  ->whereMonth('scan_date', '=', date('m'))
                  ->where('user_id', $user_id)
                  ->first();
                if($scan){
                  $validator->errors()->add('domain_name', "You can scan domain once in a month."); 
                }
              }
          });
          
          if($validator->fails()){
              return redirect()->back()->withErrors($validator)->withInput();
          }

          $result = 'yes';

          ### code to get response from API.
          $domain_name = $request->domain_name;
          $domain_name = refineDomain($domain_name);
          $apiJsonData = $this->dehashedApiData($domain_name);
          $apiGetData = json_decode($apiJsonData);

          if(isset($apiGetData->total)) {
            $dehashed_total = $apiGetData->total;

            $dehashed_entire_db = array(); 
            if(isset($apiGetData->entries)) {
              foreach($apiGetData->entries as $entrieInfo) {
                $database_name = $entrieInfo->database_name;
                $database_name = str_replace(' ', '', $database_name);
                if(@array_key_exists($database_name, $dehashed_entire_db)) { 
                   $count = $dehashed_entire_db[$database_name] + 1;
                }
                else {
                  $count = 1;
                }
                $dehashed_entire_db[$database_name] = $count;
              }
              arsort($dehashed_entire_db);
            }

            ### code to store scan domain
            $domain_name = $request->domain_name;
              StoreScan::create([
                'user_id' => $user_id,
                'domain_name' => $domain_name,
                'scan_date' => date('Y-m-d'),
            ]);
          }
          else{
            return redirect('email-breach')->with('error', 'Something went wrong. Please try again later or contact to admin.'); 
          }
          
        }
        $data['domain_data'] = Domains::select('domain_name', 'ds_domains.id')
            ->join('ds_domain_users','ds_domain_users.domain_id','=','ds_domains.id')
            ->where('ds_domains.status', 'Active')
            ->where('user_id',$user_id)
            ->groupBy('ds_domains.id')
            ->get();

        $data['domain_name'] = $domain_name;
        $data['result'] = $result;
        $data['dehashed_total'] = $dehashed_total;
        $data['dehashed_entire_db'] = $dehashed_entire_db;

        // $data['domain_name'] = '';
        // $data['result'] = '';
        // $data['dehashed_total'] = '';
        // $data['dehashed_entire_db'] = array();
        return view('front.emailBreach', $data);
    }

    ##  dehashedApi data
    public function dehashedApiData($searchKeyword=''){
      $dehashed_username = getGeneralSetting('dehashed_username');
      $dehashed_api_key = getGeneralSetting('dehashed_api_key');

      $result = "";
      if($dehashed_api_key && $searchKeyword) {
        $auth = "Basic ".base64_encode($dehashed_username.":".$dehashed_api_key);
        $url = 'https://api.dehashed.com/search?query=domain:'.$searchKeyword.'&page=1&size=10000';
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, 
          array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: '.$auth,
          )
        );
        $result = curl_exec ($curl); 
        curl_close($curl);
      }
      // $result = '{"balance":328,"entries":[{"id":"20365982863","email":"ramlagan@singsys.com","ip_address":"","username":"","password":"","hashed_password":"13652a1d1625f623272ff2a93dca1f31","name":"","vin":"","address":"","phone":"","database_name":"www.releasemyad.com  (Cit0day)"},{"id":"2133818775","email":"sweety@singsys.com","ip_address":"","username":"","password":"santoshima","hashed_password":"","name":"","vin":"","address":"","phone":"","database_name":"Exploit.in"},{"id":"2130904932","email":"awdhesh@singsys.com","ip_address":"","username":"","password":"32420932","hashed_password":"","name":"","vin":"","address":"","phone":"","database_name":"Exploit.in"},{"id":"19229137164","email":"vijayant@singsys.com","ip_address":"","username":"vijayant","password":"","hashed_password":"","name":"","vin":"","address":"","phone":"","database_name":"Canva.com"}],"success":true,"took":"42µs","total":207}';
      return $result;
    }

    public function scanDomainCron()
    {
        $domains = Domains::where('scan_status', 'NeverScan')
        ->take(1)
        ->get();
        foreach ($domains as $key => $domainInfo) {
          # code...
          $this->domainCategorySubCategoryAdd($domainInfo);
        }
    }

    public function searchDomain()
    {
      $domain = request()->search;
      $industry = request()->industry;

      $domain = refineDomain(trim($domain));

      if(!checkdnsrr($domain,"MX")) {
        return json_encode(['status' => 'error', 'message' => 'Entered domain is not exist']);
      }   

      $ip_address = $_SERVER['REMOTE_ADDR'];
      
      ## code to store scan count in the database
      ## count for the scan_data table for particular IP and today's date
      $todaySearchCount = ScanData::whereDate('created_at', Carbon::today())
        ->where('ip_address', $_SERVER['REMOTE_ADDR'])
        ->where('domain_name', '!=', $domain)
        ->distinct('domain_name')
        ->count('domain_name');

      // $todaySearchCount = 0;
      if($todaySearchCount >= 2) {
        return json_encode(['status' => 'error', 'message' => 'You can scan only 2 domains/24Hrs free. You need to Sign Up for more scans.']);
      }
      
      ## code to check and add domain
      $domainInfo = checkAndAddDomain($domain, $industry);
      if($domainInfo->status == 'Inactive') {
        return json_encode(['status' => 'error', 'message' => 'This domain is blocked by admin. Please contact to admin.']);
      }

      ## add code to store data in scan_data table
      $scanData = new ScanData;
      $scanData->domain_name = $domain;
      $scanData->ip_address = $_SERVER['REMOTE_ADDR'];
      $scanData->save();
      
      ## add categories if average score if domain is not scanned
      if($domainInfo->average_score == 0) {
        $this->domainCategorySubCategoryAdd($domainInfo);
        $domainInfo = Domains::find($domainInfo->id);
      }


      Session::put('domain_name',$domain);
      Session::put('industry_id',$industry);

      ## get message behalf average score if domain is scanned
      $domainRating = getRating($domainInfo->average_score);
      $getMessageByGrade = getMessageByGrade($domainRating);
      
      $dataArr['domain_name'] = $domainInfo->domain_name;
      $dataArr['speedMeterImage'] = speedMeterImage($domainInfo->average_score);
      $dataArr['domainRatingMessage'] = trim($getMessageByGrade['message']);
      return json_encode($dataArr);
    }

    public function domainRescanStatusByName($domain)
    {
      $domain = refineDomain(trim($domain));
      $domainInfo = Domains::where('domain_name', $domain)
        ->where('status', 'Active')
        ->first();

      $todaySearchCount = ScanData::whereDate('created_at', Carbon::today())
        ->where('ip_address', $_SERVER['REMOTE_ADDR'])
        ->where('domain_name', '!=', $domain)
        ->distinct('domain_name')
        ->count('domain_name');

      $categoryCount = 0;
      $domain_id = 0;
      if($domainInfo && $todaySearchCount < 2) {
        $categoryCount = $this->domainRescanStatus($domainInfo->id);
        $domain_id = $domainInfo->id;
      }

      // $dataArr['domain'] = $domainInfo;
      $dataArr['domain_id'] = $domain_id;
      $dataArr['categoryCount'] = $categoryCount;
      return json_encode($dataArr);
    }

    public function domainRescanStatus($domain_id)
    {
      $categoryCount = DomainScoreCategory::where('domain_id', $domain_id)->count();
      return $categoryCount?$categoryCount:0;
    }

    public function domainRescan($domain_id, $section='')
    {
      $user_id = auth::id();
      $domainInfo = Domains::where('id', $domain_id)->first();
      $domainUserInfo = DomainsUser::where('domain_id',$domain_id)->where('user_id',$user_id)->first();

      ## add validation for subscription also
      $data['domainInfo'] = $domainInfo;
      if($domainInfo && $domainUserInfo['expiry_date'] >= date('Y-m-d')) {
        $this->domainCategorySubCategoryAdd($domainInfo, $user_id);
      }
     
      if(request()->type == 'ajax') {
        return 'done';
      }
      else {
        return view('front.domainRescan', $data);
      }
    }

    public function domainRescanByAdmin($domain_id)
    {
      $domainInfo = Domains::where('id', $domain_id)->first();
      $data['domainInfo'] = $domainInfo;
      $this->domainCategorySubCategoryAdd($domainInfo);     
      
      return redirect('admin/domains')->with('successMsg', 'Domain has been scanned sucessfully!');  
    }

    public function checkApi($searchKeyword)
    {
      $domain_name = $searchKeyword;
      $this->getWfuzzData($domain_name, 'cookie-disclaim');
      $this->getWfuzzData($domain_name, 'privacy-note');

      die();
      echo "LLL1= ".$searchKeyword;
      $dehashed_api_key = getGeneralSetting('dehashed_api_key');
      $dehashed_username = getGeneralSetting('dehashed_username');

      $auth = "Basic ".base64_encode($dehashed_username.":".$dehashed_api_key);
      $url = 'https://api.dehashed.com/search?query=domain:'.$searchKeyword.'&page=1&size=1';
      $curl = curl_init($url);
      curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($curl, CURLOPT_HTTPHEADER, 
        array(
          'Content-Type: application/json',
          'Accept: application/json',
          'Authorization: '.$auth,
        )
      );
      $result = curl_exec ($curl); 
      curl_close($curl);
      // echo $result['total'];
      print_r($result);
    }

    public function checkDomainOnCdn($domain_name){
      echo $getHostInformation = shell_exec('host -a www.'.$domain_name);
      $getHostInformation = strtolower($getHostInformation);
      $cdnsArr = [
          'cloudfront.net',
          'cloudflare.com',
          'akamai.com',
          'azure.microsoft.com',
          'cloud.google.com',
          'fastly.com',
          'stackpath.com',
          'cachefly.com',
          'limelight.com',
          'imperva.com',
          'onapp.com',
          'chinacache.com',
          'keycdn.com',
          'inap.com',
          'aryaka.com',
          'leaseweb.com',
          'synaptic.att.com',
          'verizondigitalmedia.com',
          'cdn77.com',
          'sirv.com',
          'cloud.ibm.com',
          'gcorelabs.com',
          'ddos-guard.net',
          'belugacdn.com',
          'imagekit.io',
          'imgix.com',
          'superlumin.com',
          'huaweicloud.com',
          'uploadcare.com',
          'arvancloud.com',
          'jet-stream.com',
          'sitelock.com',
          'metacdn.com',
          'tencent.com',
          'ksyun.com',
          'cdnetworks.com',
          'alibabacloud.com',
          'amazonaws.cn',
          'securityboulevard.com'
      ];

      $is_cdn = 'No';
      foreach($cdnsArr as $key=>$cdn){
          // echo '<br>'.$cdn;
          if ((strpos($getHostInformation, strtolower($cdn)) !== false)) {
              $is_cdn = 'Yes';
              break;
          }
      }
      return $is_cdn;
    }

    function domainCategorySubCategoryAdd($domainInfo, $user_id=0)
    {
      $domain_id    = $domainInfo->id;
      $domain_name  = trim($domainInfo->domain_name);
      $domainIP     = gethostbyname($domain_name);
      if($domainIP == $domain_name) {
        $domainIP     = gethostbyname("www.".$domain_name);
      }

      if($domainInfo->scan_status == 'Processing') {
        return '';
      }

      ## code to get is website running on cdn
      $cdn_network = $this->checkDomainOnCdn($domain_name);

      echo $cdn_network."SSSS";

      Domains::where('id',$domain_id)
        ->update(array(
            'scan_status' => 'Processing',
            'cdn_network' => $cdn_network
          ));

      $categoryList = ProbsCategory::select('id', 'domain_percent')
        ->where('status','Active')
        ->where('is_deleted','0')
        ->orderBy('id','ASC')
        ->get(); 

      ### code to delete existing
      DomainScanScore::where('domain_id', $domain_id)->delete();
      DomainScoreCategory::where('domain_id', $domain_id)->delete();

      $grabData = array();
      $domain_score = 0;
      $domain_max_score = 0;
      ### cayegory loop to check score for categories
      foreach ($categoryList as $key => $categoryInfo) {
        $category_id = $categoryInfo->id;

        ### code to get data from APIs
        ### store api data in varaiables
        ### getting information on basis of the category to show progress bar
        if($category_id == 1) {
          $grabData['nslookupTxt']      = shell_exec("nslookup -type=txt ".$domain_name);
          $grabData['nslookupTxtDmarc'] = shell_exec("nslookup -type=txt _dmarc.".$domain_name);
        }
        elseif($category_id == 2) {
          $grabData['shodanApi']    = $this->shodanApiData($domainIP);
          $grabData['hackertarget'] = $this->hackertargetApiData($domain_name);
        }
        elseif($category_id == 3) {
          $grabData['neutrinoApi']  = $this->neutrinoApiData($domainIP);
        }
        elseif($category_id == 4) {

        }
        elseif($category_id == 5) {

        }
        // elseif($category_id == 6) {
        //   $grabData['dehashedApi']  = $this->dehashedApiData($domain_name);
        // }

        $subCategoryList = ProbsSubCategory::select('id', 'max_score', 'sub_category_name')
          ->where('category_id', $category_id)
          ->where('status','Active')
          ->where('is_deleted','0')
          ->orderBy('id','ASC')
          ->get(); 
        
        $category_score = 0;
        $category_max_score = 0;
        if(count($subCategoryList) > 0) {
          ### sub cayegory loop to check score for categories
          foreach ($subCategoryList as $subCategoryInfo) {
              $max_score = $subCategoryInfo->max_score;
              $category_max_score = $category_max_score + $max_score;
              $scanStatus = false;

              ### code to execute the function to check score
              $ScanController = new ScanController(); 
              $functionName = 'scanCategory'.$categoryInfo->id;

              $returnMsg = '';
              if(method_exists($ScanController, $functionName)) {
                $case = strtolower(trim($subCategoryInfo->sub_category_name));
                $case = str_replace(" ", "-", $case);
                $getResponse  = $this->$functionName($case, $grabData, $domain_name, $domainIP);
                // $grabData     = $getResponse['grabData'];
                $scanStatus   = $getResponse['scanStatus'];
                if(isset($getResponse['returnMsg'])) {
                  $returnMsg = $getResponse['returnMsg'];
                } 
              }

              $status = "Fail"; 
              $score = 0;
              if($scanStatus) { 
                $category_score = $category_score + $max_score;
                $status = "Pass";
                $score = $max_score;
              }

              ### code to store score 
              $data = new DomainScanScore;
              $data->domain_id = $domain_id;
              $data->probs_category_id = $category_id;
              $data->probs_sub_category_id = $subCategoryInfo->id;
              $data->score = $score;
              $data->status = $status;
              $data->message = $returnMsg;
              $data->save();
          }
        }

        ### get score percent of category
        $average_score = 0;
        if($category_score > 0) {
          $average_score = ceil(100*$category_score/$category_max_score);
        }

        ### code to score category
        $query = new DomainScoreCategory;
        $query->domain_id = $domain_id;
        $query->probs_category_id = $categoryInfo->id;
        $query->average_score = $average_score;
        $query->save();

        if($categoryInfo->domain_percent == 1) {
          $domain_score = $domain_score + $category_score;
          $domain_max_score = $domain_max_score + $category_max_score;
        }
      }

      ### get score percent of domain
      $average_score = 0;
      if($domain_score > 0) {
        $average_score = ceil(100*$domain_score/$domain_max_score);
      }

      $domainInfo = Domains::find($domain_id);
      $domainInfo->average_score = $average_score;
      $domainInfo->scan_status = 'Completed';
      $domainInfo->last_scan_date = date('Y-m-d');
      $domainInfo->save();

      ### add doamin scan data if domain is purchased
      $domainScan = new DomainScan;
      $domainScan->user_id = $user_id;
      $domainScan->domain_id = $domain_id;
      $domainScan->scan_date = date('Y-m-d');
      // $domainScan->expiry_date = $domainUserInfo->expiry_date;
      // $domainScan->subscription_id = $domainUserInfo->subscription_id;
      $domainScan->average_score = $average_score;
      $domainScan->save();

      if($user_id > 0) {
        DomainsUser::where('domain_id',$domain_id)
          ->where('user_id',$user_id)
          ->update(array('scan_date' => date('Y-m-d')));
      }
    }

    ##  shodanApi data
    public function shodanApiData($domainIP){
      // $shodan_api_key = 'I5irgodgH6Lbr18q7JkMrbnK01eWKyes'; 
      $shodan_api_key = getGeneralSetting('shodan_api_key'); 
      $url = 'https://api.shodan.io/shodan/host/'.$domainIP.'?key='.$shodan_api_key;
      $getContent = file_get_contents($url);
      return $getContent;
    }

    ##  neutrinoApi data
    public function neutrinoApiData($domainIP){
      // $neutrino_api_userid = 'gulshan.singsys';
      // $neutrino_api_key = '2BtXzzP24KlPnNH4NSE23Qnwo6kWap2PVmceyocsssp7cz0s'; 
      $neutrino_api_userid = getGeneralSetting('neutrino_api_userid'); 
      $neutrino_api_key = getGeneralSetting('neutrino_api_key'); 
      
      $url = 'https://neutrinoapi.net/ip-blocklist?user-id='.$neutrino_api_userid.'&api-key='.$neutrino_api_key.'&ip='.$domainIP;

      $getContent = file_get_contents($url);
      return $getContent;
    }

    ##  hackertargetApi data
    public function hackertargetApiData($domain_name){
      $hackertarget_api_key = getGeneralSetting('hackertarget_api_key'); 
      $url = 'https://api.hackertarget.com/httpheaders/?q='.$domain_name;
      if($hackertarget_api_key) {
        $url .= '&api-key='.$hackertarget_api_key;
      }
      $getContent = file_get_contents($url);
      return $getContent;
    }

    ## function to get shodan api data in basis of the cases
    public function refineshodanApiData($getContent, $case){
      if($case == 'vulns') {
        $getData['vuln_crit'] = array();
        $getData['vuln_hi'] = array();
        $getData['vuln_me'] = array();
        $getData['vuln'] = array();
        if($getContent) {
          $dataArr = json_decode($getContent);
          ### code to check for the first data array
          if(@is_array($dataArr->data)) { 
            foreach ($dataArr->data as $key => $value) {
              if(isset($value->vulns)) {
                $dataData = json_encode($value->vulns);
                $dataDataArr = json_decode($dataData);

                if(@is_object($dataDataArr)) {
                  foreach ($dataDataArr as $cveNumber => $cveInfo) {
                    # code...
                    $cvss = $cveInfo->cvss;
                    $ext = '';
                    // $ext = ' => CVSS='.$cvss;
                    if($cvss > 9) {
                      $getData['vuln_crit'][] = $cveNumber.$ext;
                    }
                    elseif($cvss >= 7) {
                      $getData['vuln_hi'][] = $cveNumber.$ext;
                    }
                    elseif($cvss >= 4) {
                      $getData['vuln_me'][] = $cveNumber.$ext;
                    }

                    if($cvss >= 4) {
                      $getData['vuln'][] = $cveNumber.$ext;
                    }
                  }
                }
              }
            }
            $getData['vuln_crit'] = array_unique($getData['vuln_crit']);
            $getData['vuln_hi'] = array_unique($getData['vuln_hi']);
            $getData['vuln_me'] = array_unique($getData['vuln_me']);
            $getData['vuln'] = array_unique($getData['vuln']);
          }
        }
        return $getData;
      }
      else { 
        $scanStatus = false;
        if($getContent) { 
          $dataArr = json_decode($getContent);
          ### code to check for the first data array
          if(@is_array($dataArr->data)) { 
            foreach ($dataArr->data as $key => $value) {
              if($case == 'ssl-date'){
                if(isset($value->ssl->cert)) {
                  if ($value->ssl->cert->expired === false) {
                    $scanStatus = true;
                  }
                }
              }

              if($case == 'cipher-suites'){
                $namesArr = array("ECDHE-RSA-AES256-GCM-SHA384", "TLS_AES_128_GCM_SHA256", "TLS_AES_256_GCM_SHA384 ", "TLS_CHACHA20_POLY1305_SHA256", "TLS_ECDHE_RSA_WITH_AES_128_GCM_SHA256", "TLS_ECDHE_RSA_WITH_AES_256_GCM_SHA384", "TLS_ECDHE_RSA_WITH_CHACHA20_POLY1305_SHA256");
                if(isset($value->ssl->cipher)) {
                  $version = $value->ssl->cipher->version;
                  $name = $value->ssl->cipher->name;
                  if ($version == 'TLSv1/SSLv3' && in_array($name, $namesArr)) {
                    $scanStatus = true;
                  }
                }
              }

              if($case == 'browser-trust'){
                if(isset($value->ssl->trust->browser)) {
                  $browser = $value->ssl->trust->browser;
                  if ($browser->mozilla && $browser->apple && $browser->microsoft) {
                    $scanStatus = true;
                  }
                }
              }

              if($case == 'tls-vuln'){
                if(isset($value->opts->heartbleed)) {
                  $heartbleed = $value->opts->heartbleed;
                  if (strpos(strtolower($heartbleed), strtolower('safe')) !== false) {
                    $scanStatus = true;
                  }
                }
              }
              if($case == 'tls-ver'){
                if(isset($value->ssl->versions)) {
                  $versions = $value->ssl->versions;
                  if(@is_array($versions)) { 
                    $passVersions = ["TLSv1.2", "TLSv1.3"];
                    if (@array_intersect($passVersions, $versions)) {
                      $scanStatus = true;
                    }
                  }
                }
              }

              if($case == 'web-encrypt'){
                if(isset($value->ssl->versions)) {
                  $versions = $value->ssl->versions;
                  if(@is_array($versions)) { 
                    $passVersions = ["TLSv1.2", "TLSv1.3", "TLSv1.1", "TLSv1.0", "TLSv1"];
                    if (@array_intersect($passVersions, $versions)) {
                      $scanStatus = true;
                    }
                  }
                }
              }
            }
          }
        }
        return $scanStatus;
      }
    }

    ## get wfuzz data for cookie and privacy, get status
    public function getWfuzzData($domain_name='', $case='') {
      $scanStatus = false;
      if($case == "cookie-disclaim"){
        $filename = "cookie.txt";
      }
      elseif($case == "privacy-note"){
        $filename = "privacy.txt";
      }
      $command = "wfuzz -o json -L -c -z file,$filename https://$domain_name/FUZZ";

      $wfuzzJsonData = shell_exec($command); 
      $wfuzzJsonData = str_replace('default', '', $wfuzzJsonData); // code remove default from get string
      $wfuzzDataArr = json_decode($wfuzzJsonData);
      if(is_array($wfuzzDataArr)) {
        foreach($wfuzzDataArr as $wfuzzDataInfo){
          if($wfuzzDataInfo->code == 200){
            $scanStatus = true;
            // echo "k=";
          }
        }
      }
      // echo "<br><br>".$wfuzzJsonData;
      return $scanStatus;
    }

    ##  Email Cyber Rating
    public function scanCategory1($case, $grabData, $domain_name='', $domainIP='') {
      $scanStatus = false;

      $nslookupTxt = $grabData['nslookupTxt'];
      $nslookupTxt = strtolower($nslookupTxt);
      $nslookupTxtDmarc = $grabData['nslookupTxtDmarc'];
      $nslookupTxtDmarc = strtolower($nslookupTxtDmarc);

      switch ($case) {
        case "spf-on":
          if (strpos($nslookupTxt, strtolower('v=spf1')) !== false) {
            $scanStatus = true;
          }
          break;

        case "spf-handled":
          if ((strpos($nslookupTxt, strtolower('-all')) !== false)
            || (strpos($nslookupTxt, strtolower('~all')) !== false)) {
            $scanStatus = true;
          }
          break;

        case "dmarc-on":
          if (strpos($nslookupTxtDmarc, strtolower('v=dmarc1')) !== false) {
            $scanStatus = true;
          }
          break;

        case "dmarc-treatment":
          if ((strpos($nslookupTxtDmarc, strtolower('p=reject')) !== false)
            || (strpos($nslookupTxtDmarc, strtolower('p=quarantine')) !== false)) {
            $scanStatus = true;
          }
          break;

        case "smtp-banner":
          $command = "swaks --quit-after banner --to external-user@".$domain_name;
          $getData = shell_exec($command); 
          $getData = strtolower($getData); 
          if ((strpos($getData, strtolower('=== connected to')) !== false)) {
            $scanStatus = true;
          }
          break;

        case "open-relay":
        case "start-tls":
          // $command = "swaks --to external-user@trust-dom.com --from=test@$domain_name --auth --auth-user=test --auth-password=hell-no --server $domain_name";
          // $command = "swaks --to external-user@trust-dom.com --server $domain_name:587 ";
          $command = "swaks --to external-user@trust-dom.com --server $domain_name ";
          $getData = shell_exec($command); 
          $getData = strtolower($getData);  
          
          if($case == "open-relay") {
            if ((strpos($getData, strtolower('=== connected to')) !== false)) {
              $scanStatus = true; 
            }          
          }
          if($case == "start-tls") {
            if ((strpos($getData, strtolower('250-STARTTLS')) !== false)) {
              $scanStatus = true;
            }
          }
          break;

        case "ssl-tlsv":
          // $command = "openssl s_client -connect $domain_name:25 -starttls smtp";
          $command = "openssl s_client -connect $domain_name:443 -prexit | less";
          $getData = shell_exec($command); 
          $getData = strtolower($getData); 
          if ((strpos($getData, strtolower('TLSv1.2')) !== false)
            || (strpos($getData, strtolower('TLSv1.3')) !== false)) {
            $scanStatus = true;
          }
          break;

        default:
            $scanStatus = false;
      }

      $data['scanStatus'] = $scanStatus;
      $data['grabData'] = $grabData;
      return $data;
    }

    ##  Website Cyber Rating
    public function scanCategory2($case, $grabData, $domain_name='', $domainIP='') {
      $scanStatus = false;
      $returnMsg = '';

      $htContent = $grabData['hackertarget'];
      $htContent = strtolower($htContent); 

      $shodanContent = $grabData['shodanApi'];
      $shodanContentObj = json_decode($shodanContent);

      switch ($case) {

        case "web-encrypt":
        case "ssl-date":
        case "cipher-suites":
        case "browser-trust":
        case "tls-vuln":
        case "tls-ver":
          $scanStatus = $this->refineshodanApiData($shodanContent, $case);
          break;

        case "htst-enabled":
          // false if string exist in string
          if (strpos($htContent, strtolower('Strict-Transport-Security: max-age')) !== false) {
            $scanStatus = true;
          }
          break;

        case "xframe-option":
          // false if string exist in string
          if ((strpos($htContent, strtolower('X-Frame-Options: SAMEORIGIN')) !== false)
            || (strpos($htContent, strtolower('X-Frame-Options: deny')) !== false)
            || (strpos($htContent, strtolower('X-Frame-Options: Allow-from')) !== false)
          ) {
            $scanStatus = true;
          }
          break;

        case "xss-protection":
          // false if string exist in string
          if ((strpos($htContent, strtolower('X-XSS-Protection: 1; mode=block')) !== false)
            || (strpos($htContent, strtolower('X-XSS-Protection: 1')) !== false)
            || (strpos($htContent, strtolower('X-XSS-Protection: 1: report')) !== false)
          ) {
            $scanStatus = true;
          }
          break;

        case "mime-type":
          // false if string exist in string
          if ((strpos($htContent, strtolower('X-Content-Type-Options: nosniff')) !== false)
          ) {
            $scanStatus = true;
          }
          break;

        case "content-policy":
          // false if string exist in string
          if ((strpos($htContent, strtolower('Content-Security-Policy:')) !== false)
          ) {
            $scanStatus = true;
          }
          break;

        case "referrer-policy":
          // false if string exist in string
          if ((strpos($htContent, strtolower('Referrer-Policy:')) !== false)
          ) {
            $scanStatus = true;
          }
          break;

        case "cache-control":
          // false if string exist in string
          if ((strpos($htContent, strtolower('Cache-Control:')) !== false)
          ) {
            $scanStatus = true;
          }
          break;

        case "cross-domain":
          // false if string exist in string
          if ((strpos($htContent, strtolower('x-permission cross domain policy')) !== false)
          ) {
            $scanStatus = true;
          }
          break;

        case "expect-ct":
          // false if string exist in string
          if ((strpos($htContent, strtolower('Expect-CT:')) !== false)
          ) {
            $scanStatus = true;
          }
          break;

        case "server-header":
          // false if string exist in string
          $scanStatus = true;
          if ((strpos($htContent, strtolower('Server:')) !== false)
          ) {
            $scanStatus = false;
          }
          break;

        case "xpowered-by":
          // false if string exist in string
          $scanStatus = true;
          if ((strpos($htContent, strtolower('X-Powered-By:')) !== false)
          ) {
            $scanStatus = false;
          }
          break;

        case "secure-flag":
          // false if string exist in string
          if ((strpos($htContent, strtolower('Set-Cookie:')) !== false)
            && (strpos($htContent, strtolower('Secure')) !== false)
          ) {
            $scanStatus = true;
          }
          break;

        case "samesite": 
          // false if string exist in string
          if ((strpos($htContent, strtolower('Set-Cookie:')) !== false)
            && (strpos($htContent, strtolower('SameSite=')) !== false)
          ) {
            $scanStatus = true;
          }
          break;

        case "http-flag":
          // false if string exist in string
          if ((strpos($htContent, strtolower('Set-Cookie:')) !== false)
            && (strpos($htContent, strtolower('HttpOnly')) !== false)
          ) {
            $scanStatus = true;
          }
          break;

        case "port-services":
          $ports = json_encode($shodanContentObj->ports);
          $portsArr = json_decode($ports);
          if(@is_array($portsArr)){
            $googPorts = array(443,143,80,8080,53);
            foreach ($googPorts as $port) {
              if (($key = array_search($port, $portsArr)) !== false) {
                unset($portsArr[$key]);
              }
            }
          }
          else{
            $portsArr = array();
          }

          ## checking bad ports
          if(count($portsArr) < 1) {
            $scanStatus = true;
          }
          else{
            $returnMsg = implodeArray($portsArr);
          }
          break;

        case "cookie-disclaim":
        case "privacy-note":
          $scanStatus = $this->getWfuzzData($domain_name, $case);
          break;

        default:
            $scanStatus = false;
      }

      $data['returnMsg'] = $returnMsg;
      $data['scanStatus'] = $scanStatus;
      $data['grabData'] = $grabData;
      return $data;
    }

    ## Compromised Cyber Rating
    public function scanCategory3($case, $grabData, $domain_name='', $domainIP='') {
      $scanStatus = false;

      $neutrinoContent = $grabData['neutrinoApi'];
      $neutrinoContentArr = json_decode($neutrinoContent);
      
      switch ($case) {
        case "is-proxy":
        case "is-tor":
        case "is-vpn":
        case "is-malware":
        case "is-spyware":
        case "is-dshield":
        case "is-hijacked":
        case "is-spider":
        case "is-bot":
        case "is-spam-bot":
        case "is-exploit-bot":
          if($neutrinoContentArr->$case == false) {
            $scanStatus = true;
          }
          break;

        default:
            $scanStatus = false;
      }

      $data['scanStatus'] = $scanStatus;
      $data['grabData'] = $grabData;
      return $data;
    }

    public function scanCategory4($case, $grabData, $domain_name='', $domainIP='') {
      $scanStatus = false;
      $returnMsg = '';
 
      $shodanContent = $grabData['shodanApi'];
      $refineData = $this->refineshodanApiData($shodanContent, 'vulns');
      switch ($case) {

        case "vuln-crit":
          if(count($refineData['vuln_crit']) > 0) {
            $returnMsg = implodeArray($refineData['vuln_crit']);
          }
          else{
            $scanStatus = true;
          }
          break;

        case "vuln-hi":
          if(count($refineData['vuln_hi']) > 0) {
            $returnMsg = implodeArray($refineData['vuln_hi']);
          }
          else{
            $scanStatus = true;
          }
          break;

        case "vuln-me":
          if(count($refineData['vuln_me']) > 0) {
            $returnMsg = implodeArray($refineData['vuln_me']);
          }
          else{
            $scanStatus = true;
          }
          break;

        default:
          $scanStatus = false;
      }

      $data['returnMsg'] = $returnMsg;
      $data['scanStatus'] = $scanStatus;
      $data['grabData'] = $grabData;
      return $data;
    }

    ## Data-Privacy Cyber Rating
    public function scanCategory5($case, $grabData, $domain_name='', $domainIP='') {
      $scanStatus = false;
      $returnMsg = '';

      $htContent = $grabData['hackertarget'];
      $htContent = strtolower($htContent); 
      $shodanContent = $grabData['shodanApi'];

      switch ($case) {
        case "cookie-disclaim":
        case "privacy-note":
          $scanStatus = $this->getWfuzzData($domain_name, $case);
          break;

        case "compromised-site":
          $neutrinoContent = $grabData['neutrinoApi'];
          $neutrinoContentArr = json_decode($neutrinoContent);

          if(is_array($neutrinoContentArr)) {
            if($neutrinoContentArr['is-proxy'] == false
              && $neutrinoContentArr['is-tor']  == false
              && $neutrinoContentArr['is-vpn']  == false
              && $neutrinoContentArr['is-malware']  == false
              && $neutrinoContentArr['is-spyware']  == false
              && $neutrinoContentArr['is-dshield']  == false
              && $neutrinoContentArr['is-hijacked']  == false
              && $neutrinoContentArr['is-spider'] == false
              && $neutrinoContentArr['is-bot'] == false
              && $neutrinoContentArr['is-spam-bot'] == false
              && $neutrinoContentArr['is-exploit-bot'] == false
            ) {
              $scanStatus = true;
            }
          }
          // $refineData = $this->refineshodanApiData($shodanContent, 'vulns');
          // if(count($refineData['vuln_me']) > 0) {
          //   $returnMsg = implodeArray($refineData['vuln_me']);
          // }
          // else{
          //   $scanStatus = true;
          // }
          // break;

        case "vulnerability-site":
          $refineData = $this->refineshodanApiData($shodanContent, 'vulns');
          if(count($refineData['vuln']) > 0) {
            $returnMsg = implodeArray($refineData['vuln']);
          }
          else{
            $scanStatus = true;
          }
          break;

        case "cookie-secure":
          // false if string exist in string
          if ((strpos($htContent, strtolower('Set-Cookie:')) !== false)
            && (strpos($htContent, strtolower('Secure')) !== false)
            && (strpos($htContent, strtolower('SameSite=')) !== false)
            && (strpos($htContent, strtolower('HttpOnly')) !== false)
          ) {
            $scanStatus = true;
          }

        case "data-encryp":
          $scanStatus = $this->refineshodanApiData($shodanContent, 'tls-ver');
          break;

        default:
            $scanStatus = false;
      }

      $data['returnMsg'] = $returnMsg;
      $data['scanStatus'] = $scanStatus;
      $data['grabData'] = $grabData;
      return $data;
    }

    ## Breacech Email Cyber Rating
    /*
    public function scanCategory6($case, $grabData, $domain_name='', $domainIP='') {
      $scanStatus = false;
      $returnMsg = '';

      $dehashedContent  = $grabData['dehashedApi']; 

      switch ($case) {
        case "breached-account":
          if($dehashedContent) {
            $dehashedObj = json_decode($dehashedContent);
            if(isset($dehashedObj->total)) {
              $total = $dehashedObj->total;
              if($total >= 220){
                $scanStatus = true;
              }
              $returnMsg = $total . " user(s) found for singsys.com";
            }
          }
          break;
        default:
            $scanStatus = false;
      }

      $data['returnMsg'] = $returnMsg;
      $data['scanStatus'] = $scanStatus;
      $data['grabData'] = $grabData;
      return $data;
    }
    */
}