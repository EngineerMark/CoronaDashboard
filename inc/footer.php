            <div class="row">
                <div class="col-lg-6">
                    <!-- About -->
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title"><i class="fas fa-info-circle"></i> About</h4>
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
                    </div>
                </div>
                <div class="col-lg-6">
                    <!-- Credits -->
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Credits and Sources</h4>
                            <div class="card-text">
                                <ul>
                                    <li>Datasets: <a target="_blank" href="https://rivm.nl/">RIVM</a>, <a target="_blank" href="https://rijksoverheid.nl/">Rijksoverheid</a>, <a target="_blank" href="https://overheid.nl/">Overheid</a></li>
                                    <li><a target="_blank" href="https://ec.europa.eu/">European Commission</a></li>
                                    <li><a target="_blank" href="https://mdbootstrap.com/">Material Design Bootstrap</a></li>
                                    <li><a target="_blank" href="https://fontawesome.com/">Font Awesome</a></li>
                                    <li><a target="_blank" href="https://www.chartjs.org/">ChartJS</a>, <a target="_blank" href="https://www.amcharts.com/">amCharts</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div><br />
        </div>
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

        <script src="js/scripts.js"></script>
        <script>$(document).ready(function() { $('body').bootstrapMaterialDesign(); });</script>
        <script>
            <?php
                $provinceTable = [
                    "Drenthe" => ["NL-DR", 492167],
                    "Flevoland" => ["NL-FL", 416546],
                    "Friesland" => ["NL-FR", 647672],
                    "Gelderland" => ["NL-GE", 2071972],
                    "Groningen" => ["NL-GR", 583990],
                    "Limburg" => ["NL-LI", 1116137],
                    "Noord-Brabant" => ["NL-NB", 2544806],
                    "Noord-Holland" => ["NL-NH", 2853359],
                    "Overijssel" => ["NL-OV", 1156431],
                    "Utrecht" => ["NL-UT", 1342158],
                    "Zeeland" => ["NL-ZE", 383032],
                    "Zuid-Holland" => ["NL-ZH", 3673893]
                ];
            ?>

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
                            }
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
                $dataPointsNationwideNew = $dataPointsNationwide;
                array_splice($dataPointsNationwideNew, 0, count($dataPointsNationwideNew)-31);

                echo "var newCasesTotal = ["; foreach($dataPointsNationwide as $_value){ echo (isset($_value->ReportedCases)?$_value->ReportedCases:"").",";} echo "];";
                echo "var newDeathsTotal = ["; foreach($dataPointsNationwide as $_value){ echo (isset($_value->ReportedDeaths)?$_value->ReportedDeaths:"").",";} echo "];";
                echo "var newCasesRecent = ["; foreach($dataPointsNationwideNew as $_value){ echo (isset($_value->ReportedCases)?$_value->ReportedCases:"").",";} echo "];";
                echo "var newDeathsRecent = ["; foreach($dataPointsNationwideNew as $_value){ echo (isset($_value->ReportedDeaths)?$_value->ReportedDeaths:"").",";} echo "];";

                echo "var newCasesDatesTotal = ["; foreach($dataPointsNationwide as $_dates){ echo "\"".date("F j, Y",strtotime($_dates->Date))."\",";} echo "];";
                echo "var newCasesDatesRecent = ["; foreach($dataPointsNationwideNew as $_dates){ echo "\"".date("F j, Y",strtotime($_dates->Date))."\",";} echo "];";
                echo "var newDeathsDatesTotal = ["; foreach($dataPointsNationwide as $_dates){ echo "\"".date("F j, Y",strtotime($_dates->Date))."\",";} echo "];";
                echo "var newDeathsDatesRecent = ["; foreach($dataPointsNationwideNew as $_dates){ echo "\"".date("F j, Y",strtotime($_dates->Date))."\",";} echo "];";
            ?>
            // New cases chart
            var newCaseChart = new Chart(
                document.getElementById("caseChart"),{
                    "type":"LineWithLine",
                    "data":{
                        "labels": newCasesDatesTotal,
                        "datasets":[{
                            pointHitRadius: 20,
                            "fill": false,
                            "borderColor": "#eba834",
                            "pointBackgroundColor": "#eba834",
                            "data": newCasesTotal
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
                            pointHitRadius: 20,
                            "fill": false,
                            "borderColor": "#eb4034",
                            "pointBackgroundColor": "#eb4034",
                            "data": newDeathsTotal
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
                newCaseChart.data.labels = newCasesDatesRecent;
                newCaseChart.update();

                newDeathChart.data.datasets[0].data = newDeathsRecent;
                newDeathChart.data.labels = newDeathsDatesRecent;
                newDeathChart.update();
            });

            $('#caseDeathPairShowAll').click(function () {
                newCaseChart.data.datasets[0].data = newCasesTotal;
                newCaseChart.data.labels = newCasesDatesTotal;
                newCaseChart.update();

                newDeathChart.data.datasets[0].data = newDeathsTotal;
                newDeathChart.data.labels = newDeathsDatesTotal;
                newDeathChart.update();
            });

            // Header Total cases chart
            new Chart(
                document.getElementById("headerTotalCasesChart"),{
                    "type":"LineWithLine",
                    "data":{
                        "labels": [<?php foreach($dataPointsNationwide as $_dates){ echo "\"".date("F j, Y",strtotime($_dates->Date))."\",";} ?>],
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

            // Header Total deaths chart
            new Chart(
                document.getElementById("headerTotalDeathsChart"),{
                    "type":"LineWithLine",
                    "data":{
                        "labels": [<?php foreach($dataPointsNationwide as $_dates){ echo "\"".date("F j, Y",strtotime($_dates->Date))."\",";} ?>],
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

            // Header Hospital chart
            new Chart(
                document.getElementById("headerHospitalChart"),{
                    "type":"LineWithLine",
                    "data":{
                        "labels": [<?php foreach($icBedsUsage as $isValue){ echo "\"".date("F j, Y",intval($isValue["date_of_report_unix"]))."\",";} ?>],
                        "datasets":[{
                            pointHitRadius: 20,
                            "fill": false,
                            "borderColor": "#eb4034",
                            "pointBackgroundColor": "#eb4034",
                            "label":"IC beds",
                            "data": [<?php foreach($icBedsUsage as $isValue){ echo $isValue["covid_occupied"].",";} ?>]
                        },{
                            pointHitRadius: 20,
                            "fill": false,
                            "borderColor": "#eba834",
                            "pointBackgroundColor": "#eba834",
                            "label":"Hospital beds",
                            "data": [<?php foreach($hospBedsUsage as $isValue){ echo $isValue["covid_occupied"].",";} ?>]
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
    </body>
</html>