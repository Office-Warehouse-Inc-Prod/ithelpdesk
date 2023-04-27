
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
    public function getuserinfo($id){
$qry="select it_desc from it_tech where itsup='". $id."'";
$qryexec=mysqli_query($this->link,$qry);
$qryassoc=mysqli_fetch_assoc($qryexec);
return $qryassoc;


    }
}


class regdbcon
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
    public function getuserinfo($id){
$qry="select it_desc from it_tech where itsup='". $id."'";
$qryexec=mysqli_query($this->link,$qry);
$qryassoc=mysqli_fetch_assoc($qryexec);
return $qryassoc;
}
}

