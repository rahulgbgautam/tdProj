awdhesh<?php

// phpinfo();

function checkDomainOnCdn($domain_name){
    echo $getHostInformation = shell_exec('host -a '.$domain_name);
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

echo checkDomainOnCdn('www.visitbritain.com');

die('llll'); 

error_reporting(E_ALL);
ini_set('display_errors', 1);

$domain_name = 'singsys.com';
// echo $command = "swaks --to external-user@trust-dom.com --server $domain_name ";

// echo "<br><br>";
// echo $command = "host -a singsys.com";
//           $getData = shell_exec($command); 
//           $getData = strtolower($getData);  

//           echo $getData;

// echo "<br><br>";
// echo $command = "host -a contactlensxchange.com";
//           $getData = shell_exec($command); 
//           $getData = strtolower($getData);  

//           echo $getData;

echo "<br><br>1111";
echo "<br><br>";
echo $command = "wfuzz -L -c -z file,privacy.txt https://$domain_name/FUZZ";
          $getData = shell_exec($command); 
          $getData = strtolower($getData);  

          echo $getData;


echo "<br><br>2222";
echo "<br><br>";
echo $command = "wfuzz -L -c -z file,cookie.txt https://$domain_name/FUZZ";
          $getData = shell_exec($command); 
          $getData = strtolower($getData);  

          echo $getData;

echo "<br><br>";
echo $command = "python wfuzz.py -c -z file,/var/www/html/trustdom/privacy.txt https://$domain_name/FUZZ ";
          $getData = shell_exec($command); 
          $getData = strtolower($getData);  

          echo $getData;
            

echo "LLLLLLLLLL";
die();

die(); 
function neutrinoApiData($domainIP){
      $neutrino_api_userid = 'gulshan.singsys';
      $neutrino_api_key = '2BtXzzP24KlPnNH4NSE23Qnwo6kWap2PVmceyocsssp7cz0s'; 
      // $neutrino_api_userid = getGeneralSetting('neutrino_api_userid'); 
      // $neutrino_api_key = getGeneralSetting('neutrino_api_key'); 
      
      $url = 'https://neutrinoapi.net/ip-blocklist?user-id='.$neutrino_api_userid.'&api-key='.$neutrino_api_key.'&ip='.$domainIP;

      $getContent = file_get_contents($url);
      return $getContent;
    }

    $domainIP = '52.221.172.188'; 
    $neutrinoContent = neutrinoApiData($domainIP);
      $neutrinoContentArr = json_decode($neutrinoContent);

      if($neutrinoContentArr->is-proxy == false
            && $neutrinoContentArr->is-tor  == false
            && $neutrinoContentArr->is-vpn  == false
            && $neutrinoContentArr->is-malware  == false
            && $neutrinoContentArr->is-spyware  == false
            && $neutrinoContentArr->is-dshield  == false
            && $neutrinoContentArr->is-hijacked  == false
            && $neutrinoContentArr->is-spider == false
            && $neutrinoContentArr->is-bot == false
            && $neutrinoContentArr->is-spam-bot == false
            && $neutrinoContentArr->is-exploit-bot == false
          ) {
        echo "LLLLLLL222";
            $scanStatus = true;
          }
die();