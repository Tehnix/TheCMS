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
        <table align="left" border="0" cellspacing="0" cellpadding="3">
            <tr>
                <td>{% ERROR_MSG %}</td>
            </tr>
        </table>
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