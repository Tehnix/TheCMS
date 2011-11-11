<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>{% SITE_TITLE %}</title>
	<link rel="stylesheet" type="text/css" media="screen, print, projection" href="{% STYLESHEET %}">
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
                <td><input type="text" name="user" maxlength="30"  value="{% FORM_USER %}"</td>
                <td>{% ERROR_USER %}</td>
            </tr>

            <tr>
                <td><font class="norm">Password:</font></td>
                <td><input type="password" name="pass" maxlength="30"  value="{% FORM_PASS %}"></td>
                <td>{% ERROR_PASS %}</td>
            </tr>

            <tr>
                <td><font class="norm">Firstname:</font></td>
                <td><input type="text" name="first_name" maxlength="30"  value="{% FORM_FIRSTNAME %}"></td>
                <td>{% ERROR_FIRSTNAME %}</td>
            </tr>

            <tr>
                <td><font class="norm">Lastname:</font></td>
                <td><input type="text" name="last_name" maxlength="30"  value="{% FORM_LASTNAME %}"></td>
                <td>{% ERROR_LASTNAME %}</td>
            </tr>
            
            <tr>
                <td><font class="norm">E-mail:</font></td>
                <td><input type="text" name="email" maxlength="30"  value="{% FORM_EMAIL %}"></td>
                <td>{% ERROR_EMAIL %}</td>
            </tr>
            
            <tr>
                <td colspan="2" align="right">
                    <input type="hidden" name="subjoin" value="1">
                    <input type="submit" value="Join!">
                </td>
            </tr>
            <tr>
                <td colspan="2" align="left">
                    <a href="{% URL_ROOT %}login">Back to Login</a>
                </td>
            </tr>
        </table>
        </form>
    </div>
    
    <footer>
        Copyright &copy; 2009 - 2011 Christian Laustsen, 
        <a href="{% URL_ROOT %}resources/BSDLicense.html">All rights reserved</a> |
        <a href="{% URL_ROOT %}admin">Administration</a>
    </footer>
</body>
</html>