<!DOCTYPE html>
<html>
<head>
        <meta charset="UTF-8">
        <title>{% SITE_TITLE %}</title>
        <link rel="stylesheet" type="text/css" media="screen, print, projection" href="{% STYLESHEET %}&amp;type=login">
        <link rel="shortcut icon" type="image/x-icon" href="{% FAVICON %}">
        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>

<body>
    <div id="container">
        <form action="{% URL_ROOT %}login" method="POST">
        <input type="hidden" name="action" value="process">
        <table border="0" cellspacing="0" cellpadding="3">
            <tr>
                <td><p>Username:</p></td>
                <td><input class="textfields" type="text" name="user" maxlength="30" value="{% FORM_USER %}"></td>
            </tr>
            <tr><td>{% ERROR_USER %}</td></tr>
            <tr>
                    <td><p>Password:</p></td>
                    <td><input class="textfields" type="password" name="pass" maxlength="30" value="{% FORM_PASS %}"></td>
                </tr>
            <tr><td>{% ERROR_PASS %}</td></tr>
                <tr>
                    <td colspan="2" align="left">
                        <input type="checkbox" name="remember" {% FORM_REMEMBER %}>
                        <font size="1">Remember me</font>
                        <input type="hidden" name="sublogin" value="1">
                        <input class="button darkblue" type="submit" value="Login">
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="left">
                        <p>
                            <a href="{% URL_ROOT %}forgotpass">Forgot your Password?</a>    
                            <a href="{% URL_ROOT %}register">Create an account!</a>
                        </p>
                    </td>
                </tr>
        </table>
        </form>
        <div id="logo">
                <img src="{% IMG_ROOT %}TheCMS.png">
        </div>
        <div style="clear:both;"></div>
        <footer>
            Copyright &copy; 2009 - 2011 Christian Laustsen, 
            <a href="{% URL_ROOT %}resources/BSDLicense.html">All rights reserved</a>
        </footer>
    </div>
    
</body>
</html>