<!--<h2>Google Analytics Settings</h2>-->
<!--<h5 style="margin-top: 40px;">Plugin authorization</h5>-->
<!--<p style="font-size: 15px">-->
<!--    You need to create a <a href="https://analytics.google.com">free Google analytics account</a> before proceeding to authorization.-->
<!--</p>-->
<!---->
<!--<p class="row" id="row">-->
<!--    <p id="info">If you already have an account, please input your <a href="https://console.developers.google.com/apis/credentials" target="_blank">OAuth cliend id</a> to authorize this plugin</p>-->
<!--    <button id="authorize" class="btn btn-primary" onclick="openAuthForm()">Authorize plugin</button>-->
<!--    <button id="unauthorize" class="btn btn-primary" style="display: none" onclick="unauthorize()">Clear authorization</button>-->
<!--</p>-->
<!---->
<!--<div id="authForm" class="hidden">-->
<!--    <input id="client_id" type="text" class="form-control w-50">-->
<!--    <button onclick="saveClientId()" class="btn btn-primary mt-2 save">Save client id</button>-->
<!--</div>-->
<!---->
<!--<script>-->
<!---->
<!---->
<!--    function saveClientId() {-->
<!--        if($('#client_id').val().length == 0 || $('#client_id').val().length < 20) {-->
<!--            alert('Please enter a valid oauth client id');-->
<!--        } else {-->
<!--            localStorage.setItem('clientId', $('#client_id').val());-->
<!--            $('#unauthorize').show();-->
<!--            $('#authorize').hide();-->
<!--            $('#info').hide();-->
<!--            $('#authForm').hide();-->
<!--        }-->
<!--    }-->
<!--    function openAuthForm() {-->
<!--        $('#authForm').show();-->
<!--        $('#authorize').hide();-->
<!--    }-->
<!---->
<!--    function unauthorize() {-->
<!--        localStorage.removeItem('clientId');-->
<!--        $('#unauthorize').hide();-->
<!--        $('#info').show();-->
<!--        $('#authorize').show();-->
<!--    }-->
<!---->
<!--    setTimeout(function () {-->
<!--        if(localStorage.getItem('clientId') && localStorage.getItem('clientId') != 'null') {-->
<!--            $('#info').hide();-->
<!--            $('#unauthorize').show();-->
<!--            $('#authorize').hide();-->
<!--        } else {-->
<!--            $('#info').show();-->
<!--            $('#unauthorize').hide();-->
<!--            $('#authorize').show();-->
<!--        }-->
<!--    },10);-->
<!---->
<!--</script>-->

<div id="my-signin2"></div>
<script>
    function onSuccess(googleUser) {
        console.log('Logged in as: ' + googleUser.getBasicProfile().getName());
    }
    function onFailure(error) {
        console.log(error);
    }
    function renderButton() {
        gapi.signin2.render('my-signin2', {
            'scope': 'profile email',
            'width': 240,
            'height': 50,
            'longtitle': true,
            'theme': 'dark',
            'onsuccess': onSuccess,
            'onfailure': onFailure
        });
    }
</script>

<script src="https://apis.google.com/js/platform.js?onload=renderButton" async defer></script>