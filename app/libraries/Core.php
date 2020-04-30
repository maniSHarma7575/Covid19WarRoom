<?php
   //core class
   //Creates url and load core controller
   //url format -/controller/method/params

   Class Core{

    protected $currentController='Pages';
    protected $currentMethod='index';
    protected $params=[];
     
    public function __construct()
    {
        // print_r($this->getUrl());
        $url=$this->getUrl();
        //Look in controller if this controller exists.
        if(file_exists('../app/controllers/'.ucwords($url[0]).'.php'))
        {
            $this->currentController=ucwords($url[0]);
            unset($url[0]);
            require_once '../app/controllers/'.$this->currentController.'.php';
        }
        elseif(file_exists('../app/controllers/admin/'.ucwords($url[0]).'.php'))
        {
            $this->currentController=ucwords($url[0]);
            unset($url[0]);
            require_once '../app/controllers/admin/'.$this->currentController.'.php';
        }
        elseif(file_exists('../app/controllers/user/'.ucwords($url[0]).'.php'))
        {
            $this->currentController=ucwords($url[0]);
            unset($url[0]);
            require_once '../app/controllers/user/'.$this->currentController.'.php';
        }
        else{
               unset($url[0]);
               require_once '../app/controllers/'.$this->currentController.'.php';
         }
        $this->currentController=new $this->currentController;
        //now check for the second part of url 
        if(isset($url[1]))
        {
           //now find for the method if that exist in controller class.  ??big question mark here??
           if(method_exists($this->currentController,$url[1]))
           {
               $this->currentMethod=$url[1];
               unset($url[1]);
           }
        }   

        $this->params=$url?array_values($url):[];

        //call a  callback with array of params
        call_user_func_array([$this->currentController,$this->currentMethod],$this->params);    

    }
    public function getUrl()
    {
       if(isset($_GET['url']))
       {
         $url=rtrim($_GET['url'],'/');
         $url=filter_var($url,FILTER_SANITIZE_URL);
         $url=explode('/',$url);
         return $url;
       }
    }
    public static function dnd($data)
    {
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
        exit();
    }
    public static function redirect($location)
    {
        if (!headers_sent()) {
            header('Location: ' . URLROOT . $location);
            exit();
        } else {
            echo '<script type="text/javascript">';
            echo 'window.location.href="' . URLROOT . $location . '";';
            echo '</script>';
            echo '<noscript>';
            echo '<meta http-equiv="refresh" content="0;url=' . $location . '"/>';
            echo '</noscript>';
        }
    }
   }
?>
