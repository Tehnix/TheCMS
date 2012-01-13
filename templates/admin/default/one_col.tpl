<script>
head.ready(function() {
	{% SCRIPT %}
});
</script>
{% STYLE %}

<div id="content_display" style="float:left;">
	<div id="content-header">
        <div style="float:left;"> 
        	{% TOP_RIGHT %}
        </div>
        <div style="float:right;">
        	<div id="btn">{% TOP_LEFT %}</div>
        </div>
	</div>
</div>

<div style="clear:both;padding-top:8px;"></div>

<div id="content_display" style="clear:both;padding-top:15px;">
	{% FULL %}
</div>

<br><br><br>