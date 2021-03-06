<?php 
    include "inc/header.php";
?>
<div class="row">
    <div class="col-md-6 pb-3">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title font-weight-bold"><i class="fas fa-project-diagram"></i> Reproductionvalues</h4>
                <div class="card-text"><?php
                    $reproData = GetReproductionValues();
                    $reproData = array_slice($reproData, 1, 92);
                    $reproDataReverse = array_reverse($reproData);
                    $averageRepro = (floatval($reproData[13]["Rt_low"])+floatval($reproData[13]["Rt_up"]))*0.5;
                    echo "<h5>Current average: <span class=\"badge badge-primary\">".strval($averageRepro)."</span></h5><br />";
                    echo "<canvas id=\"reproGraph\"></canvas>";
                    echo "<p>These numbers are averages of the country, each region can differ. Data up to 3 months ago. Average goes to 2 weeks ago, using newer values makes it harder to represent the actual state.</p>";
                    echo "<p>This is a key value in tracking the virus. If kept under 1, the daily cases decrease and the virus spreads slower. Above 1 means the virus is spreading more.</p>";
                    for($i=0;$i<7;$i++){
                        // print_r($reproData[$i]);
                        // echo "<br />";
                    }
                ?></div>
            </div>
            <div class="card-footer text-muted text-center">
                <i class="far fa-clock"></i> Last update: <?php echo time_elapsed_string("@".strtotime($reproData[0]["Date"])); ?>
            </div>
        </div><br />
        <div class="card chart-card">
            <div class="card-body pb-0">
                <h4 class="card-title font-weight-bold"><i class="fas fa-head-side-cough"></i> Daily Cases
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button id="caseDeathPairShowAll" type="button" class="btn btn-sm btn-primary btn-rounded">All</button>
                        <button id="caseDeathPairShowRecent" type="button" class="btn btn-sm btn-primary btn-rounded">Last month</button>
                        <button id="caseDeathPairShowPrediction" type="button" class="btn btn-sm btn-primary btn-rounded">Prediction</button>
                    </div>
                </h4>
                <div class="d-flex justify-content-between">
                    <h3 class="align-self-end"><?php echo number_format($totalCases); ?> <small>total</small></h3>
                    <p class="align-self-end"><?php echo AdditionNumberString($newCases, true, true); ?></p>
                </div>
                <canvas id="caseChart"></canvas>
                <br />
                <h4 class="card-title font-weight-bold"><i class="fas fa-skull"></i> Daily Deaths</h4>
                <div class="d-flex justify-content-between">
                    <h3 class="align-self-end"><?php echo number_format($totalDeaths); ?> <small>total</small></h3>
                    <p class="align-self-end"><?php echo AdditionNumberString($newDeaths, true, true); ?></p>
                </div>
                <canvas id="deathChart"></canvas>
                <br />
            </div>
        </div>
    </div>
    <div class="col-md-6 pb-3">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title font-weight-bold"><i class="fas fa-hand-paper"></i> Measures</h4>
                <div class="card-text">
                    <p><small>What can you do to prevent the spread of COVID-19?</small></p>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">Keep 1.5 meter distance from other people <i class="fas fa-people-arrows"></i></li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">Don't shake any hands <i class="fas fa-handshake-alt-slash"></i></li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">Travel outside of rush hours <i class="fas fa-train"></i></li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">Wear a mask in public spaces <i class="fas fa-head-side-mask"></i></li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">Work as much as possible from home <i class="fas fa-laptop-house"></i></li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">Wash your hands often <i class="fas fa-hands-wash"></i></li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">Pay contactless <i class="fas fa-credit-card"></i></li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">Stay at home and get tested if you have any sign of the virus <i class="fas fa-head-side-cough"></i></li>
                    </lul>
                </div>
            </div>
        </div><br />
        <div class="card">
            <div class="card-body">
                <div class="table-responsive-sm">
                    <table id="provinceNumbers" class="table table-hover table-sm sortable">
                        <thead class="bg-light">
                            <tr>
                                <th class="th-sm" scope="col">Province</th>
                                <th class="th-sm" scope="col">Cases</th>
                                <th class="th-sm" scope="col">Deaths</th>
                                <th class="th-sm" scope="col">Hosp. Admissions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $regDate = 0;
                                $provinceID = 0;
                                $heatMap = [];
                                $heatMap["cases"] = [];
                                $heatMap["cases"]["hot"] = [255, 74, 74];
                                $heatMap["cases"]["cold"] = [255, 255, 255];
                                $heatMap["cases"]["high"] = 0;

                                $heatMap["deaths"] = [];
                                $heatMap["deaths"]["hot"] = [82, 82, 82];
                                $heatMap["deaths"]["cold"] = [255, 255, 255];
                                $heatMap["deaths"]["high"] = 0;

                                $heatMap["hospital"] = [];
                                $heatMap["hospital"]["hot"] = [196, 143, 0];
                                $heatMap["hospital"]["cold"] = [255, 255, 255];
                                $heatMap["hospital"]["high"] = 0;

                                foreach($provinceData as $province){
                                    if(intval($province->TotalCases)>$heatMap["cases"]["high"]){
                                        $heatMap["cases"]["high"] = $province->TotalCases;
                                    }
                                    if(intval($province->TotalDeaths)>$heatMap["deaths"]["high"]){
                                        $heatMap["deaths"]["high"] = $province->TotalDeaths;
                                    }
                                    if(intval($province->TotalHospitalAdmissions)>$heatMap["hospital"]["high"]){
                                        $heatMap["hospital"]["high"] = $province->TotalHospitalAdmissions;
                                    }
                                }
                                foreach($provinceData as $province){
                                    $dailyEvents = array_values($province->DailyEvents);
                                    $regDate = $dailyEvents[0]->Date;
                                    $heatmapCases = ColorHeat($heatMap["cases"]["cold"],$heatMap["cases"]["hot"], 0, $heatMap["cases"]["high"], intval($province->TotalCases));
                                    $heatmapDeaths = ColorHeat($heatMap["deaths"]["cold"],$heatMap["deaths"]["hot"], 0, $heatMap["deaths"]["high"], intval($province->TotalDeaths));
                                    $heatmapHospital = ColorHeat($heatMap["hospital"]["cold"],$heatMap["hospital"]["hot"], 0, $heatMap["hospital"]["high"], intval($province->TotalHospitalAdmissions));
                                    echo "<tr data-toggle=\"modal\" data-target=\"#listedStatsProvince_".$provinceID."\">";
                                    echo "<td>".$province->RegionName."</td>";
                                    echo "<td style=\"border-left: 5px solid rgb(".$heatmapCases[0].",".$heatmapCases[1].",".$heatmapCases[2].")\" value=\"".$province->TotalCases."\">".number_format($province->TotalCases)." <small>".AdditionNumberString($dailyEvents[0]->ReportedCases,true,true)."</small></td>";
                                    echo "<td style=\"border-left: 5px solid rgb(".$heatmapDeaths[0].",".$heatmapDeaths[1].",".$heatmapDeaths[2].")\">".number_format($province->TotalDeaths)." <small>".AdditionNumberString($dailyEvents[0]->ReportedDeaths,true,true)."</small></td>";
                                    echo "<td style=\"border-left: 5px solid rgb(".$heatmapHospital[0].",".$heatmapHospital[1].",".$heatmapHospital[2].")\">".number_format($province->TotalHospitalAdmissions)." <small>".AdditionNumberString($dailyEvents[0]->ReportedHospitalAdmissions,true,true)."</small></td>";
                                    echo "</tr>";
                                    $provinceID++;
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
                <small>These are cumulative numbers. Fired patients or recovered people are not reported.</small>
                <small>Click on a province to view more detailed information.</small>
            </div>
            <div class="card-footer text-muted text-center">
                <i class="far fa-clock"></i> Last update: <?php echo time_elapsed_string("@".strtotime($regDate." 10:00:00")); ?>
            </div>
            <div class="card-body">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button id="mapChartTotal" type="button" class="btn btn-sm btn-primary btn-rounded">Total cases</button>
                    <button id="mapChartPerCapita" type="button" class="btn btn-sm btn-primary btn-rounded">Per capita</button>
                </div>
                <div class="netherlandsheatmap" id="netherlandsheatmap"></div>
                <small>Cases per 100.000 inhabitants</small>
            </div>
        </div>
    </div>
</div>
<?php
    $provinceID = 0;
    foreach($provinceData as $province){
        $Municipalities = $province->Municipalities;
        ksort($Municipalities);
        $reversedDaily = $province->DailyEvents;
        $reversedDaily = array_reverse($reversedDaily);
        $reversedDaily = array_values($reversedDaily);
        $heatMap["cases"]["high"] = 0;
        $heatMap["deaths"]["high"] = 0;
        $heatMap["hospital"]["high"] = 0;
        $dailyEvents = array_values($province->DailyEvents);
        echo "<div class=\"modal fade\" id=\"listedStatsProvince_".$provinceID."\" role=\"dialog\" aria-labelledby=\"listedStatsProvince_".$provinceID."-tab\">
                <div class=\"modal-dialog modal-lg\" role=\"document\">
                    <div class=\"modal-content\">
                        <div class=\"modal-header\">
                            <h4 class=\"modal-title w-100\" id=\"listedStatsProvince_".$provinceID."-tab\">".$province->RegionName." <small>".number_format($provinceTable[$province->RegionName][1])." inhabitants</small></h4>
                            <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                            <span aria-hidden=\"true\">&times;</span>
                            </button>
                        </div>
                        <div class=\"modal-body\">
                            <div class=\"row\">
                                <div class=\"col\">
                                    <p>Total Cases: ".number_format($province->TotalCases)." <small>".AdditionNumberString($reversedDaily[count($reversedDaily)-1]->ReportedCases, true, true)."</small></p>
                                    <p>Total Deaths: ".number_format($province->TotalDeaths)." <small>".AdditionNumberString($reversedDaily[count($reversedDaily)-1]->ReportedDeaths, true, true)."</small></p>
                                </div>
                                <div class=\"col\">
                                    <p>".number_format(PerValue($province->TotalCases,$provinceTable[$province->RegionName][1],1000))."/1,000 tested positive</p>
                                    <p>".number_format(PerValue($province->TotalDeaths,$provinceTable[$province->RegionName][1],1000))."/1,000 died</p>
                                </div>
                            </div>
                            <div class=\"row\">
                                <div class=\"col-lg-6\">
                                    <small>Total cases</small>
                                    <canvas id=\"totalCasesProvince_".$provinceID."\"></canvas>
                                </div>
                                <div class=\"col-lg-6\">
                                    <small>Total deaths</small>
                                    <canvas id=\"totalDeathsProvince_".$provinceID."\"></canvas>
                                </div>
                            </div>
                            <table id=\"province_".$provinceID."_municipalityNumbers\" class=\"table table-hover table-sm sortable\">
                                <thead class=\"bg-light\">
                                    <tr>
                                        <th scope=\"col\">Municipality (".count($Municipalities).")</th>
                                        <th scope=\"col\">Cases ".AdditionNumberString($dailyEvents[0]->ReportedCases,true,true)."</th>
                                        <th scope=\"col\">Deaths ".AdditionNumberString($dailyEvents[0]->ReportedDeaths,true,true)."</th>
                                        <th scope=\"col\">Hosp. Admissions ".AdditionNumberString($dailyEvents[0]->ReportedHospitalAdmissions,true,true)."</th>
                                    </tr>
                                </thead>
                                <tbody>";
                                    foreach($Municipalities as $municipality){
                                        if(intval($municipality->TotalCases)>$heatMap["cases"]["high"]){
                                            $heatMap["cases"]["high"] = $municipality->TotalCases;
                                        }
                                        if(intval($municipality->TotalDeaths)>$heatMap["deaths"]["high"]){
                                            $heatMap["deaths"]["high"] = $municipality->TotalDeaths;
                                        }
                                        if(intval($municipality->TotalHospitalAdmissions)>$heatMap["hospital"]["high"]){
                                            $heatMap["hospital"]["high"] = $municipality->TotalHospitalAdmissions;
                                        }
                                    }
                                    foreach($Municipalities as $municipality){
                                        $inRandstad = in_array($municipality->RegionName,$regions["Randstad"]);
                                        $isCapital = $municipality->RegionName==$provinceTable[$province->RegionName][2];
                                        $dailyEvents = array_values($municipality->DailyEvents);
                                        $icons = "";
                                        $heatmapCases = ColorHeat($heatMap["cases"]["cold"],$heatMap["cases"]["hot"], 0, $heatMap["cases"]["high"], intval($municipality->TotalCases));
                                        $heatmapDeaths = ColorHeat($heatMap["deaths"]["cold"],$heatMap["deaths"]["hot"], 0, $heatMap["deaths"]["high"], intval($municipality->TotalDeaths));
                                        $heatmapHospital = ColorHeat($heatMap["hospital"]["cold"],$heatMap["hospital"]["hot"], 0, $heatMap["hospital"]["high"], intval($municipality->TotalHospitalAdmissions));
                                        if($inRandstad){
                                                $icons.="<i class=\"fas fa-city material-tooltip-main\" data-toggle=\"tooltip\" title=\"Randstad\"></i> ";
                                            }
                                            if($isCapital){
                                                $icons.="<i class=\"fas fa-crown material-tooltip-main\" data-toggle=\"tooltip\" title=\"Province capital\"></i>";
                                            }
                                            echo "<tr>";
                                            echo "<td class=\"d-flex justify-content-between align-items-center\">".$municipality->RegionName." <small>".$icons."</small></td>";
                                            echo "<td style=\"border-left: 5px solid rgb(".$heatmapCases[0].",".$heatmapCases[1].",".$heatmapCases[2].")\">".number_format($municipality->TotalCases)." <small>".AdditionNumberString($dailyEvents[0]->ReportedCases,true,true)."</small></td>";
                                            echo "<td style=\"border-left: 5px solid rgb(".$heatmapDeaths[0].",".$heatmapDeaths[1].",".$heatmapDeaths[2].")\">".number_format($municipality->TotalDeaths)." <small>".AdditionNumberString($dailyEvents[0]->ReportedDeaths,true,true)."</small></td>";
                                            echo "<td style=\"border-left: 5px solid rgb(".$heatmapHospital[0].",".$heatmapHospital[1].",".$heatmapHospital[2].")\">".number_format($municipality->TotalHospitalAdmissions)." <small>".AdditionNumberString($dailyEvents[0]->ReportedHospitalAdmissions,true,true)."</small></td>";
                                            echo "</tr>";
                                        }
                                echo "</tbody>
                            </table>
                            <br />
                            <p>Legend</p>
                            <p><small><i class=\"fas fa-crown\"></i> Province capital, <i class=\"fas fa-city\"></i> Randstad</small></p>
                        </div>
                    </div>
                </div>
            </div>";
        echo "<script>
                new Chart(
                    document.getElementById(\"totalCasesProvince_".$provinceID."\"),{
                        \"type\":\"LineWithLine\",
                        \"data\":{
                            \"labels\": ["; foreach($reversedDaily as $_dates){ echo "\"".date("F j, Y",strtotime($_dates->Date))."\",";} echo"],
                            \"datasets\":[{
                                pointHitRadius: 20,
                                \"Cases\": \"Deaths\",
                                \"fill\": false,
                                \"borderColor\": \"#eba834\",
                                \"pointBackgroundColor\": \"#eba834\",
                                \"data\": ["; $cumulativeCasesBuffer = 0; foreach($reversedDaily as $_value){ $cumulativeCasesBuffer+=(isset($_value->ReportedCases)?$_value->ReportedCases:0); echo $cumulativeCasesBuffer.",";} echo "]
                            }]
                        },
                        \"options\":{
                            \"responsive\": true,
                            \"legend\": {
                                \"display\": false
                            },
                            \"tooltips\": {
                                \"intersect\": false,
                                \"custom\": function(tooltip) {
                                    if (!tooltip) return;
                                    // disable displaying the color box;
                                    tooltip.displayColors = false;
                                }
                            },
                            \"elements\": {
                                \"line\": {
                                    \"tension\": 0.5
                                },
                                \"point\":{
                                    \"radius\": 0
                                }
                            },
                            \"scales\": {
                                \"xAxes\": [{
                                    \"gridLines\": {
                                        \"display\": false,
                                    },
                                    \"ticks\": {
                                        \"autoskip\": true,
                                        \"autoSkipPadding\": 30,
                                    }
                                }],
                                \"yAxes\": [{
                                    \"gridLines\": {
                                        \"drawBorder\": false
                                    },
                                    type: 'linear',
                                    \"ticks\": {
                                        \"maxTicksLimit\": 5,
                                        \"padding\": 15,
                                        \"callback\": function(value) {
                                            var ranges = [
                                                { divider: 1e6, suffix: 'M' },
                                                { divider: 1e3, suffix: 'k' }
                                            ];
                                            function formatNumber(n) {
                                                for (var i = 0; i < ranges.length; i++) {
                                                    if (n >= ranges[i].divider) {
                                                        return (n / ranges[i].divider).toString() + ranges[i].suffix;
                                                    }
                                                }
                                                return n;
                                            }
                                            return formatNumber(value);
                                        }
                                    },
                                }]
                            }
                        }
                    }
                );
                new Chart(
                    document.getElementById(\"totalDeathsProvince_".$provinceID."\"),{
                        \"type\":\"LineWithLine\",
                        \"data\":{
                            \"labels\": ["; foreach($reversedDaily as $_dates){ echo "\"".date("F j, Y",strtotime($_dates->Date))."\",";} echo"],
                            \"datasets\":[{
                                pointHitRadius: 20,
                                \"label\": \"Deaths\",
                                \"fill\": false,
                                \"borderColor\": \"#eba834\",
                                \"pointBackgroundColor\": \"#eba834\",
                                \"data\": ["; $cumulativeDeathsBuffer = 0; foreach($reversedDaily as $_value){ $cumulativeDeathsBuffer+=(isset($_value->ReportedDeaths)?$_value->ReportedDeaths:0); echo $cumulativeDeathsBuffer.",";} echo "]
                            }]
                        },
                        \"options\":{
                            \"responsive\": true,
                            \"legend\": {
                                \"display\": false
                            },
                            \"tooltips\": {
                                \"intersect\": false,
                                \"custom\": function(tooltip) {
                                    if (!tooltip) return;
                                    // disable displaying the color box;
                                    tooltip.displayColors = false;
                                }
                            },
                            \"elements\": {
                                \"line\": {
                                    \"tension\": 0.5
                                },
                                \"point\":{
                                    \"radius\": 0
                                }
                            },
                            \"scales\": {
                                \"xAxes\": [{
                                    \"gridLines\": {
                                        \"display\": false,
                                    },
                                    \"ticks\": {
                                        \"autoskip\": true,
                                        \"autoSkipPadding\": 30,
                                    }
                                }],
                                \"yAxes\": [{
                                    \"gridLines\": {
                                        \"drawBorder\": false
                                    },
                                    type: 'linear',
                                    \"ticks\": {
                                        \"maxTicksLimit\": 5,
                                        \"padding\": 15,
                                        \"callback\": function(value) {
                                            var ranges = [
                                                { divider: 1e6, suffix: 'M' },
                                                { divider: 1e3, suffix: 'k' }
                                            ];
                                            function formatNumber(n) {
                                                for (var i = 0; i < ranges.length; i++) {
                                                if (n >= ranges[i].divider) {
                                                    return (n / ranges[i].divider).toString() + ranges[i].suffix;
                                                }
                                                }
                                                return n;
                                            }
                                            return formatNumber(value);
                                        }
                                    },
                                }]
                            }
                        },
                    }
                );
            </script>";
        $provinceID++;
    }
    include "inc/footer.php";
?>