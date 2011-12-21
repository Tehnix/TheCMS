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
    <script src="{% JS_ROOT %}head.load.min.js" type="text/javascript" charset="utf-8"></script>
</head>

<body>
    <div id="container">
        <header>
            <h1>{% TITLE %}</h1>
        </header>

        <nav>
            {% MENU %}
        </nav>
        
        <div id="content">
            {% CONTENT %}
        </div>
    </div>
    
    <footer>
        Copyright &copy; 2009 - 2011 Christian Laustsen, 
        <a href="{% URL_ROOT %}resources/BSDLicense.html">All rights reserved</a> |
        <a href="{% URL_ROOT %}admin">Administration</a>
    </footer>
    {% GOOGLE_ANALYTICS %}
</body>
</html>