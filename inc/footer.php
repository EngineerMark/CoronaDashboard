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
                                    <li>Coronavirus Datasets: <a target="_blank" href="https://rivm.nl/">RIVM</a>, <a target="_blank" href="https://rijksoverheid.nl/">Rijksoverheid</a></li>
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
        <script src="https://cdn.amcharts.com/lib/4/geodata/netherlandsLow.js"></script>
        <script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/hammerjs@2.0.8"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom@0.7.7"></script>

        <script src="js/scripts.js"></script>
        <script>$(document).ready(function() { $('body').bootstrapMaterialDesign(); });</script>
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

            // New cases chart
            new Chart(
                document.getElementById("caseChart"),{
                    "type":"LineWithLine",
                    "data":{
                        "labels": [<?php foreach($dataPointsNationwide as $_dates){ echo "\"".date("F j, Y",strtotime($_dates->Date))."\",";} ?>],
                        "datasets":[{
                            pointHitRadius: 20,
                            "fill": false,
                            "borderColor": "#eba834",
                            "pointBackgroundColor": "#eba834",
                            "data": [<?php foreach($dataPointsNationwide as $_value){ echo (isset($_value->ReportedCases)?$_value->ReportedCases:"").",";} ?>]
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
            new Chart(
                document.getElementById("deathChart"),{
                    "type":"LineWithLine",
                    "data":{
                        "labels": [<?php foreach($dataPointsNationwide as $_dates){ echo "\"".date("F j, Y",strtotime($_dates->Date))."\",";} ?>],
                        "datasets":[{
                            pointHitRadius: 20,
                            "fill": false,
                            "borderColor": "#eb4034",
                            "pointBackgroundColor": "#eb4034",
                            "data": [<?php foreach($dataPointsNationwide as $_value){ echo (isset($_value->ReportedDeaths)?$_value->ReportedDeaths:"").",";} ?>]
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
        </script>
    </body>
</html>