<?php
  //pdo class model will use this class
  //connect to database
  //Bind values
  //return rows and results
  class Database{

    private $host=DB_HOST;
    private $user=DB_USER;
    private $pass=DB_PASS;
    private $dbname=DB_NAME;

    private $dbh;
    private $stmt;
    private $error;

    public function __construct()
    {
      $dsn='mysql:host='.$this->host.';dbname='.$this->dbname;
      $options=array(
        PDO::ATTR_PERSISTENT =>true,
        PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION
      );

      //create a new pdo instance

      try{
        $this->dbh=new PDO($dsn,$this->user,$this->pass,$options);

      }
      catch(PDOException $e)
      {
        $this->error=$e->getMessage();
        echo $this->error;
      }
    }
    public function query($sql)
    {
      $this->stmt=$this->dbh->prepare($sql);
    }
    public function bindvalues($param,$value,$type=null)
    {
      if(is_null($type))
      {
        switch(true)
        {
          case is_int($value):
              $type=PDO::PARAM_INT;
              break;
          case is_bool($value):
            $type=PDO::PARAM_BOOL;
            break;
          case is_null($value):
            $type=PDO::PARAM_NULL;
            break;
          default:
            $type=PDO::PARAM_STR;
        }
      }
      $this->stmt->bindValue($param,$value,$type);
    }

    public function execute()
    {
      try{
      
        return $this->stmt->execute();
  
        }
        catch(PDOException $e)
        { 
          $this->error=$e->getMessage();
          echo $this->error;
        }

      
    
    }
    public function resultSet()
    {
      try{
      
        $this->execute();

        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
  
        }
        catch(PDOException $e)
        { 
          $this->error=$e->getMessage();
          echo $this->error;
        }
    }
    public function single()
    {
      $this->execute();
      return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    public function rowcount()
    {
      return $this->stmt->rowCount();   //rowcount is method which is part of pdo
        }
  }
?>
