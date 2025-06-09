    
<?php
class dbconfig
{
    private $host="localhost";
    private $user="root";
    private $pass="";
    private $dbname="helpdesk1";
    private $link,$error;
    public function __construct() {
        $this->connect();
    }
    public function connect()
    {
        $this->link=new mysqli($this->host, $this->user, $this->pass, $this->dbname);
        if(!$this->link)
        {
            die($this->error."DB Connect Error".$this->link->connect_errno);
        }
    }
    public function prepare($query)
    {
        return $this->link->prepare($query);
    }

}


function get_total_all_records()
{
 include('db.php');
 $statement = $connection->prepare("SELECT * FROM vw_wfittable");
 $statement->execute();
 $result = $statement->fetchAll();
 return $statement->rowCount();
}

?>
   