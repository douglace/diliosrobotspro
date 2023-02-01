<?php
/**
* 2007-2022 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2022 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

namespace Dilios\Diliosrobotspro;


use Language;
use Context;
use Tab;
use Db;

class Repository
{

    /**
     * Module
     * @param \Module $module
     */
    protected $module;

    /**
     * @param array $tabs
     */
    protected $tabs;

    /**
     * @param \Module $module
     */
    public function __construct($module)
    {
        $this->module = $module;
        $this->tabs = $this->module->tabs;
    }

    /**
     * Installer le module
     */
    public function install()
    {
        return $this->installDatabase() &&
        $this->installTab(true) &&
        $this->installFolder() &&
        $this->renameRobotFile(true) &&
        $this->registerHooks();
    }

    public function uninstall()
    {
        return $this->unInstallDatabase() &&  $this->renameRobotFile(false) && $this->installTab(false);
    }

    /**
     * Installer le dossier dans le repertoire img
     */
    public function installFolder()
    {
        return true;
    }

    

    /**
     * Installer un nouvelle onglet en admin
     */
    public function installTab($install = true)
    {
        if(empty($this->tabs)) {
            return true;
        }
        if ($install) {
            $languages = Language::getLanguages();
            
            foreach ($this->tabs as $t) {
                $exist = Tab::getIdFromClassName($t['class_name']);
                if(!$exist) { 
                    $tab = new Tab();
                    $tab->module = $this->module->name;
                    $tab->class_name = $t['class_name'];
                    $tab->id_parent = Tab::getIdFromClassName($t['parent']);

                    foreach ($languages as $language) {
                        $tab->name[$language['id_lang']] = $t['name'];
                    }
                    $tab->save();
                }
                
            }
            return true;
        } else {
            foreach ($this->tabs as $t) {
                $id = Tab::getIdFromClassName($t['class_name']);
                if ($id) {
                    $tab = new Tab($id);
                    $tab->delete();
                }
            }

            return true;
        }
    }

    /**
     * Installer la base de donné
     */
    public function installDatabase()
    {
        $sql = array();

        $sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'robotstxt` (
            `id_robotstxt` int(11) NOT NULL AUTO_INCREMENT,
            `id_shop` int(11) NOT NULL,
            `content` TEXT NOT NULL,
            `date_add` DATETIME NULL,
            `date_upd` DATETIME NULL,
            PRIMARY KEY  (`id_robotstxt`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

        $sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'robotstxt_revisions` (
            `id_robotstxt_revision` int(11) NOT NULL AUTO_INCREMENT,
            `id_robotstxt` int(11) NOT NULL,
            `id_shop` int(11) NOT NULL,
            `id_employee` int(11) NOT NULL,
            `revision` TEXT NOT NULL,
            `date_add` DATETIME NULL,
            PRIMARY KEY  (`id_robotstxt_revision`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

        $sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'robotstxt_crawled` (
            `id_robotstxt_crawled` int(11) NOT NULL AUTO_INCREMENT,
            `id_robotstxt` int(11) NOT NULL,
            `id_shop` int(11) NOT NULL,
            `user_agent` TEXT NOT NULL,
            `boot_name` TEXT NOT NULL,
            `crawls` TEXT NOT NULL,
            `date_add` DATETIME NULL,
            PRIMARY KEY  (`id_robotstxt_crawled`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';
        
        foreach ($sql as $query) {
            if (Db::getInstance()->execute($query) == false) {
                return false;
            }
        }

        return true;
    }

    /**
     * Désinstallé la base de donné
     */
    protected function unInstallDatabase()
    {
        $sql = array();
        $sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'robotstxt`';
        $sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'robotstxt_revisions`';
        $sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'robotstxt_crawled`';

        foreach ($sql as $query) {
            if (Db::getInstance()->execute($query) == false) {
                return false;
            }
        }

        return true;
    }

    /**
     * Enregistrer les hooks
     */
    public function registerHooks()
    {
        return $this->module->registerHook('header') &&
            $this->module->registerHook('ModuleRoutes') &&
            $this->module->registerHook('backOfficeHeader');
        ;
    }

    public function renameRobotFile($install = true) {
        $filename = _PS_ROOT_DIR_.'/robots.txt';
        $tofile = _PS_ROOT_DIR_.'/DILIOS-robots.txt';
        if($install) {
            if(file_exists($filename)){
                rename ($filename, $tofile);
            }
        } else {
            if(file_exists($tofile)){
                rename ($tofile, $filename);
            }
        }
        return true;
    }

    
}