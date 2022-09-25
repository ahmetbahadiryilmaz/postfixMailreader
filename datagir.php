<?

include "includes/e.php";

$e->db2 = new dbController("localhost","qrmatik_accounts","qrmatik","1234567x0536");
file_put_contents("xxc.json",$_POST["email"]);

if(!isset( $_POST["fname"] )){exit("fname yok");}

 print_r($e->db2->insert("accounts",
                 array(
                     'fullname' => $_POST["fname"] ,
                     'username' => $_POST["username"] ,
                     'password' => $_POST["password"] ,
                     'email' =>    $_POST["email"],
                     'cookie' =>   $_POST["cookie"],
                     'useragent' => $_POST["useragent"]
                     ))) ;





?>