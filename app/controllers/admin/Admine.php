<?php

class Admine extends Controller
{

    protected $adminDetails = ["Table" => ""];

    public function __construct()
    {
        $this->Admin = $this->model('Admin');
    }
    public function index()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'email' => $_POST['email'],
                'password' => $_POST['password']
            ];

            if ($this->Admin->checkAdmin($data)) {
                
                switch($_SESSION['user_id'])
                {
                    case 'admin':
                        echo '<script>alert("Verification Successful");document.location="'.URLROOT.'Patient"</script>';
                    break;
                    case 'adminbulletin':
                        
                        echo '<script>alert("Verification Successful");document.location="'.URLROOT.'notices"</script>';
                    break;
                    case 'adminanalysis':
                        echo '<script>alert("Verification Successful");document.location="'.URLROOT.'analysis"</script>';
                    break;
                    case 'adminmedia':
                        echo '<script>alert("Verification Successful");document.location="'.URLROOT.'news"</script>';
                    break;

                        
                }
                echo '<script>alert("Verification Successful");document.location="'.URLROOT.'Patient"</script>';
            } else {
                $this->views('admin/admine', $data);
            }
        } else {
            $data = [
                'email' => '',
                'password' => ''
            ];
            $this->views('admin/admine', $data);
        }
    }
}
