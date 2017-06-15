<?php
/**
 * Backup Controller
 *
 * PHP Version 5.5+
 *
 * @category Controller
 * @package  Application
 * @author   XG Proyect Team
 * @license  http://www.xgproyect.org XG Proyect
 * @link     http://www.xgproyect.org
 * @version  3.0.0
 */

namespace application\controllers\adm;

use application\core\XGPCore;
use application\libraries\adm\AdministrationLib;
use application\libraries\FunctionsLib;

/**
 * Backup Class
 *
 * @category Classes
 * @package  Application
 * @author   XG Proyect Team
 * @license  http://www.xgproyect.org XG Proyect
 * @link     http://www.xgproyect.org
 * @version  3.0.0
 */
class Backup extends XGPCore
{
    private $_lang;
    private $_current_user;

    /**
     * __construct()
     */
    public function __construct()
    {
        parent::__construct();

        // check if session is active
        AdministrationLib::checkSession();

        $this->_lang = parent::$lang;
        $this->_current_user = parent::$users->getUserData();

        // Check if the user is allowed to access
        if (AdministrationLib::haveAccess($this->_current_user['user_authlevel']) && AdministrationLib::authorization($this->_current_user['user_authlevel'], 'use_tools') == 1) {
            $this->build_page();
        } else {
            die(AdministrationLib::noAccessMessage($this->_lang['ge_no_permissions']));
        }
    }

    /**
     * method __destruct
     * param
     * return close db connection
     */
    public function __destruct()
    {
        parent::$db->closeConnection();
    }

    /**
     * method build_page
     * param
     * return main method, loads everything
     */
    private function build_page()
    {
        $parse = $this->_lang;

        // ON POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // SAVE DATA
            if (isset($_POST['save']) && $_POST['save']) {
                FunctionsLib::updateConfig('auto_backup', ( ( isset($_POST['auto_backup']) && $_POST['auto_backup'] == 'on' ) ? 1 : 0));
            }

            // BACKUP DATABASE RIGHT NOW
            if (isset($_POST['backup']) && $_POST['backup']) {
                $result = parent::$db->backupDb();

                if ($result != false) {
                    $parse['alert'] = AdministrationLib::saveMessage('ok', str_replace('%s', round($result / 1024, 2), $this->_lang['bku_backup_done']));
                }
            }
        }

        // PARSE DATA
        $auto_backup_status = FunctionsLib::readConfig('auto_backup');
        $parse['color'] = ( $auto_backup_status == 1 ) ? 'text-success' : 'text-error';
        $parse['checked'] = ( $auto_backup_status == 1 ) ? 'checked' : '';

        parent::$page->display(parent::$page->parseTemplate(parent::$page->getTemplate("adm/backup_view"), $parse));
    }
}

/* end of backup.php */
