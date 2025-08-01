<?php 
// Connection.php
class connection{
    protected $isConn;
    protected $datab;
    protected $transaction;

    public function __construct($username="emruser", $password="yourpassword", $host="emr-db", $dbname="Clinic", $options = []){
        $this->isConn = TRUE;
        try{
            $this->datab = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8", $username, $password, $options);
            $this->datab->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->transaction = $this->datab;
            $this->datab->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $e){
            throw new Exception($e->getMessage());
        }
    }

    public function Disconnect(){
        $this->datab = NULL;
        $this->isConn = FALSE;
    }
}

?>
