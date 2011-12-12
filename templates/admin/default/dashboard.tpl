<style>
#dashboardTitle{
	color:;
	font-size: 15px;
	font-weight:bold;
}
</style>

<div id="content_display" style="float:left;"> 
	<span id="dashboardTitle">Right now</span>
</div>

<div style="clear:both;padding-top:8px;"></div>

<div id="content_display" style="clear:both;border-top:1px solid #CCC;padding-top:8px;">
	{% COUNT %}
</div>

<br><br><br>

<div id="content_display" style="float:left;">
	<span id="dashboardTitle">Recent activity</span>
</div>

<div style="clear:both;padding-top:8px;"></div>

<div id="content_display" style="clear:both;border-top:1px solid #CCC;padding-top:8px;">
	{% ACTIVITY %}
</div>

<br><br><br>