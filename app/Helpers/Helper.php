<?php
use App\Models\GeneralSetting;
use App\Models\EmailTemplate;
use App\Models\User;
use App\Models\Domains;
use App\Models\DomainScan;
use App\Models\DomainsUser;
use App\Models\ProbsCategory;
use App\Models\OverallRatingMessages;
use App\Models\Industry;
use App\Models\AssignRole;
use Carbon\Carbon;
use DB;
use App\Models\subscription;

function getExpiryDate($userid){
  $current_date = date('Y-m-d');
  $subscription = subscription::where('user_id',$userid)->where('subscription_type','Membership')->orderBy('expire_date','desc')->first();

    if($subscription['expire_date'] > $current_date){
      $current_date = $subscription['expire_date'];
    }
    $add_days = getGeneralSetting('free_access_days');
    // dd($add_days);

    $expiry_date = date("Y-m-d", strtotime("+$add_days days", strtotime($current_date)));
  return $expiry_date;
}

function implodeArray($dataArray){
  if(@is_array($dataArray)) {
    return @implode(', ', $dataArray);
  }
  return '';
}

function menuName(){
   return $menuArray = array(
           'admin-users' => 'Manage Admin Users',
           'portal-users' => 'Manage Portal Users',
           'transaction-history' => 'Transaction History',
           'domains' => 'Domains',
           'probs-category' => 'Probs Category',
           'probs-sub-category' => 'Probs Sub Category',
           'email-management' => 'Email Management',
           'content-management' => 'Content Management',
           'dynamic-content' => 'Dynamic Content',
           'banner-management' => 'Banner Management',
           'features-management' => 'Features Management',
           'faq' => 'FAQ',
           'manage-industry' => 'Manage Industry',
           'manage-avg-rating-text' => 'Manage Avg Rating Text',
           'news-letter' => 'News Letter',
           'promo-code' => 'Promo Code',
           'general-settings' => 'General Settings',
          );
}

function addPermissionForSuperAdmin(){
      $superAdminInfo = User::where('type',"super_admin")->get();
      $super_admin_id = $superAdminInfo[0]->id;
      $menuArray =  menuName();
      foreach ($menuArray as $key => $value) {
          $roles = new AssignRole;
          $roles->user_id = $super_admin_id;
          $roles->menu_key = $key;
          $roles->read = "1";
          $roles->write = "1";
          $roles->save();
      }                       
}

function menuPermissionByType($user_id,$field){
      $menuObj = AssignRole::where('user_id',$user_id)
                              ->where($field,'1')
                              ->pluck('menu_key');
      $menuArr = json_decode(json_encode($menuObj));                       
      return $menuArr;                           
}


function time_elapsed_string($datetime, $full = false) {
    if($datetime == null) {
      return 'Never Login';
    }

    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function getTrandingChartData($user_id, $domain_id='', $type='') {
  $xAxis = array();
  $yAxis = array();

  $current_date = date('Y-m-d');
  $average_score = 0;
  for($i = 3; $i >= 0; $i--){
    $strDate = strtotime("-".(($i*3)-1)." month", strtotime($current_date));
    $date = date("Y-m-01", $strDate);

    // $query = DomainScan::select('average_score');
    $query = DomainScan::select(DB::raw('ROUND(AVG(ds_domain_scan.average_score), 0) as average_score'));
    if($domain_id) {
      $query->where('domain_id',$domain_id);
    }
    if($type) { 
      $query->join('ds_domains','ds_domain_scan.domain_id','=','ds_domains.id');
      $query->join('ds_domain_users','ds_domain_users.domain_id','=','ds_domains.id');
      $query->where('ds_domain_users.user_id',$user_id);
      $query->where('ds_domain_users.type',$type);
    }
    $query->where('ds_domain_scan.scan_date', '<=', $date);
    $query->orderBy('ds_domain_scan.scan_date', 'DESC');
    $domainScanInfo = $query->first();

    if($domainScanInfo['average_score'] > 0) {
      $average_score = $domainScanInfo['average_score']; 
      $average_score = intval($average_score);        
    }

    if($average_score > 0) {
      $strDate2 = strtotime("-".($i*3)." month", strtotime($current_date));
      $xAxis[] = date("M y", $strDate2);    
      $yAxis[] = $average_score; 
    }   
  }
  
  $chartData['xAxis'] = json_encode($xAxis);
  $chartData['yAxis'] = json_encode($yAxis);

  return $chartData;
}

function refineDomain($domain){
  $domain = strtolower(trim($domain));
  $domain = str_replace("http://", '', $domain);
  $domain = str_replace("https://", '', $domain);
  $domain = str_replace("www.", '', $domain);
  return $domain;
}

function generateInvoiceNumber($subscription_id, $date){
    $num = $subscription_id;
    $invoiceYear = date('Y', strtotime($date));
    $invoiceNumber = sprintf($invoiceYear."%06d",$subscription_id);
    return $invoiceNumber;
}

function checkAndAddDomain($domain_name, $industry=''){
  $domainInfo = Domains::where('domain_name',$domain_name)->first();
  if(!$domainInfo){
      //insert data in domain data
      $domainInfo = new Domains;
      $domainInfo->domain_name = $domain_name;
      if($industry) {
        $domainInfo->industry = $industry;
      }
      $domainInfo->average_score = '0';
      $domainInfo->save();
  }
  return $domainInfo;
}
function checkDomainInTable($domain_name){
  $domainInfo = Domains::where('domain_name',$domain_name)->first();
  if($domainInfo){
      return '1';
  }
}

function checkDomainStatus($domain_name){
  // dd($domain_name);
  $domainInfo = Domains::where('domain_name',$domain_name)->where('status','Inactive')->first();
  if($domainInfo){
    return 'Inactive';
  }
  return 'Active';
}

function checkAndAddDomainUser($domain_id, $userid, $ptype, $value){
  $domainsUserInfo = DomainsUser::where('domain_id', $domain_id)
    ->where('user_id', $userid)
    ->first();
  
  $current_date = date('Y-m-d');

  if($domainsUserInfo){
    if($domainsUserInfo->expiry_date > $current_date) {
      $current_date = $domainsUserInfo->expiry_date;
    }
  }

  if($ptype == 'month'){
      $expiry_date = date("Y-m-d", strtotime("+1 month", strtotime($current_date)));
  }else if($ptype == 'adminfield'){
      $add_days = getGeneralSetting('signup_access_(in_days)');
      $expiry_date = date('Y-m-d',strtotime($current_date) + (24*3600*$add_days));
  }else{
      $expiry_date = date("Y-m-d", strtotime("+1 year", strtotime($current_date)));
  }


  $added_as = isset($value['added_as'])?$value['added_as']:'C';
  $type = isset($value['type'])?$value['type']:1;
  $industry = isset($value['industry'])?$value['industry']:0;
  $subscription_id = isset($value['subscription_id'])?$value['subscription_id']:0;
  $auto_payment = isset($value['auto_payment'])?$value['auto_payment']:'No';
  
  if($domainsUserInfo){
    DomainsUser::where('id', $domainsUserInfo->id)
        ->update([
          'expiry_date'=>$expiry_date, 
          'type'=> $type, 
          'industry'=> $industry,
          'auto_payment'=> $auto_payment,
          'subscription_id'=>$subscription_id
        ]);
  }
  else{
    //insert data in domain table
    $domainsUserInfo = new DomainsUser;
    $domainsUserInfo->domain_id = $domain_id;
    $domainsUserInfo->user_id = $userid;
    $domainsUserInfo->added_as = $added_as;
    $domainsUserInfo->auto_payment = $auto_payment;
    $domainsUserInfo->type = $type;
    $domainsUserInfo->industry = $industry;
    $domainsUserInfo->subscription_id = $subscription_id;
    $domainsUserInfo->expiry_date = $expiry_date;
    $domainsUserInfo->save();
  }

  return $domainsUserInfo;
}

function showDate($date){
  $dateFormat = 'd/m/Y';
  if(in_array($date,  array('0000-00-00', '0000-00-00 00:00:00', '', NULL) )){
    // return date($dateFormat);
    return '_';
  }
  else {
    return date($dateFormat, strtotime($date));
  }
}

function getTypes(){
  return $types = array(
    '1' => 'My Brands',
    '2' => '3rd Party Domain',
  );
}

function categoryMgsByGrade($category_name,$grade){
  $grade_col = 'grade_'.strtolower($grade);
  $message = ProbsCategory::where('category_name',$category_name)->first();
  return $message[$grade_col];
}

function getTypeName($id){
  $types = getTypes();
  if(isset($types[$id])) {
    return $types[$id];
  }
}

function speedMeterImage($average_score){
  $getRating = getRating($average_score);
  $speedMeterImgName = "speed-meter-".$getRating.".png";

  if(!file_exists('img/'.$speedMeterImgName)) {
    $speedMeterImgName = "speed-meter.png";
  }
  $speedMeterImage = asset("img/".$speedMeterImgName);
  return $speedMeterImage;
}

function getIndustries(){
  $industries=[];
  $data=Industry::where('is_deleted','0')->where('status','Active')->get();
  foreach ($data as $value) {
        array_push($industries,$value->industry_name);
  }
  return $industries;
}
function getIndustriesNew(){
  $industries=[];
  $data=Industry::where('is_deleted','0')->where('status','Active')->get();
  // foreach ($data as $value) {
  //       array_push($industries,$value->industry_name);
  // }
  return $data;
}

function getIndustryName($id){
  $industries = getIndustries();
  if(isset($industries[$id])) {
    return $industries[$id];
  }
}

function getRating($score=0){
  // if($score < 1) {
  //   return '-';
  // }
  // if($score >= 80) {
  //   return 'A';
  // }
  // elseif($score >= 65) {
  //   return 'B';
  // }
  // elseif($score >= 55) {
  //   return 'C';
  // }
  // elseif($score >= 45) {
  //   return 'D';
  // }
  // else {
  //   return 'E';
  // }
  $result = getRatingInfoByScore($score);
  return $result['grade'];

}

function getRatingInfoByScore($score){
  $result = OverallRatingMessages::where('min_score', '<=' ,$score)
    ->orderBy('min_score', 'DESC')
    ->first();
  if(!$result) {
    $result['performance'] = '';
    $result['message'] = '';
    $result['grade'] = '-';
  }
  return $result;
}

function getMessageByGrade($grade){
  $result = OverallRatingMessages::where('grade',$grade)->first();
  if(!$result) {
    $result['performance'] = '';
    $result['message'] = '';
    $result['grade'] = '-';
  }
  return $result;
}

function lastScanReminder($date){
  if($date){
    $add_days = getGeneralSetting('reminder_for_last_scan(in_days)');
    $effectiveDate = date('Y-m-d',strtotime($date) + (24*3600*$add_days));

    if(strtotime($effectiveDate) <= strtotime(date('Y-m-d'))){
      return 'Yes';
    }
  }
  return 'No';
}
function expiryDateReminder($date){
  if($date){
    $add_days = getGeneralSetting('reminder_for_expiry_date(in_days)');
    $currentDate = date('Y-m-d',strtotime(date('Y-m-d')) + (24*3600*$add_days));
    if(strtotime($date) <= strtotime($currentDate)){
      return 'Yes';
    }
  }
  return 'No';
}

function getRatingValue($grade){
  $performance = '';
  // if($grade == 'A') {
  //   $performance = 'Excellent';
  // }
  // elseif($grade == 'B') {
  //   $performance = 'Good';
  // }
  // elseif($grade == 'C') {
  //   $performance = 'Fair';
  // }
  // elseif($grade == 'D') {
  //   $performance = 'Poor';
  // }
  // elseif($grade == 'E') {
  //   $performance = 'Critical';
  // }
  if($grade){
    $result = getMessageByGrade($grade);
    if($result){
      $performance = ucfirst(trim($result['performance']));
    }
  }
  return $performance;
}

function getRatings(){
  return array('A', 'B', 'C', 'D', 'E');
}

function getRatingClass($grade){
  $class = '';
  if($grade == 'A') {
    $class = 'badge-excellent';
  }
  elseif($grade == 'B') {
    $class = 'badge-good';
  }
  elseif($grade == 'C') {
    $class = 'badge-fair';
  }
  elseif($grade == 'D') {
    $class = 'badge-poor';
  }
  elseif($grade == 'E') {
    $class = 'badge-critical';
  }
  elseif($grade == '-') {
    $class = 'badge-excellent';
  }
  return $class;
}

function uploadImage($imageInfo, $folderName = '') {
  $imageName = '';
  if ($imageInfo->getClientOriginalName()) {
    $uploadFolder = "uploads";
    if ($folderName != '') {
      $uploadFolder .= '/' . $folderName;
    }
    $imageName = time() . '-' . $imageInfo->getClientOriginalName();
    $imageName = preg_replace('/[^A-Za-z0-9.]/', '-', $imageName);
    $imageInfo->move(public_path($uploadFolder), $imageName);
  }
  return $imageName;
}

### function to show image
function showImage($imageName, $folderName = '') {
  // dd($imageName, $folderName);
  //if ($imageName) {
  $uploadFolder = "uploads/";
  if ($folderName != '') {
    $uploadFolder .= $folderName . '/';
  }
  $imageAbsolutePath = public_path($uploadFolder . $imageName);
  if (file_exists($imageAbsolutePath) && $imageName != '') {
    $imageFullPath = URL::asset($uploadFolder . $imageName);
  } else {
    $imageFullPath = URL::asset($uploadFolder . 'noimage.png');
  }
  //}
  return $imageFullPath;
}


### function to delete image
function unlinkImage($imageName, $folderName = '') {
  if ($imageName) {
    $uploadFolder = "uploads/";
    if ($folderName != '') {
      $uploadFolder .= $folderName . '/';
    }
    $imageAbsolutePath = public_path($uploadFolder . $imageName);
    if (file_exists($imageAbsolutePath)) {
      unlink($imageAbsolutePath);
    }
  }
}


### function to get email
function sendEmail($userInfo, $template_code, $data = array()) {
  $email  = $userInfo['email'];
  $name   = $userInfo['name'];
  $template = EmailTemplate::where('variable_name',$template_code)->first();

  $page_title = getGeneralSetting('page_title');
  $defaultData['page_title'] = $page_title;
  $defaultData['help_email'] = getGeneralSetting('help_email');
  $defaultData['site_link'] = getGeneralSetting('site_link');

  $data = array_merge($data, $defaultData);

  if (!empty($template)) {
    $variables = explode(',', $template->variable);
    $subject = $template->title;
    $body = $template->description;

    foreach ($variables as $item) { 
      $item = trim($item); 
      $keyIndex = str_replace(array('{', '}'), '', $item);
      if(isset($data[$keyIndex])) {
        $subject = str_replace($item, $data[$keyIndex], stripslashes(html_entity_decode($subject)));
        $body = str_replace($item, $data[$keyIndex], stripslashes(html_entity_decode($body)));
        // $body = nl2br($body);
      }
      else{
        $subject = str_replace($item, '', stripslashes(html_entity_decode($subject)));
        $body = str_replace($item, '', stripslashes(html_entity_decode($body)));
      }
    }

    $sender = [
      'subject' => $subject,
      'email' => $email,
      'name' => $name,
      'from' => ['name' => $page_title, 'address'=>config('app.sender_email')]
    ];

    if(!empty($body) && !empty($email)){
      $bodyHtml = '<body style="font-family: arial; margin: 0px; letter-spacing: 0.5px; line-height: 1.6;">
        <div class="mailer" style="border: 1px solid #f5f5f5; background-color: #f7f7f7;  padding: 50px; margin: 0 auto; width: 700px;">
            <table class="table" width="100%" cellpadding="0" cellspacing="0" bgcolor="#ffffff">
                <thead>
                    <tr>
                        <th style="background: #ffffff; width: 100%; padding: 30px;">
                           <img style="width: 200px;" src="'.asset('images/logo.png').'" />
                        </th>
                    </tr>
                </thead>
                <tbody>
                  <tr><td style="color: #000000; font-size: 14px; padding: 15px 20px;">'.nl2br($body).'</td></tr>
                  <tr>
                      <td style="color: #000000; font-size: 14px; padding: 15px 20px 40px;">Thanks,<br>
                      '.getGeneralSetting('page_title').'
                      </td>
                  </tr>
                  <tr><td style="height: 40px;"></td></tr>
                  <tr style="background-color: #f7f7f7; color: #000000; font-size: 14px; text-align: center;">
                    <td style="padding: 30px 20px 0;">'.getGeneralSetting('copyright').'</td>
                  </tr>
                </tbody>
            </table>
        </div>
    </body>';                   

      Mail::send('emails.default', ['body' => $bodyHtml], function($message) use ($sender){
          $message->to(
            $sender['email'],
            $sender['name']
          )
          ->subject($sender['subject'])
          ->from(
            $sender['from']['address'],
            $sender['from']['name']
          );
      });
    }

    /*$smtp_host      = getGeneralSetting('smtp_host');
    $smtp_port      = getGeneralSetting('smtp_port');
    $smtp_email     = getGeneralSetting('smtp_email');
    $smtp_password  = getGeneralSetting('smtp_password');

    Config::set('mail.driver', 'smtp');
    Config::set('mail.host', $smtp_host);
    Config::set('mail.port', $smtp_port);
    Config::set('mail.from', array('address' => $smtp_email, 'name' => $page_title));
    Config::set('mail.encryption', 'tls');
    Config::set('mail.username', $smtp_email;
    Config::set('mail.password', $smtp_password);

    $sender['subject'] = $subject;
    $sender['email'] = $email;
    $sender['name'] = $name;
    $sender['from'] = ['address' => $smtp_email, 'name' => $page_title];

    if (!empty($body) && !empty($email)) {
      Mail::send('emails.default', ['body' => nl2br($body)], function ($message) use ($sender) {
        $message->to(
          $sender['email'],
          $sender['name']
        )
          ->subject($sender['subject'])
          ->from(
            $sender['from']['address'],
            $sender['from']['name']
          );
      });

    }
    */
  }

}


### general get setting value on basis of the passed title
function getGeneralSetting($title) {
  $settingInfo = GeneralSetting::where('title', $title)->first();
  if (isset($settingInfo)) {
    return trim($settingInfo->value);
  }
  return '';
}

function getDomainCountByType($type){
  $domainsCount = 0;
  if(Auth::check()) {
    $user_id = auth::id();
    $domainsCount = Domains::select('domain_id')
      ->join('ds_domain_users','ds_domain_users.domain_id','=','ds_domains.id')
      ->where('ds_domain_users.user_id',$user_id)
      ->where('ds_domain_users.type', $type)
      // ->groupBy('ds_domain_users.domain_id')
      ->count();
  }
  $domainsCount = sprintf("%02d", $domainsCount);
  return $domainsCount;
}

function getProfile($id,$data=""){
  if(Auth::check()) {     
      $userData = User::find($id);
      if($data==""){
        if($userData->profile_image){
          return showImage($userData->profile_image);  
        }else{
            return asset('img/default-icon.png');
        }
      }else{
        return ucwords($userData->name);  
      }
  }
}

function sendEmailMailChimp($email) {
    $list_id = '211212';
    $api_key = '12122121';

    $data_center = substr($api_key, strpos($api_key, '-') + 1);

    $url = 'https://' . $data_center . '.api.mailchimp.com/3.0/lists/' . $list_id . '/members';
    $userInfo = User::where('email', $email)->first();
    if (!$userInfo) {
      $f_name = '';
      $l_name = '';
    } else {
      $f_name = $userInfo->name;
      $l_name = $userInfo->l_name;
    }
    $json = json_encode([
      'email_address' => $email,
      'status' => 'pending', //pass 'subscribed' or 'pending'
      'merge_fields' => [
        'FNAME' => $f_name,
        'LNAME' => $l_name,
      ],
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $api_key);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    $result = curl_exec($ch);
    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    // dd($status_code);
}


class Helper{
  public static function send_email($email,$fullname,$template_code,$data) {
        $template = EmailTemplate::where('variblename',$template_code)->first();
        if(!empty($template)){
            $variables  = explode(',',$template->varibles);
            $subject    = $template->subject;
            $body       = $template->body;
            foreach ($variables as $item) {
                $subject = str_replace($item,$data[str_replace(array('{','}'),'', $item)],stripslashes(html_entity_decode($subject)));
                $body = str_replace($item,$data[str_replace(array('{','}'),'', $item)],stripslashes(html_entity_decode($body)));
            }
            $sender = [
              'subject' => $subject,
              'email' => $email,
              'name' => $fullname,
              'from' => ['name' => $data['title'],'address'=>config('app.sender_email')]
            ];
           // dd(config('app.sender_email'));
            if(!empty($body) && !empty($email)){
                Mail::send('emails.mail', ['body' => $body], function($message) use ($sender){
                    $message->to(
                    $sender['email'],
                    $sender['name']
                    )
                    ->subject($sender['subject'])
                    ->from(
                     $sender['from']['address'],
                     $sender['from']['name']
                    );
                });
            }
        }
    }
}

