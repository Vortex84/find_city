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
            //$json_city = '{"totalResultsCount":7740,"geonames":[{"adminCode1":"48","lng":"37.61556","geonameId":524901,"toponymName":"Moscow","countryId":"2017370","fcl":"P","population":10381222,"countryCode":"RU","name":"Москва","fclName":"city, village,...","countryName":"Россия","fcodeName":"столица политического образования","adminName1":"Москва","lat":"55.75222","fcode":"PPLC"},{"adminCode1":"48","lng":"37.60667","geonameId":524894,"toponymName":"Moskva","countryId":"2017370","fcl":"A","population":11503501,"countryCode":"RU","name":"Москва","fclName":"country, state, region,...","countryName":"Россия","fcodeName":"политико-административное деление первого порядка","adminName1":"Москва","lat":"55.76167","fcode":"ADM1"},{"adminCode1":"47","lng":"37.5","geonameId":524925,"toponymName":"Moscow Oblast","countryId":"2017370","fcl":"A","population":7095120,"countryCode":"RU","name":"МО","fclName":"country, state, region,...","countryName":"Россия","fcodeName":"политико-административное деление первого порядка","adminName1":"МО","lat":"55.75","fcode":"ADM1"},{"adminCode1":"47","lng":"37.89322","geonameId":532615,"toponymName":"Lyubertsy","countryId":"2017370","fcl":"P","population":154650,"countryCode":"RU","name":"Люберцы","fclName":"city, village,...","countryName":"Россия","fcodeName":"населенный пункт","adminName1":"МО","lat":"55.67719","fcode":"PPL"},{"adminCode1":"47","lng":"38.77833","geonameId":546230,"toponymName":"Kolomna","countryId":"2017370","fcl":"P","population":147690,"countryCode":"RU","name":"Коломна","fclName":"city, village,...","countryName":"Россия","fcodeName":"населенный пункт","adminName1":"МО","lat":"55.07944","fcode":"PPL"},{"adminCode1":"47","lng":"37.73076","geonameId":523812,"toponymName":"Mytishchi","countryId":"2017370","fcl":"P","population":160542,"countryCode":"RU","name":"Мытищи","fclName":"city, village,...","countryName":"Россия","fcodeName":"населенный пункт","adminName1":"МО","lat":"55.91163","fcode":"PPL"},{"adminCode1":"47","lng":"37.95806","geonameId":579464,"toponymName":"Balashikha","countryId":"2017370","fcl":"P","population":150103,"countryCode":"RU","name":"Балашиха","fclName":"city, village,...","countryName":"Россия","fcodeName":"населенный пункт","adminName1":"МО","lat":"55.80945","fcode":"PPL"},{"adminCode1":"47","lng":"37.42969","geonameId":550280,"toponymName":"Khimki","countryId":"2017370","fcl":"P","population":239967,"countryCode":"RU","name":"Химки","fclName":"city, village,...","countryName":"Россия","fcodeName":"населенный пункт","adminName1":"МО","lat":"55.89704","fcode":"PPL"},{"adminCode1":"47","lng":"38.4438","geonameId":520068,"toponymName":"Noginsk","countryId":"2017370","fcl":"P","population":115979,"countryCode":"RU","name":"Ногинск","fclName":"city, village,...","countryName":"Россия","fcodeName":"населенный пункт","adminName1":"МО","lat":"55.86647","fcode":"PPL"},{"adminCode1":"47","lng":"37.27773","geonameId":516215,"toponymName":"Odintsovo","countryId":"2017370","fcl":"P","population":137041,"countryCode":"RU","name":"Одинцово","fclName":"city, village,...","countryName":"Россия","fcodeName":"населенный пункт","adminName1":"МО","lat":"55.67798","fcode":"PPL"}]}';

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
        if(isset($_POST['city']) and strlen($_POST['city'])>2){
            $json_city = file_get_contents("http://api.geonames.org/searchJSON?q=".trim($_POST['city'])."&style=LONG&lang=ru&maxRows=1&username=demo");
            
            //$json_city = '{"totalResultsCount":7740,"geonames":[{"adminCode1":"48","lng":"37.61556","geonameId":524901,"toponymName":"Moscow","countryId":"2017370","fcl":"P","population":10381222,"countryCode":"RU","name":"Москва","fclName":"city, village,...","countryName":"Россия","fcodeName":"столица политического образования","adminName1":"Москва","lat":"55.75222","fcode":"PPLC"}]}';
            $arr_city = json_decode($json_city);
            $json_near = "";
            
            if(isset($arr_city->geonames)){
                foreach($arr_city->geonames as $val){
                    $json_near = file_get_contents("http://api.geonames.org/findNearbyWikipediaJSON?formatted=true&lat=".$val->lat."&lng=".$val->lng."&username=demo&style=full&lang=ru");
                }
                            
                $json_near2 = '{"geonames": [
                  {
                    "summary": "Старое здание оружейной палаты \u2014 постройка начала XIX века, располагавшаяся в Московском кремле около Троицкой башни, была снесена в 1959 году. До 1851 года служило местом хранения и экспозиции выставки Оружейной палаты.  (...)",
                    "elevation": 145,
                    "lng": 37.6156,
                    "distance": "0.0386",
                    "rank": 75,
                    "lang": "ru",
                    "title": "Старое здание Оружейной палаты",
                    "lat": 55.751874,
                    "wikipediaUrl": "ru.wikipedia.org/wiki/%D0%A1%D1%82%D0%B0%D1%80%D0%BE%D0%B5_%D0%B7%D0%B4%D0%B0%D0%BD%D0%B8%D0%B5_%D0%9E%D1%80%D1%83%D0%B6%D0%B5%D0%B9%D0%BD%D0%BE%D0%B9_%D0%BF%D0%B0%D0%BB%D0%B0%D1%82%D1%8B"
                  },
                  {
                    "summary": "Тро́ицкая ба́шня  \u2014 башня с воротами посередине северо-западной стены Московского Кремля, обращена к Александровскому саду.  (...)",
                    "elevation": 142,
                    "lng": 37.614602,
                    "distance": "0.06",
                    "rank": 87,
                    "lang": "ru",
                    "title": "Троицкая башня",
                    "lat": 55.752236,
                    "wikipediaUrl": "ru.wikipedia.org/wiki/%D0%A2%D1%80%D0%BE%D0%B8%D1%86%D0%BA%D0%B0%D1%8F_%D0%B1%D0%B0%D1%88%D0%BD%D1%8F"
                  },
                  {
                    "summary": "Госуда́рственный Кремлёвский дворе́цГосударственный Кремлёвский дворец. (до 1992 года \u2014 Кремлёвский дворец съездов) построен в 1961 году под руководством архитектора М. В. Посохина и при поддержке Н. С. Хрущёва.  (...)",
                    "elevation": 145,
                    "geoNameId": 6956706,
                    "feature": "landmark",
                    "lng": 37.615669,
                    "distance": "0.0878",
                    "countryCode": "RU",
                    "rank": 89,
                    "lang": "ru",
                    "title": "Государственный Кремлёвский дворец",
                    "lat": 55.751433,
                    "wikipediaUrl": "ru.wikipedia.org/wiki/%D0%93%D0%BE%D1%81%D1%83%D0%B4%D0%B0%D1%80%D1%81%D1%82%D0%B2%D0%B5%D0%BD%D0%BD%D1%8B%D0%B9_%D0%9A%D1%80%D0%B5%D0%BC%D0%BB%D1%91%D0%B2%D1%81%D0%BA%D0%B8%D0%B9_%D0%B4%D0%B2%D0%BE%D1%80%D0%B5%D1%86"
                  },
                  {
                    "summary": "Кремлё́вский бале́т \u2014 российский театр, находящийся в Москве на территории Государственного Кремлёвского дворца .  (...)",
                    "elevation": 145,
                    "lng": 37.61555555555556,
                    "distance": "0.0924",
                    "countryCode": "RU",
                    "rank": 80,
                    "lang": "ru",
                    "title": "Кремлёвский балет",
                    "lat": 55.75138888888889,
                    "wikipediaUrl": "ru.wikipedia.org/wiki/%D0%9A%D1%80%D0%B5%D0%BC%D0%BB%D1%91%D0%B2%D1%81%D0%BA%D0%B8%D0%B9_%D0%B1%D0%B0%D0%BB%D0%B5%D1%82"
                  },
                  {
                    "summary": "Дворцовая улица в Московском Кремле идет параллельно западной стене Кремля. Она связывает Троицкую и Дворцовую площади Кремля. Закрыта для посещения туристами. Восточную часть улицы формирует фасад Государственного Кремлёвского дворца и Кавалерский корпус (5-й корпус Кремля) (...)",
                    "elevation": 145,
                    "lng": 37.614857,
                    "distance": "0.0928",
                    "rank": 10,
                    "lang": "ru",
                    "title": "Дворцовая улица (Москва)",
                    "lat": 55.751485,
                    "wikipediaUrl": "ru.wikipedia.org/wiki/%D0%94%D0%B2%D0%BE%D1%80%D1%86%D0%BE%D0%B2%D0%B0%D1%8F_%D1%83%D0%BB%D0%B8%D1%86%D0%B0_%28%D0%9C%D0%BE%D1%81%D0%BA%D0%B2%D0%B0%29"
                  }
                ]}';
        
                $arr_near = json_decode($json_near);
                
                if(isset($arr_near->geonames)){
                    $i = 1;
                    $koords = "";
                    foreach($arr_near->geonames as $val){
                         $koords.= $val->lng.",".$val->lat.",pmntl".$i."~";
                         $i++;
                    }
                    
                    $map = "https://static-maps.yandex.ru/1.x/?l=map&pt=".trim($koords,'~');
                    
                    echo json_encode($map);
                }else{
                    echo json_encode("err-3");
                }
            }else{
                echo json_encode("err-2");
            }
        }else{
            echo json_encode("err-1");
        }
     }
}