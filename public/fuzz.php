Awdhesh7
<?php

$domain_name = 'facebook.com';
$domain_name = 'singsys.com';

// SMTP Banner
// swaks --quit-after banner --to external-user@domain.me --output-file savefile.xml

// Open Relay
// swaks --to external-user@trust-dom.com --from=test@domain.com --auth --auth-user=test --auth-password=hell-no --server domain.com --output-file savefile.xml

// START-TLS
// swaks --to external-user@trust-dom.com --from=test@domain.com --auth --auth-user=test --auth-password=hell-no --server domain.com --output-file savefile.xml

// SSL-TLSv
// openssl s_client -connect domain.com:25 -starttls smtp

// Cookie-Disclaim 
// wfuzz -c -z file,cookie.txt https://domain.com/ FUZZ

// Privacy-note
// wfuzz -c -z file,privacy.txt https://domain.com/ FUZZ

// echo $command = "wfuzz -c -z file,privacy.txt https://$domain_name/ FUZZ";


// ===================================
echo "<br><br><br>kkkkk=<br>";
// $command = "openssl s_client -connect $domain_name:25 -starttls smtp";
$command = "openssl s_client -connect $domain_name:443 -prexit | less";
$getData = shell_exec($command); 

echo $command;
echo "<br><br>response=<br>";
echo $getData;


// ===================================
echo "<br><br><br>kkkkk=<br>";
$command = "swaks --quit-after banner --to external-user@".$domain_name;
$getData = shell_exec($command); 

echo $command;
echo "<br><br>response=<br>";
echo $getData;


// ===================================
echo "<br><br><br>kkkkk=<br>";
$command = "swaks --to external-user@trust-dom.com --from=test@$domain_name --auth --auth-user=test --auth-password=hell-no --server $domain_name";
$getData = shell_exec($command); 

echo $command;
echo "<br><br>response=<br>";
echo $getData;



// echo shell_exec("openssl s_client -connect $domain_name:25 -starttls smtp");  