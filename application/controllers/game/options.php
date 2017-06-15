<?php
/**
 * Options Controller
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

namespace application\controllers\game;

use application\core\XGPCore;
use application\libraries\FunctionsLib;

/**
 * Options Class
 *
 * @category Classes
 * @package  Application
 * @author   XG Proyect Team
 * @license  http://www.xgproyect.org XG Proyect
 * @link     http://www.xgproyect.org
 * @version  3.0.0
 */
class Options extends XGPCore
{
    const MODULE_ID = 21;

    private $_current_user;
    private $_lang;

    /**
     * __construct()
     */
    public function __construct()
    {
        parent::__construct();

        // check if session is active
        parent::$users->checkSession();

        // Check module access
        FunctionsLib::moduleMessage(FunctionsLib::isModuleAccesible(self::MODULE_ID));

        $this->_lang = parent::$lang;
        $this->_current_user = parent::$users->getUserData();

        $this->build_page();
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
        $mode = isset($_GET['mode']) ? $_GET['mode'] : NULL;

        if ($_POST && $mode == 'exit') {

            if (isset($_POST['exit_modus']) && $_POST['exit_modus'] == 'on' && $this->_current_user['setting_vacations_until'] <= time()) {

                parent::$db->query("UPDATE " . SETTINGS . ", " . PLANETS . " SET
                    `setting_vacations_status` = '0',
                    `setting_vacations_until` = '0',
                    planet_building_metal_mine_percent = '10',
                    planet_building_crystal_mine_percent = '10',
                    planet_building_deuterium_sintetizer_percent = '10',
                    planet_building_solar_plant_percent = '10',
                    planet_building_fusion_reactor_percent = '10',
                    planet_ship_solar_satellite_percent = '10'
                    WHERE `setting_user_id` = '" . intval($this->_current_user['user_id']) . "' AND planet_user_id = '" . intval($this->_current_user['user_id']) . "'");
            }
            
            parent::$db->query(
                "UPDATE `" . SETTINGS . "` AS s SET
                    s.`setting_delete_account` = '" . ($_POST['db_deaktjava'] == 'on' ? time() : 0) . "'
                WHERE s.`setting_user_id` = '" . $this->_current_user['user_id'] . "'"
            );
            
            FunctionsLib::redirect('game.php?page=options');
        }

        if ($_POST && $mode == "change") {
            // < ------------------------------------------------- COMPROBACION DE IP -------------------------------------------------- >
            if (isset($_POST['noipcheck']) && $_POST['noipcheck'] == 'on') {
                $noipcheck = '1';
            } else {
                $noipcheck = '0';
            }
            // < ------------------------------------------------- NOMBRE DE USUARIO --------------------------------------------------- >
            if (isset($_POST['db_character']) && $_POST['db_character'] != '') {
                $username = parent::$db->escapeValue($_POST['db_character']);
            } else {
                $username = parent::$db->escapeValue($this->_current_user['user_name']);
            }
            // < ------------------------------------------------- DIRECCION DE EMAIL -------------------------------------------------- >

            if (isset($_POST['db_email']) && $_POST['db_email'] != '') {
                $db_email = parent::$db->escapeValue($_POST['db_email']);
            } else {
                $db_email = parent::$db->escapeValue($this->_current_user['user_email']);
            }
            // < ------------------------------------------------- CANTIDAD DE SONDAS -------------------------------------------------- >
            if (isset($_POST['spio_anz']) && is_numeric($_POST['spio_anz'])) {
                $spio_anz = intval($_POST['spio_anz']);
            } else {
                $spio_anz = '1';
            }
            // < ------------------------------------------------- MENSAJES DE FLOTAS -------------------------------------------------- >
            if (isset($_POST['settings_fleetactions']) && is_numeric($_POST['settings_fleetactions'])) {
                $settings_fleetactions = intval($_POST['settings_fleetactions']);
            } else {
                $settings_fleetactions = '1';
            }
            // < ------------------------------------------------- SONDAS DE ESPIONAJE ------------------------------------------------- >
            if (isset($_POST['settings_esp']) && $_POST['settings_esp'] == 'on') {
                $settings_esp = '1';
            } else {
                $settings_esp = '0';
            }
            // < ------------------------------------------------- ESCRIBIR MENSAJE ---------------------------------------------------- >
            if (isset($_POST['settings_wri']) && $_POST['settings_wri'] == 'on') {
                $settings_wri = '1';
            } else {
                $settings_wri = '0';
            }
            // < --------------------------------------------- A�ADIR A LISTA DE AMIGOS ------------------------------------------------ >
            if (isset($_POST['settings_bud']) && $_POST['settings_bud'] == 'on') {
                $settings_bud = '1';
            } else {
                $settings_bud = '0';
            }

            // < ------------------------------------------------- ATAQUE CON MISILES -------------------------------------------------- >
            if (isset($_POST['settings_mis']) && $_POST['settings_mis'] == 'on') {
                $settings_mis = '1';
            } else {
                $settings_mis = '0';
            }
            // < ------------------------------------------------- VER REPORTE --------------------------------------------------------- >
            if (isset($_POST['settings_rep']) && $_POST['settings_rep'] == 'on') {
                $settings_rep = '1';
            } else {
                $settings_rep = '0';
            }
            // < ------------------------------------------------- MODO VACACIONES ----------------------------------------------------- >
            if (isset($_POST['urlaubs_modus']) && $_POST['urlaubs_modus'] == 'on') {
                if ($this->CheckIfIsBuilding()) {
                    FunctionsLib::message($this->_lang['op_cant_activate_vacation_mode'], "game.php?page=options", 2);
                }

                $urlaubs_modus = '1';
                $time = FunctionsLib::getDefaultVacationTime();
                parent::$db->query("UPDATE " . SETTINGS . ", " . PLANETS . " SET
										`setting_vacations_status` = '$urlaubs_modus',
										`setting_vacations_until` = '$time',
										planet_metal_perhour = '" . FunctionsLib::readConfig('metal_basic_income') . "',
										planet_crystal_perhour = '" . FunctionsLib::readConfig('crystal_basic_income') . "',
										planet_deuterium_perhour = '" . FunctionsLib::readConfig('deuterium_basic_income') . "',
										planet_energy_used = '0',
										planet_energy_max = '0',
										planet_building_metal_mine_percent = '0',
										planet_building_crystal_mine_percent = '0',
										planet_building_deuterium_sintetizer_percent = '0',
										planet_building_solar_plant_percent = '0',
										planet_building_fusion_reactor_percent = '0',
										planet_ship_solar_satellite_percent = '0'
										WHERE `setting_user_id` = '" . intval($this->_current_user['user_id']) . "' AND planet_user_id = '" . intval($this->_current_user['user_id']) . "'");
            } else {
                $urlaubs_modus = '0';
            }
            // < ------------------------------------------------- BORRAR CUENTA ------------------------------------------------------- >
            if (isset($_POST['db_deaktjava']) && $_POST['db_deaktjava'] == 'on') {
                $db_deaktjava = time();
            } else {
                $db_deaktjava = '0';
            }

            $SetSort = parent::$db->escapeValue($_POST['settings_sort']);
            $SetOrder = parent::$db->escapeValue($_POST['settings_order']);
            //// < -------------------------------------- ACTUALIZAR TODO LO SETEADO ANTES --------------------------------------------- >

            parent::$db->query("UPDATE " . USERS . " AS u, " . SETTINGS . " AS s SET
									u.`user_email` = '$db_email',
									s.`setting_no_ip_check` = '$noipcheck',
									s.`setting_planet_sort` = '$SetSort',
									s.`setting_planet_order` = '$SetOrder',
									s.`setting_probes_amount` = '$spio_anz',
									s.`setting_fleet_actions` = '$settings_fleetactions',
									s.`setting_galaxy_espionage` = '$settings_esp',
									s.`setting_galaxy_write` = '$settings_wri',
									s.`setting_galaxy_buddy` = '$settings_bud',
									s.`setting_galaxy_missile` = '$settings_mis',
									s.`setting_vacations_status` = '$urlaubs_modus',
									s.`setting_delete_account` = '$db_deaktjava'
									WHERE u.`user_id` = '" . $this->_current_user['user_id'] . "' AND
											s.`setting_user_id` = '" . $this->_current_user['user_id'] . "'");
            // < ------------------------------------------------- CAMBIO DE CLAVE ----------------------------------------------------- >
            if (isset($_POST['db_password']) && sha1($_POST['db_password']) == $this->_current_user['user_password']) {
                if ($_POST['newpass1'] == $_POST['newpass2']) {
                    if ($_POST['newpass1'] != '') {
                        $newpass = sha1($_POST['newpass1']);
                        parent::$db->query("UPDATE " . USERS . " SET
												`user_password` = '{$newpass}'
												WHERE `user_id` = '" . intval($this->_current_user['user_id']) . "' LIMIT 1");

                        FunctionsLib::message($this->_lang['op_password_changed'], "index.php", 1);
                    }
                }
            }
            // < --------------------------------------------- CAMBIO DE NOMBRE DE USUARIO --------------------------------------------- >
            if ($this->_current_user['user_name'] != $_POST['db_character']) {
                $query = parent::$db->queryFetch("SELECT `user_id`
                    FROM `" . USERS . "`
                    WHERE user_name = '" . parent::$db->escapeValue($_POST['db_character']) . "'");

                if (!$query) {
                    parent::$db->query("UPDATE `" . USERS . "` SET
                        `user_name` = '" . parent::$db->escapeValue($username) . "'
                        WHERE `user_id` = '" . $this->_current_user['user_id'] . "'
                        LIMIT 1");

                    session_destroy();
                    FunctionsLib::message($this->_lang['op_username_changed'], "game.php?page=options", 1);
                }
            }
            FunctionsLib::message($this->_lang['op_options_changed'], "game.php?page=options", 1);
        } else {
            $parse = $this->_lang;
            $parse['dpath'] = DPATH;

            if ($this->_current_user['setting_vacations_status']) {
                
                $opt_modev_exit = ($this->_current_user['setting_vacations_status'] == 0) ? " checked='1'/" : '';
                $vacation_until = date(FunctionsLib::readConfig('date_format_extended'), $this->_current_user['setting_vacations_until']);
                
                if ($this->_current_user['setting_vacations_until'] <= time()) {
                    
                    $parse['op_finish_vac_mode']    = '<th>' . $this->_lang['op_end_vacation_mode'] . '</th>';
                    $parse['op_finish_vac_mode']   .= '<th><input type="checkbox" name="exit_modus" ' . $opt_modev_exit . '/></th>';
                } else {

                    $parse['op_vac_mode_msg']       = '<th colspan="2">' . $this->_lang['op_vacation_mode_active_message'] . '<br> ' . $vacation_until . '</th>';
                }

                $parse['db_deaktjava']      = '';
                $parse['db_deaktjava_until']= '';
                $parse['verify']            = '';
                
                if ($this->_current_user['setting_delete_account'] > 0) {
                    
                    $parse['db_deaktjava']      = " checked='checked'";
                    $parse['db_deaktjava_until']= date(FunctionsLib::readConfig('date_format_extended'), ($this->_current_user['setting_delete_account'] + 60 * 60 * 24 * 7));
                    $parse['verify']            = '<input type="hidden" name="loeschen_am" value="' . $this->_current_user['setting_delete_account'] . '">';
                }
                
                parent::$page->display(parent::$page->parseTemplate(parent::$page->getTemplate('options/options_body_vmode'), $parse));
            } else {
                $parse['opt_lst_ord_data'] = "<option value =\"0\"" . (($this->_current_user['setting_planet_sort'] == 0) ? " selected" : "") . ">" . $this->_lang['op_sort_colonization'] . "</option>";
                $parse['opt_lst_ord_data'] .= "<option value =\"1\"" . (($this->_current_user['setting_planet_sort'] == 1) ? " selected" : "") . ">" . $this->_lang['op_sort_coords'] . "</option>";
                $parse['opt_lst_ord_data'] .= "<option value =\"2\"" . (($this->_current_user['setting_planet_sort'] == 2) ? " selected" : "") . ">" . $this->_lang['op_sort_alpha'] . "</option>";
                $parse['opt_lst_cla_data'] = "<option value =\"0\"" . (($this->_current_user['setting_planet_order'] == 0) ? " selected" : "") . ">" . $this->_lang['op_sort_asc'] . "</option>";
                $parse['opt_lst_cla_data'] .= "<option value =\"1\"" . (($this->_current_user['setting_planet_order'] == 1) ? " selected" : "") . ">" . $this->_lang['op_sort_desc'] . "</option>";
                $parse['opt_usern_data'] = $this->_current_user['user_name'];
                $parse['opt_mail1_data'] = $this->_current_user['user_email'];
                $parse['opt_mail2_data'] = $this->_current_user['user_email_permanent'];
                $parse['opt_probe_data'] = $this->_current_user['setting_probes_amount'];
                $parse['opt_fleet_data'] = $this->_current_user['setting_fleet_actions'];
                $parse['opt_noipc_data'] = ($this->_current_user['setting_no_ip_check'] == 1) ? " checked='checked'" : '';
                $parse['opt_delac_data'] = ($this->_current_user['setting_delete_account'] == 1) ? " checked='checked'" : '';
                $parse['user_settings_esp'] = ($this->_current_user['setting_galaxy_espionage'] == 1) ? " checked='checked'" : '';
                $parse['user_settings_wri'] = ($this->_current_user['setting_galaxy_write'] == 1) ? " checked='checked'" : '';
                $parse['user_settings_mis'] = ($this->_current_user['setting_galaxy_missile'] == 1) ? " checked='checked'" : '';
                $parse['user_settings_bud'] = ($this->_current_user['setting_galaxy_buddy'] == 1) ? " checked='checked'" : '';
                $parse['db_deaktjava'] = ($this->_current_user['setting_delete_account'] > 0) ? " checked='checked'" : '';

                parent::$page->display(parent::$page->parseTemplate(parent::$page->getTemplate('options/options_body'), $parse));
            }
        }
    }

    private function CheckIfIsBuilding()
    {
        $activity = parent::$db->queryFetch("SELECT (
                (
                        SELECT COUNT( fleet_id ) AS quantity
                                FROM " . FLEETS . "
                                        WHERE fleet_owner = '" . intval($this->_current_user['user_id']) . "'
                )
            +
                (
                        SELECT COUNT(planet_id) AS quantity
                                FROM " . PLANETS . "
                                        WHERE planet_user_id = '" . intval($this->_current_user['user_id']) . "' AND
                                        (planet_b_building <> 0 OR planet_b_tech <> 0 OR planet_b_hangar <> 0)
                )
            ) as total"
        );

        if ($activity['total'] > 0) {
            return true;
        } else {
            return false;
        }
    }
}

/* end of options.php */
