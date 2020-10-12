<?php

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

class RegionData{
    public $RegionName;

    public $TotalCases = 0;
    public $TotalDeaths = 0;
    public $TotalHospitalAdmissions = 0;

    public function __construct($name) {
        $this->RegionName = $name;
    }

    public function Add($data){
        $this->TotalCases += $data["Total_reported"];
        $this->TotalDeaths += $data["Deceased"];
        $this->TotalHospitalAdmissions += $data["Hospital_admission"];
    }
}

class ProvinceData extends RegionData{
    public $Municipalities = [];

    public function Add($data){
        parent::Add($data);

        if(!array_key_exists($data["Municipality_name"],$this->Municipalities)){
            $newMunicipality = new MunicipalityData($data["Municipality_name"]);
            $this->Municipalities[$data["Municipality_name"]] = $newMunicipality;
        }
        $this->Municipalities[$data["Municipality_name"]]->Add($data);
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

    $fileUrl = base64_encode($url);

    $openApi = false;
    $cacheReady = false;
    $path = "api_cache/".$fileUrl;
    $ageLimit = 6000;
    if(file_exists($path)){
        $cacheReady = true;
        $age = time()-filemtime($path);
        if($age>$ageLimit){
            $openApi = true;
        }            
    }
    $output = "";
    if(!$openApi && $cacheReady){
        $cachefile = fopen($path, "r");
        $output = fread($cachefile,filesize($path));
        fclose($cachefile);
    }else{
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
?>