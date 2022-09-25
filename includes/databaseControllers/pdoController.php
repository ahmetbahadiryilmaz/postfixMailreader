<?

class pdoController{

    private $host,$dbname,$uname,$pw;
    private $engine=null;
    function __construct($host,$dbname,$uname,$pw){
        $this->host=$host;
        $this->dbname =$dbname;
        $this->uname=$uname;
        $this->pw=$pw;
    }

    function getEngine(){
        if(!$this->engine){
           $this->engine = $this->connect();

            $this->engine->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->engine->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        }
        return $this->engine;
    }
    function connect(){
        return new PDO("mysql:host=".$this->host.";dbname=".$this->dbname.";charset=utf8", $this->uname,  $this->pw);
    }
    function error(){

        //"SQLSTATE[42000]: Syntax error or access violation: 1064
       $info =  $this->engine->errorInfo();
       if($info){
       return array(
           "type"=>$info[0],
            "code"=>$info[1],
            "text"=>$info[2]
        );
       }else{
        return false;
       }
    }
    function query($sorgu,$data=array()){
        GLOBAL $e;
        try {
             $engine = $this->getEngine();
            //$sql    = "SELECT * FROM users WHERE user_id = :user_id";
            $q   = $engine->prepare($sorgu);
            $q->execute($data);//array(":user_id" => $user_id));
            return $q;
        } catch (Exception $er) {
            if($e->debug){exit(json_encode($this->error())) ;}
            return false;
        }

    }


    function fetch($sorgu,$data=array()){
        $q=$this->query($sorgu,$data);

        return   $q?$q->fetch(PDO::FETCH_ASSOC):false;
    }

    function fetchAll($sorgu,$data=array()){
        $q=$this->query($sorgu,$data);
        return   $q?$q->fetchAll(PDO::FETCH_ASSOC):false;
    }

    function insert($tablo,$data){


        try {


            GLOBAL $e;
            $engine = $this->getEngine();




            $rowsSQL = "";
            $toBind = array();
            $columnNames = array_keys($data);

            foreach($data as $columnName => $columnValue){
                $param = ":" . $columnName  ;
                $params[] = $param;
                $toBind[$param] = $columnValue;
            }
            $rowsSQL  = "(" . implode(", ", $params) . ")";
            $sql = "INSERT INTO `".$tablo."` (" . implode(", ", $columnNames) . ") VALUES " . $rowsSQL ;

            $pdoStatement = $engine->prepare($sql);
            foreach($toBind as $param => $val){
                $pdoStatement->bindValue($param, $val);
            }
            if($pdoStatement->execute()){
               return $engine->lastInsertId();
            }else{
               return false;
            };
        } catch (Exception $er) {
            if($e->debug){exit(json_encode($this->error())) ;}
            return false;
        }

    }

    function update($tablo,$data,$where=array()){

        $engine = $this->getEngine();

        $data = array_filter($data, function ($value) {
            return null !== $value;
        });
        $query = 'UPDATE '.$tablo.' SET ';
        $values = array();
        $queryParams=array();
        $queryParamsFilter=array();

        foreach ($data as $name => $value) {
            if($name!="id"){
             $queryParams[] = $name.' = :'.$name;
             $values[':'.$name] = $value;
            }
        }


        if(count($where)>0){
            foreach ($where as $name => $value) {

                 $queryParamsFilter[] = $name.' =:'.$name;
                 $values[':'.$name] = $value;

            }
        }
        $query = $query.implode(" , " , $queryParams).((count($where)>0)?" where ".implode(" and " ,$queryParamsFilter):" where id=:id"); //

        $sth = $engine->prepare($query);
        foreach($values as $param => $val){
            $sth->bindValue($param, $val);
        }

        return $sth->execute();
    }

    function delete($tablo,$where){
        $pdoObject=pdoBaglan();
        $pdoObject->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        $where = array_filter($where, function ($value) {
            return null !== $value;
        });

         $query = 'delete from '.$tablo;
         $queryParamsFilter=array();
         $values = array();
         if(count($where)>0){
            foreach ($where as $name => $value) {

                 $queryParamsFilter[] = $name.' = :'.$name;
                 $values[':'.$name] = $value;

            }
        }
        $query = $query." where ".implode(" and " ,$queryParamsFilter); //
        $sth = $pdoObject->prepare($query);
        foreach($values as $param => $val){
            $sth->bindValue($param, $val);
        }

        return $sth->execute();

    }

}
    ?>