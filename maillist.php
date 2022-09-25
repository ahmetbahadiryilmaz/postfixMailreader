<?
include "includes/e.php";
$uname=$_GET["uname"];
$upw=$_GET["upw"];
$domain=$_GET["domain"];
if(!$domain) exit( json_encode( array("hata"=>"domain giriniz")  ));
 echo json_encode(

    $e->db->fetchAll("select username,password,domain,created from mailbox where domain=:domain",array("domain"=>$_GET["domain"]) )
) ;

