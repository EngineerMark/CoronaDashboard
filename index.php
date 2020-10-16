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
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="listedStatsProvinces" role="tabpanel" aria-labelledby="listedStatsProvinces-tab">
                        <div class="table-responsive-sm">
                            <p><i class="fas fa-angle-double-right"></i> Netherlands</p>
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
                                        foreach($provinceData as $province){
                                            $dailyEvents = array_values($province->DailyEvents);
                                            $regDate = $dailyEvents[0]->Date;
                                            echo "<tr data-toggle=\"tab\" data-target=\"#listedStatsProvince_".$provinceID."\">";
                                            echo "<td>".$province->RegionName."</td>";
                                            echo "<td value=\"".$province->TotalCases."\">".$province->TotalCases." <small>".($dailyEvents[0]->ReportedCases!=0?AdditionNumberString($dailyEvents[0]->ReportedCases,true,true):"")."</small></td>";
                                            echo "<td>".$province->TotalDeaths." <small>".($dailyEvents[0]->ReportedDeaths!=0?AdditionNumberString($dailyEvents[0]->ReportedDeaths,true,true):"")."</small></td>";
                                            echo "<td>".$province->TotalHospitalAdmissions." <small>".($dailyEvents[0]->ReportedHospitalAdmissions!=0?AdditionNumberString($dailyEvents[0]->ReportedHospitalAdmissions,true,true):"")."</small></td>";
                                            echo "</tr>";
                                            $provinceID++;
                                        }
                                    ?>
                                </tbody>
                            </table>
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
                            $dailyValues = array_values($province->DailyEvents);
                            echo "<div class=\"tab-pane fade\" id=\"listedStatsProvince_".$provinceID."\" role=\"tabpanel\" aria-labelledby=\"listedStatsProvince_".$provinceID."-tab\">
                                    <p><i class=\"fas fa-angle-double-right\"></i> <a data-toggle=\"tab\" data-target=\"#listedStatsProvinces\">Netherlands</a> <i class=\"fas fa-angle-double-right\"></i> ".$province->RegionName."</p>
                                    <div class=\"row\">
                                        <div class=\"col\">
                                            <p>Total Cases: ".number_format($province->TotalCases)." <small>".AdditionNumberString($dailyValues[0]->ReportedCases, true, true)."</small></p>
                                            <p>Total Deaths: ".number_format($province->TotalDeaths)." <small>".AdditionNumberString($dailyValues[0]->ReportedDeaths, true, true)."</small></p>
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
                                                <th scope=\"col\">Cases</th>
                                                <th scope=\"col\">Deaths</th>
                                                <th scope=\"col\">Hosp. Admissions</th>
                                            </tr>
                                        </thead>
                                        <tbody>";
                                            foreach($Municipalities as $municipality){
                                                $inRandstad = in_array($municipality->RegionName,$regions["Randstad"]);
                                                $isCapital = $municipality->RegionName==$provinceTable[$province->RegionName][2];
                                                $dailyEvents = array_values($municipality->DailyEvents);
                                                $icons = "";
                                                if($inRandstad){
                                                    $icons.="<i class=\"fas fa-city material-tooltip-main\" data-toggle=\"tooltip\" title=\"Randstad\"></i> ";
                                                }
                                                if($isCapital){
                                                    $icons.="<i class=\"fas fa-crown material-tooltip-main\" data-toggle=\"tooltip\" title=\"Province capital\"></i>";
                                                }
                                                echo "<tr>";
                                                echo "<td class=\"d-flex justify-content-between align-items-center\">".$municipality->RegionName." <small>".$icons."</small></td>";
                                                echo "<td>".$municipality->TotalCases." <small>".($dailyEvents[0]->ReportedCases!=0?AdditionNumberString($dailyEvents[0]->ReportedCases,true,true):"")."</small></td>";
                                                echo "<td>".$municipality->TotalDeaths." <small>".($dailyEvents[0]->ReportedDeaths!=0?AdditionNumberString($dailyEvents[0]->ReportedDeaths,true,true):"")."</small></td>";
                                                echo "<td>".$municipality->TotalHospitalAdmissions." <small>".($dailyEvents[0]->ReportedHospitalAdmissions!=0?AdditionNumberString($dailyEvents[0]->ReportedHospitalAdmissions,true,true):"")."</small></td>";
                                                echo "</tr>";
                                            }
                                        echo "</tbody>
                                    </table>
                                    <br />
                                    <p>Legend</p>
                                    <p><small><i class=\"fas fa-crown\"></i> Province capital, <i class=\"fas fa-city\"></i> Randstad</small></p>
                                </div>";
                            $provinceID++;
                        }
                    ?>
                </div>
                <small>These are cumulative numbers. Fired patients or recovered people are not reported.</small>
                <small>Click on a province to view more detailed information.</small>
            </div>
            <div class="card-footer text-muted text-center">
                <i class="far fa-clock"></i> Last update: <?php echo time_elapsed_string("@".strtotime($regDate." 10:00:00")); ?>
            </div>
            <div class="card-body">
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
        echo "<script>
                new Chart(
                    document.getElementById(\"totalCasesProvince_".$provinceID."\"),{
                        \"type\":\"LineWithLine\",
                        \"data\":{
                            \"labels\": ["; foreach($reversedDaily as $_dates){ echo "\"".date("F j, Y",strtotime($_dates->Date))."\",";} echo"],
                            \"datasets\":[{
                                pointHitRadius: 20,
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
                                    \"id\": 'left-y-axis',
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
                                    \"position\": 'left'
                                }]
                            }
                        },
                    }
                );
                new Chart(
                    document.getElementById(\"totalDeathsProvince_".$provinceID."\"),{
                        \"type\":\"LineWithLine\",
                        \"data\":{
                            \"labels\": ["; foreach($reversedDaily as $_dates){ echo "\"".date("F j, Y",strtotime($_dates->Date))."\",";} echo"],
                            \"datasets\":[{
                                pointHitRadius: 20,
                                \"fill\": false,
                                \"borderColor\": \"#eba834\",
                                \"pointBackgroundColor\": \"#eba834\",
                                \"data\": ["; $cumulativeDeathsBuffer = 0; foreach($reversedDaily as $_value){ $cumulativeDeathsBuffer+=(isset($_value->ReportedCases)?$_value->ReportedDeaths:0); echo $cumulativeDeathsBuffer.",";} echo "]
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
                                    \"id\": 'left-y-axis',
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
                                    \"position\": 'left'
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