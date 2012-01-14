<?php
#____________________________________________________________________________#
# File: manage.php                                                           #
#____________________________________________________________________________#

require('settings.php');

/**                                                                          *
 *                                                                           *
 * The Database class is meant to simplify SQL executions,                   *
 * and also make it safer to use, by coupling it with                        *
 * PHP's PDO for database handling.                                          *
 */
class Database
{
    protected $DB_TYPE;
    protected $DB_SERVER;
    protected $DB_NAME;
    protected $DB_USER;
    protected $DB_PASS;
    
    public static $lastinsertid;

    public function __construct($DB_TYPE, $DB_SERVER, $DB_NAME, 
                                $DB_USER, $DB_PASS) {
        $this->DB_TYPE = $DB_TYPE;
        $this->DB_SERVER = $DB_SERVER;
        $this->DB_NAME = $DB_NAME;
        $this->DB_USER = $DB_USER;
        $this->DB_PASS = $DB_PASS;
    }
    
    public function execute($type, $sql, $args) {
        try {
            # MySQL with PDO_MYSQL
            if ($this->DB_TYPE == 'MySQL') {
                $dbh = new PDO('mysql:host=' . $this->DB_SERVER 
                               . ';dbname=' . $this->DB_NAME . '',
                               $this->DB_USER,
                               $this->DB_PASS);
            }
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $stmt = $dbh->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            
            if ($type === 'fetchone') {
                $stmt->execute($args);
                $data = $stmt->fetch();
            } else if ($type === 'fetchall') {
                $stmt->execute($args);
                $data = $stmt->fetchAll();
            } else if ($type === 'fetchall_nonassoc') {
                $stmt->execute($args);
                $data = $stmt->fetchAll(PDO::FETCH_BOTH);
            } else if ($type === 'count') {
                $count = $stmt->execute($args);
                $data = $stmt->fetchColumn(); 
            } else if ($type === 'exec') {
                $dbh->beginTransaction();
                $stmt->execute($args);
                self::$lastinsertid = $dbh->lastInsertId();
                $dbh->commit();
            }
            $dbh = null;
            
            return $data;
        }
        catch (PDOException $e) {
            if ($type == 'exec') {
                $dbh->rollBack();
            }
            file_put_contents('includes/errors/PDOErrors.txt',
                              $e->getMessage()."\n", FILE_APPEND);
            
            return "Sorry, an internal database error has occurred !";
        }
        
    }
    
    private function keysToSql($keyarray, $seperator, $prefix='') {
        if ($keyarray == null) {
            return 1;
        } else {
            $list = array();
            foreach($keyarray as $key => $value) {
                $list[] = $key . ' = ' . $prefix . $key . ' ';
            }
            
            return implode($seperator, $list);
        }
    }
    
    private function keysToInsertSql($keyarray, $seperator, $prefix='') {
        if ($keyarray == null) {
            return 1;
        } else {
            $list = array();
            foreach($keyarray as $key => $value) {
                $list[] = $key;
                $list2[] = $prefix . $key;
            }
            $insertSQL = '(' . implode($seperator, $list) . ') VALUES (' 
                         . implode($seperator, $list2) . ')';
            
            return $insertSQL;
        }
    }
    
    public function fetchOne($table, $filters=null) {
        $sql = 'SELECT * FROM ' . $table . ' WHERE ' 
               . $this->keysToSql($filters, "AND ", ":") . '';
        
        return $this->execute('fetchone', $sql, $filters);
    }
    
    public function fetchAll($table, $filters=null, $additional='') {
        $sql = 'SELECT * FROM ' . $table . ' WHERE ' 
               . $this->keysToSql($filters, "AND ", ":") . ' ' . $additional;
        
        return $this->execute('fetchall', $sql, $filters);
    }
    
    public function insert($table, $data=null) {
        $sql = 'INSERT INTO ' . $table . ' ' 
               . $this->keysToInsertSql($data, ", ", ":") . '';
        return $this->execute('exec', $sql, $data);
    }
    
    public function update($table, $data=null, $filters=null) {
        $sql = 'UPDATE ' . $table . ' SET ' 
               . $this->keysToSql($data, ", ", ":") . ' WHERE ' 
               . $this->keysToSql($filters, "AND ", ":filter_") . '';
        
        $tmp_filters = array();
        foreach($filters as $key => $value) {
            $tmp_filters['filter_'.$key] = $value;
        }
        $filters = array_merge($data, $tmp_filters);
        
        return $this->execute('exec', $sql, $filters);
    }
    
    public function delete($table, $filters=null) {
        $sql = 'DELETE FROM ' . $table . ' WHERE ' 
        . $this->keysToSql($filters, "AND ", ":") . '';
        
        return $this->execute('exec', $sql, $filters);
    }
    
    public function backupDatabase($tables, $filename=null) {
        if (empty($tables)){
            return false;
        } else {
            if (empty($filename)){
                $filename = ROOT . '_backup/database/' . date("Y-m-d.H-i") 
                            . '.backup.sql';
            }
            # Get all of the tables
            if ($tables == '*'){
                $tables = array();
                $query = $this->execute('fetchall_nonassoc', 'SHOW TABLES');
                foreach($query as $row) {
                    $tables[] = $row[0];
                }
            } else {
                $tables = is_array($tables) ? $tables : explode(',',$tables);
            }
            $return .= "--\n";
            $return .= "-- SQL Dump\n";
            $return .= "--\n";
            $return .= "-- Site: " . $_SERVER['SERVER_NAME'] . "\n";
            $return .= "-- Generation Time: " . date("M j, Y") . " at " 
                       . date("h:i A") . "\n";
            $return .= "-- Server Version: " . APACHE_VERSION_NUM . "\n";
            $return .= "-- PHP Version: " . PHP_VERSION_NUM . "\n\n";
            $return .= "--\n";
            $return .= "-- Database: `" . DB_NAME . "`\n";
            $return .= "--\n\n";
            $return .= "-- --------------------------------------------------"
                       . "------\n\n";
            # cycle through tables
            for ($k=0; $k<sizeof($tables);$k++) {
                $table = $tables[$k];
                $sql = 'SELECT * FROM ' . $table;
                $query = $this->execute('fetchall_nonassoc', $sql);
                $num_fields = sizeof($query[0]) / 2;
                
                $return .= "--\n";
                $return .= "-- Table structure for table `" . $table . "`\n";
                $return .= "--\n\n";
                $return .= "DROP TABLE IF EXISTS `" . $table . "`;";
                $create_table = $this->execute('fetchall_nonassoc',
                                               'SHOW CREATE TABLE ' . $table);
                $return .= "\n" . $create_table[0][1] . ";\n\n";
                $return .= "--\n-- Dumping data for table `" . $table 
                           . "`\n--\n\n";
                # $query is diveded by two because we're stuck 
                # with PDO::FECTCH_BOTH
                for ($p=0;$p<(sizeof($query)/2);$p++){
                    $row = $query[$p];
                    $return .= 'INSERT INTO ' . $table . ' VALUES(';
                    for ($j=0; $j<$num_fields; $j++){
                        $row[$j] = addslashes($row[$j]);
                        $row[$j] = ereg_replace("\n", "\\n", $row[$j]);
                        if (isset($row[$j])){
                            $return .= "'" . $row[$j] . "'"; 
                        }
                        else {
                            $return .= "''";
                        }
                        if ($j<($num_fields-1)){
                            $return .= ',';
                        }
                    }
                    $return .= ");\n";
                }
                $return .= "\n\n\n";
            }
            
            # save file
            $handle = fopen($filename, 'w+');
            fwrite($handle, $return);
            fclose($handle);
            return true;
        }
    }
    
    public function runSQLDump($filename=null) {
        if (!empty($filename)){
            # Temporary variable, used to store current query
            $templine = '';
            # Read in entire file
            $lines = file($filename);
            # Loop through each line
            foreach($lines as $line_num => $line){
                # Only continue if it's not a comment
                if (substr($line, 0, 2) != '--' && $line != ''){
                    # Add this line to the current segment
                    $templine .= $line;
                    # If it has a semicolon at the end, it's the 
                    # end of the query
                    if (substr(trim($line), -1, 1) == ';'){
                        $this->execute('exec', $templine);
                        # Reset temp variable to empty
                        $templine = '';
                    }
                }
            }
        }
    }
    
    public static function lastInsertId() {
        return self::$lastinsertid;
    }
    
    public function count($table, $filters=null){
        $sql = 'SELECT COUNT(*) FROM ' . $table . ' WHERE ' 
               . $this->keysToSql($filters, "AND ", ":") . '';
        
        return $this->execute('count', $sql, $filters);
    }
}

/**                                                                          *
 *                                                                           *
 * The ModulesBase class holds all the functions                             *
 * that all modules should inherit                                           *
 */
class ModulesBase
{
    protected $database;

    public function __construct() {
        global $Database;
        $this->database = $Database;
    }
}

/**                                                                          *
 *                                                                           *
 * The Modules class is meant to simplify handling of                        *
 * modules, be it importing, or running various operations.                  *
 */
class Modules extends ModulesBase
{
    public function getModules() {
        $dir = 'modules';
        $modulesfolder = scandir($dir, 0);
        $exclude = array('.', '..', '.DS_Store');
        
        foreach($modulesfolder as $module) {
            if (!in_array($module, $exclude)) {
                $modules[$module] = $module;
            }
        }
        return $modules;
    }
    
    public function installSQL() {
        # Install our main INSTALL.sql file
        $this->database->runSQLDump('INSTALL.sql');
        
        # Install INSTALL.sql files belonging to modules
        $modules = $this->getModules();
        foreach($modules as $module) {
            $filename = MODULE_ROOT . $module . DS . 'INSTALL.sql';
            $this->database->runSQLDump($filename);
        }
    }
}

/**                                                                          *
 *                                                                           *
 * The TemplateBase class holds all the functions                            *
 * that all templates should inherit                                         *
 */
class TemplateBase
{
    protected static $_settings;
    public static $theme;
    public static $admin_theme;

    public static function setSettings($setting) {
        self::$_settings = $setting;
        if (isset($setting['theme']) and $setting['theme'] != ''){
            self::$theme = $setting['theme'];
        }
        else {
            self::$theme = 'default';
        }
        if (isset($setting['admintheme']) and $setting['admintheme'] != '' and
           $setting['admintheme'] != 0){
            self::$admin_theme = $setting['admintheme'];
        }
        else {
            self::$admin_theme = 'default';
        }
    }

    public function getSettings() {
        return self::$_settings;
    }

    public function getTheme() {
        return self::$theme;
    }

    public static function getFile($file) {
        if (file_exists(TEMPLATES_ROOT . 'site' . DS . self::$theme . DS 
           . $file)){
            return TEMPLATES_ROOT . 'site' . DS . self::$theme . DS . $file;
        }
        elseif (file_exists(TEMPLATES_ROOT . 'site' . DS . 'default' . DS 
               . $file)){
            return TEMPLATES_ROOT . 'site' . DS . 'default' . DS . $file;
        }
        else {
            throw new Exception('Error loading template file (' . $file 
            . ').<br />');
        }
    }

    public static function getAdminFile($file) {
        if (file_exists(TEMPLATES_ROOT . 'admin' . DS . self::$admin_theme . DS 
           . $file)){
            return TEMPLATES_ROOT . 'admin' . DS . self::$admin_theme . DS 
                   . $file;
        }
        elseif (file_exists(TEMPLATES_ROOT . 'admin' . DS . 'default' . DS 
               . $file)){
            return TEMPLATES_ROOT . 'admin' . DS . 'default' . DS . $file;
        }
        else {
            throw new Exception('Error loading template file (' . $file 
            . ').<br />');
        }
    }
}

/**                                                                          *
 *                                                                           *
 * The Templates class is meant to simplify template                         *
 * creation, and merging.                                                    *
 */
class Template extends TemplateBase
{
    protected $file;
    protected $values = array();
    /**
     * Creates a new Template object and sets its associated file.
     *
     * @param string $file the filename of the template to load
     */
    public function __construct($file) {
        $this->file = $file;
        
        # Set some of the default values
        $this->set('URL_ROOT', URL_ROOT);
        $this->set('JS_ROOT', RESOURCES_ROOT . 'js' . DS);
        $this->set('STYLESHEET', URL_ROOT . 'interact.php?css=css');
        $this->set('IMG_ROOT', RESOURCES_ROOT . 'img' . DS);
        $this->set('UPLOAD_ROOT', RESOURCES_ROOT . 'uploads' . DS);
        $this->set('THEME_ROOT', URL_TEMPLATES_ROOT . 'site' . DS 
                    . Template::$theme . DS);
        $this->set('FAVICON', RESOURCES_ROOT . 'img' . DS . 'favicon.ico');

        $this->set('SITE_TITLE', $settings['sitetitle']);
    }
    
    /**
     * Sets a value for replacing a specific tag.
     *
     * @param string $key the name of the tag to replace
     * @param string $value the value to replace
     */
    public function set($key, $value) {
        $this->values[$key] = $value;
    }
    
    /**
     * Outputs the content of the template, replacing the keys for 
     * its respective values.
     *
     * @return string
     */
    public function output() {
        /**
         * Tries to verify if the file exists.
         * If it doesn't return with an error message.
         * Anything else loads the file contents and loops through 
         * the array replacing every key for its value.
         */
        if (!file_exists($this->file)) {
            throw new Exception('Error loading template file (' . $this->file 
            . ').<br />');
        }
        $output = file_get_contents($this->file);
        /**
        * Execute the PHP code in our template file (if there is any)
        * We do this before the key replacement, so that we can make loops containing
        * actual keys and such.
        */
        ob_start();
        eval('?>' . $output);
        $output = ob_get_contents();
        ob_end_clean();
        
        foreach ($this->values as $key => $value) {
            $tagToReplace = '{% ' . $key . ' %}';
            $output = str_replace($tagToReplace, $value, $output);
        }
        
        return $output;
    }
    
    /**
     * Merges the content from an array of templates 
     * and separates it with $separator.
     *
     * @param array $templates an array of Template objects to merge
     * @param string $separator the string that is used between each Template 
     * object
     * @return string
     */
    static public function merge($templates, $separator = "\n") {
        /**
         * Loops through the array concatenating the outputs 
         * from each template, separating with $separator.
         * If a type different from Template is found 
         * we provide an error message. 
         */
        $output = "";
        
        foreach ($templates as $template) {
            $content = (get_class($template) !== "Template")
                ? "Error, incorrect type - expected Template."
                : $template->output();
            $output .= $content . $separator;
        }
        
        return $output;
    }
}

/**                                                                          *
 *                                                                           *
 * The AdminGenerator class is meant to simplify admin                       *
 * page creation, by giving a set of widgets.                                *
 */
class AdminGenerator
{
    public $script = '';
    public $validate = array();
    
    public function validateForm(){
        $val_items = "";
        $check = array();
        foreach($this->validate as $val){
            $check[] = "$('#$val').isValid()";
            $val_items .= "$('#$val').isValid(); $('#$val').focus();";
        }
        $val = "
        if (" . implode('&&', $check) . "){
            return true;
        }
        else {
            alert('Please fill out the required fields !');" 
            . $val_items . "
            return false;
        }";
        return $val;
    }
    
    public function validateField($type, $target){
        $id = $target['id'];
        if (isset($target['error'])){
            $error = $target['error'];
        }
        else {
            $error = '';
        }
        if (isset($target['class'])){
            $class = $target['class'];
        }
        else {
            $class = '';
        }
        
        if ($type == 'email'){
            $val = "
            var $id = $('#$id');
            $id.valid8({
                regularExpressions: [
                    {expression: /^[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\."
                . "[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]"
        . "*[a-z0-9])?\.)+(aero|asia|biz|cat|com|coop|edu|gov|info|int|jobs|"
        . "mil|mobi|museum|name|net|org|pro|tel|travel.ac|ad|ae|af|ag|ai|al|"
        . "am|an|ao|aq|ar|as|at|au|aw|ax|az|ba|bb|bd|be|bf|bg|bh|bi"
        . "|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|"
        . "cl|cm|cn|co|cr|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|ee|eg|er|es|"
        . "et|eu|fi|fj|fk|fm|.fo|fr|ga|gb|gd|ge|gf|gg|gh|gi|gl|gm|gn|gp|gq"
        . "|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|.il|im|in|io|iq|ir"
        . "|is|it|je|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc"
        . "|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|me|mg|mh|mk|ml|mm|mn|mo|mp"
        . "|mq|mr|ms|mt|mu|mv|mw|mx|my|mz|na|nc|ne|nf|ng|ni|nl|no|np|nr"
        . "|nu|nz|om|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|ps|pt|pw|py|qa|re|ro|rs"
        . "|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy"
        . "|sz|tc|td|tf|tg|th|tj|tk|tl|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|us"
        . "|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)\b$/, 
                    errormessage: '$error'}
                ]
            });
            ";
        }
        else if ($type == 'required'){
            $val = "
                var $id = $('#$id');
                $id.valid8({
                    regularExpressions: [
                        {expression: /^.+$/, errormessage: '$error'}
                    ]
                });
                ";
        }
        else if ($type == 'numeric'){
            $val = "
                var $id = $('#$id');
                $id.valid8({
                    regularExpressions: [
                        {expression: /^.+$/, errormessage: '$error'}
                    ]
                });
                $id.numeric();
                ";
        }
        else if ($type == 'alphanumeric'){
            $val = "
                var $id = $('#$id');
                $id.valid8({
                    regularExpressions: [
                        {expression: /^.+$/, errormessage: '$error'}
                    ]
                });
                $id.alphanumeric();
                ";
        }
        else if ($type == 'alpha'){
            $val = "
                var $id = $('#$id');
                $id.valid8({
                    regularExpressions: [
                        {expression: /^.+$/, errormessage: '$error'}
                    ]
                });
                $id.alpha();
                ";
        }
        $this->validate[] = $id;
        $this->script .= $val;
    }
    
    public function uploader($id){
        $upload = "
        $(\"#$id\").pluploadQueue({
                // General settings
                runtimes : 'gears,html5,html4',
                url : 'index.php?action=upload_file',
                max_file_size : '1000mb',
                chunk_size : '30mb',
                unique_names : true,
                flash_swf_url : '" . RESOURCES_ROOT . 'js' 
                . DS . "plupload.swf'

                // Resize images on clientside if we can
                // resize : {width : 320, height : 240, quality : 90},

                // Specify what files to browse for
                //filters : [
                //    {title : \"Image files\", extensions : \"jpg,gif,png\"},
                //    {title : \"Zip files\", extensions : \"zip\"}
                //]
            });

            // Client side form validation
            $('form').submit(function(e) {
                var uploader = $('#$id').pluploadQueue();

                // Files in queue upload them first
                if (uploader.files.length > 0) {
                    // When all files are uploaded submit form
                    uploader.bind('StateChanged', function() {
                        if (uploader.files.length === "
                        . "(uploader.total.uploaded + uploader.total.failed)){
                            $('form')[0].submit();
                        }
                    });

                    uploader.start();
                } else {
                    alert('You must queue at least one file.');
                }

                return false;
            });
        ";
        $this->script .= $upload;
    }
    
    public function textarea($widget){
        $textarea = 
        '<div class="w">
        <textarea 
        id="' . $widget['id'] . '" 
        class="' . $widget['class'] . '" 
        style="' . $widget['style'] . '" 
        name="' . $widget['name'] . '" 
        rows="' . $widget['rows'] . '" 
        cols="' . $widget['cols'] . '" ' 
        . $widget['state'] . '>' 
        . $widget['value'] . '</textarea>
        </div>';
        return $textarea;
    }
    
    public function input($widget){
        if (isset($widget['defaultvalue'])){
            $value = $widget['defaultvalue'];
            $jssnippet = $this->defaultText();
            $style = 'color:#808080;' . $widget['style'];
            
        }
        else {
            $value = $widget['value'];
            $style = $widget['style'];
        }
        if (isset($widget['placerholder'])){
            $placeholder = 
            "$('#" .  $widget['id'] . " [placeholder] ').defaultValue();";
            $this->script .= $placeholder;
        }
        $input = 
        '<div class="w">
        <input 
        id="' . $widget['id'] . '" 
        class="' . $widget['class'] . '" 
        style="' . $style . '" 
        name="' . $widget['name'] . '" 
        type="' . $widget['type'] . '" 
        value="' . $value . '" 
        placeholder="' . $widget['placeholder'] . '" 
        maxlength="' . $widget['maxlength'] . '" 
        size="' . $widget['size'] . '" ' 
        . $widget['state'] . ' ' 
        . $widget['checked'] 
        . $jssnippet . ' />
        </div>';
        return $input;
    }
    
    public function select($widget, $options = null){
        if (isset($options)){
            $opts = '';
            foreach($options as $key=>$value){
                if ($widget['selected'] == $key){
                    $opts .= '<option value="' . $key . '" SELECTED>' 
                             . $value . '</option>';
                }
                else {
                    $opts .= '<option value="' . $key . '">' 
                             . $value . '</option>';
                }
            }
        }
        $input = 
        '<div class="w">
        <select 
        id="' . $widget['id'] . '" 
        class="' . $widget['class'] . '" 
        style="' . $widget['style'] . '" 
        name="' . $widget['name'] . '" 
        size="' . $widget['size'] . '" 
        ' . $widget['multiple'] . '>
        ' . $opts . '
        </select>
        </div>';
        return $input;
    }
    
    public function button($widget){
        $button = 
        '<button type="button" 
        id="' . $widget['id'] . '" 
        class="' . $widget['class'] . '" 
        tabindex="' . $widget['tabindex'] . '">
        ' . $widget['value'] . '
        </button>';
        return $button;
    }
    
    public function form($widget){
        if (isset($widget['validate'])){
            $val = $this->validateForm($widget['validate']);
            $validateForm = 'onsubmit="' . $val . '"';
        }
        else {
            $validateForm = '';
        }
        $action = $this->input(array('name'=>'action',
                                     'type'=>'hidden',
                                     'value'=>$widget['action']));
        $ref = URL_ROOT . $widget['referer'];
        $referer = $this->input(array('name'=>'referer',
                                      'type'=>'hidden',
                                      'value'=>$ref)
                                      );
        $form = '<form action="' . URL_ROOT . 'index.php" method="POST" ' 
                . $validateForm . '>' 
                . $action . $referer;
        return $form;
    }
    
    public function multi_form($widget) {
        $action = $this->input(array('name'=>'action',
                                     'type'=>'hidden',
                                     'value'=>'multipleSelect'));
        $ref = URL_ROOT . $widget['referer'];
        $referer = $this->input(array('name'=>'referer',
                                      'type'=>'hidden',
                                      'value'=>$ref)
                                      );
        $targetModule = $widget['module'];
        $form = '<form action="' . URL_ROOT . 'index.php" method="POST">' 
                . $action . $referer;
        $targetButtons = "
        $('#multiDeleteButton').on('click', function() {
            var targetModule = '" . $targetModule . "';
            var allData = new Array();
            $('input:checked').each(function(i) {
                    allData.push($(this).val());
            });
            allData = allData.join(',');
            
            $.ajax({
                url: '" . URL_ROOT . "interact.php',
                type: 'POST',
                data: {action: targetModule, multiAction: 'delete', data: allData},
                success: function(response) {
                    location.href = location.href;
                }
            }); 
        });
        ";
        $this->script .= $targetButtons;
        return $form;
    }
}

/**                                                                          *
 *                                                                           *
 * The Paginator class is meant to simplify pagination                       *
 * effectively by using a DB limit instead of hiding content !               *
 */
class Paginator
{
    var $db;
    var $url_prefix;
    var $items_per_page;
    var $items_total;
    var $current_page;
    var $num_pages;
    var $mid_range;
    var $low;
    var $high;
    var $limit;
    var $return;
    var $default_ipp;
    var $querystring;

    function __construct($url_query,
                         $pagination_page = null,
                         $pagination_ipp = null){
        global $Database;
        $this->db = $Database;
        $this->current_page = 1;
        $this->mid_range = 7;
        $this->items_per_page = (!empty($pagination_page)) 
                                ? $pagination_ipp:$this->default_ipp;
        if ($url_query[0] == 'admin'){
            if (isset($url_query[0])){
                $this->url_prefix = '' . URL_ROOT . $url_query[0] 
                                    . '/' . $url_query[1] . '/';
            }
            else {
                $this->url_prefix = '' . URL_ROOT 
                                    . $url_query[0] . '/';
            }
        }
        else {
            $this->url_prefix = '' . URL_ROOT . $url_query[0] . '/';
        }
    }

    function paginate($count_tbl, 
                      $pagination_page = null, 
                      $pagination_ipp = null){
        $this->items_total = $this->db->count($count_tbl);
        
        if ($pagination_ipp == 'All'){
            $this->num_pages = ceil($this->items_total/$this->default_ipp);
            $this->items_per_page = $this->default_ipp;
        }
        else {
            if (!is_numeric($this->items_per_page) 
               or $this->items_per_page <= 0){
                $this->items_per_page = $this->default_ipp;
            }
            $this->num_pages = ceil($this->items_total/$this->items_per_page);
        }
        $this->current_page = (int) $pagination_page; # must be numeric > 0
        if ($this->current_page < 1 
           or !is_numeric($this->current_page)){
            $this->current_page = 1;
        }
        if ($this->current_page > $this->num_pages){
            $this->current_page = $this->num_pages;
        }
        $prev_page = $this->current_page-1;
        $next_page = $this->current_page+1;

        if ($_GET){
            $args = explode("&", $_SERVER['QUERY_STRING']);
            foreach($args as $arg){
                $keyval = explode("=", $arg);
                if ($keyval[0] != "page" And $keyval[0] != "ipp"){
                    $this->querystring .= "&" . $arg;
                }
            }
        }

        if ($_POST){
            foreach($_POST as $key=>$val){
                if ($key != "page" And $key != "ipp"){
                    $this->querystring .= "&" . $key . "=" . $val;
                }
            }
        }

        if ($this->num_pages > 10){
            $this->return = ($this->current_page != 1 
                             and $this->items_total >= 10) 
                            ? "<a class=\"paginate\"  " 
                              . "href=\"" . $this->url_prefix 
                              . "page/" . $prev_page . "/ipp/" 
                              . $this->items_per_page 
                              . "\">&laquo; Previous</a> " 
                            : "<span class=\"inactive\" "
                              . "href=\"#\">&laquo; Previous</span> ";

            $this->start_range = $this->current_page 
                                 - floor($this->mid_range / 2);
            $this->end_range = $this->current_page 
                               + floor($this->mid_range / 2);

            if ($this->start_range <= 0){
                $this->end_range += abs($this->start_range) + 1;
                $this->start_range = 1;
            }
            if ($this->end_range > $this->num_pages){
                $this->start_range -= $this->end_range-$this->num_pages;
                $this->end_range = $this->num_pages;
            }
            $this->range = range($this->start_range,$this->end_range);

            for ($i=1;$i<=$this->num_pages;$i++){
                if ($this->range[0] > 2 and $i == $this->range[0]){
                    $this->return .= " ... ";
                }
                # loop through all pages. if first, last, or in range, display
                if ($i==1 Or $i==$this->num_pages Or in_array($i,$this->range))
                {
                    $this->return .= ($i == $this->current_page 
                                     and $pagination_page != 'All') 
                                     ? "<a title=\"Go to page " 
                                       . $i ." of " 
                                       . $this->num_pages 
                                       . "\" class=\"current\" href=\"#\">"
                                       . $i . "</a> "
                                     : "<a class=\"paginate\" " 
                                       . "title=\"Go to page "
                                       . $i . " of " 
                                       . $this->num_pages . "\"" 
                                       . " href=\"" . $this->url_prefix 
                                       . "/page/" . $i 
                                       . "/ipp/" . $this->items_per_page 
                                       . "\">" . $i 
                                       . "</a> ";
                }
                if ($this->range[$this->mid_range-1] < $this->num_pages-1 
                   and $i == $this->range[$this->mid_range-1]){
                    $this->return .= " ... ";
                }
            }
            $this->return .= (($this->current_page != $this->num_pages 
                              and $this->items_total >= 10) 
                              and ($pagination_page != 'All')) 
                             ? "<a class=\"paginate\"" 
                               . " href=\"" . $this->url_prefix 
                               . "page/" . $next_page 
                               . "/ipp/" . $this->items_per_page 
                               . "\">Next &raquo;</a>\n"
                             : "<span class=\"inactive\" "
                               . "href=\"#\">&raquo; Next</span>\n";
            $this->return .= ($pagination_page == 'All') 
                             ? "<a class=\"current\" "
                               . "style=\"margin-left:10px\" "
                               . "href=\"#\">All</a> \n"
                             : "<a class=\"paginate\" "
                               . "style=\"margin-left:10px\" href=\""
                               . $this->url_prefix 
                               . "page/All/ipp/All\">All</a> \n";
        }
        else {
            for ($i=1;$i<=$this->num_pages;$i++){
                $this->return .= ($i == $this->current_page 
                                  and $pagination_page != 'All') 
                                 ? "<a class=\"current\" href=\"#\">"
                                   . $i . "</a> ":"<a class=\"paginate\""
                                   . " href=\"" . $this->url_prefix . "page/"
                                   . $i . "/ipp/" . $this->items_per_page 
                                   . "\">" . $i . "</a> ";
            }
            $this->return .= ($pagination_page == 'All') 
                             ? "<a class=\"current\" href=\"#\">All</a> "
                             : "<a class=\"paginate\" href=\""
                               . $this->url_prefix 
                               . "page/All/ipp/All\">All</a>  ";
        }
        $this->low = ($this->current_page-1) * $this->items_per_page;
        $this->high = ($pagination_ipp == 'All') 
                      ? $this->items_total
                      : ($this->current_page * $this->items_per_page) - 1;
        $this->limit = ($pagination_ipp == 'All') 
                       ? ""
                       : " LIMIT " . $this->low . "," . $this->items_per_page;
    }

    function display_items_per_page(){
        $items = '';
        $ipp_array = array(4, 10, 25, 50, 100, 'All');
        foreach($ipp_array as $ipp_opt){
            $items .= ($ipp_opt == $this->items_per_page) 
                      ? "<option selected value=\"" 
                        . $ipp_opt . "\">" . $ipp_opt 
                        . "</option>\n"
                      : "<option value=\"" . $ipp_opt . "\">" . $ipp_opt 
                        . "</option>\n";
        }
        return "<span class=\"paginate\">Items per page:</span>"
               . "<select class=\"paginate\" onchange=\"window.location='" 
               . $this->url_prefix . "page/1/ipp/"
               . "'+this[this.selectedIndex].value+'';return false\">"
               . $items . "</select>\n";
    }

    function display_jump_menu(){
        for ($i = 1; $i <= $this->num_pages; $i++){
            $option .= ($i == $this->current_page) 
                       ? "<option value=\"" . $i . "\" selected>" 
                         . $i . "</option>\n"
                       : "<option value=\"" . $i . "\">" 
                         . $i . "</option>\n";
        }
        return "<span class=\"paginate\">Page:</span>"
               . "<select class=\"paginate\" onchange=\"window.location='" 
               . $this->url_prefix 
               . "/page/'+this[this.selectedIndex].value+'/ipp/"
               . $this->items_per_page . "';return false\">"
               . $option . "</select>\n";
    }

    function display_pages(){
        return $this->return;
    }
}

/**                                                                          *
 *                                                                           *
 * The UsersHandler class is meant to simplify the task                      *
 * of keeping track of, registering, deleting, banning                       *
 * and other actions revolving users.                                        *
 */
class UsersHandler
{
    var $connection; //The MySQL database connection
    var $num_active_users; //Number of active users viewing site
    var $num_active_guests; //Number of active guests viewing site
    var $num_members; //Number of signed-up users
    /* Note: call getNumMembers() to access $num_members! */

    /* Class constructor */
    function __construct(){
        /* Make connection to database */
        $this->connection = mysql_connect(DB_SERVER, DB_USER, DB_PASS) 
                          or die(mysql_error());
        mysql_select_db(DB_NAME, $this->connection) or die(mysql_error());
        
        /**
         * Only query database to find out number of members
         * when getNumMembers() is called for the first time,
         * until then, default value set.
         */
        $this->num_members = -1;
        
        if (TRACK_VISITORS){
            /* Calculate number of users at site */
            $this->calcNumActiveUsers();
        
            /* Calculate number of guests at site */
            $this->calcNumActiveGuests();
        }
    }

    /**
     * confirmUserPass - Checks whether or not the given
     * username is in the database, if so it checks if the
     * given password is the same password in the database
     * for that user. If the user doesn't exist or if the
     * passwords don't match up, it returns an error code
     * (1 or 2). On success it returns 0.
     */
    function confirmUserPass($username, $password){
        /* Add slashes if necessary (for query) */
        if (!get_magic_quotes_gpc()) {
            $username = addslashes($username);
        }

        /* Verify that user is in database */
        $q = "SELECT password FROM " . TBL_USERS 
             . " WHERE username = '$username'";
        $result = mysql_query($q, $this->connection);
        if (!$result || (mysql_numrows($result) < 1)){
            return 1; //Indicates username failure
        }

        /* Retrieve password from result, strip slashes */
        $dbarray = mysql_fetch_array($result);
        $dbarray['password'] = stripslashes($dbarray['password']);
        $password = stripslashes($password);

        /* Validate that password is correct */
        if ($password == $dbarray['password']){
            return 0; //Success! Username and password confirmed
        }
        else {
            return 2; //Indicates password failure
        }
    }
    
    /**
     * confirmUserID - Checks whether or not the given
     * username is in the database, if so it checks if the
     * given userid is the same userid in the database
     * for that user. If the user doesn't exist or if the
     * userids don't match up, it returns an error code
     * (1 or 2). On success it returns 0.
     */
    function confirmUserID($username, $userid){
        /* Add slashes if necessary (for query) */
        if (!get_magic_quotes_gpc()) {
            $username = addslashes($username);
        }

        /* Verify that user is in database */
        $q = "SELECT userid FROM " . TBL_USERS 
             . " WHERE username = '$username'";
        $result = mysql_query($q, $this->connection);
        if (!$result || (mysql_numrows($result) < 1)){
            return 1; //Indicates username failure
        }

        /* Retrieve userid from result, strip slashes */
        $dbarray = mysql_fetch_array($result);
        $dbarray['userid'] = stripslashes($dbarray['userid']);
        $userid = stripslashes($userid);

        /* Validate that userid is correct */
        if ($userid == $dbarray['userid']){
            return 0; //Success! Username and userid confirmed
        }
        else {
            return 2; //Indicates userid invalid
        }
    }
    
    /**
     * usernameTaken - Returns true if the username has
     * been taken by another user, false otherwise.
     */
    function usernameTaken($username){
        if (!get_magic_quotes_gpc()){
            $username = addslashes($username);
        }
        $q = "SELECT username FROM " . TBL_USERS 
            . " WHERE username = '$username'";
        $result = mysql_query($q, $this->connection);
        return (mysql_numrows($result) > 0);
    }
    
    /**
     * usernameBanned - Returns true if the username has
     * been banned by the administrator.
     */
    function usernameBanned($username){
        if (!get_magic_quotes_gpc()){
            $username = addslashes($username);
        }
        $q = "SELECT username FROM " . TBL_BANNED_USERS 
            . " WHERE username = '$username'";
        $result = mysql_query($q, $this->connection);
        return (mysql_numrows($result) > 0);
    }
    
    /**
     * addNewUser - Inserts the given (username, password, email)
     * info into the database. Appropriate user level is set.
     * Returns true on success, false otherwise.
     */
    function addNewUser($username, $password, $first_name,
                        $last_name, $email){
        $time = time();
        /* If admin sign up, give admin user level */
        if (strcasecmp($username, ADMIN_NAME) == 0){
            $ulevel = ADMIN_LEVEL;
        }else {
            $ulevel = USER_LEVEL;
        }
        $q = "INSERT INTO " . TBL_USERS 
            . " VALUES ('', '$username', '$password', '$first_name', "
            . "'$last_name', '0', $ulevel, '$email', '0', $time)";
        return mysql_query($q, $this->connection);
    }

    /**
     * createNewUser - Inserts the given (username, password, email)
     * info into the database. Appropriate user level is set.
     * Returns true on success, false otherwise.
     */
    function createNewUser($cusername, $cpassword, $cfirst_name, 
                          $clast_name, $cemail, $cregkey){
        $time = time();
        /* If admin sign up, give admin user level */
        if (strcasecmp($username, ADMIN_NAME) == 0){
            $ulevel = ADMIN_LEVEL;
        }else {
            $ulevel = USER_LEVEL;
        }
        $q = "UPDATE " . TBL_USERS 
            . " SET username='$cusername', password='$cpassword', "
            . "first_name='$cfirst_name', last_name='$clast_name', "
            . "userlevel='$ulevel', userid='0', regkey='0', timestamp=$time, "
            . "email='$cemail' "
            . "WHERE regkey='$cregkey' AND regkey!='0' AND regkey!=''";
        return mysql_query($q, $this->connection);
    }
 
    /**
     * updateUserField - Updates a field, specified by the field
     * parameter, in the user's row of the database.
     */
    function updateUserField($username, $field, $value){
        $q = "UPDATE " . TBL_USERS . " SET " . $field 
            . " = '$value' WHERE username = '$username'";
        return mysql_query($q, $this->connection);
    }
    
    /**
     * getUserInfo - Returns the result array from a mysql
     * query asking for all information stored regarding
     * the given username. If query fails, NULL is returned.
     */
    function getUserInfo($username){
        $q = "SELECT * FROM " . TBL_USERS . " WHERE username = '$username'";
        $result = mysql_query($q, $this->connection);
        /* Error occurred, return given name by default */
        if (!$result || (mysql_numrows($result) < 1)){
            return NULL;
        }
        /* Return result array */
        $dbarray = mysql_fetch_array($result);
        return $dbarray;
    }
    
    /**
     * getNumMembers - Returns the number of signed-up users
     * of the website, banned members not included. The first
     * time the function is called on page load, the database
     * is queried, on subsequent calls, the stored result
     * is returned. This is to improve efficiency, effectively
     * not querying the database when no call is made.
     */
    function getNumMembers(){
        if ($this->num_members < 0){
            $q = "SELECT * FROM ".TBL_USERS;
            $result = mysql_query($q, $this->connection);
            $this->num_members = mysql_numrows($result);
        }
        return $this->num_members;
    }
    
    /**
     * calcNumActiveUsers - Finds out how many active users
     * are viewing site and sets class variable accordingly.
     */
    function calcNumActiveUsers(){
        /* Calculate number of users at site */
        $q = "SELECT * FROM ".TBL_ACTIVE_USERS;
        $result = mysql_query($q, $this->connection);
        $this->num_active_users = mysql_numrows($result);
    }
    
    /**
     * calcNumActiveGuests - Finds out how many active guests
     * are viewing site and sets class variable accordingly.
     */
    function calcNumActiveGuests(){
        /* Calculate number of guests at site */
        $q = "SELECT * FROM ".TBL_ACTIVE_GUESTS;
        $result = mysql_query($q, $this->connection);
        $this->num_active_guests = mysql_numrows($result);
    }
    
    /**
     * addActiveUser - Updates username's last active timestamp
     * in the database, and also adds him to the table of
     * active users, or updates timestamp if already there.
     */
    function addActiveUser($username, $time){
        $q = "UPDATE " . TBL_USERS 
            . " SET timestamp = '$time' WHERE username = '$username'";
        mysql_query($q, $this->connection);
        
        if (!TRACK_VISITORS) return;
        $q = "REPLACE INTO " . TBL_ACTIVE_USERS 
            . " VALUES ('$username', '$time')";
        mysql_query($q, $this->connection);
        $this->calcNumActiveUsers();
    }
    
    /* addActiveGuest - Adds guest to active guests table */
    function addActiveGuest($ip, $time){
        if (!TRACK_VISITORS) return;
        $q = "REPLACE INTO " . TBL_ACTIVE_GUESTS . " VALUES ('$ip', '$time')";
        mysql_query($q, $this->connection);
        $this->calcNumActiveGuests();
    }
    
    /* These functions are self explanatory, no need for comments */
    
    /* removeActiveUser */
    function removeActiveUser($username){
        if (!TRACK_VISITORS) return;
        $q = "DELETE FROM " . TBL_ACTIVE_USERS 
            . " WHERE username = '$username'";
        mysql_query($q, $this->connection);
        $this->calcNumActiveUsers();
    }
    
    /* removeActiveGuest */
    function removeActiveGuest($ip){
        if (!TRACK_VISITORS) return;
        $q = "DELETE FROM " . TBL_ACTIVE_GUESTS 
            . " WHERE ip = '$ip'";
        mysql_query($q, $this->connection);
        $this->calcNumActiveGuests();
    }
    
    /* removeInactiveUsers */
    function removeInactiveUsers(){
        if (!TRACK_VISITORS) return;
        $timeout = time()-USER_TIMEOUT*60;
        $q = "DELETE FROM " . TBL_ACTIVE_USERS 
             . " WHERE timestamp < $timeout";
        mysql_query($q, $this->connection);
        $this->calcNumActiveUsers();
    }

    /* removeInactiveGuests */
    function removeInactiveGuests(){
        if (!TRACK_VISITORS) return;
        $timeout = time()-GUEST_TIMEOUT*60;
        $q = "DELETE FROM " . TBL_ACTIVE_GUESTS 
             . " WHERE timestamp < $timeout";
        mysql_query($q, $this->connection);
        $this->calcNumActiveGuests();
    }
    
    /**
     * query - Performs the given query on the database and
     * returns the result, which may be false, true or a
     * resource identifier.
     */
    function query($query){
        return mysql_query($query, $this->connection);
    }
}

/**                                                                          *
 *                                                                           *
 * The Mailer class is meant to simplify the task of sending                 *
 * emails to users. Note: this email system will not work                    *
 * if your server is not setup to send mail.                                 *
 */
class Mailer
{
    /**
     * sendWelcome - Sends a welcome message to the newly
     * registered user, also supplying the username and
     * password.
     */
    function sendWelcome($user, $email, $pass){
        $from = "From: " . EMAIL_FROM_NAME . " <" . EMAIL_FROM_ADDR . ">";
        $subject = "Welcome!";
        $body = new Template(TEMPLATES_ROOT . 'email' . DS 
                             . 'sendWelcome.tpl');
        $body->set('USERNAME', $user);
        $body->set('PASSWORD', $pass);
        $body->set('SIGNATURE', EMAIL_SIGNATURE);
        $body = $body->output();

        return mail($email,$subject,$body,$from);
    }
    
    /**
     * sendNewPass - Sends the newly generated password
     * to the user's email address that was specified at
     * sign-up.
     */
    function sendNewPass($user, $email, $pass){
        $from = "From: " . EMAIL_FROM_NAME . " <" . EMAIL_FROM_ADDR . ">";
        $subject = "Your new password";
        $body = new Template(TEMPLATES_ROOT . 'email' . DS 
                             . 'sendNewPass.tpl');
        $body->set('USERNAME', $user);
        $body->set('PASSWORD', $pass);
        $body->set('SIGNATURE', EMAIL_SIGNATURE);
        $body = $body->output();
                 
        return mail($email,$subject,$body,$from);
    }
}


class Form
{
    var $values = array();  //Holds submitted form field values
    var $errors = array();  //Holds submitted form error messages
    var $num_errors;    //The number of errors in submitted form

    /* Class constructor */
    function __construct(){
        /**
         * Get form value and error arrays, used when there
         * is an error with a user-submitted form.
         */
        if (isset($_SESSION['value_array']) 
           && isset($_SESSION['error_array'])){
            $this->values = $_SESSION['value_array'];
            $this->errors = $_SESSION['error_array'];
            $this->num_errors = count($this->errors);

            unset($_SESSION['value_array']);
            unset($_SESSION['error_array']);
        }
        else {
            $this->num_errors = 0;
        }
    }

    /**
     * setValue - Records the value typed into the given
     * form field by the user.
     */
    function setValue($field, $value){
        $this->values[$field] = $value;
    }

    /**
     * setError - Records new form error given the form
     * field name and the error message attached to it.
     */
    function setError($field, $errmsg){
        $this->errors[$field] = $errmsg;
        $this->num_errors = count($this->errors);
    }

    /**
     * value - Returns the value attached to the given
     * field, if none exists, the empty string is returned.
     */
    function value($field){
        if (array_key_exists($field,$this->values)){
            return htmlspecialchars(stripslashes($this->values[$field]));
        }else {
            return "";
        }
    }

    /**
     * error - Returns the error message attached to the
     * given field, if none exists, the empty string is returned.
     */
    function error($field){
        if (array_key_exists($field,$this->errors)){
            return "<font size=\"2\" color=\"#ff0000\">" 
                   . $this->errors[$field] . "</font>";
        }else {
            return "";
        }
    }

    /* getErrorArray - Returns the array of error messages */
    function getErrorArray(){
        return $this->errors;
    }
}

/**                                                                          *
 *                                                                           *
 * The Session class is meant to simplify the task of keeping                *
 * track of logged in users and also guests.                                 *
 */
class Session
{
    var $username;      //Username given on sign-up
    var $userid;         //Random value generated on current login
    var $userlevel;     //The level to which the user pertains
    var $first_name;     //The users firstname
    var $last_name;     //The users last name
    var $id;         //The users id
    var $profilepic;    //The users profile picture
    var $time;            //Time user was last active (page loaded)
    var $logged_in;     //True if user is logged in, false otherwise
    var $userinfo = array();  //The array holding all user info
    var $url;             //The page url current being viewed
    var $referrer;      //Last recorded site page viewed
    /**
     * Note: referrer should really only be considered the actual
     * page referrer in process.php, any other time it may be
     * inaccurate.
     */

    /* Class constructor */
    function __construct(){
        $this->time = time();
        $this->startSession();
    }

    /**
     * startSession - Performs all the actions necessary to 
     * initialize this session object. Tries to determine if the
     * the user has logged in already, and sets the variables 
     * accordingly. Also takes advantage of this page load to
     * update the active visitors tables.
     */
    function startSession(){
        global $UsersHandler;  //The database connection
        session_start();    //Tell PHP to start the session

        /* Determine if user is logged in */
        $this->logged_in = $this->checkLogin();

        /**
         * Set guest value to users not logged in, and update
         * active guests table accordingly.
         */
        if (!$this->logged_in){
            $this->username = $_SESSION['username'] = GUEST_NAME;
            $this->userlevel = GUEST_LEVEL;
            $UsersHandler->addActiveGuest($_SERVER['REMOTE_ADDR'],
                                        $this->time);
        }
        /* Update users last active timestamp */
        else {
            $UsersHandler->addActiveUser($this->username, $this->time);
        }
        
        /* Remove inactive visitors from database */
        $UsersHandler->removeInactiveUsers();
        $UsersHandler->removeInactiveGuests();
        
        /* Set referrer page */
        if (isset($_SESSION['url'])){
            $this->referrer = $_SESSION['url'];
        }else {
            $this->referrer = "/";
        }

        /* Set current url */
        $this->url = $_SESSION['url'] = $_SERVER['PHP_SELF'];
    }

    /**
     * checkLogin - Checks if the user has already previously
     * logged in, and a session with the user has already been
     * established. Also checks to see if user has been remembered.
     * If so, the database is queried to make sure of the user's 
     * authenticity. Returns true if the user has logged in.
     */
    function checkLogin(){
        global $UsersHandler;  //The database connection
        /* Check if user has been remembered */
        if (isset($_COOKIE['cookname']) && isset($_COOKIE['cookid'])){
            $this->username = $_SESSION['username'] = $_COOKIE['cookname'];
            $this->userid    = $_SESSION['userid']    = $_COOKIE['cookid'];
        }

        /* Username and userid have been set and not guest */
        if (isset($_SESSION['username']) && isset($_SESSION['userid']) &&
            $_SESSION['username'] != GUEST_NAME){
            /* Confirm that username and userid are valid */
            if ($UsersHandler->confirmUserID($_SESSION['username'], 
            $_SESSION['userid']) != 0){
                /* Variables are incorrect, user not logged in */
                unset($_SESSION['username']);
                unset($_SESSION['userid']);
                return false;
        }

        /* User is logged in, set class variables */
        $this->userinfo  = $UsersHandler->getUserInfo($_SESSION['username']);
        $this->username  = $this->userinfo['username'];
        $this->first_name = $this->userinfo['first_name'];
        $this->last_name = $this->userinfo['last_name'];
        $this->id = $this->userinfo['id'];
        $this->profilepic = $this->userinfo['profilepic'];
        $this->userid     = $this->userinfo['userid'];
        $this->userlevel = $this->userinfo['userlevel'];
        return true;
        }
        /* User not logged in */
        else {
            return false;
        }
    }

    /**
     * login - The user has submitted his username and password
     * through the login form, this function checks the authenticity
     * of that information in the database and creates the session.
     * Effectively logging in the user if all goes well.
     */
    function login($subuser, $subpass, $subremember){
        global $UsersHandler, $Form;  //The database and form object

        /* Username error checking */
        $field = "user";  //Use field name for username
        if (!$subuser || strlen($subuser = trim($subuser)) == 0){
            $Form->setError($field, "* Username not entered");
        }
        else {
            /* Check if username is not alphanumeric */
            if (!eregi("^([0-9a-z])*$", $subuser)){
                $Form->setError($field, "* Username not alphanumeric");
            }
        }

        /* Password error checking */
        $field = "pass";  //Use field name for password
        if (!$subpass){
            $Form->setError($field, "* Password not entered");
        }
        
        /* Return if form errors exist */
        if ($Form->num_errors > 0){
            return false;
        }

        /* Checks that username is in database and password is correct */
        $subuser = stripslashes($subuser);
        $result = $UsersHandler->confirmUserPass($subuser, md5($subpass));

        /* Check error codes */
        if ($result == 1){
            $field = "user";
            $Form->setError($field, "* Username not found");
        }
        else if ($result == 2){
            $field = "pass";
            $Form->setError($field, "* Invalid password");
        }
        
        /* Return if form errors exist */
        if ($Form->num_errors > 0){
            return false;
        }

        /* Username and password correct, register session variables */
        $this->userinfo = $UsersHandler->getUserInfo($subuser);
        $this->username = $_SESSION['username'] = $this->userinfo['username'];
        $this->userid = $_SESSION['userid'] = $this->generateRandID();
        $this->userlevel = $this->userinfo['userlevel'];
        $this->first_name = $_SESSION['first_name'] 
                          = $this->userinfo['first_name'];
        $this->last_name = $_SESSION['last_name'] 
                         = $this->userinfo['last_name'];
        $this->id = $_SESSION['id'] = $this->userinfo['id'];
        $this->profilepic = $_SESSION['profilepic'] 
                          = $this->userinfo['profilepic'];
        
        /* Insert userid into database and update active users table */
        $UsersHandler->updateUserField($this->username,
                                       "userid",
                                       $this->userid);
        $UsersHandler->addActiveUser($this->username, $this->time);
        $UsersHandler->removeActiveGuest($_SERVER['REMOTE_ADDR']);

        /**
         * This is the cool part: the user has requested that we remember that
         * he's logged in, so we set two cookies. One to hold his username,
         * and one to hold his random value userid. It expires by the time
         * specified in constants.php. Now, next time he comes to our site, 
         * we will log him in automatically, but only if he didn't log out 
         * before he left.
         */
        if ($subremember){
            setcookie("cookname", 
                    $this->username,
                    time() + COOKIE_EXPIRE, 
                    COOKIE_PATH);
            setcookie("cookid",
                    $this->userid,
                    time() + COOKIE_EXPIRE,
                    COOKIE_PATH);
        }

        /* Login completed successfully */
        return true;
    }

    /**
     * logout - Gets called when the user wants to be logged out of the
     * website. It deletes any cookies that were stored on the users
     * computer as a result of him wanting to be remembered, and also
     * unsets session variables and demotes his user level to guest.
     */
    function logout(){
        global $UsersHandler;  //The database connection
        /**
         * Delete cookies - the time must be in the past,
         * so just negate what you added when creating the
         * cookie.
         */
        if (isset($_COOKIE['cookname']) && isset($_COOKIE['cookid'])){
            setcookie("cookname", "", time()-COOKIE_EXPIRE, COOKIE_PATH);
            setcookie("cookid",    "", time()-COOKIE_EXPIRE, COOKIE_PATH);
        }

        /* Unset PHP session variables */
        unset($_SESSION['username']);
        unset($_SESSION['userid']);

        /* Reflect fact that user has logged out */
        $this->logged_in = false;
        
        /**
         * Remove from active users table and add to
         * active guests tables.
         */
        $UsersHandler->removeActiveUser($this->username);
        $UsersHandler->addActiveGuest($_SERVER['REMOTE_ADDR'], $this->time);
        
        /* Set user level to guest */
        $this->username  = GUEST_NAME;
        $this->userlevel = GUEST_LEVEL;
    }

    /**
     * register - Gets called when the user has just submitted the
     * registration form. Determines if there were any errors with
     * the entry fields, if so, it records the errors and returns
     * 1. If no errors were found, it registers the new user and
     * returns 0. Returns 2 if registration failed.
     */
    function register($subuser, $subpass, $subemail, 
                     $subfirst_name, $sublast_name){
        # The database, form and mailer objects
        global $UsersHandler, $Form, $Mailer;
        
        /* Username error checking */
        $field = "user";  //Use field name for username
        if (!$subuser || strlen($subuser = trim($subuser)) == 0){
            $Form->setError($field, "* Username not entered");
        }
        else {
            /* Spruce up username, check length */
            $subuser = stripslashes($subuser);
            if (strlen($subuser) < 3){
                $Form->setError($field, "* Username below 3 characters");
            }
            else if (strlen($subuser) > 30){
                $Form->setError($field, "* Username above 30 characters");
            }
            /* Check if username is not alphanumeric */
            else if (!eregi("^([0-9a-z])+$", $subuser)){
                $Form->setError($field, "* Username not alphanumeric");
            }
            /* Check if username is reserved */
            else if (strcasecmp($subuser, GUEST_NAME) == 0){
                $Form->setError($field, "* Username reserved word");
            }
            /* Check if username is already in use */
            else if ($UsersHandler->usernameTaken($subuser)){
                $Form->setError($field, "* Username already in use");
            }
            /* Check if username is banned */
            else if ($UsersHandler->usernameBanned($subuser)){
                $Form->setError($field, "* Username banned");
            }
        }

        /* Password error checking */
        $field = "pass";  # Use field name for password
        if (!$subpass){
            $Form->setError($field, "* Password not entered");
        }
        else {
            /* Spruce up password and check length*/
            $subpass = stripslashes($subpass);
            if (strlen($subpass) < 4){
                $Form->setError($field, "* Password too short");
            }
            /* Check if password is not alphanumeric */
            else if (!eregi("^([0-9a-z])+$", ($subpass = trim($subpass)))){
                $Form->setError($field, "* Password not alphanumeric");
            }
            /**
             * Note: I trimmed the password only after I checked the length
             * because if you fill the password field up with spaces
             * it looks like a lot more characters than 4, so it looks
             * kind of stupid to report "password too short".
             */
        }
        
    /* first name error checking */
        $field = "first_name";  //Use field name for first name
        if (!$subfirst_name){
            $Form->setError($field, "* First name not entered");
        }
    /* last name error checking */
        $field = "last_name";  //Use field name for last name
        if (!$sublast_name){
            $Form->setError($field, "* Last name not entered");
        }
    


        /* Email error checking */
        $field = "email";  //Use field name for email
        if (!$subemail || strlen($subemail = trim($subemail)) == 0){
            $Form->setError($field, "* Email not entered");
        }
        else {
            /* Check if valid email address */
            $regex = "^[_+a-z0-9-]+(\.[_+a-z0-9-]+)*"
                      ."@[a-z0-9-]+(\.[a-z0-9-]{1,})*"
                      ."\.([a-z]{2,}){1}$";
            if (!eregi($regex,$subemail)){
                $Form->setError($field, "* Email invalid");
            }
            $subemail = stripslashes($subemail);
        }

        /* Errors exist, have user correct them */
        if ($Form->num_errors > 0){
            return 1;  //Errors with form
        }
        /* No errors, add the new account to the */
        else {
            if ($UsersHandler->addNewUser($subuser,
                                      md5($subpass),
                                      $subfirst_name,
                                      $sublast_name,
                                      $subemail)){
                if (EMAIL_WELCOME){
                    $Mailer->sendWelcome($subuser,
                                    $subemail,
                                    $subpass,
                                    $subfirst_name,
                                    $sublast_name);
                }
                return 0;  //New user added succesfully
            }else {
                return 2;  //Registration attempt failed
            }
        }
    }
    /**
     * create - Gets called when the user has just submitted the
     * creation form. Determines if there were any errors with
     * the entry fields, if so, it records the errors and returns
     * 1. If no errors were found, it registers the new user and
     * returns 0. Returns 2 if registration failed.
     */
    function create($subcuser, $subcpass, $subcfirst_name, 
                    $subclast_name, $subcemail, $subcregkey){
        global $UsersHandler, $Form, $Mailer;
        
        /* Username error checking */
        $field = "cuser";  //Use field name for username
        if (!$subcuser || strlen($subcuser = trim($subcuser)) == 0){
            $Form->setError($field, "* Username not entered");
        }
        else {
            /* Spruce up username, check length */
            $subcuser = stripslashes($subcuser);
            if (strlen($subcuser) < 3){
                $Form->setError($field, "* Username below 3 characters");
            }
            else if (strlen($subcuser) > 30){
                $Form->setError($field, "* Username above 30 characters");
            }
            /* Check if username is not alphanumeric */
            else if (!eregi("^([0-9a-z])+$", $subcuser)){
                $Form->setError($field, "* Username not alphanumeric");
            }
            /* Check if username is reserved */
            else if (strcasecmp($subcuser, GUEST_NAME) == 0){
                $Form->setError($field, "* Username reserved word");
            }
            /* Check if username is already in use */
            else if ($UsersHandler->usernameTaken($subcuser)){
                $Form->setError($field, "* Username already in use");
            }
            /* Check if username is banned */
            else if ($UsersHandler->usernameBanned($subcuser)){
                $Form->setError($field, "* Username banned");
            }
        }

        /* Password error checking */
        $field = "cpass";  //Use field name for password
        if (!$subcpass){
            $Form->setError($field, "* Password not entered");
        }
        else {
            /* Spruce up password and check length*/
            $subcpass = stripslashes($subcpass);
            if (strlen($subcpass) < 4){
                $Form->setError($field, "* Password too short");
            }
            /* Check if password is not alphanumeric */
            else if (!eregi("^([0-9a-z])+$", ($subcpass = trim($subcpass)))){
                $Form->setError($field, "* Password not alphanumeric");
            }
            /**
             * Note: I trimmed the password only after I checked the length
             * because if you fill the password field up with spaces
             * it looks like a lot more characters than 4, so it looks
             * kind of stupid to report "password too short".
             */
        }
        
    /* first name error checking */
        $field = "cfirst_name";  //Use field name for first name
        if (!$subcfirst_name){
            $Form->setError($field, "* Firstname not entered");
        }
    /* last name error checking */
        $field = "clast_name";  //Use field name for last name
        if (!$subclast_name){
            $Form->setError($field, "* Lastname not entered");
        }
    /* firm error checking */
        $field = "cregkey";  //Use field name for firm
        if (!$subcregkey){
            $Form->setError($field, "* Registration key not entered");
        }

    /* Email error checking */
        $field = "cemail";  //Use field name for email
        if (!$subcemail || strlen($subcemail = trim($subcemail)) == 0){
            $Form->setError($field, "* Email not entered");
        }
        else {
            /* Check if valid email address */
            $regex = "^[_+a-z0-9-]+(\.[_+a-z0-9-]+)*"
                      ."@[a-z0-9-]+(\.[a-z0-9-]{1,})*"
                      ."\.([a-z]{2,}){1}$";
            if (!eregi($regex,$subcemail)){
                $Form->setError($field, "* Email invalid");
            }
            $subcemail = stripslashes($subcemail);
        }

        /* Errors exist, have user correct them */
        if ($Form->num_errors > 0){
            return 1;  //Errors with form
        }
        /* No errors, add the new account to the */
        else {
            if ($UsersHandler->createNewUser($subcuser,
                                            md5($subcpass),
                                            $subcfirst_name,
                                            $subclast_name,
                                            $subcemail,
                                            $subcregkey)){
                return 0;  //New user added succesfully
            }else {
                return 2;  //Registration attempt failed
            }
        }
    }

    /**
     * editAccount - Attempts to edit the user's account information
     * including the password, which it first makes sure is correct
     * if entered, if so and the new password is in the right
     * format, the change is made. All other fields are changed
     * automatically.
     */
    function editAccount($subcurpass, $subnewpass, $subemail){
        global $UsersHandler, $Form;  //The database and form object
        /* New password entered */
        if ($subnewpass){
            /* Current Password error checking */
            $field = "curpass";  //Use field name for current password
            if (!$subcurpass){
                $Form->setError($field, "* Current Password not entered");
            }
            else {
                /* Check if password too short or is not alphanumeric */
                $subcurpass = stripslashes($subcurpass);
                if (strlen($subcurpass) < 4 ||
                    !eregi("^([0-9a-z])+$", 
                           ($subcurpass = trim($subcurpass)))){
                    $Form->setError($field, "* Current Password incorrect");
                }
                /* Password entered is incorrect */
                if ($UsersHandler->confirmUserPass($this->username,
                                              md5($subcurpass)) != 0){
                    $Form->setError($field, "* Current Password incorrect");
                }
            }
            
            /* New Password error checking */
            $field = "newpass";  //Use field name for new password
            /* Spruce up password and check length*/
            $subpass = stripslashes($subnewpass);
            if (strlen($subnewpass) < 4){
                $Form->setError($field, "* New Password too short");
            }
            /* Check if password is not alphanumeric */
            else if (!eregi("^([0-9a-z])+$", 
                    ($subnewpass = trim($subnewpass)))){
                $Form->setError($field, "* New Password not alphanumeric");
            }
        }
        /* Change password attempted */
        else if ($subcurpass){
            /* New Password error reporting */
            $field = "newpass";  //Use field name for new password
            $Form->setError($field, "* New Password not entered");
        }
        
        /* Email error checking */
        $field = "email";  //Use field name for email
        if ($subemail && strlen($subemail = trim($subemail)) > 0){
            /* Check if valid email address */
            $regex = "^[_+a-z0-9-]+(\.[_+a-z0-9-]+)*"
                      ."@[a-z0-9-]+(\.[a-z0-9-]{1,})*"
                      ."\.([a-z]{2,}){1}$";
            if (!eregi($regex,$subemail)){
                $Form->setError($field, "* Email invalid");
            }
            $subemail = stripslashes($subemail);
        }
        
        /* Errors exist, have user correct them */
        if ($Form->num_errors > 0){
            return false;  //Errors with form
        }
        
        /* Update password since there were no errors */
        if ($subcurpass && $subnewpass){
            $UsersHandler->updateUserField($this->username,
                                        "password",
                                        md5($subnewpass));
        }
        
        /* Change Email */
        if ($subemail){
            $UsersHandler->updateUserField($this->username,
                                        "email",
                                        $subemail);
        }
        
        /* Success! */
        return true;
    }
    
    /**
     * isAdmin - Returns true if currently logged in user is
     * an administrator, false otherwise.
     */
    function isAdmin(){
        return ($this->userlevel == ADMIN_LEVEL ||
                  $this->username  == ADMIN_NAME);
    }
    /**
     * isUser2 - Returns true if currently logged in user is
     * an user level 2, false otherwise. Etc...
     */
    function isUser2(){
        return ($this->userlevel == USER2_LEVEL);
    }
    function isUser3(){
        return ($this->userlevel == USER3_LEVEL);
    }
    function isUser4(){
        return ($this->userlevel == USER4_LEVEL);
    }
    function isUser5(){
        return ($this->userlevel == USER5_LEVEL);
    }
    function isUser6(){
        return ($this->userlevel == USER6_LEVEL);
    }
    function isUser7(){
        return ($this->userlevel == USER7_LEVEL);
    }
    function isUser8(){
        return ($this->userlevel == USER8_LEVEL);
    }
    /**
     * generateRandID - Generates a string made up of randomized
     * letters (lower and upper case) and digits and returns
     * the md5 hash of it to be used as a userid.
     */
    function generateRandID(){
        return md5($this->generateRandStr(16));
    }
    
    /**
     * generateRandStr - Generates a string made up of randomized
     * letters (lower and upper case) and digits, the length
     * is a specified parameter.
     */
    function generateRandStr($length){
        $randstr = "";
        for ($i=0; $i<$length; $i++){
            $randnum = mt_rand(0,61);
            if ($randnum < 10){
                $randstr .= chr($randnum+48);
            }else if ($randnum < 36){
                $randstr .= chr($randnum+55);
            }else {
                $randstr .= chr($randnum+61);
            }
        }
        return $randstr;
    }
}

class Process
{
    /* Class constructor */
    function __construct(){
        global $Session;
        /* User submitted login form */
        if (isset($_POST['sublogin'])){
            $this->procLogin();
        }
        /* User submitted registration form */
        else if (isset($_POST['subjoin'])){
            $this->procRegister();
        }
        /* User submitted account creation form */
        else if (isset($_POST['subcreate'])){
            $this->procCreate();
        }
        /* User submitted forgot password form */
        else if (isset($_POST['subforgot'])){
            $this->procForgotPass();
        }
        /* User submitted edit account form */
        else if (isset($_POST['subedit'])){
            $this->procEditAccount();
        }
        /**
         * The only other reason user should be directed here
         * is if he wants to logout, which means user is
         * logged in currently.
         */
        else if ($Session->logged_in){
            $this->procLogout();
        }
        /**
         * Should not get here, which means user is viewing this page
         * by mistake and therefore is redirected.
         */
         else {
             header("Location: " . URL_ROOT);
         }
    }

    /**
     * procLogin - Processes the user submitted login form, if errors
     * are found, the user is redirected to correct the information,
     * if not, the user is effectively logged in to the system.
     */
    function procLogin(){
        global $Session, $Form;
        /* Login attempt */
        $retval = $Session->login($_POST['user'],
                                $_POST['pass'],
                                isset($_POST['remember']));
        
        /* Login successful */
        if ($retval){
            if ($Session->isAdmin()){
                header("Location: " . URL_ROOT . ADMIN_PATH);
            }
            else {
                header("Location: " . URL_ROOT);
            }
        }
        /* Login failed */
        else {
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $Form->getErrorArray();
            #header("Location: " . $Session->referrer);
        }
    }
    
    /**
     * procLogout - Simply attempts to log the user out of the system
     * given that there is no logout form to process.
     */
    function procLogout(){
        global $Session;
        $retval = $Session->logout();
        #header("Location: /#!/1");
    }
    
    /**
     * procRegister - Processes the user submitted registration form,
     * if errors are found, the user is redirected to correct the
     * information, if not, the user is effectively registered with
     * the system and an email is (optionally) sent to the newly
     * created user.
     */
    function procRegister(){
        global $Session, $Form;
        /* Convert username to all lowercase (by option) */
        if (ALL_LOWERCASE){
            $_POST['user'] = strtolower($_POST['user']);
        }
        /* Registration attempt */
        $retval = $Session->register($_POST['user'],
                                    $_POST['pass'],
                                    $_POST['email'],
                                    $_POST['first_name'],
                                    $_POST['last_name']);
        
        /* Registration Successful */
        if ($retval == 0){
            $_SESSION['reguname'] = $_POST['user'];
            $_SESSION['regsuccess'] = true;
            #header("Location: ".$Session->referrer);
        }
        /* Error found with form */
        else if ($retval == 1){
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $Form->getErrorArray();
            #header("Location: ".$Session->referrer);
        }
        /* Registration attempt failed */
        else if ($retval == 2){
            $_SESSION['reguname'] = $_POST['user'];
            $_SESSION['regsuccess'] = false;
            #header("Location: ".$Session->referrer);
        }
    }

    /**
     * procCreate - Processes the user submitted registration form,
     * if errors are found, the user is redirected to correct the
     * information, if not, the user is effectively registered with
     * the system and an email is (optionally) sent to the newly
     * created user.
     */
    function procCreate(){
        global $Session, $Form;
        /* Convert username to all lowercase (by option) */
        if (ALL_LOWERCASE){
            $_POST['cuser'] = strtolower($_POST['cuser']);
        }
        /* Registration attempt */
        $retval = $Session->create($_POST['cuser'],
                                 $_POST['cpass'],
                                 $_POST['cfirst_name'],
                                 $_POST['clast_name'],
                                 $_POST['cemail'],
                                 $_POST['cregkey']);
        
        /* Registration Successful */
        if ($retval == 0){
            $_SESSION['reguname'] = $_POST['cuser'];
            $_SESSION['regsuccess'] = true;
            #header("Location: ".$Session->referrer);
        }
        /* Error found with form */
        else if ($retval == 1){
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $Form->getErrorArray();
            #header("Location: " . $Session->referrer);
        }
        /* Registration attempt failed */
        else if ($retval == 2){
            $_SESSION['reguname'] = $_POST['cuser'];
            $_SESSION['regsuccess'] = false;
            #header("Location: " . $Session->referrer);
        }
    }

    /**
     * procForgotPass - Validates the given username then if
     * everything is fine, a new password is generated and
     * emailed to the address the user gave on sign up.
     */
    function procForgotPass(){
        global $UsersHandler, $Session, $Mailer, $Form;
        /* Username error checking */
        $subuser = $_POST['user'];
        $field = "user";  //Use field name for username
        if (!$subuser || strlen($subuser = trim($subuser)) == 0){
            $Form->setError($field, "* Username not entered<br>");
        }
        else {
            /* Make sure username is in database */
            $subuser = stripslashes($subuser);
            if (strlen($subuser) < 5 || strlen($subuser) > 30 ||
                !eregi("^([0-9a-z])+$", $subuser) ||
                (!$UsersHandler->usernameTaken($subuser))){
                $Form->setError($field, "* Username does not exist<br>");
            }
        }
        
        /* Errors exist, have user correct them */
        if ($Form->num_errors > 0){
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $Form->getErrorArray();
        }
        /* Generate new password and email it to user */
        else {
            /* Generate new password */
            $newpass = $Session->generateRandStr(8);
            
            /* Get email of user */
            $usrinf = $UsersHandler->getUserInfo($subuser);
            $email  = $usrinf['email'];
            
            /* Attempt to send the email with new password */
            if ($Mailer->sendNewPass($subuser,$email,$newpass)){
                /* Email sent, update database */
                $UsersHandler->updateUserField($subuser,
                                            "password",
                                            md5($newpass));
                $_SESSION['forgotpass'] = true;
            }
            /* Email failure, do not change password */
            else {
                $_SESSION['forgotpass'] = false;
            }
        }
        
        header("Location: " . $Session->referrer);
    }
    
    /**
     * procEditAccount - Attempts to edit the user's account
     * information, including the password, which must be verified
     * before a change is made.
     */
    function procEditAccount(){
        global $Session, $Form;
        /* Account edit attempt */
        $retval = $Session->editAccount($_POST['curpass'],
                                      $_POST['newpass'],
                                      $_POST['email']);

        /* Account edit successful */
        if ($retval){
            $_SESSION['useredit'] = true;
            header("Location: " . $Session->referrer);
        }
        /* Error found with form */
        else {
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $Form->getErrorArray();
            header("Location: " . $Session->referrer);
        }
    }
}

/**                                                                          *
 *                                                                           *
 * The Uploader section is meant to simplify uploading                       *
 * of files by working together with the plupload plugin                     *
 */
class Uploader
{
    public function upload($_REQUEST){
        global $Database;
        
        # HTTP headers for no cache etc
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        # Settings
        $targetDir = UPLOAD_ROOT;

        #$cleanupTargetDir = false; // Remove old files
        #$maxFileAge = 60 * 60; // Temp file age in seconds

        # 5 minutes execution time
        @set_time_limit(5 * 60);

        # Uncomment this one to fake upload time
        #usleep(5000);

        # Get parameters
        $chunk = isset($_REQUEST["chunk"]) ? $_REQUEST["chunk"] : 0;
        $chunks = isset($_REQUEST["chunks"]) ? $_REQUEST["chunks"] : 0;
        $fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';

        # Clean the fileName for security reasons
        $fileName = preg_replace('/[^\w\._]+/', '', $fileName);

        # Make sure the fileName is unique but only if chunking is disabled
        if ($chunks < 2 && 
            file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName)){
            $ext = strrpos($fileName, '.');
            $fileName_a = substr($fileName, 0, $ext);
            $fileName_b = substr($fileName, $ext);

            $count = 1;
            while(file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName_a 
                              . '_' . $count . $fileName_b))
                $count++;

            $fileName = $fileName_a . '_' . $count . $fileName_b;
        }

        # Create target dir
        if (!file_exists($targetDir))
            @mkdir($targetDir);

        # Look for the content type header
        if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
            $contentType = $_SERVER["HTTP_CONTENT_TYPE"];

        if (isset($_SERVER["CONTENT_TYPE"]))
            $contentType = $_SERVER["CONTENT_TYPE"];

        # Handle non multipart uploads older WebKit versions didn't 
        # support multipart in HTML5
        if (strpos($contentType, "multipart") !== false){
            if (isset($_FILES['file']['tmp_name']) && 
                is_uploaded_file($_FILES['file']['tmp_name'])){
                # Open temp file
                $out = fopen($targetDir . DIRECTORY_SEPARATOR . $fileName,
                             $chunk == 0 ? "wb" : "ab");
                if ($out){
                    # Read binary input stream and append it to temp file
                    $in = fopen($_FILES['file']['tmp_name'], "rb");

                    if ($in){
                        while ($buff = fread($in, 4096))
                            fwrite($out, $buff);
                    } else
                        die('{"jsonrpc" : "2.0", '
                            . '"error" : {"code": 101, '
                            . '"message": "Failed to open input stream."}, '
                            . '"id" : "id"}');
                    fclose($in);
                    fclose($out);
                    @unlink($_FILES['file']['tmp_name']);
                } else
                    die('{"jsonrpc" : "2.0", '
                        . '"error" : {"code": 102, '
                        . '"message": "Failed to open output stream."}, '
                        . '"id" : "id"}');
            } else
                die('{"jsonrpc" : "2.0", '
                    . '"error" : {"code": 103, '
                    . '"message": "Failed to move uploaded file."}, '
                    . '"id" : "id"}');
        } else {
            # Open temp file
            $out = fopen($targetDir . DIRECTORY_SEPARATOR . $fileName, 
                         $chunk == 0 ? "wb" : "ab");
            if ($out) {
                # Read binary input stream and append it to temp file
                $in = fopen("php://input", "rb");

                if ($in) {
                    while ($buff = fread($in, 4096))
                        fwrite($out, $buff);
                } else
                    die('{"jsonrpc" : "2.0", '
                        . '"error" : {"code": 101, '
                        . '"message": "Failed to open input stream."}, '
                        . '"id" : "id"}');

                fclose($in);
                fclose($out);
            } else
                die('{"jsonrpc" : "2.0", '
                    . '"error" : {"code": 102, '
                    . '"message": "Failed to open output stream."}, '
                    . '"id" : "id"}');
        }

        # Return JSON-RPC response
        $Database->insert('_uploads_log',
                          array('log_originalname'=>$_FILES['file']['name'],
                          'log_filename'=>$fileName,
                          'log_size'=>$_FILES['file']['size'],
                          'log_ip'=>$_SERVER['REMOTE_ADDR']));
        $Database->insert('_recent_activity',
                          array('name'=>'uploads',
                          'grouping'=>'uploads'.date("Y-m-d"),
                          'action'=>'upload',
                          'additional'=>$_FILES['file']['name']));
        
        die('{"jsonrpc" : "2.0", '
            . '"result" : null, '
            . '"id" : "id"}');

    }
}
