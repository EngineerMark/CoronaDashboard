var reproductionChart = new Chart(
    document.getElementById("reproGraph"),{
        "type":"line",
        "data":{
            "labels":"",
            "datasets":[
                {
                    "label":"R low",
                    // "data":[65,59,80,81,56,55,40],
                    "data":"",
                    "fill":"+1",
                    "borderColor":"rgba(75, 192, 192, 0)",
                    "lineTension":0.1,
                    "pointRadius": 0,
                },
                {
                    "label":"R high",
                    // "data":[65,59,80,81,56,55,40],
                    "data":"",
                    "fill":false,
                    "borderColor":"rgba(75, 192, 192, 0)",
                    "lineTension":0.1,
                    "pointRadius": 0,
                },
                {
                    "label":"R avg",
                    // "data":[65,59,80,81,56,55,40],
                    "data":"",
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

var newCaseChart = new Chart(
    document.getElementById("caseChart"),{
        "type":"LineWithLine",
        "data":{
            "labels": "",
            "datasets":[{
                "label": "New cases",
                pointHitRadius: 20,
                "fill": false,
                "borderColor": "#eba834",
                "pointBackgroundColor": "#eba834",
                "data": ""
            },{
                "label": "Week average",
                pointHitRadius: 20,
                "fill": false,
                "borderColor": "#4287f5",
                "pointBackgroundColor": "#4287f5",
                "data": ""
            },
            {
                "label": "Prediction",
                pointHitRadius: 20,
                "fill": false,
                "borderColor": "#bababa",
                "pointBackgroundColor": "#4287f5",
                "data": "",
                "hidden":true
            },{
                "label": "Week average (Recent)",
                pointHitRadius: 20,
                "fill": false,
                "borderColor": "#4287f5",
                "pointBackgroundColor": "#4287f5",
                "data": "",
                "hidden":true
            },]
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

var newDeathChart = new Chart(
    document.getElementById("deathChart"),{
        "type":"LineWithLine",
        "data":{
            "labels": "",
            "datasets":[{
                "label":"New deaths",
                pointHitRadius: 20,
                "fill": false,
                "borderColor": "#eb4034",
                "pointBackgroundColor": "#eb4034",
                "data": ""
            },{
                "label": "Week average",
                pointHitRadius: 20,
                "fill": false,
                "borderColor": "#4287f5",
                "pointBackgroundColor": "#4287f5",
                "data": ""
            },
            {
                "label": "Prediction",
                pointHitRadius: 20,
                "fill": false,
                "borderColor": "#4287f5",
                "pointBackgroundColor": "#4287f5",
                "data": "",
                "hidden":true
            },{
                "label": "Week average (Recent)",
                pointHitRadius: 20,
                "fill": false,
                "borderColor": "#4287f5",
                "pointBackgroundColor": "#4287f5",
                "data": "",
                "hidden":true
            },]
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
var headerTotalCasesChart = new Chart(
    document.getElementById("headerTotalCasesChart"),{
        "type":"LineWithLine",
        "data":{
            "labels": "",
            "datasets":[{
                pointHitRadius: 20,
                "fill": false,
                "borderColor": "#eba834",
                "pointBackgroundColor": "#eba834",
                "data": ""
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
var headerTotalDeathsChart = new Chart(
    document.getElementById("headerTotalDeathsChart"),{
        "type":"LineWithLine",
        "data":{
            "labels": "",
            "datasets":[{
                pointHitRadius: 20,
                "fill": false,
                "borderColor": "#eb4034",
                "pointBackgroundColor": "#eb4034",
                "data": ""
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
var headerHospitalChart = new Chart(
    document.getElementById("headerHospitalChart"),{
        "type":"LineWithLine",
        "data":{
            "labels": "",
            "datasets":[{
                pointHitRadius: 20,
                "fill": false,
                "borderColor": "#eb4034",
                "pointBackgroundColor": "#eb4034",
                "label":"IC beds",
                "data": ""
            },{
                pointHitRadius: 20,
                "fill": false,
                "borderColor": "#eba834",
                "pointBackgroundColor": "#eba834",
                "label":"Hospital beds",
                "data": ""
            },
            {
                pointHitRadius: 20,
                "fill": false,
                "borderColor": "#bababa",
                "pointBackgroundColor": "#eba834",
                "label":"IC Limit",
                "data": ""
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
                        "maxTicksLimit": 4,
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