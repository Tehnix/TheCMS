<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>{% SITE_TITLE %}</title>
	<link rel="stylesheet" type="text/css" media="screen, print, projection" href="{% STYLESHEET %}?type=login">
	<link rel="shortcut icon" type="image/x-icon" href="{% FAVICON %}">
	<!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>

<body>
    <div id="container">
        <form action="{% URL_ROOT %}register" method="POST">
        <input type="hidden" name="action" value="process">
        <table align="left" border="0" cellspacing="0" cellpadding="3">
            <tr>
                <td><font class="norm">Username:</font></td>
                <td><input class="textfields" type="text" name="cuser" maxlength="30"  value="{% FORM_USER %}"</td>
                <td>{% ERROR_USER %}</td>
            </tr>

            <tr>
                <td><font class="norm">Password:</font></td>
                <td><input class="textfields" type="password" name="cpass" maxlength="30"  value="{% FORM_PASS %}"></td>
                <td>{% ERROR_PASS %}</td>
            </tr>

            <tr>
                <td><font class="norm">Firstname:</font></td>
                <td><input class="textfields" type="text" name="cfirst_name" maxlength="30"  value="{% FORM_FIRSTNAME %}"></td>
                <td>{% ERROR_FIRSTNAME %}</td>
            </tr>

            <tr>
                <td><font class="norm">Lastname:</font></td>
                <td><input class="textfields" type="text" name="clast_name" maxlength="30"  value="{% FORM_LASTNAME %}"></td>
                <td>{% ERROR_LASTNAME %}</td>
            </tr>

            <tr>
                <td><font class="norm">E-mail:</font></td>
                <td><input class="textfields" type="text" name="cemail" maxlength="30"  value="{% FORM_EMAIL %}"></td>
                <td>{% ERROR_EMAIL %}</td>
            </tr>

            <tr>
                <td><font class="norm">Registration key:</font></td>
                <td><input class="textfields" type="text" name="cregkey" maxlength="90"  value="{% FORM_REG %}"></td>
                <td>{% ERROR_REG %}</td>
            </tr>
            <tr>
                <td colspan="2" align="right">
                    <input type="hidden" name="subcreate" value="1">
                    <input class="button darkblue" type="submit" value="Create Account">
                </td>
            </tr>
            <tr>
                <td colspan="2" align="left">
                    <a href="{% URL_ROOT %}login">Back to Login</a>
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