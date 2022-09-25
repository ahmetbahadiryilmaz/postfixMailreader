<?

include "includes/e.php";
$uname=$_GET["uname"];
$upw=$_GET["upw"];
$domain=$_GET["domain"];
if(!$domain)exit(json_encode(array("hata"=>"domaingirilmedi" )));


$domainvarmi= $e->db->fetch("select * from domain where domain=:domain",array("domain"=>$domain) );


if(!isset($domainvarmi["domain"])){
    echo "domain yoktu eklendi";
$e->db->insert("domain",
                 array(
                     'domain' => $domain,
                     'description' => "" ,
                     'aliases' => "0" ,
                     'mailboxes' => "0",
                     'maxquota' =>"0",
                     'quota' => "0",
                     'transport' => "",
                     'backupmx' => "0",
                     'created' => '2019-04-23 19:52:54',
                     'modified' => '2019-04-23 19:52:54',
                     'active' => 1
                     ));
}
 print_r($e->db->insert("mailbox",
                 array(
                     'username' => $uname."@".$domain,
                     'name' => "" ,
                     'password' => "{PLAIN-MD5}".md5($upw) ,
                     'maildir' => $domain."/".$uname."/" ,
                     'local_part' =>$uname,
                     'domain' => $domain,
                     'active' => 1
                     ))) ;

/*
$socket = fsockopen("http://qrmatik.com",2082);
$cuser = "qrmatik";
$cpassword = "1234567x0536";
$authstr = base64_encode("".$cpuser.":".$cppass."");
$in = "GET /frontend/$cpskin/mail/doaddpop.html?email=$euser&$edomain&password=$epass&quota=$equota
HTTP/1.0\r\nAuthorization: Basic $authstr \r\n";
fputs($socket,$in);
fclose( $socket );
*/
?>