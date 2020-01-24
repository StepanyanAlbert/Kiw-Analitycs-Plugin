<!DOCTYPE html>
<html>
<head>
    <title>KIWI GAD</title>
    <link rel="stylesheet" type="text/css" href="'../wp-content/plugins/Kiw-Analitycs-Plugin/src/css/style.css'">
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
            <div id="regions_div" style="width: 500px; height: 500px; display: none"></div>
            <figure class="Chartjs-figure" id="chart-1-container"></figure>
            <ol class="Chartjs-legend" id="legend-1-container"></ol>
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

<!-- Include custom js file-->
<script src="../wp-content/plugins/Kiw-Analitycs-Plugin/src/js/script.js"></script>

</body>
</html>