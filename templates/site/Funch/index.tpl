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
    !window.jQuery && document.write('<script src="{% JS_ROOT %}jquery-1.7.1.min.js"><\/script>');
    head.js('{% THEME_ROOT %}slideshow.js');
    $(document).ready(function () {
        var w=window,d=document,e=d.documentElement,g=d.getElementsByTagName('body')[0],x=w.innerWidth||e.clientWidth||g.clientWidth,y=w.innerHeight||e.clientHeight||g.clientHeight;
        $("#slideshow").css({"width":x, "height":y});
        $(".slides img").css({"width":x, "height":y});
        $(window).resize(function() {
            var w=window,d=document,e=d.documentElement,g=d.getElementsByTagName('body')[0],x=w.innerWidth||e.clientWidth||g.clientWidth,y=w.innerHeight||e.clientHeight||g.clientHeight;
            console.log(x + ' : ' + y );
            $("#slideshow").css({"width":x, "height":y});
            $(".slides img").css({"width":x, "height":y});
        });
        $('#content').fadeIn(200);
        var upM = false;
        function slideDownMenu(){
            $('#menu').slideDown(200);
            upM = false;
        }
        function slideUpMenu(){
            $('#menu').slideUp(200);
            upM = true;
        }
        $('#menuToggle').click(function () {  
            if(upM){
                slideDownMenu();
            }
            else{
                slideUpMenu();
            }
        });


        var upC = false;
        function showContent(){
            $('#content').fadeIn();
            upC = false;
        }
        function hideContent(){
            $('#content').fadeOut();
            upC = true;
        }
        $('#contentToggle').click(function () {  
            if(upC){
                showContent();
            }
            else{
                hideContent();
            }
        });
    });
    </script>
</head>

<body>
    <div id="container">
    <!--<img id="backgroundImage" src="{% UPLOAD_ROOT %}p16h1igrjbmip1anp1oe8fnb1qe54.jpg">-->
        <nav>
            <table>
                <tr>
                    <td colspan="3" class="logo-fill">
                        <img src="{% IMG_ROOT %}icons/close.png" id="menuToggle">
                    </td>
                </tr>
                <tr>
                    <td class="logo-fill fill-middle"> </td>
                    <td id="logo"></td>
                    <td class="logo-fill fill-middle"> </td>
                </tr>
                <tr>
                    <td colspan="3" class="logo-fill"></td>
                </tr>
            </table>
            <div id="menu">
                {% MENU %}
            </div>
        </nav>

        <div id="content">
                <img src="{% IMG_ROOT %}icons/close.png" id="contentToggle">
            {% CONTENT %}
        </div>

        <div id="slideshow">

            <ul class="slides">
                <li>
                    <img src="{% UPLOAD_ROOT %}p16h1igrjbmip1anp1oe8fnb1qe54.jpg" style="width:100%;height:100%;">
                </li>
                <li>
                    <img src="{% UPLOAD_ROOT %}p16h1j6spe1l571fss1pnkrio1nk64.jpeg" style="width:100%;height:100%;">
                </li>
                <li>
                    <img src="{% UPLOAD_ROOT %}p16h1j7e2b6vknduvsp8mn14.png" style="width:100%;height:100%;">
                </li>
                <li>
                    <img src="{% UPLOAD_ROOT %}p16h1j7e2b15p3ipr11k1mn1an35.jpeg" style="width:100%;height:100%;">
                </li>
            </ul>

            <span class="arrow previous"></span>
            <span class="arrow next"></span>
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