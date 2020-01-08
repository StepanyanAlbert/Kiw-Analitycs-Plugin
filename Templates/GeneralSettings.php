<!-- Authorize in google -->
<meta name="google-signin-client_id" content="935340515013-pm30ic9co6edran12s4hotdclfoe86va.apps.googleusercontent.com">
<script src="https://apis.google.com/js/platform.js" async defer></script>

<h2>Google Analytics Settings</h2>
<h5>Plugin authorization</h5>
<p>
    You need to create a <a href="https://analytics.google.com">free analytics account</a>
    and watch this <a href="#">video tutorial</a> before proceeding to authorization.
</p>
<div class="row" id="row">
    <div class="g-signin2" data-onsuccess="onSignIn"></div>
    <button id="authorize" class="btn btn-primary" onclick="openAuthForm()">Authorize plugin</button>
    <button id="unauthorize" class="btn btn-primary" style="display: none" onclick="unauthorize()">Clear authorization</button>
</div>

<div id="authForm" class="hidden">
    <p>Please input your project's <a target="_blank" href="https://console.developers.google.com/apis/credentials">client id</a> to authorize</p>
    <input id="client_id" type="text" class="form-control w-50">
    <button onclick="saveClientId()" class="btn btn-primary mt-2">Save client id</button>
</div>

<script>

    setTimeout(function () {
        if(localStorage.getItem('clientId') && localStorage.getItem('clientId') != 'null') {
            addGoogleAuthField();
        }
    }, 1);

    function openAuthForm() {
        $('#authForm').show();
        $('#authorize').hide();
    }
    function saveClientId() {
        let id = $('#client_id').val();
        if(id.length > 0) {
            localStorage.setItem('clientId', id);
            addGoogleAuthField();
            // location.href = 'admin.php?page=google-analytics-dashboard';
        } else {
            alert('Please input a client id');
        }
    }
    function unauthorize() {
        $('#unauthorize').hide();
        $('.g-signin2').hide();
        $('#authorize').show();
        localStorage.removeItem('clientId');
        // location.href = 'admin.php?page=google-analytics-dashboard-settings';
    }
    function addGoogleAuthField() {
        let meta = document.createElement('meta');
        meta.name = 'google-signin-client_id';
        meta.content = localStorage.getItem('clientId');

        let script = document.createElement('script');
        script.src = 'https://apis.google.com/js/platform.js';
        let div = document.createElement('div');
        div.className = 'g-signin2 hidden';
        // div.
        // $('<div class="g-signin2 hidden" data-onsuccess="onSignIn"></div>');
        // script.setAttribute('async defer');
        // script.setAttribute('async');

        $('head').append(meta);
        $('body').append(script);
        $('#row').append(div);

        $('.g-signin2').show();
        $('#unauthorize').show();
        $('#authorize').hide();
        $('#authForm').hide();
    }
</script>