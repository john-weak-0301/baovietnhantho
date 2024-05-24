@if ($pageId = setting('messenger_page_id'))
    <div id="fb-root"></div>

    <script>
        window.fbAsyncInit = function () {
            FB.init({
                xfbml: true,
                version: 'v3.3',
            });
        };

        (function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s);
            js.id = id;
            js.src = 'https://connect.facebook.net/vi_VN/sdk/xfbml.customerchat.js';
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>

    <div class="fb-customerchat"
         attribution=setup_tool
         page_id="{{ $pageId }}">
    </div>
@endif
