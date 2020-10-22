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
                            <p>The full source code of this project can be found on <a target="_blank" href="https://github.com/EngineerMark/CoronaDashboard">GitHub</a></p>
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
        <script src="js/scripts.js"></script>
        <script src="js/charts.js"></script>
        <script>
            $(document).ready(function() { $('body').bootstrapMaterialDesign(); });
            $(function () {
                $('[data-toggle="tooltip"]').tooltip()
            })
        </script>
        <!-- Charts Code -->
        <script>
            <?php
                //Reproductionchart
                echo "reproductionChart.data.labels = ["; foreach($reproDataReverse as $reproVal){ echo "\"".date("F j, Y",strtotime($reproVal["Date"]))."\",";} echo "];";
                echo "reproductionChart.data.datasets[0].data = ["; foreach($reproDataReverse as $reproVal){ echo $reproVal["Rt_low"].",";} echo "];";
                echo "reproductionChart.data.datasets[1].data = ["; foreach($reproDataReverse as $reproVal){ echo $reproVal["Rt_up"].",";} echo "];";
                echo "reproductionChart.data.datasets[2].data = ["; foreach($reproDataReverse as $reproVal){ echo (isset($reproVal["Rt_avg"])?$reproVal["Rt_avg"]:"").",";} echo "];";
                echo "reproductionChart.update();";

                $dataPointsNationwideValues = array_values($dataPointsNationwide);
                $dataPointsNationwideValuesReversed = array_reverse($dataPointsNationwideValues);
                $dataPointsNationwideNew = $dataPointsNationwideValues;
                array_splice($dataPointsNationwideNew, 0, count($dataPointsNationwideNew)-31);

                
                echo "var newCasesTotal = ["; foreach($dataPointsNationwideValues as $_value){ echo (isset($_value->ReportedCases)?$_value->ReportedCases:"").",";} echo "];";
                echo "var newDeathsTotal = ["; foreach($dataPointsNationwideValues as $_value){ echo (isset($_value->ReportedDeaths)?$_value->ReportedDeaths:"").",";} echo "];";
                echo "var newCasesRecent = ["; foreach($dataPointsNationwideNew as $_value){ echo (isset($_value->ReportedCases)?$_value->ReportedCases:"").",";} echo "];";
                echo "var newDeathsRecent = ["; foreach($dataPointsNationwideNew as $_value){ echo (isset($_value->ReportedDeaths)?$_value->ReportedDeaths:"").",";} echo "];";
                
                echo "var newCasesDatesTotal = ["; foreach($dataPointsNationwideValues as $_dates){ echo "\"".date("F j, Y",strtotime($_dates->Date))."\",";} echo "];";
                echo "var newCasesDatesRecent = ["; foreach($dataPointsNationwideNew as $_dates){ echo "\"".date("F j, Y",strtotime($_dates->Date))."\",";} echo "];";
                echo "var newDeathsDatesTotal = ["; foreach($dataPointsNationwideValues as $_dates){ echo "\"".date("F j, Y",strtotime($_dates->Date))."\",";} echo "];";
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

                echo "var newCasesWeekAverageRecent = JSON.parse(JSON.stringify(newCasesWeekAverage));";
                echo "newCasesWeekAverageRecent = newCasesWeekAverageRecent.splice(newCasesWeekAverage.length-32, newCasesWeekAverage.length-1);";

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

                echo "var newDeathsWeekAverageRecent = JSON.parse(JSON.stringify(newDeathsWeekAverage));";
                echo "newDeathsWeekAverageRecent = newDeathsWeekAverageRecent.splice(newDeathsWeekAverage.length-32, newDeathsWeekAverage.length-1);";

                // echo "var newDeathsWeekAverageRecent = [";
                // $i = 0;
                // foreach($dataPointsNationwideNew as $_dates){
                //     $avg = 0;
                //     $counted = 0;
                //     for($j=0;$j<7;$j++){
                //         if($i-$j>=0){
                //             $curVal = $dataPointsNationwideNew[$i-$j]->ReportedDeaths;
                //             $avg+=$curVal;
                //             $counted++;
                //         }
                //     }
                //     $avg/=$counted;
                //     echo (isset($avg)?round($avg):"").",";
                //     $i++;
                // } 
                // echo "];";
                
                $mostRecentData = end($dataPointsNationwideValues);
                $dataPointsNationwidePrediction = [];
                foreach($dataPointsNationwideValues as $oldDataPoint){
                    $oldDataPointClone = $oldDataPoint->Clone();
                    array_push($dataPointsNationwidePrediction, $oldDataPointClone);
                }


                $predictionTime = 62; //2 Month prediction
                $casesPreviousPeriodTotalGrowth = 0;
                $casesPreviousPeriodGrowthPerDay = 0;
                
                for($i=0;$i<5;$i++){
                    $diff = $dataPointsNationwideValuesReversed[$i]->ReportedCases-$dataPointsNationwideValuesReversed[$i+1]->ReportedCases;
                    $casesPreviousPeriodTotalGrowth+=$diff;
                }
                $casesPreviousPeriodTotalGrowth /= 5;
                $casesPreviousPeriodGrowthPerDay = $casesPreviousPeriodTotalGrowth;

                $casesPredictionPrevGrowth = $mostRecentData->ReportedCases;

                for($i=0;$i<$predictionTime;$i++){
                    // $curve = pow(31,(-($i/1000)));
                    $casesPredictionPrevGrowth += (($casesPreviousPeriodGrowthPerDay))*($averageRepro*2);
                    // $casesPredictionPrevGrowth *= 0.95;
                    $casesPredictionPrevGrowth -= ($casesPreviousPeriodGrowthPerDay*$i)*0.1;
                    // $casesPredictionPrevGrowth *= ($curve);
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

                echo "newCaseChart.data.labels = newCasesDatesTotal;";
                echo "newCaseChart.data.datasets[0].data = newCasesTotal;";
                echo "newCaseChart.data.datasets[1].data = newCasesWeekAverage;";
                echo "newCaseChart.data.datasets[2].data = casePrediction;";
                echo "newCaseChart.data.datasets[3].data = newCasesWeekAverageRecent;";
                echo "newCaseChart.update();";

                echo "newDeathChart.data.labels = newDeathsDatesTotal;";
                echo "newDeathChart.data.datasets[0].data = newDeathsTotal;";
                echo "newDeathChart.data.datasets[1].data = newDeathsWeekAverage;";
                echo "newDeathChart.data.datasets[2].data = deathPrediction;";
                echo "newDeathChart.data.datasets[3].data = newDeathsWeekAverageRecent;";
                echo "newDeathChart.update();";
            ?>

            function ChartDisplayRecent(){
                newCaseChart.data.datasets[0].data = newCasesRecent;
                newCaseChart.data.datasets[1].hidden = true;
                newCaseChart.data.datasets[2].hidden = true;
                newCaseChart.data.datasets[3].hidden = false;
                newCaseChart.data.labels = newCasesDatesRecent;
                newCaseChart.update();

                newDeathChart.data.datasets[0].data = newDeathsRecent;
                newDeathChart.data.datasets[1].hidden = true;
                newDeathChart.data.datasets[2].hidden = true;
                newDeathChart.data.datasets[3].hidden = false;
                newDeathChart.data.labels = newDeathsDatesRecent;
                newDeathChart.update();
            }

            function ChartDisplayAll(){
                newCaseChart.data.datasets[0].data = newCasesTotal;
                newCaseChart.data.datasets[1].hidden = false;
                newCaseChart.data.datasets[2].hidden = true;
                newCaseChart.data.datasets[3].hidden = true;
                newCaseChart.data.labels = newCasesDatesTotal;
                newCaseChart.update();

                newDeathChart.data.datasets[0].data = newDeathsTotal;
                newDeathChart.data.datasets[1].hidden = false;
                newDeathChart.data.datasets[2].hidden = true;
                newDeathChart.data.datasets[3].hidden = true;
                newDeathChart.data.labels = newDeathsDatesTotal;
                newDeathChart.update();
            }

            function ChartDisplayPrediction(){
                newCaseChart.data.datasets[0].data = newCasesTotal;
                newCaseChart.data.datasets[1].hidden = true;
                newCaseChart.data.datasets[2].hidden = false;
                newCaseChart.data.datasets[3].hidden = true;
                newCaseChart.data.labels = dataPointsNationwidePrediction;
                newCaseChart.update();
            }

            $('#caseDeathPairShowRecent').click(function(){ChartDisplayRecent()});
            $('#caseDeathPairShowAll').click(function(){ChartDisplayAll()});
            $('#caseDeathPairShowPrediction').click(function(){ChartDisplayPrediction()});

            headerTotalCasesChart.data.labels = [<?php foreach($dataPointsNationwide as $_dates){ echo "\"".date("M j Y",strtotime($_dates->Date))."\",";} ?>];
            headerTotalCasesChart.data.datasets[0].data = [<?php $cumulativeCasesBuffer = 0; foreach($dataPointsNationwide as $_value){ $cumulativeCasesBuffer+=(isset($_value->ReportedCases)?$_value->ReportedCases:0); echo $cumulativeCasesBuffer.",";} ?>];
            headerTotalCasesChart.update();

            headerTotalDeathsChart.data.labels = [<?php foreach($dataPointsNationwide as $_dates){ echo "\"".date("M j Y",strtotime($_dates->Date))."\",";} ?>];
            headerTotalDeathsChart.data.datasets[0].data = [<?php $cumulativeDeathsBuffer = 0; foreach($dataPointsNationwide as $_value){ $cumulativeDeathsBuffer+=(isset($_value->ReportedDeaths)?$_value->ReportedDeaths:0); echo $cumulativeDeathsBuffer.",";} ?>];
            headerTotalDeathsChart.update();

            headerHospitalChart.data.labels = [<?php foreach($icBedsUsage as $isValue){ echo "\"".date("M j Y",intval($isValue["date_of_report_unix"]))."\",";} ?>];
            headerHospitalChart.data.datasets[0].data = [<?php foreach($icBedsUsage as $isValue){ echo $isValue["covid_occupied"].",";} ?>];
            headerHospitalChart.data.datasets[1].data = [<?php foreach($hospBedsUsage as $isValue){ echo $isValue["covid_occupied"].",";} ?>];
            headerHospitalChart.data.datasets[2].data = [<?php foreach($hospBedsUsage as $isValue){ echo "1600,";} ?>];
            headerHospitalChart.update();
            
            var mapDataPerCapita = [
                <?php
                    foreach($provinceData as $province)
                    echo "{
                        \"id\":\"".$provinceTable[$province->RegionName][0]."\",
                        \"value\":".round(PerValue($province->TotalCases, $provinceTable[$province->RegionName][1], 100000))."
                    },";
                ?>
            ];

            var mapDataTotal = [
                <?php
                    foreach($provinceData as $province)
                    echo "{
                        \"id\":\"".$provinceTable[$province->RegionName][0]."\",
                        \"value\":".round($province->TotalCases)."
                    },";
                ?>
            ];

            var mapchart = null;
            var polygonSeries = null;
            // Heatmap
            am4core.ready(function() {
                // Themes begin
                am4core.useTheme(am4themes_animated);
                // Themes end

                // Create map instance
                mapchart = am4core.create("netherlandsheatmap", am4maps.MapChart);

                mapchart.seriesContainer.draggable = false;
                mapchart.seriesContainer.resizable = false;
                mapchart.maxZoomLevel = 1;

                // Set map definition
                mapchart.geodata = am4geodata_netherlandsHigh;

                // Set projection
                mapchart.projection = new am4maps.projections.Mercator();

                // Create map polygon series
                polygonSeries = mapchart.series.push(new am4maps.MapPolygonSeries());


                //Set min/max fill color for each area
                polygonSeries.heatRules.push({
                    property: "fill",
                    target: polygonSeries.mapPolygons.template,
                        min: mapchart.colors.getIndex(9).brighten(1),
                        max: mapchart.colors.getIndex(9).brighten(-0.3)
                    });

                    // Make map load polygon data (state shapes and names) from GeoJSON
                    polygonSeries.useGeodata = true;

                    // Set heatmap values for each state
                    polygonSeries.data = mapDataTotal;

                    
                    // Configure series tooltip
                    var polygonTemplate = polygonSeries.mapPolygons.template;
                    polygonTemplate.tooltipText = "{name}: {value}";
                    polygonTemplate.nonScalingStroke = true;
                    polygonTemplate.strokeWidth = 0.5;

                    // Create hover state and set alternative fill color
                    var hs = polygonTemplate.states.create("hover");
                    hs.properties.fill = mapchart.colors.getIndex(9).brighten(-0.3);
            }); // end am4core.ready()

            function MapChartTotal(){
                polygonSeries.data = mapDataTotal;
                mapchart.validateData();
            }

            function MapChartPerCapita(){
                polygonSeries.data = mapDataPerCapita;
                mapchart.validateData();
            }

            $('#mapChartTotal').click(function(){MapChartTotal()});
            $('#mapChartPerCapita').click(function(){MapChartPerCapita()});
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