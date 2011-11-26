<script>
head.ready(function() {
	$.ajaxSetup ({
		cache: false
	});
	var ajax_load = "<img src='../resources/img/load.gif' width='12px'>";
	
	
	var loadUrl = "../cgi-bin/check.py?backupType=server&run=";
	$("#backup_server_result .backup_btn").click(function(){
		$("#backup_server_result").html(ajax_load + " Checking...");

		$.get(loadUrl+"check", function(responseText){
			if(responseText == "runBackupScript\n"){
				$("#backup_server_result").html(ajax_load + " backing up...").load(loadUrl+"runBackupScript");
			}
			else{
				$("#backup_server_result").html(responseText);
			}
		});
		$("#backup_server_result .new_backup_btn").live("click", function()
		{
			$("#backup_server_result").html(ajax_load + " Backing up...").load(loadUrl+"runBackupScript");
		});
		$("#backup_server_result .old_backup_btn").live("click", function()
		{
			$("#backup_server_result").html(ajax_load+" Backing up...").load(loadUrl+"no");
		});
		$("#backup_server_result .reset_backup_btn").live("click", function()
		{
			$("#backup_server_result").html("Backup Cancelled!");
		});
	});
	var loadUrl2 = "../cgi-bin/check.py?backupType=database&run=";
	$("#backup_db_result .backup_btn").click(function(){
		$("#backup_db_result").html(ajax_load + " Checking...");

		$.get(loadUrl2+"check", function(responseText){
			if(responseText == "runBackupScript\n"){
				$("#backup_db_result").html(ajax_load + " backing up...").load(loadUrl2+"runBackupScript");
			}
			else{
				$("#backup_db_result").html(responseText);
			}
		});
		$("#backup_db_result .new_backup_btn").live("click", function()
		{
			$("#backup_db_result").html(ajax_load + " Backing up...").load(loadUrl2+"runBackupScript");
		});
		$("#backup_db_result .old_backup_btn").live("click", function()
		{
			$("#backup_db_result").html(ajax_load+" Backing up...").load(loadUrl2+"no");
		});
		$("#backup_db_result .reset_backup_btn").live("click", function()
		{
			$("#backup_db_result").html("Backup Cancelled!");
		});
	});
	
	
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