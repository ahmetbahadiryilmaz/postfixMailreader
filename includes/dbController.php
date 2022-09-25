<?
/*
Author
enbiri@gmail.com
Ahmet Bahadır YILMAZ
*/

require_once __DIR__."/databaseControllers/pdoController.php";

class dbController{

    private $host,$db,$uname,$pw;
    private $dbmsType="pdo";
    private $dbms;
    function __construct($host,$db,$uname,$pw){
        $this->setDbInfo($host,$db,$uname,$pw);
        $this->setType("pdo");
        if($this->dbmsType=="pdo"){
            $this->dbms=new pdoController($this->host,$this->db,$this->uname,$this->pw);
            //$this->connect(); query- fetch - insert if not connected then connect
        }///elseif mysql mysqli mssql nosql sqlite oracle

    }

    //sets
    function setDbInfo($host,$db,$uname,$pw){
        $this->host=$host;
        $this->db=$db;
        $this->uname=$uname;
        $this->pw=$pw;
    }
    function setType($type){
         $this->dbmsType=$type;
    }


    /////

    function error(){
        return $this->dbms->error()?json_encode(
            array_merge(array(
                "error"=>"dbError"),
                $this->dbms->error())
        )
        :false;
    }

    function connect(){ // no need this auto connect
         return $this->dbms->connect();
    }

    function query($query,$data=array()){
        return $this->dbms->query($query,$data);
    }

    function fetch($query,$data=array()){
        return $this->dbms->fetch($query,$data);
    }

    function fetchAll($query,$data=array()){
        return $this->dbms->fetchAll($query,$data);
    }




    function insert($tablo,$data){
        return $this->dbms->insert($tablo,$data);
    }

    function update($table,$data,$where=array()){

        return  $this->dbms->update($table,$data,$where);
    }


    function delete(){
        return  $this->dbms->delete($query,$data);
    }


}
?>