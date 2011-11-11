<script>
head.ready(function() {
	{% SCRIPT %}
});
</script>
{% STYLE %}
<div style="float:left;"> 
	{% TOP_RIGHT %}
</div>

<div id="btn" style="float:right;">
	{% TOP_LEFT %}
</div>

<div style="clear:both;padding-top:8px;"></div>

<div id="content_display" style="float:left;width:67%;border-top:1px solid #CCC;">
	{% LEFT %}
</div>

<div id="content_display" style="float:right;width:27%;border-top:1px solid #CCC;">
	{% RIGHT %}
</div>
<div style="clear:both;"></div>

<br><br><br>