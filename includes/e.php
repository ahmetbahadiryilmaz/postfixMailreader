<?

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR | E_WARNING | E_PARSE);
class e{

}
include __DIR__."/dbController.php";


$e = new e();
$e->db = new dbController("localhost","postfix","postfix","e80dZqnRCH6z");




?>