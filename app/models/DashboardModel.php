<?php


  class DashboardModel{
    protected $db='';
    
    public function __construct()
    {
        $this->db=new Database();
        
    }


}

?>