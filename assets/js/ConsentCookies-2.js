window.onload = function() {
    var policyurl = "https://dmp.com/policy-cookie",
        okcolor = "#000000",
        want_cookie = Cookies.get('cookie_crunch');

    document.write("<div id='cookie-bar' style='display:none;z-index:10;position:absolute; bottom:0; left:0; width: 100%;  text-align: center; padding: 12px 0; margin:0;  background: rgba(244, 244, 244, 1);  color: #919191;  font: 14px arial, sans-serif;'><div style='display:inline-block;width:78%;margin:0; font-family: Arial;'>This website uses cookies - for more information, <a id='cookie-policy' href='" + policyurl + "' style='color: #919191;font-weight:bold;'>read our cookie policy</a>. You can easily block cookies : <span id='stop-cookie' style='text-decoration:underline;'>just click here</span>.</div><div style='width:20%;'><span id='agree' style='position:absolute;bottom:4px;right:4%;color: #FFFFFF;background: " + okcolor + ";border-radius: 3px; line-height: 30px; padding: 0 6px;margin: 1px 8px 0 0;font-weight: 600;'>I agree</span></div></div>");

    document.write("<span id='checkcookies' style='z-index:1;position:absolute;bottom:4px;right:2%;color: #FFFFFF;background: " + okcolor + ";border-radius: 3px; line-height: 30px; padding: 0 6px;margin: 1px 8px 0 0;font-weight: 600;'>cookies " + want_cookie + "</span>");

    if (want_cookie == null || want_cookie == "") {
        document.getElementById("cookie-bar").style.display = "block";
    }

    $(document).ready(function () {
        $('#stop-cookie').click(function () {
            Cookies.set("cookie_crunch", "blocked", {expires: 120});
            $('#cookie-bar').hide(500);
            document.getElementById("checkcookies").innerHTML = "cookies blocked";
        });
    });

//in jquery
// If the user agree with using cookies on my website
    $(document).ready(function () {
        $('#agree').click(function () {
            // I save this information in a cookie
            document.cookie = "cookie_crunch=alive;expires=Thu, 18 Dec 2020 12:00:00 UTC;path=/";
            // I hide the cookie bar
            $('#cookie-bar').hide(500);
            // Then, I display that cookies are used
            document.getElementById("checkcookies").innerHTML = "cookies alive";
        });
    });

// if user want to stop cookies
    $(document).ready(function () {
        $('#stop-cookie').click(function () {
            // I save this information in a cookie
            document.cookie = "cookie_crunch=blocked;expires=Thu, 18 Dec 2020 12:00:00 UTC;path=/";
            // I hide the cookie bar
            $('#cookie-bar').hide(500);
            // I remove specific cookies
            // Then, I display that cookies have been blocked
            document.getElementById("checkcookies").innerHTML = "cookies blocked";
        });
    });

// I want to allow the used to change his choice about cookies
    $(document).ready(function () {
        $('#checkcookies').click(function () {
            $('#cookie-bar').show(500);
        });
    });

    /* in javascript
    document.getElementById("checkcookies").addEventListener("click", showCookiebar);
    function showCookiebar() {
      document.getElementById("cookie-bar").style.display = 'block';
    }

    document.getElementById("agree").addEventListener("click", hideCookiebar);
    function hideCookiebar() {
       document.cookie = "cookie_crunch=yes;expires=Thu, 18 Dec 2020 12:00:00 UTC;path=/";
      document.getElementById("checkcookies").innerHTML = "cookies alive";
      document.getElementById("cookie-bar").style.display = 'none';
    }

    document.getElementById("stop-cookie").addEventListener("click", hideCookiebar);
    function hideCookiebar() {
       document.cookie = "cookie_crunch=no;expires=Thu, 18 Dec 2020 12:00:00 UTC;path=/";
      document.getElementById("checkcookies").innerHTML = "cookies blocked";
      document.getElementById("cookie-bar").style.display = 'none';
    }*/

}