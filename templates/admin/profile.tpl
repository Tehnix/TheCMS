<script>
head.ready(function() {
	{% SCRIPT %}
});
</script>
<style>
#settingsTable{
	color:;
	font-size: 13px;
}
#settingsTable td{
	padding-top:10px;
}
#backup_result{
	padding:5px 0 5px 0;
}
</style>

<div style="clear:both;padding-top:8px;"></div>

<div id="content_display" style="clear:both;border-top:1px solid #CCC;padding-top:8px;">
	{% FIRST %}
</div>

<br><br><br>

<div style="clear:both;padding-top:8px;"></div>

<div id="content_display" style="clear:both;border-top:1px solid #CCC;padding-top:8px;">
	{% SECOND %}
	<div id="backup_server_result">
		<input class="backup_btn" type="button" value="Backup website !">
	</div>
	<div id="backup_db_result">
		<input class="backup_btn" type="button" value="Backup database !">
	</div>
</div>

<br><br><br>