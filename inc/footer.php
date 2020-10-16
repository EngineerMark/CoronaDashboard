                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <!-- About -->
                                <h4 class="card-title font-weight-bold"><i class="fas fa-info-circle"></i> About</h4>
                                <div class="card-text">
                                    This website mirrors alot from the official <a target="_blank" href="https://coronadashboard.rijksoverheid.nl/">RIVM Dashboard</a>, 
                                    but might contain few invisible data information. 
                                    The goal of this website is primarly to seperate it to a standalone location. 
                                    This is also a personal project of mine to develop in these homestaying times.
                                    <br />
                                    The Netherlands does not provide official numbers on recovered COVID-19 patients. Therefore this is not included on the website (yet).
                                    <br />
                                    <br />
                                    Please stay at home and wear a mask when near other people.
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <!-- Credits -->
                                <h4 class="card-title font-weight-bold"><i class="fas fa-address-book"></i> Credits and Sources</h4>
                                <div class="card-text">
                                    <small>A list where all external content comes from.</small>
                                    <ul>
                                        <li><a target="_blank" href="https://rivm.nl/">RIVM</a>, <a target="_blank" href="https://rijksoverheid.nl/">Rijksoverheid</a></li>
                                        <li><a target="_blank" href="https://ec.europa.eu/">European Commission</a></li>
                                        <li><a target="_blank" href="https://mdbootstrap.com/">Material Design Bootstrap</a></li>
                                        <li><a target="_blank" href="https://fontawesome.com/">Font Awesome</a></li>
                                        <li><a target="_blank" href="https://www.chartjs.org/">ChartJS</a>, <a target="_blank" href="https://www.amcharts.com/">amCharts</a></li>
                                        <li><a target="_blank" href="https://disqus.com/">Disqus</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div><br />
                        <h4 class="card-title font-weight-bold"><i class="fas fa-code-branch"></i> Opensource</h4>
                        <div class="card-text">
                            <p>Currently, the sourcecode is still closed. Reason for this is: no data is ever stored and there is a premium service being used for the structure of the website (MDBootstrap).<br/>
                                Once I found a good way to Git this site without leaking all the Pro edition sections of it, the repository will be set to public.</p>
                        </div>
                    </div>
                </div>
            </div><br />
            <div class="modal fade" id="covidChat" tabindex="-1" role="dialog" aria-labelledby="covidChatLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="covidChatLabel">Discuss COVID-19</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="disqus_thread"></div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() { $('body').bootstrapMaterialDesign(); });
            $(function () {
                $('[data-toggle="tooltip"]').tooltip()
            })
        </script>
        <!-- Charts Code -->
        <script>
            // Reproductionvalue chart
            new Chart(
                document.getElementById("reproGraph"),{
                    "type":"line",
                    "data":{
                        "labels":[<?php foreach($reproDataReverse as $reproVal){ echo "\"".date("F j, Y",strtotime($reproVal["Date"]))."\",";} ?>],
                        "datasets":[
                            {
                                "label":"R low",
                                // "data":[65,59,80,81,56,55,40],
                                "data":[<?php foreach($reproDataReverse as $reproVal){ echo $reproVal["Rt_low"].",";} ?>],
                                "fill":"+1",
                                "borderColor":"rgba(75, 192, 192, 0)",
                                "lineTension":0.1,
                                "pointRadius": 0,
                            },
                            {
                                "label":"R high",
                                // "data":[65,59,80,81,56,55,40],
                                "data":[<?php foreach($reproDataReverse as $reproVal){ echo $reproVal["Rt_up"].",";} ?>],
                                "fill":false,
                                "borderColor":"rgba(75, 192, 192, 0)",
                                "lineTension":0.1,
                                "pointRadius": 0,
                            },
                            {
                                "label":"R avg",
                                // "data":[65,59,80,81,56,55,40],
                                "data":[<?php foreach($reproDataReverse as $reproVal){ echo (isset($reproVal["Rt_avg"])?$reproVal["Rt_avg"]:"").",";} ?>],
                                "fill":false,
                                "borderColor":"rgb(50, 168, 82)",
                                "lineTension":0.1,
                                "pointRadius": 0,
                            },
                        ]
                    },
                    "options":{
                        "tooltips": {
                            "intersect": false,
                            "custom": function(tooltip) {
                                if (!tooltip) return;
                                // disable displaying the color box;
                                tooltip.displayColors = false;
                            }
                        },
                        "legend": {
                            "display": false
                        },
                        "scales": {
                            "xAxes": [{
                                "gridLines": {
                                    "display": false,
                                },
                                "ticks": {
                                    "autoskip": true,
                                    "autoSkipPadding": 30,
                                }
                            }]
                        }
                    }
                }
            );


            <?php
                $dataPointsNationwideValues = array_values($dataPointsNationwide);
                $dataPointsNationwideValuesReversed = array_reverse($dataPointsNationwideValues);
                $dataPointsNationwideNew = $dataPointsNationwideValues;
                array_splice($dataPointsNationwideNew, 0, count($dataPointsNationwideNew)-31);

                
                echo "var newCasesTotal = ["; foreach($dataPointsNationwide as $_value){ echo (isset($_value->ReportedCases)?$_value->ReportedCases:"").",";} echo "];";
                echo "var newDeathsTotal = ["; foreach($dataPointsNationwide as $_value){ echo (isset($_value->ReportedDeaths)?$_value->ReportedDeaths:"").",";} echo "];";
                echo "var newCasesRecent = ["; foreach($dataPointsNationwideNew as $_value){ echo (isset($_value->ReportedCases)?$_value->ReportedCases:"").",";} echo "];";
                echo "var newDeathsRecent = ["; foreach($dataPointsNationwideNew as $_value){ echo (isset($_value->ReportedDeaths)?$_value->ReportedDeaths:"").",";} echo "];";
                
                echo "var newCasesDatesTotal = ["; foreach($dataPointsNationwide as $_dates){ echo "\"".date("F j, Y",strtotime($_dates->Date))."\",";} echo "];";
                echo "var newCasesDatesRecent = ["; foreach($dataPointsNationwideNew as $_dates){ echo "\"".date("F j, Y",strtotime($_dates->Date))."\",";} echo "];";
                echo "var newDeathsDatesTotal = ["; foreach($dataPointsNationwide as $_dates){ echo "\"".date("F j, Y",strtotime($_dates->Date))."\",";} echo "];";
                echo "var newDeathsDatesRecent = ["; foreach($dataPointsNationwideNew as $_dates){ echo "\"".date("F j, Y",strtotime($_dates->Date))."\",";} echo "];";
                
                echo "var newCasesWeekAverage = [";
                $i = 0;
                foreach($dataPointsNationwideValues as $_dates){
                    $avg = 0;
                    $counted = 0;
                    for($j=0;$j<7;$j++){
                        if($i-$j>=0){
                            $curVal = $dataPointsNationwideValues[$i-$j]->ReportedCases;
                            $avg+=$curVal;
                            $counted++;
                        }
                    }
                    $avg/=$counted;
                    echo (isset($avg)?round($avg):"").",";
                    $i++;
                } 
                echo "];";
                
                echo "var newDeathsWeekAverage = [";
                $i = 0;
                foreach($dataPointsNationwideValues as $_dates){
                    $avg = 0;
                    $counted = 0;
                    for($j=0;$j<7;$j++){
                        if($i-$j>=0){
                            $curVal = $dataPointsNationwideValues[$i-$j]->ReportedDeaths;
                            $avg+=$curVal;
                            $counted++;
                        }
                    }
                    $avg/=$counted;
                    echo (isset($avg)?round($avg):"").",";
                    $i++;
                } 
                echo "];";
                
                $mostRecentData = end($dataPointsNationwideValues);
                $dataPointsNationwidePrediction = [];
                foreach($dataPointsNationwideValues as $oldDataPoint){
                    $oldDataPointClone = $oldDataPoint->Clone();
                    array_push($dataPointsNationwidePrediction, $oldDataPointClone);
                }


                $predictionTime = 62; //2 Month prediction
                $casesPreviousPeriodTotalGrowth = 0;
                $casesPreviousPeriodGrowthPerDay = 0;
                
                for($i=0;$i<31;$i++){
                    $diff = $dataPointsNationwideValuesReversed[$i]->ReportedCases-$dataPointsNationwideValuesReversed[$i+1]->ReportedCases;
                    $casesPreviousPeriodTotalGrowth+=$diff;
                }
                $casesPreviousPeriodGrowthPerDay = $casesPreviousPeriodTotalGrowth/31;

                $casesPredictionPrevGrowth = $mostRecentData->ReportedCases;

                for($i=0;$i<$predictionTime;$i++){
                    $casesPredictionPrevGrowth += (($casesPreviousPeriodGrowthPerDay))*($averageRepro*2);
                    $casesPredictionPrevGrowth *=0.96;
                    // $newDay = date('F j, Y', strtotime('+1 day', strtotime($mostRecentDate)));
                    $newDataPoint = new DataPoint();
                    $newDataPoint->Date = date('F j, Y', strtotime("+".($i+1)." day", strtotime($mostRecentData->Date)));
                    $casesPredictionPrevGrowth = max(0,$casesPredictionPrevGrowth);
                    $newDataPoint->ReportedCases = round($casesPredictionPrevGrowth);
                    array_push($dataPointsNationwidePrediction,$newDataPoint);
                }

                echo "var dataPointsNationwidePrediction = ["; foreach($dataPointsNationwidePrediction as $_dates){ echo "\"".date("F j, Y",strtotime($_dates->Date))."\",";} echo "];";
                echo "var casePrediction = ["; foreach($dataPointsNationwidePrediction as $_value){ echo (isset($_value->ReportedCases)?$_value->ReportedCases:"").",";} echo "];";
                echo "var deathPrediction = ["; foreach($dataPointsNationwidePrediction as $_value){ echo (isset($_value->ReportedDeaths)?$_value->ReportedDeaths:"").",";} echo "];";
                ?>
            // New cases chart
            var newCaseChart = new Chart(
                document.getElementById("caseChart"),{
                    "type":"LineWithLine",
                    "data":{
                        "labels": newCasesDatesTotal,
                        "datasets":[{
                            "label": "New cases",
                            pointHitRadius: 20,
                            "fill": false,
                            "borderColor": "#eba834",
                            "pointBackgroundColor": "#eba834",
                            "data": newCasesTotal
                        },{
                            "label": "7-Day average",
                            pointHitRadius: 20,
                            "fill": false,
                            "borderColor": "#4287f5",
                            "pointBackgroundColor": "#4287f5",
                            "data": newCasesWeekAverage
                        },
                        {
                            "label": "Prediction",
                            pointHitRadius: 20,
                            "fill": false,
                            "borderColor": "#4287f5",
                            "pointBackgroundColor": "#4287f5",
                            "data": casePrediction,
                            "hidden":true
                        }]
                    },
                    "options":{
                        "responsive": true,
                        "legend": {
                            "display": false
                        },
                        "tooltips": {
                            "intersect": false,
                            "custom": function(tooltip) {
                                if (!tooltip) return;
                                // disable displaying the color box;
                                tooltip.displayColors = false;
                            }
                        },
                        "elements": {
                            "line": {
                                "tension": 0.0
                            },
                            "point":{
                                "radius": 0
                            }
                        },
                        "scales": {
                            "xAxes": [{
                                "gridLines": {
                                    "display": false,
                                },
                                "ticks": {
                                    "autoskip": true,
                                    "autoSkipPadding": 30,
                                }
                            }],
                            "yAxes": [{
                                "id": 'left-y-axis',
                                "gridLines": {
                                    "drawBorder": false
                                },
                                type: 'linear',
                                "ticks": {
                                    "maxTicksLimit": 5,
                                    "padding": 15,
                                    "callback": function(value) {
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
                                "position": 'left'
                            }]
                        }
                    },
                }
            );

            // New deaths chart
            var newDeathChart = new Chart(
                document.getElementById("deathChart"),{
                    "type":"LineWithLine",
                    "data":{
                        "labels": newDeathsDatesTotal,
                        "datasets":[{
                            "label":"New deaths",
                            pointHitRadius: 20,
                            "fill": false,
                            "borderColor": "#eb4034",
                            "pointBackgroundColor": "#eb4034",
                            "data": newDeathsTotal
                        },{
                            "label": "7-Day average",
                            pointHitRadius: 20,
                            "fill": false,
                            "borderColor": "#4287f5",
                            "pointBackgroundColor": "#4287f5",
                            "data": newDeathsWeekAverage
                        },
                        {
                            "label": "Prediction",
                            pointHitRadius: 20,
                            "fill": false,
                            "borderColor": "#4287f5",
                            "pointBackgroundColor": "#4287f5",
                            "data": deathPrediction,
                            "hidden":true
                        }]
                    },
                    "options":{
                        "responsive": true,
                        "legend": {
                            "display": false
                        },
                        "tooltips": {
                            "intersect": false,
                            "custom": function(tooltip) {
                                if (!tooltip) return;
                                // disable displaying the color box;
                                tooltip.displayColors = false;
                            }
                        },
                        "elements": {
                            "line": {
                                "tension": 0.0
                            },
                            "point":{
                                "radius": 0
                            }
                        },
                        "scales": {
                            "xAxes": [{
                                "gridLines": {
                                    "display": false,
                                },
                                "ticks": {
                                    "autoskip": true,
                                    "autoSkipPadding": 30,
                                }
                            }],
                            "yAxes": [{
                                "gridLines": {
                                    "drawBorder": false
                                },
                                "ticks": {
                                    "maxTicksLimit": 5,
                                    "padding": 15,
                                    "callback": function(value) {
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
                                }
                            }]
                        }
                    },
                }
            );

            $('#caseDeathPairShowRecent').click(function () {
                newCaseChart.data.datasets[0].data = newCasesRecent;
                newCaseChart.data.datasets[1].hidden = true;
                newCaseChart.data.datasets[2].hidden = true;
                newCaseChart.data.labels = newCasesDatesRecent;
                newCaseChart.update();

                newDeathChart.data.datasets[0].data = newDeathsRecent;
                newDeathChart.data.datasets[1].hidden = true;
                newDeathChart.data.datasets[2].hidden = true;
                newDeathChart.data.labels = newDeathsDatesRecent;
                newDeathChart.update();
            });

            $('#caseDeathPairShowAll').click(function () {
                newCaseChart.data.datasets[0].data = newCasesTotal;
                newCaseChart.data.datasets[1].hidden = false;
                newCaseChart.data.datasets[2].hidden = true;
                newCaseChart.data.labels = newCasesDatesTotal;
                newCaseChart.update();

                newDeathChart.data.datasets[0].data = newDeathsTotal;
                newDeathChart.data.datasets[1].hidden = false;
                newDeathChart.data.datasets[2].hidden = true;
                newDeathChart.data.labels = newDeathsDatesTotal;
                newDeathChart.update();
            });

            $('#caseDeathPairShowPrediction').click(function () {
                newCaseChart.data.datasets[0].data = newCasesTotal;
                newCaseChart.data.datasets[1].hidden = true;
                newCaseChart.data.datasets[2].hidden = false;
                newCaseChart.data.labels = dataPointsNationwidePrediction;
                newCaseChart.update();

                // newDeathChart.data.datasets[0].data = newDeathsTotal;
                // newDeathChart.data.datasets[1].hidden = true;
                // newDeathChart.data.datasets[2].hidden = false;
                // newDeathChart.data.labels = dataPointsNationwidePrediction;
                // newDeathChart.update();
            });

            // Header Total cases chart
            new Chart(
                document.getElementById("headerTotalCasesChart"),{
                    "type":"LineWithLine",
                    "data":{
                        "labels": [<?php foreach($dataPointsNationwide as $_dates){ echo "\"".date("M j Y",strtotime($_dates->Date))."\",";} ?>],
                        "datasets":[{
                            pointHitRadius: 20,
                            "fill": false,
                            "borderColor": "#eba834",
                            "pointBackgroundColor": "#eba834",
                            "data": [<?php $cumulativeCasesBuffer = 0; foreach($dataPointsNationwide as $_value){ $cumulativeCasesBuffer+=(isset($_value->ReportedCases)?$_value->ReportedCases:0); echo $cumulativeCasesBuffer.",";} ?>]
                        }]
                    },
                    "options":{
                        "responsive": true,
                        "legend": {
                            "display": false
                        },
                        "tooltips": {
                            "intersect": false,
                            "custom": function(tooltip) {
                                if (!tooltip) return;
                                // disable displaying the color box;
                                tooltip.displayColors = false;
                            }
                        },
                        "elements": {
                            "line": {
                                "tension": 0.5
                            },
                            "point":{
                                "radius": 0
                            }
                        },
                        "scales": {
                            "xAxes": [{
                                "gridLines": {
                                    "display": false,
                                },
                                "ticks": {
                                    "autoskip": true,
                                    "autoSkipPadding": 30,
                                }
                            }],
                            "yAxes": [{
                                "id": 'left-y-axis',
                                "gridLines": {
                                    "drawBorder": false
                                },
                                type: 'linear',
                                "ticks": {
                                    "maxTicksLimit": 5,
                                    "padding": 15,
                                    "callback": function(value) {
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
                                "position": 'left'
                            }]
                        }
                    },
                }
            );

            // Header Total deaths chart
            new Chart(
                document.getElementById("headerTotalDeathsChart"),{
                    "type":"LineWithLine",
                    "data":{
                        "labels": [<?php foreach($dataPointsNationwide as $_dates){ echo "\"".date("M j Y",strtotime($_dates->Date))."\",";} ?>],
                        "datasets":[{
                            pointHitRadius: 20,
                            "fill": false,
                            "borderColor": "#eb4034",
                            "pointBackgroundColor": "#eb4034",
                            "data": [<?php $cumulativeDeathsBuffer = 0; foreach($dataPointsNationwide as $_value){ $cumulativeDeathsBuffer+=(isset($_value->ReportedDeaths)?$_value->ReportedDeaths:0); echo $cumulativeDeathsBuffer.",";} ?>]
                        }]
                    },
                    "options":{
                        "responsive": true,
                        "legend": {
                            "display": false
                        },
                        "tooltips": {
                            "intersect": false,
                            "custom": function(tooltip) {
                                if (!tooltip) return;
                                // disable displaying the color box;
                                tooltip.displayColors = false;
                            }
                        },
                        "elements": {
                            "line": {
                                "tension": 0.5
                            },
                            "point":{
                                "radius": 0
                            }
                        },
                        "scales": {
                            "xAxes": [{
                                "gridLines": {
                                    "display": false,
                                },
                                "ticks": {
                                    "autoskip": true,
                                    "autoSkipPadding": 30,
                                }
                            }],
                            "yAxes": [{
                                "id": 'left-y-axis',
                                "gridLines": {
                                    "drawBorder": false
                                },
                                type: 'linear',
                                "ticks": {
                                    "maxTicksLimit": 5,
                                    "padding": 15,
                                    "callback": function(value) {
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
                                "position": 'left'
                            }]
                        }
                    },
                }
            );

            // Header Hospital chart
            new Chart(
                document.getElementById("headerHospitalChart"),{
                    "type":"LineWithLine",
                    "data":{
                        "labels": [<?php foreach($icBedsUsage as $isValue){ echo "\"".date("M j Y",intval($isValue["date_of_report_unix"]))."\",";} ?>],
                        "datasets":[{
                            pointHitRadius: 20,
                            "fill": true,
                            "borderColor": "#eb4034",
                            "pointBackgroundColor": "#eb4034",
                            "label":"IC beds",
                            "data": [<?php foreach($icBedsUsage as $isValue){ echo $isValue["covid_occupied"].",";} ?>]
                        },{
                            pointHitRadius: 20,
                            "fill": true,
                            "borderColor": "#eba834",
                            "pointBackgroundColor": "#eba834",
                            "label":"Hospital beds",
                            "data": [<?php foreach($hospBedsUsage as $isValue){ echo $isValue["covid_occupied"].",";} ?>]
                        }]
                    },
                    "options":{
                        "responsive": false,
                        "legend": {
                            "display": false
                        },
                        "tooltips": {
                            "intersect": false,
                            "custom": function(tooltip) {
                                if (!tooltip) return;
                                // disable displaying the color box;
                                tooltip.displayColors = false;
                            }
                        },
                        "elements": {
                            "line": {
                                "tension": 0.5
                            },
                            "point":{
                                "radius": 0
                            }
                        },
                        "scales": {
                            "xAxes": [{
                                "gridLines": {
                                    "display": false,
                                },
                                "ticks": {
                                    "autoskip": true,
                                    "autoSkipPadding": 30,
                                }
                            }],
                            "yAxes": [{
                                "gridLines": {
                                    "drawBorder": false
                                },
                                "type": 'linear',
                                "ticks": {
                                    "maxTicksLimit": 5,
                                    "padding": 15,
                                    "callback": function(value) {
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
                                "stacked": true,
                            }]
                        }
                    },
                }
            );

            // Heatmap
            am4core.ready(function() {

            // Themes begin
            am4core.useTheme(am4themes_animated);
            // Themes end

            // Create map instance
            var chart = am4core.create("netherlandsheatmap", am4maps.MapChart);

            // Set map definition
            chart.geodata = am4geodata_netherlandsHigh;

            // Set projection
            chart.projection = new am4maps.projections.Mercator();

            // Create map polygon series
            var polygonSeries = chart.series.push(new am4maps.MapPolygonSeries());

            //Set min/max fill color for each area
            polygonSeries.heatRules.push({
            property: "fill",
            target: polygonSeries.mapPolygons.template,
                min: chart.colors.getIndex(9).brighten(1),
                max: chart.colors.getIndex(9).brighten(-0.3)
            });

            // Make map load polygon data (state shapes and names) from GeoJSON
            polygonSeries.useGeodata = true;

            // Set heatmap values for each state
            polygonSeries.data = [
                // {
                //     id: "US-AL",
                //     value: 4447100
                // }
                <?php
                    foreach($provinceData as $province)
                    echo "{
                        \"id\":\"".$provinceTable[$province->RegionName][0]."\",
                        \"value\":".round(PerValue($province->TotalCases, $provinceTable[$province->RegionName][1], 100000))."
                    },";
                ?>
            ];

            
            // Configure series tooltip
            var polygonTemplate = polygonSeries.mapPolygons.template;
            polygonTemplate.tooltipText = "{name}: {value}";
            polygonTemplate.nonScalingStroke = true;
            polygonTemplate.strokeWidth = 0.5;

            // Create hover state and set alternative fill color
            var hs = polygonTemplate.states.create("hover");
            hs.properties.fill = chart.colors.getIndex(9).brighten(-0.3);
            
            }); // end am4core.ready()
        </script>
        <!-- Disqus Code -->
        <script>
        /*
        var disqus_config = function () {
        this.page.url = PAGE_URL;  // Replace PAGE_URL with your page's canonical URL variable
        this.page.identifier = PAGE_IDENTIFIER; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
        };
        */
        (function() { // DON'T EDIT BELOW THIS LINE
        var d = document, s = d.createElement('script');
        s.src = 'https://dutchcovid.disqus.com/embed.js';
        s.setAttribute('data-timestamp', +new Date());
        (d.head || d.body).appendChild(s);
        })();
        </script>
    </body>
</html>