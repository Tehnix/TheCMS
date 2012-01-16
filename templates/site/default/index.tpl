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
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="{% JS_ROOT %}head.load.min.js" type="text/javascript" charset="utf-8"></script>
	<script>
    // Make sure jQuery is loaded, but we prefer from googles CDN
    !window.jQuery && document.write('<script src="{% JS_ROOT %}jquery-1.7.1.min.js"><\/script>');
    //{% AJAX %}
    </script>
</head>

<body>
    <div id="container">
        <header class="ajax-title">
            <h1>{% TITLE %}</h1>
        </header>
        
        <nav>
            <ul id="menu" class="ajax-menu">
            <?php
                foreach(Pages::getMenu() as $menu){
                    print '<li><a ' . $menu['active'] . ' href="' . $menu['href'] . '">' . $menu['name'] . '</a></li>';
                }
            ?>
            </ul>
        </nav>
        
        <div id="content" class="ajax-content">
            {% CONTENT %}
        </div>
    </div>
    
    <footer>
        Copyright &copy; 2009 - 2011 Christian Laustsen, 
        <a href="{% URL_ROOT %}LICENSE">All rights reserved</a> |
        <a href="{% URL_ROOT %}admin">Administration</a>
    </footer>
    {% GOOGLE_ANALYTICS %}
</body>
</html>