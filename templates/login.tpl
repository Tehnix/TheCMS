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
        <form action="{% URL_ROOT %}login" method="POST">
        <input type="hidden" name="action" value="process">
        <table border="0" cellspacing="0" cellpadding="3">
            <tr>
                <td><p>Username:</p></td>
                <td><input type="text" name="user" maxlength="30" value="{% FORM_USER %}"></td>
                <td>{% ERROR_USER %}</td>
            </tr>
        	<tr>
        	    <td><p>Password:</p></td>
        	    <td><input type="password" name="pass" maxlength="30" value="{% FORM_PASS %}"></td>
        	    <td>{% ERROR_PASS %}</td>
        	</tr>
        	<tr>
        	    <td colspan="2" align="left">
        	        <input type="checkbox" name="remember" {% FORM_REMEMBER %}>
        	        <font size="2">Remember me next time &nbsp;&nbsp;&nbsp;&nbsp;</font>
        	        <input type="hidden" name="sublogin" value="1">
        	        <input type="submit" value="Login">
        	    </td>
        	</tr>
        	<tr>
        	    <td colspan="2" align="left">
        	        <p>
        	            <a href="{% URL_ROOT %}forgotpass">Forgot Password?</a> 
        	            <a href="{% URL_ROOT %}register">Create account</a>
        	        </p>
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