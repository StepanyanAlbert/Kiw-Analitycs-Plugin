<!DOCTYPE html>
<html>
<head>
    <title>KIWI GAD</title>
    <link rel="stylesheet" type="text/css" href="'../wp-content/plugins/Kiw-Analitycs-Plugin/Src/front/css/style.css'">
</head>
<body>
<!-- Create the containing elements. -->
<div id="authorize-button-box" style="display: none">
    <h1 class="text-center">Google analytics</h1>
    <h6 class="text-center">You need to <a href="admin.php?page=google-analytics-dashboard-settings">authorize account</a> to see your analytics data</h6>
</div>

<div id="content" style="display: none">
    <header>
        <div id="query-output"></div>
        <div id="embed-api-auth-container"></div>
        <div class="dashboard-header row">
            <div>
                <div id="view-name"></div>
                <div class="title-sub">Letious visualizations</div>
            </div>
            <div id="active-users-container"></div>
        </div>
        <div id="view-selector-container"></div>
    </header>
    <div class="row">
        <div class="input-side">
            <label for="form-control-select1">Category</label>
            <div class="form-group">
                <select class="form-control" id="form-control-select1">
                    <option value="Sessions" selected="selected">Sessions</option>
                    <option value="Users">Users</option>
                    <option value="Technology">Browsers</option>
                    <option value="Device">Device</option>
                    <option value="Location">Location</option>
                    <option value="Traffic">Top Channels</option>
                    <option value="LoadTime">Page Load Time</option>
                    <option value="Age">Age</option>
                    <option value="Gender">Gender</option>
                </select>
            </div>
        </div>
        <div id="date-range-selector-1-container"></div>
    </div>

    <div class="row">
        <div id="myProgress">
            <div id="myBar"></div>
        </div>
        <div class="Chartjs Chart-content">
            <figure class="Chartjs-figure" id="chart-1-container"></figure>
            <ol class="Chartjs-legend" id="legend-1-container"></ol>
            <div id="regions_div" style="width: 500px; height: 500px; display: none"></div>
        </div>
    </div>
</div>
<!-- Load the library. -->
<script>
    (function(w,d,s,g,js,fs){
        g=w.gapi||(w.gapi={});g.analytics={q:[],ready:function(f){this.q.push(f);}};
        js=d.createElement(s);fs=d.getElementsByTagName(s)[0];
        js.src='https://apis.google.com/js/platform.js';
        fs.parentNode.insertBefore(js,fs);js.onload=function(){g.load('analytics');};
    }(window,document,'script'));
</script>

<!-- Include the chart.js and moment.js scripts. -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>

<!-- Include the ViewSelector2 component script. -->
<script src="https://ga-dev-tools.appspot.com/public/javascript/embed-api/components/view-selector2.js"></script>

<!-- Include the DateRangeSelector component script. -->
<script src="https://ga-dev-tools.appspot.com/public/javascript/embed-api/components/date-range-selector.js"></script>

<!-- Include the ActiveUsers component script. -->
<script src="https://ga-dev-tools.appspot.com/public/javascript/embed-api/components/active-users.js"></script>

<!-- Include the CSS that styles the charts. -->
<link rel="stylesheet" href="https://ga-dev-tools.appspot.com/public/css/chartjs-visualizations.css">

<script>
    let dataIds,
        currentValue,
        dateRangeSelector1,
        i = 0,
        countriesArr = [];

    gapi.analytics.ready(function() {
        if(localStorage.getItem('clientId') && localStorage.getItem('clientId') != 'null') {
            document.getElementById('content').style.display = 'block';
            renderAll();
        } else {
            document.getElementById('authorize-button-box').style.display = 'block';
        }
    });

    function renderAll() {

        /**
         * Authorize the user immediately if the user has already granted access.
         * If no access has been created, render an authorize button inside the
         * element with the ID "embed-api-auth-container".
         */
        gapi.analytics.auth.authorize({
            container: 'embed-api-auth-container',
            clientid: localStorage.getItem('clientId')
        });


        /**
         * Create a new ActiveUsers instance to be rendered inside of an
         * element with the id "active-users-container" and poll for changes every
         * five seconds.
         */
        let activeUsers = new gapi.analytics.ext.ActiveUsers({
            container: 'active-users-container',
            pollingInterval: 5
        });


        /**
         * Add CSS animation to visually show the when users come and go.
         */
        activeUsers.once('success', function () {
            let element = this.container.firstChild;
            let timeout;

            this.on('change', function (data) {
                let element = this.container.firstChild;
                let animationClass = data.delta > 0 ? 'is-increasing' : 'is-decreasing';
                element.className += (' ' + animationClass);
                clearTimeout(timeout);
                timeout = setTimeout(function () {
                    element.className =
                        element.className.replace(/ is-(increasing|decreasing)/g, '');
                }, 3000);
            });
        });


        /**
         * Query params representing the first chart's date range.
         */
        let dateRange1 = {
            'start-date': '7daysAgo',
            'end-date': 'today'
        };

        /**
         * Create a new DateRangeSelector instance to be rendered inside of an
         * element with the id "date-range-selector-1-container", set its date range
         * and then render it to the page.
         */
        dateRangeSelector1 = new gapi.analytics.ext.DateRangeSelector({
            container: 'date-range-selector-1-container'
        }).set(dateRange1).execute();


        /**
         * Create a new ViewSelector2 instance to be rendered inside of an
         * element with the id "view-selector-container".
         */
        let viewSelector = new gapi.analytics.ext.ViewSelector2({
            container: 'view-selector-container',
        }).execute();


        /**
         * Update the activeUsers component, the Chartjs charts, and the dashboard
         * title whenever the user changes the view.
         */
        viewSelector.on('viewChange', function (data) {
            dataIds = data.ids;
            let title = document.getElementById('view-name');
            title.textContent = data.property.name + ' (' + data.view.name + ')';

            // Start tracking active users for this view.
            activeUsers.set(data).execute();
            $("#content").css('overflow','').css('height','');
            load();
            // Render all the of charts for this view.
            $("#form-control-select1").val('Sessions')
                .find("option[value=Sessions]").attr('selected', true);
            renderWeekOverWeekChart(data.ids);
        });

        // Set some global Chart.js defaults.
        Chart.defaults.global.animationSteps = 60;
        Chart.defaults.global.animationEasing = 'easeInOutQuart';
        Chart.defaults.global.responsive = true;
        Chart.defaults.global.maintainAspectRatio = false;

        /**
         * Register a handler to run whenever the user changes the date range from
         * the first datepicker. The handler will update the first dataChart
         * instance as well as change the dashboard subtitle to reflect the range.
         */
        dateRangeSelector1.on('change', function( data ) {
            // Render all the of charts for this view.
            let currentValue = $("#form-control-select1").val();
            $("#content").css('overflow','').css('overflow-y','visible').css('height','');

            load();

            if (currentValue === "Users"){
                renderYearOverYearChart( dataIds );
            } else if (currentValue === 'Sessions') {
                 renderWeekOverWeekChart( dataIds );
            } else if(currentValue ==='Technology') {
                 renderTopBrowsersChart( dataIds );
            } else if (currentValue === 'Location') {
                 renderTopCountriesChart( dataIds );
            } else if (currentValue ===  'Traffic'){
                 renderReferrersChart( dataIds );
            } else if (currentValue ===  'LoadTime'){
                 renderPageLoadChart( dataIds );
            } else if (currentValue ===  'Age'){
                 renderAgeChart( dataIds );
            } else if (currentValue ===  'Gender'){
                 renderGenderChart( dataIds );
            } else if (currentValue ===  'Device'){
                renderDeviceChart( dataIds );
            }
        });

        $( "#form-control-select1" ).on('change', function(){ selectCategory() });
    }

    /**
     * Draw the a chart.js line chart with data from the specified view that
     * overlays session data for the current week over session data for the
     * previous week.
     */
    function renderWeekOverWeekChart( ids ) {
        // Adjust `now` to experiment with different days, for testing only...
        let now = moment(); // .subtract(3, 'day');

        let thisWeek = query({
            'ids': ids,
            'dimensions': 'ga:date,ga:nthDay',
            'metrics': 'ga:sessions',
            'start-date': moment(dateRangeSelector1.startDateInput.value).format('YYYY-MM-DD'),
            'end-date': moment(dateRangeSelector1.endDateInput.value).format('YYYY-MM-DD')
        });

        let lastWeek = query({
            'ids': ids,
            'dimensions': 'ga:date,ga:nthDay',
            'metrics': 'ga:sessions',
            'start-date': moment(dateRangeSelector1.startDateInput.value).subtract(1, 'week').format('YYYY-MM-DD'),
            'end-date': moment(dateRangeSelector1.endDateInput.value).subtract(1, 'week').format('YYYY-MM-DD')
        });

        Promise.all( [thisWeek, lastWeek] ).then(function ( results ) {

            let data1 = results[0].rows.map(function ( row ) {
                return +row[2];
            });
            let data2 = results[1].rows.map(function ( row ) {
                return +row[2];
            });
            let labels = results[1].rows.map(function ( row ) {
                return +row[0];
            });

            labels = labels.map(function ( label ) {
                return moment(label, 'YYYYMMDD').format('ddd');
            });

            let data = {
                labels: labels,
                datasets: [
                    {
                        label: '1 week before',
                        fillColor: 'rgba(220,220,220,0.5)',
                        strokeColor: 'rgba(220,220,220,1)',
                        pointColor: 'rgba(220,220,220,1)',
                        pointStrokeColor: '#fff',
                        data: data2
                    },
                    {
                        label: 'Current range',
                        fillColor: 'rgba(151,187,205,0.5)',
                        strokeColor: 'rgba(151,187,205,1)',
                        pointColor: 'rgba(151,187,205,1)',
                        pointStrokeColor: '#fff',
                        data: data1
                    }
                ]
            };

            new Chart(makeCanvas('chart-1-container')).Line(data);
            generateLegend('legend-1-container', data.datasets);
        });
    }


    /**
     * Draw the a chart.js bar chart with data from the specified view that
     * overlays session data for the current year over session data for the
     * previous year, grouped by month.
     */
    function renderYearOverYearChart( ids ) {
        // Adjust `now` to experiment with different days, for testing only...
        let now = moment(); // .subtract(3, 'day');

        let thisYear = query({
            'ids': ids,
            'dimensions': 'ga:month,ga:nthMonth',
            'metrics': 'ga:users',
            'start-date': moment().date(1).month(0).format('YYYY-MM-DD'),
            'end-date': moment().add(1, 'year').date(1).month(0).format('YYYY-MM-DD')
        });

        let lastYear = query({
            'ids': ids,
            'dimensions': 'ga:month,ga:nthMonth',
            'metrics': 'ga:users',
            'start-date': moment().subtract(1, 'year').date(1).month(0).format('YYYY-MM-DD'),
            'end-date': moment().date(1).month(0).format('YYYY-MM-DD'),
        });

        Promise.all([thisYear, lastYear]).then(function (results) {
            let data1 = results[0].rows.map(function (row) {
                return +row[2];
            });
            let data2 = results[1].rows.map(function (row) {
                return +row[2];
            });
            let labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

            // Ensure the data arrays are at least as long as the labels array.
            // Chart.js bar charts don't (yet) accept sparse datasets.
            for (let i = 0, len = labels.length; i < len; i++) {
                if (data1[i] === undefined) data1[i] = null;
                if (data2[i] === undefined) data2[i] = null;
            }

            let data = {
                labels: labels,
                datasets: [
                    {
                        label: 'Last year',
                        fillColor: 'rgba(220,220,220,0.5)',
                        strokeColor: 'rgba(220,220,220,1)',
                        data: data2
                    },
                    {
                        label: 'Current year',
                        fillColor: 'rgba(151,187,205,0.5)',
                        strokeColor: 'rgba(151,187,205,1)',
                        data: data1
                    }
                ]
            };

            new Chart(makeCanvas('chart-1-container')).Bar(data);
            generateLegend('legend-1-container', data.datasets);
        }).catch(function (err) {
            console.error(err.stack);
        });
    }


    /**
     * Draw the a chart.js doughnut chart with data from the specified view that
     * show the top 5 browsers over the past seven days.
     */
    function renderTopBrowsersChart( ids ) {
        query({
            'ids': ids,
            'dimensions': 'ga:browser',
            'metrics': 'ga:pageviews',
            'sort': '-ga:pageviews',
            'start-date': moment(dateRangeSelector1.startDateInput.value).format('YYYY-MM-DD'),
            'end-date': moment(dateRangeSelector1.endDateInput.value).format('YYYY-MM-DD'),
            'max-results': 5
        })
            .then(function (response) {
                let data = [];
                let colors = ['#4D5360', '#949FB1', '#D4CCC5', '#E2EAE9', '#F7464A'];
                if(response.rows) {
                    response.rows.forEach(function (row, i) {
                        data.push({value: +row[1], color: colors[i], label: row[0]});
                    });
                }else{
                    data.push({value: 100, color: colors[0], label: 'Empty'});
                }
                new Chart(makeCanvas('chart-1-container')).Doughnut(data);
                generateLegend('legend-1-container', data);
            });
    }


    /**
     * Draw the a chart.js doughnut chart with data from the specified view that
     * compares sessions from mobile, desktop, and tablet over the past seven
     * days.
     */
    function renderTopCountriesChart( ids ) {
        query({
            'ids': ids,
            'dimensions': 'ga:country',
            'metrics': 'ga:sessions',
            'sort': '-ga:sessions',
            'start-date': moment(dateRangeSelector1.startDateInput.value).format('YYYY-MM-DD'),
            'end-date': moment(dateRangeSelector1.endDateInput.value).format('YYYY-MM-DD'),
            'max-results': 5
        })
            .then(function (response) {
                console.log('conutries', response);
                let data = [['Country', 'Popularity']];
                let colors = ['#4D5360', '#949FB1', '#D4CCC5', '#E2EAE9', '#F7464A'];

                if(response.rows) {
                    response.rows.forEach(function (row, i) {
                        data.push([row[0], +row[1]]);
                    });
                } else {
                    data.push(['Empty',100]);
                }

                countriesArr = data;

                google.charts.load('current', {
                    'packages':['geochart'],
                    'mapsApiKey': 'AIzaSyD-9tSrke72PouQMnMX-a7eZSW0jkFMBWY'
                });
                google.charts.setOnLoadCallback(drawRegionsMap);

                // new Chart(makeCanvas('chart-1-container')).Doughnut(data);
                // generateLegend('legend-1-container', data);
            });
    }
    /**
     * Draw the a chart.js bar chart with data about site Top Channels
     */

    function renderReferrersChart( ids ) {
        query({
            'ids': ids,
            'dimensions': 'ga:source, ga:medium',
            'metrics': 'ga:pageviews,ga:sessionDuration,ga:exits',
            'sort': '-ga:pageviews',
            'start-date': moment(dateRangeSelector1.startDateInput.value).format('YYYY-MM-DD'),
            'end-date': moment(dateRangeSelector1.endDateInput.value).format('YYYY-MM-DD')
        })
            .then(function (response) {
                let data1 = [];
                let labels = [];
                if(response.rows){ response.rows.forEach(function (row) {
                    let name = row[0].replace(/^\(+|\)+$/g, '');
                    labels.push(name.charAt(0).toUpperCase() + name.slice(1));
                    data1.push(+row[4]);
                });
                }else{
                    labels.push('');
                    data1.push(0);
                }
                let data = {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Top Channels',
                            fillColor: 'rgba(72,154,197,0.7)',
                            strokeColor: 'rgba(151,187,205,1)',
                            data: data1,
                        }
                    ]
                };
                new Chart(makeCanvas('chart-1-container')).Bar(data);
                generateLegend('legend-1-container', data.datasets);
            });
    }

    function renderPageLoadChart( ids ) {
        query({
            'ids': ids,
            'dimensions': 'ga:date,ga:nthDay',
            'metrics': 'ga:avgPageLoadTime',
            'start-date': moment(dateRangeSelector1.startDateInput.value).format('YYYY-MM-DD'),
            'end-date': moment(dateRangeSelector1.endDateInput.value).format('YYYY-MM-DD')
        }).then(function (response) {
            console.log('pageLoad', response);
            let data1 = [];
            let labels = [];
            let labelsAll = [];
            if(response.rows){
                response.rows.forEach(function (row) {
                    data1.push(parseFloat(row[2]));
                    labels.push(+row[0]);
                });
            } else {
                labels.push('');
                data1.push(0);
            }
//             data1.push(parseFloat('0.0'),parseFloat('0.5'),parseFloat('0.4'),parseFloat('0.1'),parseFloat('1.0'),parseFloat('0.3'),parseFloat('0.5'),parseFloat('1.1'));
            labels.map(function (label) {
                let month =  +moment(label, 'YYYYMMDD').format('MM');
                let months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                let element = moment(label, 'YYYYMMDD').format('DD') + ' ' + months[month-1];
                labelsAll.push(element);
            });
            let data = {
                labels: labelsAll,
                datasets: [{
                    label: 'Avg. Page Load Time(sec)',
                    fillColor: 'rgba(72,154,197,0.7)',
                    strokeColor: 'rgba(151,187,205,1)',
                    data: data1,
                }]
            };
            new Chart(makeCanvas('chart-1-container')).Bar(data);
            generateLegend('legend-1-container', data.datasets);
        });
    }

    function renderAgeChart( ids ) {
        query({
            'ids': ids,
            'dimensions': 'ga:userAgeBracket',
            'metrics': 'ga:sessions',
            'start-date': moment(dateRangeSelector1.startDateInput.value).format('YYYY-MM-DD'),
            'end-date': moment(dateRangeSelector1.endDateInput.value).format('YYYY-MM-DD')
        }).then(function (response) {
            console.log('age', response);

            // let data1 = [];
            // let labels = [];
            // let colors = ['#4D5360', '#949FB1'];
            // if(response.rows){ response.rows.forEach(function (row) {
            //     let name = row[0].replace(/^\(+|\)+$/g, '');
            //     labels.push(name.charAt(0).toUpperCase() + name.slice(1));
            //     data1.push(+row[4]);
            // });
            // } else {
            //     labels.push('Male','Female');
            //     data1.push(0,0);
            // }
            // let data = {
            //     labels: labels,
            //     datasets: [
            //         {
            //             label: 'Genders',
            //             fillColor: 'rgba(72,154,197,0.7)',
            //             strokeColor: 'rgba(151,187,205,1)',
            //             data: data1,
            //         }
            //     ]
            // };

            // let data = [];
            // let colors = ['#4D5360', '#949FB1'];
            //
            // if(response.rows) {
            //     response.rows.forEach(function (row, i) {
            //         data.push({
            //             label: row[0],
            //             value: +row[1],
            //             color: colors[i]
            //         });
            //     });
            // } else {
            //     data.push({
            //         label: 'Empty',
            //         value: 100,
            //         color: colors[0]
            //     });
            // }
            // new Chart(makeCanvas('chart-1-container')).Bar(data);
            // generateLegend('legend-1-container', data);
        });
    }

    function renderGenderChart( ids ) {
        query({
            'ids': ids,
            'dimensions': 'ga:userGender',
            'metrics': 'ga:sessions',
            'start-date': moment(dateRangeSelector1.startDateInput.value).format('YYYY-MM-DD'),
            'end-date': moment(dateRangeSelector1.endDateInput.value).format('YYYY-MM-DD')
        }).then(function (response) {
            console.log('gender',response);


            let data = [];
            let colors = ['#4D5360', '#949FB1'];
            if(response.rows) {
                response.rows.forEach(function (row, i) {
                    data.push({
                        label: row[0],
                        value: +row[1],
                        color: colors[i]
                    });
                });
            } else {
                data.push({
                    label: 'Empty',
                    value: 100,
                    color: colors[0]
                });
            }


            // let data1 = [];
            //    let labels = [];
            //    let colors = ['#4D5360', '#949FB1'];
            //    if(response.rows){ response.rows.forEach(function (row) {
            //        let name = row[0].replace(/^\(+|\)+$/g, '');
            //        labels.push(name.charAt(0).toUpperCase() + name.slice(1));
            //        data1.push(+row[4]);
            //    });
            //    } else {
            //        labels.push('Male','Female');
            //        data1.push(0,0);
            //    }
            //    let data = {
            //        labels: labels,
            //        datasets: [
            //            {
            //                label: 'Genders',
            //                fillColor: 'rgba(72,154,197,0.7)',
            //                strokeColor: 'rgba(151,187,205,1)',
            //                data: data1,
            //            }
            //        ]
            //    };
               new Chart(makeCanvas('chart-1-container')).Doughnut(data);
               generateLegend('legend-1-container', data);
           });
    }

    function renderDeviceChart( ids ) {
        query({
            'ids': ids,
            'dimensions': 'ga:deviceCategory',
            'metrics': 'ga:pageviews',
            'sort': '-ga:pageviews',
            'start-date': moment(dateRangeSelector1.startDateInput.value).format('YYYY-MM-DD'),
            'end-date': moment(dateRangeSelector1.endDateInput.value).format('YYYY-MM-DD')
        })
            .then(function (response) {

                let data = [];
                let colors = ['#4D5360', '#949FB1', '#D4CCC5'];

                if(response.rows) {
                    response.rows.forEach(function (row, i) {
                        data.push({
                            label: row[0].charAt(0).toUpperCase() + row[0].slice(1),
                            value: +row[1],
                            color: colors[i]
                        });
                    });
                }else{
                    data.push({
                        label: 'Empty',
                        value: 100,
                        color: colors[0]
                    });
                }
                new Chart(makeCanvas('chart-1-container')).Doughnut(data);
                generateLegend('legend-1-container', data);
            });
    }
    /**
     * Extend the Embed APIs `gapi.analytics.report.Data` component to
     * return a promise the is fulfilled with the value returned by the API.
     * @param {Object} params The request parameters.
     * @return {Promise} A promise.
     */
    function query( params ) {
        return new Promise(function (resolve, reject) {
            let data = new gapi.analytics.report.Data({query: params});
            data.once('success', function (response) {
                resolve(response);
            })
                .once('error', function (response) {
                    reject(response);
                })
                .execute();
        });
    }


    /**
     * Create a new canvas inside the specified element. Set it to be the width
     * and height of its container.
     * @param {string} id The id attribute of the element to host the canvas.
     * @return {RenderingContext} The 2D canvas context.
     */
    function makeCanvas( id ) {
        let container = document.getElementById(id);
        let canvas = document.createElement('canvas');
        let ctx = canvas.getContext('2d');

        container.innerHTML = '';
        canvas.width = container.offsetWidth;
        canvas.height = container.offsetHeight;
        container.appendChild(canvas);

        return ctx;
    }


    /**
     * Create a visual legend inside the specified element based off of a
     * Chart.js dataset.
     * @param {string} id The id attribute of the element to host the legend.
     * @param {Array.<Object>} items A list of labels and colors for the legend.
     */
    function generateLegend( id, items ) {
        console.log('items', items);
        let legend = document.getElementById(id);
        legend.innerHTML = items.map(function (item) {
            let color = item.color || item.fillColor;
            let label = item.label;
            return '<li><i style="background:' + color + '"></i>' +
                escapeHtml( label ) + '</li>';
        }).join('');
    }


    /**
     * Escapes a potentially unsafe HTML string.
     * @param {string} str An string that may contain HTML entities.
     * @return {string} The HTML-escaped string.
     */
    function escapeHtml( str ) {
        let div = document.createElement('div');
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    }

    function queryAccounts() {
        // Load the Google Analytics client library.
        gapi.client.load('analytics', 'v3').then(function() {
            // Get a list of all Google Analytics accounts for this user
            gapi.client.analytics.management.accounts.list().then(handleAccounts);
        });
    }

    function handleAccounts( response ) {
        // Handles the response from the accounts list method.
        if (response.result.items && response.result.items.length) {
            // Get the first Google Analytics account.
            var firstAccountId = response.result.items[0].id;

            // Query for properties.
            queryProperties(firstAccountId);
        } else {
            console.log('No accounts found for this user.');
        }
    }

    function queryProperties( accountId ) {
        // Get a list of all the properties for the account.
        gapi.client.analytics.management.webproperties.list(
            {'accountId': accountId})
            .then(handleProperties)
            .then(null, function(err) {
                // Log any errors.
                console.log(err);
            });
    }

    function handleProperties( response ) {
        // Handles the response from the webproperties list method.
        if (response.result.items && response.result.items.length) {

            // Get the first Google Analytics account
            var firstAccountId = response.result.items[0].accountId;

            // Get the first property ID
            var firstPropertyId = response.result.items[0].id;

            // Query for Views (Profiles).
            queryProfiles(firstAccountId, firstPropertyId);
        } else {
            console.log('No properties found for this user.');
        }
    }

    function queryProfiles( accountId, propertyId ) {
        // Get a list of all Views (Profiles) for the first property
        // of the first Account.
        gapi.client.analytics.management.profiles.list({
            'accountId': accountId,
            'webPropertyId': propertyId
        })
            .then(handleProfiles)
            .then(null, function(err) {
                // Log any errors.
                console.log(err);
            });
    }

    function handleProfiles( response ) {
        // Handles the response from the profiles list method.
        if (response.result.items && response.result.items.length) {
            // Get the first View (Profile) ID.
            var firstProfileId = response.result.items[0].id;

            // Query the Core Reporting API.
            queryCoreReportingApi(firstProfileId);
        } else {
            console.log('No views (profiles) found for this user.');
        }
    }

    function queryCoreReportingApi( profileId ) {
        // Query the Core Reporting API for the number sessions for
        // the past seven days.
        gapi.client.analytics.data.ga.get({
            'ids': 'ga:' + profileId,
            'start-date': '7daysAgo',
            'end-date': 'today',
            'metrics': 'ga:sessions'
        })
            .then(function( response ) {
                console.log(response);
                var formattedJson = JSON.stringify(response.result, null, 2);
                document.getElementById('query-output').value = formattedJson;
            })
            .then(null, function(err) {
                // Log any errors.
                console.log(err);
            });
    }

    function selectCategory() {
        currentValue = $( "#form-control-select1" ).val();
        $("#content").css('overflow','').css('overflow-y','visible').css('height','');
        load();


        if(currentValue == 'Location') {
            $('#chart-1-container').hide();
            $('#legend-1-container').hide();
            $('#regions_div').show();
        } else {
            $('#chart-1-container').show();
            $('#legend-1-container').show();
            $('#regions_div').hide();
        }

        switch (currentValue){
            case 'Users':
                renderYearOverYearChart( dataIds );
                break;
            case 'Sessions':
                renderWeekOverWeekChart( dataIds );
                break;
            case 'Technology':
                renderTopBrowsersChart( dataIds );
                break;
            case 'Location':
                renderTopCountriesChart( dataIds );
                break;
            case 'Traffic':
                renderReferrersChart( dataIds );
                break;
            case 'LoadTime':
                renderPageLoadChart( dataIds );
                break;
            case 'Age':
                renderAgeChart( dataIds );
                break;
            case 'Gender':
                renderGenderChart( dataIds );
                break;
            case 'Device':
                renderDeviceChart( dataIds );
                break;
        }
    }

    function load() {
        if (i === 0) {
            i = 1;
            let elem = document.getElementById("myBar");
            let width = 1;
            let id = setInterval(frame, 10);
            function frame() {
                if (width >= 100) {
                    clearInterval(id);
                    i = 0;
                } else {
                    width++;
                    elem.style.width = width + "%";
                }
            }
        }
    }


    function drawRegionsMap() {
        let data = google.visualization.arrayToDataTable(countriesArr);

        let options = {};

        let chart = new google.visualization.GeoChart(document.getElementById('regions_div'));

        chart.draw(data, options);
    }

    setTimeout(function () {
        queryAccounts();
    }, 5000);
</script>
</body>
</html>