<?

include "includes/e.php";

$username=$_GET["uname"];
$password=$_GET["upw"];
$domain=$_GET["domain"];

$user= $e->db->fetch("select username,password from mailbox where username=:username",array("username"=>$username) );
if($user[password]!="{PLAIN-MD5}".md5($password)) {
    exit(json_encode(array("hata"=>"kadiyadasifreyanlis")));
}









//The location of the mailbox.
$mailbox = '{localhost:143/notls}';
//The username / email address that we want to login to.

if(!$password){exit(json_encode(array("hata"=>"sifre gonderilmedi")));}
//Attempt to connect using the imap_open function.
$imapResource = @imap_open($mailbox, $username, $password);
if (!$imapResource){
  exit(json_encode(array("durum"=>"baglanilamadi")));
}

//If the imap_open function returns a boolean FALSE value,
//then we failed to connect.
if($imapResource === false){
    //If it failed, throw an exception that contains
    //the last imap error.
    throw new Exception(imap_last_error());
}

//If we get to this point, it means that we have successfully
//connected to our mailbox via IMAP.

//Lets get all emails that were received since a given date.
$search = 'SINCE "' . date("j F Y", strtotime("-700 days")) . '"';
$emails = imap_search($imapResource, $search);

//If the $emails variable is not a boolean FALSE value or
//an empty array.
$mails="";
if(!empty($emails)){
    //Loop through the emails.

    foreach($emails as $email){
        //Fetch an overview of the email.
        $overview = imap_fetch_overview($imapResource, $email);


        $overview = $overview[0];
        //Print out the subject of the email.

       // echo '<b>' . htmlentities($overview->subject) . '</b><br>';
        //Print out the sender's email address / from email address.
        //echo 'From: ' . $overview->from . '<br><br>';
        //Get the body of the email.
         $structure = imap_fetchstructure($imapResource, $email);
         $message = imap_fetchbody($imapResource, $email, 1, FT_PEEK);

        if($structure->encoding == 3) {
            $overview->body = imap_base64($message);
        } else if($structure->encoding == 4) {
           $overview->body  =  imap_qprint($message);
        }else{
             $overview->body = imap_qprint($message);
        }
          //$g ["subject"]= $overview->subject ;
          $mails[][mail]= $overview;

    }
}
$mailler["result"] = $mails;
$jse=json_encode($mailler,true);

 echo  $jse ;//$jse;





//substr($jse, 1,strlen($jse)-2 ) ;
?>