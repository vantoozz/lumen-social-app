<html>
<body>
<script>

    window.fbAsyncInit = function () {
        FB.init({
            appId: '{{ getenv('FB_APP_ID') }}',
            xfbml: true,
            version: '{{ getenv('FB_API_VERSION') }}'
        });

        FB.getLoginStatus(function (response) {
            // Check login status on load, and if the user is
            // already logged in, go directly to the welcome message.
            if (response.status == 'connected') {
                location.reload(true);
            } else {
                // Otherwise, show Login dialog first.
                FB.login(function (response) {
                    location.reload(true);
                }, {scope: '{{ getenv('FB_SCOPE') }}'});
            }
        });
    };

    (function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {
            return;
        }
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

</script>
</body>
</html>