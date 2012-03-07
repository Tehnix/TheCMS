<?php
require_once('manage.php');

/**                                                                          *
 *                                                                           *
 * This section is where class that needs                                    *
 * it, are instantiated                                                      *
 */

# Initialize Database object
$Database = new Database(DB_TYPE, DB_SERVER, DB_NAME, DB_USER, DB_PASS);
# Initialize Modules object
$Modules = new Modules;
# Initialize userhandler object
$UsersHandler = new UsersHandler;
# Initialize mailer object
$Mailer = new Mailer;
# Initialize session object
$Session = new Session;
# Initialize form object
$Form = new Form;

# Set settings values
$settings = $Database->fetchOne('settings', array('id'=>'1'));
# Save our settings in the TemplateBase class
TemplateBase::setSettings($settings);

/**                                                                          *
 *                                                                           *
 * This section is for handling the interaction                              *
 * with the core objects in manage.php                                       *
 */
$FieldStorage = False;
if ($_POST){
    $FieldStorage = $_POST;
} else if ($_GET){
    $FieldStorage = $_GET;
}
# Import the module: Models, URL handlers and Views
$getmodules = $Modules->getModules();
foreach($getmodules as $module) {
    if (is_file(MODULE_ROOT . $module . DS . 'model.php')) {
        include(MODULE_ROOT . $module . DS . 'model.php');
    }
}
$cssFiles = array();
# Creation of the minified and gzippid compressed css
if (isset($_GET['css']) and $_GET['css'] == 'css') {
    /* Add your CSS files to this array */
    if (isset($_GET['type'])) {
        if ($_GET['type'] != 'admin') {
        	$type = '.' . $_GET['type'];
        } elseif ($_GET['type'] == 'admin') {
            $type = $_GET['type'];
        }
    } else {
        $type = '';
    }

    if ($type != 'admin') {
        $getmodules = $Modules->getModules();
        foreach($getmodules as $module) {
            if (file_exists(MODULE_ROOT . $module . DS . 'style.css')) {
                $cssFiles[] = MODULE_ROOT . $module . DS . 'style.css';
            }
        }
        $file = 'style' . $type . '.css';
        if (file_exists(TEMPLATES_ROOT . 'site' . DS . $settings['theme'] . DS 
           . $file)) {
            $cssFiles[] = TEMPLATES_ROOT . 'site' . DS . $settings['theme'] . DS . $file;
        } elseif (file_exists(TEMPLATES_ROOT . 'site' . DS . 'default' . DS 
               . $file)) {
            $cssFiles[] = TEMPLATES_ROOT . 'site' . DS . 'default' . DS . $file;
        }
    } else {
        $getmodules = $Modules->getModules();
        foreach($getmodules as $module) {
            if (file_exists(MODULE_ROOT . $module . DS . 'style.admin.css')) {
                $cssFiles[] = MODULE_ROOT . $module . DS . 'style.admin.css';
            }
        }
        $file = 'style.css';
        if (file_exists(TEMPLATES_ROOT . 'admin' . DS . $settings['admin_theme'] . DS 
           . $file)) {
            $cssFiles[] = TEMPLATES_ROOT . 'admin' . DS . $settings['admin_theme'] . DS . $file;
        } elseif (file_exists(TEMPLATES_ROOT . 'admin' . DS . 'default' . DS 
               . $file)) {
            $cssFiles[] = TEMPLATES_ROOT . 'admin' . DS . 'default' . DS . $file;
        }
    }

    /**
     * Ideally, you wouldn't need to change any code beyond this point.
     */
    $buffer = "";
    foreach ($cssFiles as $cssFile) {
      $buffer .= file_get_contents($cssFile);
    }
    $defValues = array(
                       'THEME'=>URL_TEMPLATES_ROOT . 'site' . DS . $settings['theme'],
                      );
    foreach ($defValues as $key => $value) {
        $tagToReplace = '{% ' . $key . ' %}';
        $buffer = str_replace($tagToReplace, $value, $buffer);
    }

    // Remove comments
    $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);

    // Remove space after colons
    $buffer = str_replace(': ', ':', $buffer);

    // Remove whitespace
    $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);

    // Enable GZip encoding.
    ob_start("ob_gzhandler");

    // Enable caching
    header('Cache-Control: public'); 

    // Expire in one week
    header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 604800) . ' GMT'); 

    // Set the correct MIME type, because Apache won't set it for us
    header("Content-type: text/css");

    // Write everything out
    echo($buffer);
    exit();
}
if (isset($FieldStorage['action'])) {
    # Interaction with the Database object
    if ($FieldStorage['action'] == 'backup_db') {
        if (isset($FieldStorage['output'])){
            $output = $FieldStorage['output'];
        } else {
            $output = '';
        }
        $backup_db = $Database->backupDatabase('*');
        if ($backup_db and $output == 'print') {
            print 'The database has been backed up successfully !';
        } else {
            print 'Something went wrong while trying to backup the database, '
                  . 'please try again <input class="old_backup_btn" '
                  . 'type="button" value="Backup database !">';
        }
    }
    # Interaction with the AdminGenerator object
    if ($FieldStorage['action'] == 'settings_settings') {
        $Database->update('settings',
                          array('sitetitle'=>$FieldStorage['settings_sitetitle'],
                          'url'=>$FieldStorage['settings_url'],
                          'email'=>$FieldStorage['settings_email'],
                          'startpage'=>$FieldStorage['settings_startpage'], 
                          'membership'=>$FieldStorage['settings_membership'],
                          'theme'=>$FieldStorage['settings_theme'],
                          'themeAdmin'=>$FieldStorage['settings_themeAdmin'],
                          'googleanalytics'=>$FieldStorage['settings_googleanalytics'],
                          'analyticscode'=>$FieldStorage['settings_analyticscode']),
                          array('id'=>'1'));
        header("Location: " . $FieldStorage['referer'] . "");
    }
}
# Interaction with the Uploader object
if (isset($_GET['action']) and $_GET['action'] == 'upload_file') {
    # Initialize Uploader object
    $Uploader = new Uploader;
    $Uploader->upload($_REQUEST);
}
# Interaction with the Process object
if (isset($_POST['action']) and $_POST['action'] == 'process') {
    $Process = new Process;
}
