<?php
    include "inc/functions.php";

    $hospitalData = GetHospitalData();
    $icBedsUsage = $hospitalData["intensive_care_beds_occupied"]["values"];
    $hospBedsUsage = $hospitalData["hospital_beds_occupied"]["values"];

    $icBedsUsageReverse = array_reverse($hospBedsUsage);
    $hospBedsUsageReverse = array_reverse($icBedsUsage);
    $hospBedsDifference = $hospBedsUsageReverse[0]["covid_occupied"]-$hospBedsUsageReverse[1]["covid_occupied"];
    $icBedsDifference = $icBedsUsageReverse[0]["covid_occupied"]-$icBedsUsageReverse[1]["covid_occupied"];

    $provinceData = GetProvinceData();
    $totalCases = 0;
    $totalDeaths = 0;
    $newCases = 0;
    $newDeaths = 0;
    foreach($provinceData as $province){
        $totalCases+=$province->TotalCases;
        $totalDeaths+=$province->TotalDeaths;
        $dailyEvents = array_values($province->DailyEvents);
        $newCases += $dailyEvents[0]->ReportedCases;
        $newDeaths += $dailyEvents[0]->ReportedDeaths;
    }
    $dataPointsNationwide = array();
    foreach($provinceData as $province){
        $_provinceData = $province->DailyEvents;
        foreach($_provinceData as $dataPoint){
            $date = $dataPoint->Date;
            if(!array_key_exists($date,$dataPointsNationwide)){
                $newNationDataPoint = new DataPoint();
                $newNationDataPoint->Date = $date;
                $dataPointsNationwide[$date] = $newNationDataPoint;
                // echo $this->Municipalities[$data["Municipality_name"]]->RegionName;
            }
            $dataPointsNationwide[$date]->AddDataPoint($dataPoint->Clone());
        }
    }
    $dataPointsNationwide = array_reverse($dataPointsNationwide);
    // print_r($dataPointsNationwide);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="title" content="Netherlands COVID-19 Coronavirus Dashboard">
        <meta name="description" content="Visualization of the COVID-19 pandemic in the Netherlands">
        <meta name="keywords" content="corona, covid, coronavirus, netherlands, dutch, graphs, data, stats, statistics, graph">
        <meta name="robots" content="index, follow">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="language" content="English">

        <title>Coronavirus COVID-19 Dashboard</title>

        <link rel="stylesheet" href="fontawesome/css/all.css">
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/mdb.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">

        <script src="js/jquery.min.js"></script>
        <script src="js/popper.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/addons/datatables.min.js"></script>

        <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
        <script src="https://cdn.amcharts.com/lib/4/maps.js"></script>
        <script src="https://cdn.amcharts.com/lib/4/geodata/netherlandsHigh.js"></script>
        <script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/hammerjs@2.0.8"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom@0.7.7"></script>

    </head>
    <body>
        <div class="container">
            <nav class="navbar navbar-light blue lighten-4">
                <span class="navbar-brand"><i class="fas fa-virus"></i> COVID-19 Dashboard</span>
            </nav>
            <br />
            <div class="alert alert-danger" role="alert"><i class="fas fa-exclamation-triangle"></i> The current situation in the Netherlands is <strong>bad</strong>. Please minimize as much physical contact as possible.</div>
            <div class="card card-image text-center" style="background-image: url(https://ec.europa.eu/culture/sites/default/files/styles/oe_theme_medium_no_crop/public/2020-09/cover-Covid19.png?itok=PPFgniH3);">

                <!-- Content -->
                <div class="text-white text-center align-items-center rgba-black-strong py-5 px-4 ">
                    <div class="text-center">
                        <h5 class="pink-text"><i class="fas fa-virus"></i> COVID-19</h5>
                        <h3 class="card-title pt-2"><strong>Dutch Coronavirus Dashboard</strong></h3>
                        <p>Visualizing the coronavirus pandemic in the Netherlands. Currently one of the worst hit countries in europe.</p>
                        <!-- <a class="btn btn-pink"><i class="fas fa-clone left"></i> View project</a> -->
                        <button data-toggle="modal" data-target="#covidChat" type="button" class="btn btn-danger btn-sm btn-rounded">Discuss</button>
                    </div>
                </div>

            </div>
            <br />
            <div class="row text-black-50">
                <div class="col-lg-4">
                    <div class="card chart-card">
                        <div class="card-body">
                            <h5 class="body-title">Total Cases</h5>
                            <h6><?php echo number_format($totalCases)." <small data-toggle=\"tooltip\" title=\"Registered in last 24 hours\">".AdditionNumberString($newCases,true,true)."</small>"; ?></h6>
                            <canvas id="headerTotalCasesChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card chart-card">
                        <div class="card-body">
                            <h5 class="body-title">Total Deaths</h5>
                            <h6><?php echo number_format($totalDeaths)." <small data-toggle=\"tooltip\" title=\"Registered in last 24 hours\">".AdditionNumberString($newDeaths,true,true)."</small>"; ?></h6>
                            <canvas id="headerTotalDeathsChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card chart-card">
                        <div class="card-body">
                            <h5 class="body-title">Hospitalizations</h5>
                            <h6><?php echo number_format($hospBedsUsageReverse[0]["covid_occupied"]+$icBedsUsageReverse[0]["covid_occupied"])." <small data-toggle=\"tooltip\" title=\"Registered in last 24 hours\">".AdditionNumberString($hospBedsDifference+$icBedsDifference,true,true)."</small>"; ?></h6>
                            <canvas id="headerHospitalChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <br />
