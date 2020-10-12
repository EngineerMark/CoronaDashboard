<?php include "inc/header.php"; ?>

<div class="row">
    <div class="col-md-6 pb-3">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Reproductionvalues</h4>
                <div class="card-text"><?php
                    $reproData = GetReproductionValues();
                    $reproData = array_slice($reproData, 1, 62);
                    $reproDataReverse = array_reverse($reproData);
                    echo "<h5>Current average: <span class=\"badge badge-primary\">".strval((floatval($reproData[13]["Rt_low"])+floatval($reproData[13]["Rt_up"]))*0.5)."</span></h5><br />";
                    echo "<canvas id=\"reproGraph\"></canvas>";
                    echo "These numbers are averages of the country, each region can differ. Data up to 2 months ago. Average goes to 2 weeks ago, using newer values makes it harder to represent the actual state.";
                    for($i=0;$i<7;$i++){
                        // print_r($reproData[$i]);
                        // echo "<br />";
                    }
                ?></div>
            </div>
            <div class="card-footer text-muted text-center">
                Last update: <?php echo time_elapsed_string("@".strtotime($reproData[0]["Date"])); ?>
            </div>
        </div>
    </div>
    <div class="col-md-6 pb-3">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive-sm">
                    <table id="provinceNumbers" class="table">
                        <thead>
                            <tr>
                                <th scope="col">Province</th>
                                <th scope="col">Cases</th>
                                <th scope="col">Deaths</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $provinceData = GetProvinceData();
                                // print_r($provinceData);
                                foreach($provinceData as $province){
                                    echo "<tr>";
                                    echo "<td>".$province->RegionName."</td>";
                                    echo "<td>".$province->TotalCases."</td>";
                                    echo "<td>".$province->TotalDeaths."</td>";
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                    <script>
                        $(document).ready(function () {
                        $('#provinceNumbers').DataTable();
                        $('.dataTables_length').addClass('bs-select');
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    new Chart(
        document.getElementById("reproGraph"),{
            "type":"line",
            "data":{
                "labels":[<?php foreach($reproDataReverse as $reproVal){ echo "\"".$reproVal["Date"]."\",";} ?>],
                "datasets":[{
                    "label":"R low",
                    // "data":[65,59,80,81,56,55,40],
                    "data":[<?php foreach($reproDataReverse as $reproVal){ echo $reproVal["Rt_low"].",";} ?>],
                    "fill":"+1",
                    "borderColor":"rgb(75, 192, 192)",
                    "lineTension":0.1
                },
                {
                    "label":"R high",
                    // "data":[65,59,80,81,56,55,40],
                    "data":[<?php foreach($reproDataReverse as $reproVal){ echo $reproVal["Rt_up"].",";} ?>],
                    "fill":false,
                    "borderColor":"rgb(75, 192, 192)",
                    "lineTension":0.1
                },
                {
                    "label":"R avg",
                    // "data":[65,59,80,81,56,55,40],
                    "data":[<?php foreach($reproDataReverse as $reproVal){ echo (isset($reproVal["Rt_avg"])?$reproVal["Rt_avg"]:"").",";} ?>],
                    "fill":false,
                    "borderColor":"rgb(50, 168, 82)",
                    "lineTension":0.1
                }]
            },
            "options":{}});
</script>
<?php include "inc/footer.php"; ?>