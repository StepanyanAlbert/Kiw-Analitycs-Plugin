(function ($) {

    const wrapperSelector = '#ga_dashboard_widget';
    const minWidth = 350;
    const offset = 10;

    ga_dashboard = {
        chartData: [],
        init: function (dataArr, showLoader) {

            let today = new Date();

            $('#range-selector-end').val(today.getFullYear() + '-' +
                (today.getMonth().toString().length < 2 ? ('0' + parseInt(today.getMonth() + 1)) : parseInt(today.getMonth() + 1)) + '-' +
                (today.getDate().toString().length < 2 ? ('0' + today.getDate()) : today.getDate())
            );

            today.setDate(today.getDate() - 7);

            let weekBefore = today.toLocaleDateString().split(',')[0].split('/').reverse();

            $('#range-selector-start').val(weekBefore[0] + '-' + (weekBefore[2].length < 2 ? ('0' + weekBefore[2]) : weekBefore[2]) + '-' +
                (weekBefore[1].length < 2 ? ('0' + weekBefore[1]) : weekBefore[1]));

            if (showLoader) {
                ga_loader.show();
            }
            google.charts.load('current', {'packages': ['corechart', 'bar', 'geochart']});
            google.charts.setOnLoadCallback(function () {
                if (dataArr) {
                    ga_dashboard.drawChart(dataArr);
                    ga_dashboard.setChartData(dataArr);
                }
            });
        },
        events: function (data) {
            $(document).ready(function () {
                $('#range-selector-start, #range-selector-end').on('change', function () {
                    const start = $('#range-selector-start').val() || null;
                    const end = $('#range-selector-end').val() || null;
                    const selected_dimension = $('#dimensions-selector option:selected').val() || null;
                    const selected_metric = ga_dashboard.getMetric(selected_dimension);

                    ga_loader.show();

                    var dataObj = {};
                    dataObj['action'] = "ga_ajax_data_change";
                    dataObj['date_range_start'] = start;
                    dataObj['date_range_end'] = end;
                    dataObj['metric'] = selected_metric;
                    dataObj['dimension'] = selected_dimension;
                    dataObj[GA_NONCE_FIELD] = GA_NONCE;

                    $.ajax({
                        type: "post",
                        dataType: "json",
                        url: ajaxurl,
                        data: dataObj,
                        success: function (response) {

                            ga_loader.hide();

                            if (typeof response.error !== "undefined") {
                                $('#ga_widget_error').show().html(response.error);
                            } else {
                                var dataT = [['Day', selected_dimension]];
                                $.each(response.chart, function (k, v) {
                                    dataT.push([v.day, parseInt(v.current)]);
                                });

                                $.each(response.boxes, function (k, v) {
                                    $('#ga_box_dashboard_label_' + k).html(v.label);
                                    $('#ga_box_dashboard_value_' + k).html(v.value);
                                });

                                ga_dashboard.drawChart(dataT, selected_dimension);

                                // Set new data
                                ga_dashboard.setChartData(dataT);
                            }
                        }
                    });
                });

                $('#dimensions-selector').on('change', function () {
                    const start = $('#range-selector-start').val() || null;
                    const end = $('#range-selector-end').val() || null;
                    const selected_dimension = $('#dimensions-selector option:selected').val() || null;
                    const selected_metric = ga_dashboard.getMetric(selected_dimension);

                    ga_loader.show();

                    var dataObj = {};
                    dataObj['action'] = "ga_ajax_data_change";
                    dataObj['metric'] = selected_metric;
                    dataObj['dimension'] = selected_dimension;
                    dataObj['date_range_start'] = start;
                    dataObj['date_range_end'] = end;
                    dataObj[GA_NONCE_FIELD] = GA_NONCE;

                    $.ajax({
                        type: "post",
                        dataType: "json",
                        url: ajaxurl,
                        data: dataObj,
                        success: function (response) {
                            ga_loader.hide();

                            if (typeof response.error !== "undefined") {
                                $('#ga_widget_error').show().html(response.error);
                            } else {
                                var dataT = [['Day', selected_dimension]];
                                $.each(response.chart, function (k, v) {
                                    dataT.push([v.day, parseInt(v.current)]);
                                });

                                ga_dashboard.drawChart(dataT, selected_dimension);

                                // Set new data
                                ga_dashboard.setChartData(dataT);
                            }
                        }
                    });
                });

                $('#ga-widget-trigger').on('click', function () {
                    const start = $('#range-selector-start').val() || null;
                    const end = $('#range-selector-end').val() || null;
                    const selected_dimension = $('#dimensions-selector option:selected').val() || null;
                    const selected_metric = ga_dashboard.getMetric(selected_dimension);

                    ga_loader.show();

                    var dataObj = {};
                    dataObj['action'] = "ga_ajax_data_change";
                    dataObj['metric'] = selected_metric;
                    dataObj['dimension'] = selected_dimension;
                    dataObj['date_range_start'] = start;
                    dataObj['date_range_end'] = end;
                    dataObj[GA_NONCE_FIELD] = GA_NONCE;

                    $.ajax({
                        type: "post",
                        dataType: "json",
                        url: ajaxurl,
                        data: dataObj,
                        success: function (response) {

                            ga_loader.hide();

                            if (typeof response.error !== "undefined") {
                                $('#ga_widget_error').show().html(response.error);
                            } else {
                                var dataT = [['Day', selected_dimension]];
                                $.each(response.chart, function (k, v) {
                                    dataT.push([v.day, parseInt(v.current)]);
                                });

                                $.each(response.boxes, function (k, v) {
                                    $('#ga_box_dashboard_label_' + k).html(v.label);
                                    $('#ga_box_dashboard_value_' + k).html(v.value);
                                });

                                ga_dashboard.drawChart(dataT, selected_dimension);

                                // Set new data
                                ga_dashboard.setChartData(dataT);
                            }
                        }
                    });
                });

                $(window).on('resize', function () {
                    ga_dashboard.drawChart(ga_dashboard.getChartData(), ga_tools.recomputeChartWidth(minWidth, offset, wrapperSelector));
                });
            });
        },
        getMetric(dimension) {
            switch(dimension) {
                case 'date':
                    return 'sessions';
                case 'browser':
                    return 'pageviews';
                case 'country':
                    return 'sessions';
                case 'deviceCategory':
                    return 'pageviews';
                case 'source':
                    return 'pageviews';
                default:
                    return 'sessions';
            }
        },
        /**
         * Returns chart data array.
         * @returns {Array}
         */
        getChartData: function () {
            return ga_dashboard.chartData;
        },
        /**
         * Overwrites initial data array.
         * @param new_data
         */
        setChartData: function (new_data) {
            ga_dashboard.chartData = new_data;
        },
        getChart(dimension) {
            const chart_dom_element = document.getElementById('chart_div');
            switch(dimension) {
                case 'date':
                    return new google.visualization.AreaChart(chart_dom_element);
                case 'browser':
                    return new google.visualization.PieChart(chart_dom_element);
                case 'country':
                    return new google.visualization.GeoChart(chart_dom_element);
                case 'deviceCategory':
                    return  new google.visualization.BarChart(chart_dom_element);
                case 'source':
                    return new google.visualization.PieChart(chart_dom_element);
                default:
                    return new google.visualization.BarChart(chart_dom_element);
            }
        },
        drawChart: function (dataArr, title) {
            if (dataArr.length > 1) {
                const data = google.visualization.arrayToDataTable(dataArr);
                let options = {
                    title: title == 'date' || typeof title == 'number' ? 'Activity' : title.charAt( 0 ).toUpperCase() + title.slice( 1 ),
                    lineWidth: 2,
                    chartArea: {
                        left: 50,
                        top: 60,
                        bottom: 50,
                        right: 40
                    },
                    width: '95%',
                    height: 300,
                    hAxis: { title: 'Day', titleTextStyle: {color: '#333'}, direction: 1 },
                    vAxis: { minValue: 0 },
                    pointSize: 5
                };

                if(title == 'browser' || title == 'source') {
                    options.is3D = true;
                }

                var chart = ga_dashboard.getChart(title);
                google.visualization.events.addListener(chart, 'ready', function () {
                    ga_loader.hide();
                });
                chart.draw(data, options);
            } else {
                $('#ga_widget_error').show().html('No data available for selected range.');
            }
        }
    };

})(jQuery);
