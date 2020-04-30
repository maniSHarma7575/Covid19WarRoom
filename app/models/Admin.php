<?php
class Admin
{
  protected $db = '';
  public function __construct()
  {
    $this->db = new Database();
  }
  public function updatePatientData()
  {

    echo "Running Truncate";
    $qry = 'TRUNCATE TABLE patient';
    $this->db->query($qry);
    if ($this->db->execute()) {
      echo "Truncated patient <br>";
    } else
      echo "Error On Truncate patient<br>";

    $qry = 'TRUNCATE TABLE uDistrict';
    $this->db->query("TRUNCATE TABLE uDistrict");
    if ($this->db->execute()) {
      echo "Truncated uDistrict<br>";
    } else
      echo "Error On Truncate uDistrict<br>";
    $url = "https://api.covid19india.org/raw_data.json";
    $local = APPROOT . "/Jdata/raw_data.json";
    $stri = file_get_contents($url);

    if ($stri === false) {
      echo "No file";
    }
    $json_a = json_decode($stri, true);
    if ($json_a === null) {
      echo "error Parsing";
    }
    $qur = 'Insert into patient ("", :bnotes, :susContra , :status, :dateannounced,
        :city, :dist, :state, :gender, :nationality, :notes, :pno, :src, :statuschange,
        :transmission
         )';

    $cnt = 0;

    //FOR UDISTRICT TABLE AND PATIENT TABLE
    $query = "INSERT INTO uDistrict (district, confirmed , recovered, deceased) VALUES (:district,:confirmed,:recovered,:deceased )";

    $datax = [
      "Dehradun" => [0, 0, 0], "Nainital" => [0, 0, 0], "Chamoli" => [0, 0, 0], "Bageshwar" => [0, 0, 0], "Pauri Garhwal" => [0, 0, 0], "Tehri Garhwal" => [0, 0, 0], "Haridwar" => [0, 0, 0], "Champawat" => [0, 0, 0], "Rudraprayag" => [0, 0, 0], "Almora" => [0, 0, 0], "Udham Singh Nagar" => [0, 0, 0], "Uttarkashi" => [0, 0, 0], "Pithoragarh" => [0, 0, 0],
      "Total" => [0, 0, 0], "Unknown" => [0, 0, 0]
    ];

        $qry = 'Insert into patient
        (agebracket,backnotes,suspectedcontraction,currentstatus,dateannounced,city,district,state,gender,nationality,notes,patientnumber,sources,statuschangedate,transmissiontype,type)
         values (:age, :bnotes, :susContra , :status, :dateannounced,:city, :dist, :state, :gender, :nationality, :notes, :pno, :src, :statuschange, :transmission,:type)';
       

    foreach ($json_a["raw_data"] as $data => $value) {
      if ($value['detectedstate'] == "Uttarakhand") {

        if ($value['detecteddistrict'] == "") {
          $value['detecteddistrict'] == "Unknown";
        }
        if ($value['currentstatus'] == "Hospitalized") {
          $datax["Total"][0]++;
          $datax[$value['detecteddistrict']][0]++;
        } else if ($value['currentstatus'] == "Recovered") {
          $datax["Total"][1]++;
          $datax[$value['detecteddistrict']][1]++;
        } else {
          $datax['Total'][2]++;
          $datax[$value['detecteddistrict']][2]++;
        }
        $this->db->query($qry);
        $this->db->bindvalues(':age', $value['agebracket']);
        $this->db->bindvalues(':bnotes', $value['backupnotes']);
        $this->db->bindvalues(':susContra', $value['contractedfromwhichpatientsuspected']);
        $this->db->bindvalues(':status', $value['currentstatus']);
        $this->db->bindvalues(':dateannounced', $value['dateannounced']);
        $this->db->bindvalues(':city', $value['detectedcity']);
        $this->db->bindvalues(':dist', $value['detecteddistrict']);
        $this->db->bindvalues(':state', $value['detectedstate']);
        $this->db->bindvalues(':gender', $value['gender']);
        $this->db->bindvalues(':nationality', $value['nationality']);
        $this->db->bindvalues(':notes', $value['notes']);
        $this->db->bindvalues(':pno', $value['patientnumber']);
        $this->db->bindvalues(':src', $value['source1']);
        $this->db->bindvalues(':statuschange', $value['statuschangedate']);
        $this->db->bindvalues(':transmission', $value['typeoftransmission']);
        $this->db->bindvalues(':type','patient');
        // echo "<br>Query : ".$qry."<hr>";
        $this->db->execute();
      }
    }

    foreach ($datax as $district => $values) {

      $this->db->query($query);
      echo "<br>District : $district Confirmed : $values[0] Recovered : $values[1] Deceased : $values[2]<br>";
      $this->db->bindvalues(':district', $district);
      $this->db->bindvalues(':confirmed', $values[0]);
      $this->db->bindvalues(':recovered', $values[1]);
      $this->db->bindvalues(':deceased', $values[2]);
      $this->db->execute();
    }
  }

  public function getPatientData()
  {
    $this->db->query('SELECT * FROM patient');
    return $this->db->resultSet();
  }
  public function getNoticesData()
  {
    $this->db->query('SELECT * FROM notices');
    return $this->db->resultSet();
  }
  public function removeNoticeData($id)
  {
    $this->db->query("DELETE FROM notices WHERE id =:id");
    $this->db->bindvalues(':id', $id);
    $this->db->execute();
  }

  //this function now will used to update hotspot data 
  public function getHotspotData()
  {
    $this->db->query('SELECT * FROM hotspot');
    return $this->db->resultSet();
  }
  public function addNewHotspot($hotspotdata)
  {
     $this->db->query("INSERT INTO hotspot(city,district,latitude,longitude,active,recovered,deceased,homequarantine,facilityquarantine)
     values(:city,:district,:latitude,:longitude,:active,:recovered,:deceased,:homequarantine,:facilityquarantine)");
     $this->db->bindvalues(':city',$hotspotdata['city']);
     $this->db->bindvalues(':district',$hotspotdata['district']);
     $this->db->bindvalues(':latitude',$hotspotdata['latitude']);
     $this->db->bindvalues(':longitude',$hotspotdata['longitude']);
     $this->db->bindvalues(':active',$hotspotdata['active']);
     $this->db->bindvalues(':recovered',$hotspotdata['recovered']);
     $this->db->bindvalues(':deceased',$hotspotdata['deceased']);
     $this->db->bindvalues(':homequarantine',$hotspotdata['homequarantine']);
     $this->db->bindvalues(':facilityquarantine',$hotspotdata['facilityquarantine']);
     if($this->db->execute())
     {
        return true;
     }
     else
     {
        return false;
     }
  }


  public function deleteHotspot($id)
  {
    $this->db->query('DELETE FROM hotspot WHERE id=:id');
    $this->db->bindvalues(':id',$id);
    if($this->db->execute())
    {
      return true;
    }
    else
    {
      return false;
    }
  }
 
  public function editHotspot($editdata)
  {
    $this->db->query('UPDATE hotspot SET city=:city,district=:district,latitude=:latitude,longitude=:longitude,active=:active,recovered=:recovered,
    deceased=:deceased,homequarantine=:homequarantine,facilityquarantine=:facilityquarantine WHERE id=:id');
    $this->db->bindvalues(':city',$editdata['city']);
    $this->db->bindvalues(':district',$editdata['district']);
    $this->db->bindvalues(':latitude',$editdata['latitude']);
    $this->db->bindvalues(':longitude',$editdata['longitude']);
    $this->db->bindvalues(':active',$editdata['active']);
    $this->db->bindvalues(':recovered',$editdata['recovered']);
    $this->db->bindvalues(':deceased',$editdata['deceased']);
    $this->db->bindvalues(':homequarantine',$editdata['homequarantine']);
    $this->db->bindvalues(':facilityquarantine',$editdata['facilityquarantine']);
    $this->db->bindvalues(':id',$editdata['id']);
    if($this->db->execute())
    {
      return true;
    }
    else{
      return false;
    }
  }


 public function checkAdmin($data)
  {
    $this->db->query('SELECT * FROM users WHERE email=:email AND otp=:password');
    $this->db->bindvalues(':email', $data['email']);
    $this->db->bindvalues(':password', $data['password']);
    $row = $this->db->single();
    
    if ($this->db->rowCount() > 0) {
      if (session_status() == PHP_SESSION_NONE) {
        session_start();
      }
      
      $_SESSION['user_admin'] = $row->email;
      $_SESSION['user_type'] = $row->type;
      $_SESSION['user_id'] = $row->type;
      $_SESSION['admin_id'] = $row->id;

      return true;
    } else {
      return false;
    }
  }


  public function uploadNewNotice($data)
  {

    $databaseData = [];
    $databaseData['users_id'] = $_SESSION['admin_id'];
    $databaseData['name'] = $data['noticename'];
    $databaseData['document_path'] = $data['attachment'];
    $this->db->query('INSERT INTO notices(users_id,name,document_path) values(:users_id,:name,:document_path)');
    $this->db->bindvalues(':users_id', $databaseData['users_id']);
    $this->db->bindvalues(':name', $databaseData['name']);
    $this->db->bindvalues(':document_path', $databaseData['document_path']);
    if ($this->db->execute()) {
      return true;
    } else {
      return false;
    }
  }
  public function checkExistingPatient($data)
  {
      $this->db->query('SELECT * FROM patient WHERE patientnumber=:patientnumber');
      $this->db->bindvalues(':patientnumber',$data);
      $this->db->execute();
      if($this->db->rowCount()>0)
      {
          return true;
      }  
      else
      {
          return false;
      }
  }
  public function updatePatientStatus($statustype,$district)
  {
    if(!empty($district) && !empty($statustype))
    {
       switch(strtolower($statustype))
      {
        case 'quarantined home':
          $statustype='quarantined_home';
        break;
        case 'active':
          $statustype='confirmed';
        break;
        case 'quarantined government facility':
          $statustype='quarantined_government';
        break;
        case 'isolation home':
          $statustype='isolation_home';
        break;
        case 'isolation government facility':
          $statustype='isolation_government';
        break;
      }
      
      $this->db->query('SELECT '.strtolower($statustype).' FROM uDistrict WHERE district=:district');
      $this->db->bindvalues(':district',$district);
      $result=$this->db->resultSet();
      $status=strtolower($statustype);
      $count=$result[0]->$status;
      $this->db->query('UPDATE uDistrict SET '.$status.'=:status WHERE district=:district');
      $this->db->bindvalues(':status',$count+1);
      $this->db->bindvalues(':district',$district);
      $this->db->execute();
      $this->db->query('SELECT '.strtolower($statustype).' FROM uDistrict WHERE district="Total"');
      $result=$this->db->resultSet();
      $status=strtolower($statustype);
      $count=$result[0]->$status;
      $this->db->query('UPDATE uDistrict SET '.$status.'=:status WHERE district="Total"');
      $this->db->bindvalues(':status',$count+1);

      if($this->db->execute())
          {
            
              return true;
          }
          else
          {
            
              return false;
          }
    }
  }

  public function updatePatient($data)
  {
    if(strtolower($data['type'])=="patient"){$data['patientnumber']= "P-".$data['patientnumber'];}
			else {$data['patientnumber']= "S-".$data['patientnumber'];}
    if(!$this->checkExistingPatient($data['patientnumber']))
    {
    $qry = 'Insert into patient
    (agebracket,backnotes,suspectedcontraction,currentstatus,dateannounced,city,district,state,gender,nationality,notes,patientnumber,sources,statuschangedate,transmissiontype,type,latitude,longitude)
     values (:age, :bnotes, :susContra , :status, :dateannounced,:city, :dist, :state, :gender, :nationality, :notes, :pno, :src, :statuschange, :transmission, :type,:latitude,:longitude)';
     $this->db->query($qry);
     $this->db->bindvalues(':age', "");
     $this->db->bindvalues(':bnotes', "");
     $this->db->bindvalues(':susContra', "");
     $this->db->bindvalues(':status', $data['currentstatus']);
     $this->db->bindvalues(':dateannounced', $data['dateannounced']);
     $this->db->bindvalues(':city', $data['city']);
     $this->db->bindvalues(':dist', $data['district']);
     $this->db->bindvalues(':state', $data['state']);
     $this->db->bindvalues(':gender', $data['gender']);
     $this->db->bindvalues(':nationality', $data['nationality']);
     $this->db->bindvalues(':notes', $data['notes']);
     $this->db->bindvalues(':pno', $data['patientnumber']);
     $this->db->bindvalues(':src', "");
     $this->db->bindvalues(':statuschange', "");
     $this->db->bindvalues(':transmission', $data['transmissiontype']);
     $this->db->bindvalues(':type', $data['type']);
     $this->db->bindvalues(':latitude',$data['latitude']);
     $this->db->bindvalues(':longitude',$data['longitude']);
          if($this->db->execute())
          {
              $result=$this->updatePatientStatus($data['currentstatus'],$data['district']);
              return true;
          }
          else
          {
            return false;
          }
    }
    else{
      return false;
    }
  }

  public function deletePatient($pdata)
  {
    
    $district=$pdata['district'];
    $statustype=$pdata['status'];

    if(!empty($district) && !empty($statustype))
    {
       switch(strtolower($statustype))
      {
        case 'quarantined home':
          $statustype='quarantined_home';
        break;
        case 'active':
          $statustype='confirmed';
        break;
        case 'quarantined government facility':
          $statustype='quarantined_government';
        break;
        case 'isolation home':
          $statustype='isolation_home';
        break;
        case 'isolation government facility':
          $statustype='isolation_government';
        break;
      }
      $this->db->query('SELECT '.strtolower($statustype).' FROM uDistrict WHERE district=:district');
      $this->db->bindvalues(':district',$district);
      $result=$this->db->resultSet();
      $status=strtolower($statustype);
      $count=$result[0]->$status;
      $this->db->query('UPDATE uDistrict SET '.$status.'=:status WHERE district=:district');
      $this->db->bindvalues(':status',$count-1);
      $this->db->bindvalues(':district',$district);
      $this->db->execute();
      $this->db->query('SELECT '.strtolower($statustype).' FROM uDistrict WHERE district="Total"');
      $result=$this->db->resultSet();
      $status=strtolower($statustype);
      $count=$result[0]->$status;
      $this->db->query('UPDATE uDistrict SET '.$status.'=:status WHERE district="Total"');
      $this->db->bindvalues(':status',$count-1);

      if($this->db->execute())
          {
            $this->db->query('DELETE FROM patient WHERE patientnumber=:id');
            $this->db->bindvalues(':id',$pdata['id']);
            $this->db->execute();
            return true;
          }
          else
          {
            return false;
          }
    }

      
      
       
  }

  public function editPatient($edata)
  {
      $this->db->query('SELECT * FROM patient WHERE patientnumber=:patientnumber');
      $this->db->bindvalues(':patientnumber',$edata['patientnumber']);
      $res=$this->db->single();
      $oldstatus=$res->currentstatus;
      $currentstatus=$edata['status'];
      if($oldstatus==$edata['status'])
      {
        //nochange needed in uDistrict Table.
        $this->db->query('UPDATE patient SET city=:city,nationality=:nationality,notes=:notes,statuschangedate=:date,latitude=:lat,longitude=:long WHERE patientnumber=:patientnumber');
        $this->db->bindvalues(':city', $edata['city']);
        $this->db->bindvalues(':nationality', $edata['nationality']);
        $this->db->bindvalues(':notes', $edata['notes']);
        $this->db->bindvalues(':date',$edata['date']);
        $this->db->bindvalues(':patientnumber',$edata['patientnumber']);
        $this->db->bindvalues(':lat',$edata['latitude']);
        $this->db->bindvalues(':long',$edata['longitude']);
        if($this->db->execute())
        {
          return true;
        }
        else
        {
          return false;
        }
        
      }
      else
      {
        //change NEEDED Udistrict table.
        //you need old status(minus 1 in that) and new status(plus 1 in that) of that district (dehradun) same for total
        $district=$edata['district'];
        switch(strtolower($currentstatus))
        {
          case 'quarantined home':
            $currentstatus='quarantined_home';
          break;
          case 'active':
            $currentstatus='confirmed';
          break;
          case 'quarantined government facility':
            $currentstatus='quarantined_government';
          break;
          case 'isolation home':
            $currentstatus='isolation_home';
          break;
          case 'isolation government facility':
            $currentstatus='isolation_government';
          break;
        }
        switch(strtolower($oldstatus))
        {
          case 'quarantined home':
            $oldstatus='quarantined_home';
          break;
          case 'active':
            $oldstatus='confirmed';
          break;
          case 'quarantined government facility':
            $oldstatus='quarantined_government';
          break;
          case 'isolation home':
            $oldstatus='isolation_home';
          break;
          case 'isolation government facility':
            $oldstatus='isolation_government';
          break;
        }
        

        $this->db->query('SELECT '.strtolower($currentstatus).' FROM uDistrict WHERE district=:district');
        $this->db->bindvalues(':district',$district);
        $result=$this->db->resultSet();
        $status=strtolower($currentstatus);
        $count=$result[0]->$status;
        $this->db->query('UPDATE uDistrict SET '.$status.'=:status WHERE district=:district');
        $this->db->bindvalues(':status',$count+1);
        $this->db->bindvalues(':district',$district);
        $this->db->execute();

        $this->db->query('SELECT '.strtolower($oldstatus).' FROM uDistrict WHERE district=:district');
        $this->db->bindvalues(':district',$district);
        $result=$this->db->resultSet();
        $status=strtolower($oldstatus);
        $count=$result[0]->$status;
        $this->db->query('UPDATE uDistrict SET '.$status.'=:status WHERE district=:district');
        $this->db->bindvalues(':status',$count-1);
        $this->db->bindvalues(':district',$district);
        $this->db->execute();


        $this->db->query('SELECT '.strtolower($currentstatus).' FROM uDistrict WHERE district="Total"');
        $result=$this->db->resultSet();
        $status=strtolower($currentstatus);
        $count=$result[0]->$status;
        $this->db->query('UPDATE uDistrict SET '.$status.'=:status WHERE district="Total"');
        $this->db->bindvalues(':status',$count+1);
        $this->db->execute();

        $this->db->query('SELECT '.strtolower($oldstatus).' FROM uDistrict WHERE district="Total"');
        $result=$this->db->resultSet();
        $status=strtolower($oldstatus);
        $count=$result[0]->$status;
        $this->db->query('UPDATE uDistrict SET '.$status.'=:status WHERE district="Total"');
        $this->db->bindvalues(':status',$count-1);
        $this->db->execute();
        
        $this->db->query('UPDATE patient SET city=:city,nationality=:nationality,currentstatus=:status,notes=:notes,statuschangedate=:date,type="patient",latitude=:lat,longitude=:long WHERE patientnumber=:patientnumber');
        $this->db->bindvalues(':city', $edata['city']);
        $this->db->bindvalues(':nationality', $edata['nationality']);
        $this->db->bindvalues(':notes', $edata['notes']);
        $this->db->bindvalues(':date',$edata['date']);
        $this->db->bindvalues(':patientnumber',$edata['patientnumber']);
        $this->db->bindvalues(':status',$edata['status']);
        $this->db->bindvalues(':lat',$edata['latitude']);
        $this->db->bindvalues(':long',$edata['longitude']);
        if($this->db->execute())
        {
          return true;
        }
        else
        {
          return false;
        }

        
      }
  
  }


  //QUARANTINE DATE

   public function getQuarantineData()
   {
      $this->db->query('SELECT * FROM uDistrict');
      return $this->db->resultSet();
   }

   public function updateQuarantine($data)
   {
     $this->db->query('SELECT * from uDistrict WHERE id=:id');
     $this->db->bindvalues(':id',$data['id']);
     $result=$this->db->single();

     if($result->quarantined_home==$data['homequarantine']&&$result->quarantined_government==$data['facilityquarantine'])
     {
           //no need to change 
           return true;
     }
     {   
        $this->db->query('SELECT * from uDistrict WHERE id=14');
        $totalresult=$this->db->single(); 
        $totalhomequarantine=(int)$totalresult->quarantined_home-(int)$result->quarantined_home+(int)$data['homequarantine'];
        $totalfacilityquarantine=(int)$totalresult->quarantined_government-(int)$result->quarantined_government+(int)$data['facilityquarantine'];
        $this->db->query('UPDATE uDistrict SET quarantined_home=:qh,quarantined_government=:qf WHERE id=:id'); 
        $this->db->bindvalues(':qh',$data['homequarantine']);
        $this->db->bindvalues(':qf',$data['facilityquarantine']);
        $this->db->bindvalues(':id',$data['id']);
        $this->db->execute();

        $this->db->query('UPDATE uDistrict SET quarantined_home=:qh,quarantined_government=:qf WHERE id=14'); 
        $this->db->bindvalues(':qh',$totalhomequarantine);
        $this->db->bindvalues(':qf',$totalfacilityquarantine);
        if($this->db->execute())
        {
          return true;
        }
        else
        {
          return false;
        }
     }
   }





  public function saveJson()
  {
    // die(dirname(dirname(dirname(__FILE__))));
    $this->db->query('SELECT currentstatus,dateannounced, city, district, patientnumber,transmissiontype,latitude,longitude FROM patient Where type=:type
            ');
    $this->db->bindvalues(':type','Patient');
    $file_name=dirname(dirname(dirname(__FILE__))).'/public/uttarakhanddata.json';
    $res=json_encode($this->db->resultSet());
    file_put_contents($file_name,$res);
    // die($res);
    $this->db->query('SELECT * FROM uDistrict');
    $file_name=dirname(dirname(dirname(__FILE__))).'/public/districtdata.json';
    $res=json_encode($this->db->resultSet());
    file_put_contents($file_name,$res);
    //die($res);
    $this->db->query('SELECT * FROM hotspot');
    $file_name=dirname(dirname(dirname(__FILE__))).'/public/hotspotdata.json';
    $res=json_encode($this->db->resultSet());
    file_put_contents($file_name,$res);
  } 
}
