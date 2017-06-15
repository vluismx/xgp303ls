<?php
/**
 * Functions Library
 *
 * PHP Version 5.5+
 *
 * @category Library
 * @package  Application
 * @author   XG Proyect Team
 * @license  http://www.xgproyect.org XG Proyect
 * @link     http://www.xgproyect.org
 * @version  3.0.0
 */

namespace application\libraries;

use application\core\XGPCore;
use application\core\Options;

/**
 * FunctionsLib Class
 *
 * @category Classes
 * @package  Application
 * @author   XG Proyect Team
 * @license  http://www.xgproyect.org XG Proyect
 * @link     http://www.xgproyect.org
 * @version  3.0.0
 */
abstract class FunctionsLib extends XGPCore
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * loadLibrary
     *
     * @param string $library Library
     *
     * @return boolean
     */
    public static function loadLibrary($library = '')
    {
        if (!empty($library)) {

            // Require file
            require_once XGP_ROOT . LIB_PATH . $library . '.php';

            $class_name = 'application\libraries\\' . $library;

            // Create new $library object
            return new $class_name();
        } else {

            // ups!
            return false;
        }
    }

    /**
     * formatText
     *
     * @param string $text Text
     *
     * @return string
     */
    public static function formatText($text)
    {
        $text   = parent::$db->escapeValue($text);
        $text   = trim(nl2br(strip_tags($text, '<br>')));
        $text   = preg_replace('|[\r][\n]|', '\\r\\n', $text);

        return $text;
    }

    /**
     * chronoApplet
     *
     * @param string  $type  Type
     * @param string  $ref   Ref
     * @param string  $value Value
     * @param boolean $init  Init
     *
     * @return string
     */
    public static function chronoApplet($type, $ref, $value, $init)
    {
        if ($init == true) {

            $template = parent::$page->getTemplate('general/chrono_applet_init');
        } else {

            $template = parent::$page->getTemplate('general/chrono_applet');
        }

        $parse['type']  = $type;
        $parse['ref']   = $ref;
        $parse['value'] = $value;

        return parent::$page->parseTemplate($template, $parse);
    }

    /**
     * readConfig
     *
     * @param string  $config_name Config name
     * @param boolean $all         All
     *
     * @return string
     */
    public static function readConfig($config_name = '', $all = false)
    {
        $configs = Options::getInstance();

        if ($all) {

            foreach ($configs->getOptions()  as $row) {
                $return[$row['option_name']]   = $row['option_value'];
            }
            
            return $return;
        } else {

            return $configs->getOptions($config_name);
        }
    }

    /**
     * updateConfig
     *
     * @param string $config_name  Config name
     * @param string $config_value Config value
     *
     * @return string
     */
    public static function updateConfig($config_name, $config_value)
    {
        return Options::getInstance()->writeOptions($config_name, $config_value);
    }

    /**
     * validEmail
     *
     * @param string $address Email address
     *
     * @return string
     */
    public static function validEmail($address)
    {
        return (!preg_match(
            "/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix",
            $address
        )) ? false : true;
    }

    /**
     * prepUrl
     *
     * @param string $url URL
     *
     * @return string
     */
    public static function prepUrl($url = '')
    {
        if ($url == 'http://' or $url == '') {

            return '';
        }

        if (substr($url, 0, 7) != 'http://' && substr($url, 0, 8) != 'https://') {

            $url    = 'http://' . $url;
        }

        return $url;
    }

    /**
     * fleetSpeedFactor
     *
     * @return string
     */
    public static function fleetSpeedFactor()
    {
        return FunctionsLib::readConfig('fleet_speed') / 2500;
    }

    /**
     * message
     *
     * @param string  $mes    Message
     * @param string  $dest   Redirect destination
     * @param string  $time   Time to redirect
     * @param boolean $topnav Show top navigation
     * @param boolean $menu   Show menu
     * @param boolean $center Center message
     *
     * @return void
     */
    public static function message($mes, $dest = '', $time = '3', $topnav = false, $menu = true, $center = true)
    {
        define('IN_MESSAGE', true);

        $parse['mes']       = $mes;
        $parse['middle1']   = '';
        $parse['middle2']   = '';

        if ($center) {

            $parse['middle1']   = '<div id="content">';
            $parse['middle2']   = '</div>';
        }

        $page   = parent::$page->parseTemplate(parent::$page->getTemplate('general/message_body'), $parse);

        if (!defined('IN_ADMIN')) {

            parent::$page->display(
                $page,
                $topnav,
                (($dest != "") ? "<meta http-equiv=\"refresh\" content=\"$time;URL=$dest\">" : ""),
                $menu
            );
        } else {

            parent::$page->display(
                $page,
                $topnav,
                (($dest != "") ? "<meta http-equiv=\"refresh\" content=\"$time;URL=$dest\">" : ""),
                $menu
            );
        }
    }

    /**
     * isModuleAccesible
     *
     * @param int $module_id Module ID
     *
     * @return array
     */
    public static function isModuleAccesible($module_id = 0)
    {
        $modules_array  = self::readConfig('modules');
        $modules_array  = explode(';', $modules_array);

        if ($module_id == 0) {

            return $modules_array;
        } else {

            return $modules_array[$module_id];
        }
    }

    /**
     * moduleMessage
     *
     * @param int $access_level Access level
     *
     * @return void
     */
    public static function moduleMessage($access_level)
    {
        if ($access_level == 0) {

            die(self::message(parent::$lang['lm_module_not_accesible'], '', '', true));
        }
    }

    /**
     * sortPlanets
     *
     * @param array $current_user Current user
     *
     * @return array
     */
    public static function sortPlanets($current_user)
    {
        $order  = $current_user['setting_planet_order'] == 1 ? "DESC" : "ASC";
        $sort   = $current_user['setting_planet_sort'];

        $planets = "SELECT `planet_id`, `planet_name`, `planet_galaxy`, `planet_system`, `planet_planet`, `planet_type`
                    FROM " . PLANETS . "
                    WHERE `planet_user_id` = '" . (int) $current_user['user_id'] . "'
                        AND `planet_destroyed` = 0 ORDER BY ";

        switch ($sort) {
            case 0:
                $planets    .= "`planet_id` " . $order;

                break;

            case 1:
                $planets    .= "`planet_galaxy`, `planet_system`, `planet_planet`, `planet_type` " . $order;

                break;

            case 2:
                $planets    .= "`planet_name` " . $order;

                break;
        }

        return parent::$db->query($planets);
    }

    /**
     * buildPlanetList
     *
     * @param array $current_user      Current user
     * @param int   $current_planet_id Current planet ID
     *
     * @return mixed
     */
    public static function buildPlanetList($current_user, $current_planet_id = 0)
    {
        $list           = '';
        $user_planets   = self::sortPlanets($current_user);

        $page   = isset($_GET['page']) ? $_GET['page'] : '';
        $gid    = isset($_GET['gid']) ? $_GET['gid'] : '';
        $mode   = isset($_GET['mode']) ? $_GET['mode'] : '';

        if ($user_planets) {

            while ($planets = parent::$db->fetchArray($user_planets)) {

                if ($current_planet_id != $planets['planet_id']) {

                    $list   .= "\n<option ";
                    $list   .= (($planets['planet_id'] == $current_user['user_current_planet'] ) ?
                        'selected="selected" ' : '');

                    // FOR TOPNAVIGATION BAR PLANET LIST
                    if ($current_planet_id == 0) {

                        $list .= "value=\"game.php?page=" . $page . "&gid=" .
                            $gid . "&cp=" . $planets['planet_id'] . "";
                        $list .= "&amp;mode=" . $mode;
                        $list .= "&amp;re=0\">";
                    } else {

                        // FOR FLEETS2 PAGE COLONIES SHORTCUTS
                        $list   .= "value=\"" . $planets['planet_galaxy'] . ';' . $planets['planet_system'] . ';' .
                            $planets['planet_planet'] . ';' . $planets['planet_type'] . "\">";
                    }

                    $list .= (($planets['planet_type'] != 3)
                        ? $planets['planet_name'] : $planets['planet_name'] . ' (' . parent::$lang['fcm_moon'] . ')');
                    $list .= "&nbsp;[" . $planets['planet_galaxy'] . ":";
                    $list .= $planets['planet_system'] . ":";
                    $list .= $planets['planet_planet'];
                    $list .= "]&nbsp;&nbsp;</option>";
                }
            }
        }

        // IF THE LIST OF PLANETS IS EMPTY WE SHOULD RETURN false
        if ($list !== '') {

            return $list;
        } else {

            return false;
        }
    }

    /**
     * sendMessage
     *
     * @param int    $to      To
     * @param int    $sender  Sender
     * @param int    $time    Time
     * @param int    $type    Type
     * @param string $from    From
     * @param string $subject Subject
     * @param string $message Message
     *
     * @return void
     */
    public static function sendMessage($to, $sender, $time = '', $type = '', $from = '', $subject = '', $message = '')
    {
        if ($time == '') {

            $time   = time();
        }

        $message    = (strpos($message, '/admin.php/') === false) ? $message : '';

        parent::$db->query(
            "INSERT INTO " . MESSAGES . " SET
            `message_receiver` = '" . $to . "',
            `message_sender` = '" . $sender . "',
            `message_time` = '" . $time . "',
            `message_type` = '" . $type . "',
            `message_from` = '" . $from . "',
            `message_subject` = '" . $subject . "',
            `message_text` 	= '" . parent::$db->escapeValue($message) . "';"
        );
    }
    
    /**
     * Send and email
     * 
     * @param string $to      Mail To
     * @param string $subject Mail Subject
     * @param string $body    Mail Body
     * @param array  $from    Mail From
     * @param mixed  $headers Mail headers (optional)
     * 
     * @return mixed
     */
    public static function sendEmail($to, $subject, $body, $from, $format = 'text', $headers = '')
    {
        // require email library
        $mail_library_path  = XGP_ROOT . VENDOR_PATH . 'ci/system/libraries/Email.php';
        
        if (!file_exists($mail_library_path)) {
            
            return;
        }
        
        // required by the library
        if (!defined('BASEPATH')) {

            define('BASEPATH', true);
        }
        
        // use CI library
        require_once $mail_library_path;
        
        $mail   = new \CI_Email();
        
        if ($format === 'text' or $format === 'html') {

            $mail->set_mailtype($format);
        }
        
        // from
        if (is_array($from)) {

            $mail->from($from['mail'], $from['name']);
        }

        // to
        $mail->to($to);

        // headers
        if (is_array($headers)) {
            
            foreach ($headers as $header => $value) {

                $mail->set_header($header, $value);
            }
        }
        
        // subject
        $mail->subject($subject);
        
        // message body
        $mail->message($body);

        // send!
        $mail->send();
    }
    
    /**
     * getDefaultVacationTime
     *
     * @return int
     */
    public static function getDefaultVacationTime()
    {
        return (time() + (3600 * 24 * VACATION_TIME_FORCED));
    }

    /**
     * setUrl
     *
     * @param string $url        URL
     * @param string $title      Title
     * @param string $content    Content - Visible part
     * @param string $attributes Attributes - css & js
     *
     * @return string
     */
    public static function setUrl($url, $title, $content, $attributes = '')
    {
        if (empty($url)) {

            $url = '#';
        }

        if (!empty($title)) {

            $title = 'title="' . $title . '"';
        }

        if (!empty($attributes)) {

            $attributes = ' ' . $attributes;
        }

        return '<a href="' . $url . '" ' . $title . ' ' . $attributes . '>' . $content . '</a>';
    }

    /**
     * setImage
     *
     * @param string $path       Image path
     * @param string $title      Title
     * @param string $attributes Attributes - css & js
     *
     * @return string
     */
    public static function setImage($path, $title = 'img', $attributes = '')
    {
        if (!empty($attributes)) {

            $attributes = ' ' . $attributes;
        }

        return '<img src="' . $path . '" title="' . $title . '" border="0"' . $attributes . '>';
    }

    /**
     * Like in_array but going deeper
     *
     * @param string $needle   Needle
     * @param array  $haystack Haystack
     *
     * @return boolean
     */
    public static function inMultiarray($needle, $haystack)
    {
        foreach ($haystack as $key => $value) {

            if ($value == $needle) {

                return true;
            } elseif (is_array($value)) {

                if (self::inMultiarray($needle, $value)) {

                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Like array search but going deeper
     *
     * @param string $needle   Needle
     * @param array  $haystack Haystack
     *
     * @return boolean
     */
    public static function recursiveArraySearch($needle, $haystack)
    {
        foreach ($haystack as $key => $value) {

            $current_key = $key;

            if ($needle === $value or (is_array($value) && self::recursiveArraySearch($needle, $value) !== false )) {

                return $current_key;
            }
        }

        return false;
    }

    /**
     * redirect
     *
     * @param string $route Route
     *
     * @return void
     */
    public static function redirect($route)
    {
        exit(header('location:' . $route));
    }

    /**
     * getCurrentLanguage
     *
     * @return string
     */
    public static function getCurrentLanguage($installed = false)
    {
        if ($installed) {
            return self::readConfig('lang');
        }
        
        // set the user language reading the config file
        if ($installed && !isset($_COOKIE['current_lang'])) {

            $_COOKIE['current_lang']    = self::readConfig('lang');
        }

        // get the language from the session
        if (isset($_COOKIE['current_lang'])) {

            return $_COOKIE['current_lang'];
        }
        
        return 'english'; // the universal language if nothing was set
    }
    
    /**
     * setCurrentLanguage
     * 
     * @param string $lang Language
     * 
     * @return void
     */
    public static function setCurrentLanguage($lang = '')
    {
        // set the user language reading the config file
        if (parent::$db != null && parent::$db->testConnection() && !isset($_COOKIE['current_lang'])) {

            self::updateConfig('lang', $lang);
        }
        
        setcookie('current_lang', $lang);
    }
    
    /**
     * getLanguages
     *
     * @param string $current_lang Current language
     *
     * @return string
     */
    public static function getLanguages($current_lang)
    {
        $langs_dir      = opendir(XGP_ROOT . LANG_PATH);
        $exceptions     = ['.', '..', '.htaccess', 'index.html', '.DS_Store'];
        $lang_options   = '';

        while (($lang_dir = readdir($langs_dir)) !== false) {

            if (!in_array($lang_dir, $exceptions)) {

                $lang_options   .= '<option ';

                if ($current_lang == $lang_dir) {

                    $lang_options .= 'selected = selected';
                }

                $lang_options   .= ' value="' . $lang_dir . '">' . $lang_dir . '</option>';
            }
        }

        return $lang_options;
    }

    /**
     * checkServer
     *
     * @param array $current_user Current user
     *
     * @return void
     */
    public static function checkServer($current_user)
    {
        if (self::readConfig('game_enable') == 0 && $current_user['user_authlevel'] == 3) {

            self::message(stripslashes(FunctionsLib::readConfig('close_reason')), '', '', false, false);
            die();
        }
    }
}

/* end of FunctionsLib.php */
