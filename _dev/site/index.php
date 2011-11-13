<?php
if(substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start();
require('manage.php');
require('urls.php');
# Import the module: Models, URL handlers and Views
$getmodules = $Modules->getModules();
foreach($getmodules as $module) {
	include(MODULE_ROOT . $module . DS . 'model.php');
	include(MODULE_ROOT . $module . DS . 'urls.php');
	include(MODULE_ROOT . $module . DS . 'view.php');
}

# URL variable
$url_query = explode('/', URL);

# Set settings values
$settings = $Database->fetchOne('settings', array('id'=>'1'));

# Construct the layout from the template
if($Module_admin) {
	# Create the dashboard arrays
	$admin_count_array_left = array();
	$admin_count_array_right = array();
	$admin_activity = array();
	# Construct the admin menu
	$admin_menu_array = array('dashboard'=>'Dashboard');
	foreach($getmodules as $module) {
		if(is_file(MODULE_ROOT . $module . DS . 'admin.php')){
			include(MODULE_ROOT . $module . DS . 'admin.php');
			$admin_menu_array[$module] = ucwords($module);
		}
	}
	foreach($admin_menu_array as $key => $value){
		$admin_menu .= '<li><a href="' . URL_ROOT . 'admin/' . $key . '">' . $value . '</a></li>';
	}
	
	# Set admin title
	if(isset($url_query[1]) and $url_query[1] != '' or isset($admin_title)){
		$admin_title = ucfirst($url_query[1]) . $admin_title;
	}
	else{
		# Fallback is dashboard
		$admin_title = 'Dashboard';
	}
	if($admin_title == 'Dashboard'){
		# And then we construct the dashboard items
		foreach($admin_count_array_left as $key => $value){
			$admin_dashboard_left .= '<tr><td><b>' . $key . '</b></td><td>' . $value . '</td></tr>';
		}
		foreach($admin_count_array_right as $key => $value){
			$admin_dashboard_right .= '<tr><td><b>' . $key . '</b></td><td>' . $value . '</td></tr>';
		}
		$admin_activity_log = $Database->fetchall('_recent_activity', array(), 'ORDER BY date DESC LIMIT 10');
		foreach($admin_activity_log as $log){
			$tagToReplace = '{% ADDIT %}';
			$admin_activity_text .= '
			<tr>
				<td><img src="' . RESOURCES_ROOT . 'img/icons/' . $log['action'] . '.png" style="width:16px;margin:0 10px 0 0;"></td>
				<td>' . $admin_activity[$log['name']][$log['action']] . '</td>
			</tr>';
			$admin_activity_text = str_replace($tagToReplace, $log['additional'], $admin_activity_text);
		}
		
		$count = '
		<table id="dashboardTable">
			<tr>
				<td><table>' . $admin_dashboard_left . '</table></td>
				<td><table>' . $admin_dashboard_right . '</table></td>
			</tr>
		</table>';
		
		$activity = '
		<table id="activityTable">
			' . $admin_activity_text . '
		</table>';
		
		$tpl_content = new Template(TEMPLATES_ROOT . 'admin/dashboard.tpl');
		$tpl_content->set('SCRIPT', '');
		$tpl_content->set('COUNT' , $count);
		$tpl_content->set('ACTIVITY' , $activity);
		
		$tpl_content = $tpl_content->output();
	}
	if($admin_title == 'Settings'){
		$admin = new AdminGenerator;
		$pages_array = array();
		$pages = $Database->fetchAll('pages');
		foreach($pages as $page){
			$pages_array[$page['id']] = $page['name'];
		}
		$startpage = $admin->select(array('name'=>'settings_startpage',
		                                  'style'=>'width:130px;',
		                                  'selected'=>$settings['startpage']),
		                            $pages_array);
		$membership = $admin->select(array('name'=>'settings_membership',
		                                   'style'=>'width:130px;',
		                                   'selected'=>$settings['membership']),
		                             array('0'=>'None',
		                                   '1'=>'Open',
		                                   '2'=>'Closed'));
		$theme = $admin->select(array('name'=>'settings_theme',
		                              'style'=>'width:130px;',
		                              'selected'=>$settings['theme']),
		                        array());
		
		$sitetitle = $admin->input(array('name'=>'settings_sitetitle',
		                                 'id'=>'settings_sitetitle',
		                                 'class'=>'input',
		                                 'style'=>'width:300px;',
		                                 'type'=>'text',
		                                 'value'=>$settings['sitetitle'],
		                                 'placeholder'=>'This is my sites title !'));
		
		$url = $admin->input(array('name'=>'settings_url',
		                           'id'=>'settings_url',
		                           'class'=>'input',
		                           'style'=>'width:300px;',
		                           'type'=>'text',
		                           'value'=>$settings['url'],
		                           'placeholder'=>'http://www.yoursite.com'));
		
		$email = $admin->input(array('name'=>'settings_email',
		                             'id'=>'settings_email',
		                             'class'=>'input',
		                             'style'=>'width:300px;',
		                             'type'=>'text',
		                             'value'=>$settings['email'],
		                             'placeholder'=>'MyOwn@Email.com'));
		
		$submit = $admin->input(array('id'=>'settings_submit',
		                              'class'=>'button darkblue',
		                              'type'=>'submit',
		                              'value'=>'Submit'));
		
		$form = $admin->form(array('action'=>'settings_settings',
                                   'referer'=>'admin/settings'));
                                   
		$first = $form . '
		<table id="settingsTable">
			<thead>
				<tr>
					<th style="width:250px;"></th>
					<th style="width:;"></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Site title :</td>
					<td>' . $sitetitle . '</td>
				</tr>
				<tr>
					<td>URL :</td>
					<td>' . $url . '</td>
				</tr>
				<tr>
					<td>E-Mail :</td>
					<td>' . $email . '</td>
				</tr>
				<tr>
					<td>Startpage :</td>
					<td>' . $startpage . '</td>
				</tr>
				<tr>
					<td>Registration :</td>
					<td>'. $membership .'</td>
				</tr>
				<tr>
					<td>Theme :</td>
					<td>'. $theme .'</td>
				</tr>
				<tr>
					<td></td>
					<td style="text-align:right;">'. $submit .'</td>
				</tr>
			</tbody>
		</table>
		</form>';
		
		$second = 'Sådan noget som google analytics!';
		
		$tpl_content = new Template(TEMPLATES_ROOT . 'admin/settings.tpl');
		$tpl_content->set('SCRIPT', $admin->script);
		$tpl_content->set('FIRST' , $first);
		$tpl_content->set('SECOND' , $second);
		
		$tpl_content = $tpl_content->output();
	}
	# The admin page
	$tpl_layout = new Template(TEMPLATES_ROOT . 'admin' . DS . 'index.tpl');
	$tpl_layout->set('STYLESHEET', ADMIN_MEDIA_ROOT . 'compressed.php');
	$tpl_layout->set('FAVICON', RESOURCES_ROOT . 'img' . DS . 'favicon.ico');
	$tpl_layout->set('IMG_ROOT', RESOURCES_ROOT . 'img' . DS);
	$tpl_layout->set('JS_ROOT', RESOURCES_ROOT . 'js' . DS);
	$tpl_layout->set('URL_ROOT', URL_ROOT);
	$tpl_layout->set('HEAD_TITLE', $settings['sitetitle'] . ' | Admin');
	$tpl_layout->set('USERNAME', $Session->username);
	$tpl_layout->set('MENU', $admin_menu);
	$tpl_layout->set('TITLE', $admin_title);
	$tpl_layout->set('CONTENT', $tpl_content);
	print $tpl_layout->output();
}
else {
	# The general page
	$tpl_layout = new Template(TEMPLATES_ROOT . 'index.tpl');
	$tpl_layout->set('STYLESHEET', MEDIA_ROOT . 'compressed.php');
	$tpl_layout->set('FAVICON', RESOURCES_ROOT . 'img' . DS . 'favicon.ico');
	$tpl_layout->set('TITLE', 'T3$t1ng l13k t3h 60$$ ! !');
	$tpl_layout->set('CONTENT', $tpl_content);
	print $tpl_layout->output();
}