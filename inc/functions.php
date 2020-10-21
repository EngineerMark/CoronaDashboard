<?php

date_default_timezone_set("Europe/Amsterdam");
$dutchPopulation = 17019800;

$provinceTable = [
    "Drenthe" => ["NL-DR", 492167, "Assen"],
    "Flevoland" => ["NL-FL", 416546, "Lelystad"],
    "Friesland" => ["NL-FR", 647672, "Leeuwarden"],
    "Gelderland" => ["NL-GE", 2071972, "Arnhem"],
    "Groningen" => ["NL-GR", 583990, "Groningen"],
    "Limburg" => ["NL-LI", 1116137, "Maastricht"],
    "Noord-Brabant" => ["NL-NB", 2544806, "'s-Hertogenbosch"],
    "Noord-Holland" => ["NL-NH", 2853359, "Haarlem"],
    "Overijssel" => ["NL-OV", 1156431, "Zwolle"],
    "Utrecht" => ["NL-UT", 1342158, "Utrecht"],
    "Zeeland" => ["NL-ZE", 383032, "Middelburg"],
    "Zuid-Holland" => ["NL-ZH", 3673893,"'s-Gravenhage"]
];

$regions = [
    "Randstad" => ["'s-Gravenhage","Amsterdam","Rotterdam",
    "Utrecht","Almere","Amersfoort","Haarlem","Zaanstad",
    "Haarlemmermeer","Zoetermeer","Leiden","Dordrecht","Nieuwegein",
    "Alphen aan den Rijn","Westland","Delft","Alkmaar","Prumerend",
    "Schiedam","Hoorn","Vlaardingen","Gouda"]
];

function ColorHeat($rgbHot, $rgbCold, $low, $high, $value){
    $t = ($value-$low)/($high-$low);
    // echo $t."<br />";
    // $t = min(1,max(0,(log10($t)+1)*0.5));
    // $t = sin($t*pi()*0.5);
    $r = Lerp($rgbHot[0], $rgbCold[0], $t);
    $g = Lerp($rgbHot[1], $rgbCold[1], $t);
    $b = Lerp($rgbHot[2], $rgbCold[2], $t);

    return array(round($r),round($g),round($b));
}

function Lerp($a, $b, $t){
    return $a*(1-$t)+$b*$t;
}

//Adds an arrow up, down or a straight line if number is negative/positive,
//used for additions/subtractions on cases etc
function AdditionNumberString($val, $positiveIsBad = true, $formatNumber = false){
    $front = "";
    if($val<0){
        $front = "<span class=\"text-success\"><i class=\"fas fa-caret-down\"></i>";
    }elseif($val>0){
        $front = "<span class=\"text-danger\"><i class=\"fas fa-caret-up\"></i>";
    }else{
        $front = "<span class=\"text-black-50\"><i class=\"fas fa-sort\"></i>";
    }
    $back = "</span>";
    $val = abs($val);
    if($formatNumber){
        $val = number_format($val);
    }

    return $front.$val.$back;
}

// a = cases, b = pop, c = per 100k
// ^ just an example
function PerValue($a, $b, $c){
    return $a/$b*$c;
}

function GetHospitalData(){
    $data = ApiCallArray("https://coronadashboard.rijksoverheid.nl/json/NL.json");

    return $data;
}

function GetReproductionValues(){
    $data = ApiCallArray("https://data.rivm.nl/covid-19/COVID-19_reproductiegetal.json");
    $data = array_reverse($data);
    return $data;
    // print_r($data[14]);
}

function GetProvinceData(){
    $data = ApiCallArray("https://data.rivm.nl/covid-19/COVID-19_aantallen_gemeente_per_dag.json");
    $data = array_reverse($data);

    // print_r($data[66]);
    // echo "<br />";
    // echo "<br />";

    $provinces = [];
    foreach($data as $ProvinceDataPiece){
        $localProvince = $ProvinceDataPiece["Province"];
        if(!array_key_exists($localProvince, $provinces)){
            $newProvince = new ProvinceData($localProvince);
            $provinces[$localProvince] = $newProvince;
        }
        $provinces[$localProvince]->Add($ProvinceDataPiece);
    }
    ksort($provinces);

    return $provinces;
    // print_r($data[14]);
}

class DataPoint{
    public $Date = "";
    public $ReportedCases = 0;
    public $ReportedDeaths = 0;
    public $ReportedHospitalAdmissions = 0;
    
    public function Add($data){
        if(empty($this->Date)){
            $this->Date = $data["Date_of_publication"];
        }
        $this->ReportedCases += $data["Total_reported"];
        $this->ReportedDeaths += $data["Deceased"];
        $this->ReportedHospitalAdmissions += $data["Hospital_admission"];
    }

    public function AddDataPoint($data){
        $this->ReportedCases+=$data->ReportedCases;
        $this->ReportedDeaths+=$data->ReportedDeaths;
        $this->ReportedHospitalAdmissions+=$data->ReportedHospitalAdmissions;
    }

    public function Clone(){
        $newObject = new DataPoint();
        $newObject->Date = $this->Date;
        $newObject->ReportedCases = $this->ReportedCases;
        $newObject->ReportedDeaths = $this->ReportedDeaths;
        $newObject->ReportedHospitalAdmissions = $this->ReportedHospitalAdmissions;
        return $newObject;
    }
}

class RegionData{
    public $RegionName;

    public $TotalCases = 0;
    public $TotalDeaths = 0;
    public $TotalHospitalAdmissions = 0;

    public $DailyEvents = array();

    public function __construct($name) {
        $this->RegionName = $name;
    }

    public function Add($data){
        $this->TotalCases += $data["Total_reported"];
        $this->TotalDeaths += $data["Deceased"];
        $this->TotalHospitalAdmissions += $data["Hospital_admission"];

        if(!array_key_exists($data["Date_of_publication"],$this->DailyEvents)){
            $this->DailyEvents[$data["Date_of_publication"]] = new DataPoint();
            // echo "New data point for ".$this->RegionName.", date ".$data["Date_of_publication"]."<br />";
        }
        $this->DailyEvents[$data["Date_of_publication"]]->Add($data);
        // echo "Added to data point for ".$this->RegionName.", date ".$data["Date_of_publication"]."<br />";

        // $DailyEvents[0] = "Test";
    }
}

class ProvinceData extends RegionData{
    public $Municipalities = [];

    public function Add($data){
        parent::Add($data);

        if($data["Municipality_name"]!=null){
            if(!array_key_exists($data["Municipality_name"],$this->Municipalities)){
                $newMunicipality = new MunicipalityData($data["Municipality_name"]);
                $this->Municipalities[$data["Municipality_name"]] = $newMunicipality;
                // echo $this->Municipalities[$data["Municipality_name"]]->RegionName;
            }
            $this->Municipalities[$data["Municipality_name"]]->Add($data);
        }
    }
}

class MunicipalityData extends RegionData{
    public function Add($data){
        parent::Add($data);
    }
}

function ApiCallArray($url){
    $output = ApiCall($url);
    $response = json_decode($output, true);
    return $response;
}

function ApiCall($url){
    if (!is_dir("./api_cache/")) {
        mkdir("api_cache", 0777, true);
    }

    $fileUrl = basename($url);

    $openApi = false;
    $cacheReady = false;
    $path = "api_cache/".$fileUrl;
    $ageLimit = 1800;
    if(file_exists($path)){
        $cacheReady = true;
        $age = time()-filemtime($path);
        if($age>$ageLimit 
            // || (($age/60/60<intval("H")-14)&&($age<$ageLimit)&&(intval(date("H"))>=14))
            ){
            $openApi = true;
        }            
    }
    $output = "";
    if((!$openApi && $cacheReady)||file_exists($path."tmp")||!url_exists($url)){
        $cachefile = fopen($path, "r");
        $output = fread($cachefile,filesize($path));
        fclose($cachefile);
    }else{
        $cachingProcess = fopen($path."_tmp","w");
        fclose($cachingProcess);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
        $output = curl_exec($curl);
        curl_close($curl);
        $newcache = fopen($path, "w");
        fwrite($newcache, $output);
        fclose($newcache);
    }
    if(file_exists($path."_tmp")){
        unlink($path."_tmp");
    }
    return $output;
}

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = round($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function url_exists($url) {
    return curl_init($url) !== false;
}
?>