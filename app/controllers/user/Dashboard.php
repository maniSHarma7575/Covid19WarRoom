<?php 

class Dashboard extends Controller{

    protected $dataForView = ["table"=>"","notices"=>"","StatsTable"=>""];

     public function __construct()
     {
        
           $this->dashboardModel=$this->model('DashboardModel');
           
     }

     public function index()
     {  
         
        
         $this->views('user/dashboard',$this->dataForView);
     }
   
}


?>