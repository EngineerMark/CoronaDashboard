<?php 
    include "inc/header.php";
?>
<div class="row">
    <div class="col-md-6 pb-3">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title font-weight-bold">Reproductionvalues</h4>
                <div class="card-text"><?php
                    $reproData = GetReproductionValues();
                    $reproData = array_slice($reproData, 1, 92);
                    $reproDataReverse = array_reverse($reproData);
                    echo "<h5>Current average: <span class=\"badge badge-primary\">".strval((floatval($reproData[13]["Rt_low"])+floatval($reproData[13]["Rt_up"]))*0.5)."</span></h5><br />";
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
                Last update: <?php echo time_elapsed_string("@".strtotime($reproData[0]["Date"])); ?>
            </div>
        </div><br />
        <div class="card chart-card">
            <div class="card-body pb-0">
                <h4 class="card-title font-weight-bold">Total Cases
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button id="caseDeathPairShowAll" type="button" class="btn btn-sm btn-primary btn-rounded">All</button>
                        <button id="caseDeathPairShowRecent" type="button" class="btn btn-sm btn-primary btn-rounded">Last month</button>
                    </div>
                </h4>
                <div class="d-flex justify-content-between">
                    <h3 class="align-self-end"><?php echo number_format($totalCases); ?></h3>
                    <p class="align-self-end"><?php echo AdditionNumberString($newCases, true, true); ?></p>
                </div>
                <canvas id="caseChart"></canvas>
                <br />
                <h4 class="card-title font-weight-bold">Total Deaths</h4>
                <div class="d-flex justify-content-between">
                    <h3 class="align-self-end"><?php echo number_format($totalDeaths); ?></h3>
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
                <div class="table-responsive-sm">
                    <table id="provinceNumbers" class="table table-hover table-sm sortable">
                        <thead>
                            <tr>
                                <th scope="col">Province</th>
                                <th scope="col">Cases</th>
                                <th scope="col">Deaths</th>
                                <th scope="col">Hosp. Admissions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $regDate = 0;
                                $provinceID = 0;
                                foreach($provinceData as $province){
                                    $dailyEvents = array_values($province->DailyEvents);
                                    $regDate = $dailyEvents[0]->Date;
                                    echo "<tr data-toggle=\"modal\" data-target=\"#provinceData_".$provinceID."\">";
                                    echo "<td>".$province->RegionName."</td>";
                                    echo "<td>".$province->TotalCases." <small>".($dailyEvents[0]->ReportedCases!=0?AdditionNumberString($dailyEvents[0]->ReportedCases,true,true):"")."</small></td>";
                                    echo "<td>".$province->TotalDeaths." <small>".($dailyEvents[0]->ReportedDeaths!=0?AdditionNumberString($dailyEvents[0]->ReportedDeaths,true,true):"")."</small></td>";
                                    echo "<td>".$province->TotalHospitalAdmissions." <small>".($dailyEvents[0]->ReportedHospitalAdmissions!=0?AdditionNumberString($dailyEvents[0]->ReportedHospitalAdmissions,true,true):"")."</small></td>";
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
                Last update: <?php echo time_elapsed_string("@".strtotime($regDate." 10:00:00")); ?>
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
        echo "<div class=\"modal fade\" id=\"provinceData_".$provinceID."\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"provinceData_".$provinceID."_Label\" aria-hidden=\"true\">
                <div class=\"modal-dialog\" role=\"document\">
                    <div class=\"modal-content\">
                        <div class=\"modal-header\">
                            <h5 class=\"modal-title\" id=\"provinceData_".$provinceID."_Label\">".$province->RegionName."</h5>
                            <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                                <span aria-hidden=\"true\">&times;</span>
                            </button>
                        </div>
                        <div class=\"modal-body\">
                            <table id=\"province_".$provinceID."_municipalityNumbers\" class=\"table table-hover table-sm sortable\">
                                <thead>
                                    <tr>
                                        <th scope=\"col\">Province</th>
                                        <th scope=\"col\">Cases</th>
                                        <th scope=\"col\">Deaths</th>
                                        <th scope=\"col\">Hosp. Admissions</th>
                                    </tr>
                                </thead>
                                <tbody>";
                                foreach($Municipalities as $municipality){
                                    $dailyEvents = array_values($municipality->DailyEvents);
                                    echo "<tr>";
                                    echo "<td>".$municipality->RegionName."</td>";
                                    echo "<td>".$municipality->TotalCases." <small>".($dailyEvents[0]->ReportedCases!=0?AdditionNumberString($dailyEvents[0]->ReportedCases,true,true):"")."</small></td>";
                                    echo "<td>".$municipality->TotalDeaths." <small>".($dailyEvents[0]->ReportedDeaths!=0?AdditionNumberString($dailyEvents[0]->ReportedDeaths,true,true):"")."</small></td>";
                                    echo "<td>".$municipality->TotalHospitalAdmissions." <small>".($dailyEvents[0]->ReportedHospitalAdmissions!=0?AdditionNumberString($dailyEvents[0]->ReportedHospitalAdmissions,true,true):"")."</small></td>";
                                    echo "</tr>";
                                }
                                echo "
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>";

        $provinceID++;
    }
    include "inc/footer.php";
?>