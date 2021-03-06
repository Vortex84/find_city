<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Maps extends CI_Controller {

	function __construct(){
        parent::__construct();
	}

	public function index(){
		
        $this->load->view('maps_v');
	}
    
    private function sortByTagname ($arr){
        $index = array();
        foreach($arr as $a) $index[] = $a->name;
        array_multisort($index, $arr);
        return $arr;
    }
               
    public function getcity(){       
        
       if(isset($_GET['term']) and strlen($_GET['term'])>2){
            $json_city = file_get_contents("http://api.geonames.org/searchJSON?q=".trim($_GET['term'])."&style=LONG&lang=ru&orderby=name&maxRows=10&username=demo");

            $arr_city = json_decode($json_city);
            $arr = array();
            if(isset($arr_city->geonames)){
               $arr = $this->sortByTagname($arr_city->geonames);

               echo json_encode($arr);
            }else{
                echo json_encode("err-2");
            }
        }else{
            echo json_encode("err-1");
        }
    }
    
     public function getmap(){
        if(!isset($_POST['city']) or strlen($_POST['city'])<3){ echo json_encode("err-1"); exit(); }
        
        $json_city = file_get_contents("http://api.geonames.org/searchJSON?q=".trim($_POST['city'])."&style=LONG&lang=ru&maxRows=1&username=demo");
        
        $arr_city = json_decode($json_city);
        $json_near = "";
            
        if(!isset($arr_city->geonames)){ echo json_encode("err-2"); exit(); }

        $gps = (array)$arr_city->geonames[0];
            
        $json_near = file_get_contents("http://api.geonames.org/findNearbyWikipediaJSON?formatted=true&lat=".$gps['lat']."&lng=".$gps['lng']."&username=demo&style=full&lang=ru");
              
        $arr_near = json_decode($json_near);
        
        if(!isset($arr_near->geonames)){ echo json_encode("err-3"); exit(); }
         
        $i = 1;
        $koords = "";
        foreach($arr_near->geonames as $val){
             $koords.= $val->lng.",".$val->lat.",pmntl".$i."~";
             $i++;
        }
        
        if($koords==""){ echo json_encode("err-3"); exit(); }
            
        $map = "https://static-maps.yandex.ru/1.x/?l=map&pt=".trim($koords,'~');
        echo json_encode($map);
     }
}