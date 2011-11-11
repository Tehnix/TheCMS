<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>{% HEAD_TITLE %}</title>
	<link rel="stylesheet" type="text/css" media="screen, print, projection" href="{% STYLESHEET %}">
	<link rel="shortcut icon" type="image/x-icon" href="{% FAVICON %}">
	<script src="{% JS_ROOT %}jquery-1.6.4.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="{% JS_ROOT %}head.min.js" type="text/javascript" charset="utf-8"></script>
	<script> 
	$(document).ready(function () {
		var up = true;
		function slideDownMenu(){
			$('ul.menu_body').slideDown(1);
			$('#MenuQuickBarHeader').css({
			"background":"none"});
			up = false;
		}
		function slideUpMenu(){
			$('ul.menu_body').slideUp(1, function() {
				$('#MenuQuickBarHeader').css({
				"background":"none"});
			});
			up = true;
		}
		$('.menu_head').click(function () {  
			if(up){
				slideDownMenu();
			}
			else{
				slideUpMenu();
			}
			$('html').one('click',function() {
				slideUpMenu();
			});
			event.stopPropagation();
		});
		
		$('#zebraTable tr:odd').addClass("alt");
		$('div[id^=content_display]').css('visibility','visible').hide().fadeIn();
	});
	</script>
</head>


<body>
    <div id="MenuContainer">
        <div id="Menu">
    		<a href ="{% URL_ROOT %}"> <img id="MenuTitle" src="{% IMG_ROOT %}TheCMS_small.gif" alt="TheCMS"> </a>
    		<ul id="MenuAnchorList">
    			{% MENU %}
    		</ul>
    		<span id="MenuRightSide">
    			<div style="float:left;">
    				Ohai thar, <span style="font-weight:bold;cursor:pointer;" class="menu_head">{% USERNAME %}</span> !&nbsp;
    			</div>
    			<div style="float:right;">
    				<div id="MenuQuickBarHeader" class="menu_head">
    					<span class="gbma"></span>&nbsp;&nbsp;
    					<!-- <img src="{% IMG_ROOT %}icons/down_arrow.png" alt="v"> -->
    				</div>
    				<ul id="MenuQuickBar" class="menu_body"> 
    					<li><span class="rgbma"></span></li> 
    					<li><a href="{% URL_ROOT %}admin/settings">Settings</a></li> 
    					<li><a href="{% URL_ROOT %}admin/profile">Profile</a></li> 
    					<li>
    						<form action="{% URL_ROOT %}" method="POST">
    							<input type="hidden" name="action" value="process">
    							<input type="submit" value="Log Out">
    						</form>
    					</li>  
    				</ul>
    			</div>
    		</span>
    	</div>
	</div>
	
	<div id="Content">
		<h1>{% TITLE %}</h1>
		{% CONTENT %}
	</div>
	
	<div id="Footer">
		
	</div>
	<script>
	head.js("{% JS_ROOT %}jquery.plugins.js", function() {
		head.js("{% JS_ROOT %}plupload.full.js", "{% JS_ROOT %}plupload.queue.js");
		head.js("{% JS_ROOT %}jquery.cleditor.min.js", function() {
			$(".advancedEditor").cleditor({width:"99.5%", height:400});
			$(".simpleEditor").cleditor({
				width:"98.5%", 
				height:150,
				controls: "bold italic underline strikethrough | image | subscript superscript | font size " +
				"| alignleft center alignright justify | undo redo | source"
			});
		});
		head.js("{% JS_ROOT %}jquery.fancybox-1.3.4.pack.js", function() {
			$(".iframe").fancybox({
				'width'				: 550,
				'height'			: 300,
				'autoScale'			: false,
				'transitionIn'		: 'none',
				'transitionOut'		: 'none',
				'type'				: 'iframe'
			});
		});
	});
	</script>
</body>
</html>