<?php
/**
* 2007-2023 PrestaShop
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
*  @copyright 2007-2023 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

if (file_exists(_PS_MODULE_DIR_. 'diliosrobotspro/vendor/autoload.php')) {
    require_once _PS_MODULE_DIR_.  'diliosrobotspro/vendor/autoload.php';
}


class Diliosrobotspro extends Module
{
    protected $config_form = false;

    /**
     * @param Dilios\Diliosrobotspro\Repository $repository
     */
    protected $repository;

    /**
     * @param array $tabs
     */
    public $tabs = [];

    public function __construct()
    {
        $this->name = 'diliosrobotspro';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Dilios';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        $this->tabs = array(
            array(
                'name'=> $this->trans('Robots pro', array(), 'Modules.Diliosrobotspro.Admin'),
                'class_name'=>'AdminParentRobotsPro',
                'parent'=>'AdminParentModulesSf',
            ),
            array(
                'name'=> $this->trans('Revision', array(), 'Modules.Diliosrobotspro.Admin'),
                'class_name'=>'AdminRobotsProRevision',
                'parent'=>'AdminParentRobotsPro',
            ),
            array(
                'name'=> $this->trans('Crawled', array(), 'Modules.Diliosrobotspro.Admin'),
                'class_name'=>'AdminRobotsProCrawled',
                'parent'=>'AdminParentRobotsPro',
            )
        );

        $this->repository = new Dilios\Diliosrobotspro\Repository($this); 
        parent::__construct();
        
        $this->displayName = $this->l('Robots generators');
        $this->description = $this->l('Générateur de robot txt');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        return parent::install() && $this->repository->install();
    }

    public function uninstall()
    {
        return parent::uninstall() && $this->repository->uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        
        $process = $this->postProcess();
        
        $this->context->smarty->assign(
            array(
                'module_dir' => $this->_path,
                'config_form' => $this->config_form,
                'home_global' => $this->renderFormGlobal(),
            )
        );

        return $process.$this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

    }

    public function renderFormGlobal(){
        $form = new Dilios\Diliosrobotspro\Utils\FormForm($this);
        $form->setShowToolbar(false)
            ->setTable($this->table)
            ->setModule($this)
            ->setDefaultFromLanguage($this->context->language->id)
            ->setAllowEmployeFromLang(Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0))
            ->setIdentifier($this->identifier)
            ->setSubmitAction('submitDILIOSROBOTSPROModuleGlobal')
            ->setCurrentIndex($this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name)
            ->setToken(Tools::getAdminTokenLite('AdminModules'))
            ->setTplVar([
                'fields_value' => $this->getConfigGlobalFormValues(), /* Add values for your inputs */
                'languages' => $this->context->controller->getLanguages(),
                'id_language' => $this->context->language->id,
            ])
            ->addField(
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Robots'),
                    'name' => 'DILIOSROBOTSPRO_SHOP',
                    'col' => 8,
                    'class' => "robots-textarea",
                )
            )
            ->setLegend([
                'title' => $this->l('Global Settings'),
                'icon' => 'icon-cogs',
            ])->setSubmit([
                'title' => $this->l('Save'),
            ])
        ;

        return $form->make();
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigGlobalFormValues()
    {
        $id_shop = $this->context->shop->id;
        $data =  array(
            'DILIOSROBOTSPRO_SHOP' => Configuration::get('DILIOSROBOTSPRO_SHOP', null, null, $id_shop),
        );
        
        return $data;
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $multilang = array();
        $form_values = array();
        $specific_testimony = null;

        $int_fields = [
        ];

        if(Tools::isSubmit('submitDILIOSROBOTSPROModuleGlobal')) {
            $form_values = $this->getConfigGlobalFormValues();
            $this->config_form = 'config-global';
            $this->saveToFile();
        } else {
            return false;
        }

        $id_shop = $this->context->shop->id;
        if(!empty($form_values)) {
            try{
                $values = [];
                if(!empty($multilang)) {
                    $languages = Language::getLanguages();
                    foreach($multilang as $k) {
                        foreach($languages as $l) {
                            $values[$k][$l['id_lang']] = Tools::getValue($k."_".$l['id_lang']);
                        }
                    }
                }
                foreach (array_keys($form_values) as $key) {
                    if(in_array($key, $multilang)) {
                        if(isset($values[$key])) {
                            Configuration::updateValue($key, $values[$key], false, false, $id_shop);
                        }
                    } else {
                        if($key == $specific_testimony.'[]') {
                            $lists = Tools::getValue($specific_testimony);
                            if($lists && !empty($lists)) {
                                Configuration::updateValue($specific_testimony, implode(',', $lists), false, false, $id_shop);
                            }else {
                                Configuration::updateValue($specific_testimony, "", false, false, $id_shop);
                            }
                        } else {
                            if(in_array($key, $int_fields)){
                                $v = Tools::getValue($key);
                                if(!Validate::isInt($v)) {
                                    $this->_errors[] = $this->l('Some fields are not valid') ;
                                }
                                Configuration::updateValue($key, (int)$v, false, false, $id_shop);
                            } else {
                                Configuration::updateValue($key, Tools::getValue($key), false, false, $id_shop);
                            }
                            
                        }
                    }
                    
                    
                }
                
                if(!empty($this->_errors)) {
                    return $this->displayError(current($this->_errors));
                }
                return $this->displayConfirmation($this->l('Configuration save with success'));
            }catch(Exception $e) {
                return $this->displayError($this->l('Something when wrong'));
            }
        }
        
    }

    public function saveToFile(){
        $robotsValues = Tools::getValue('DILIOSROBOTSPRO_SHOP');
        $id_shop = $this->context->shop->id;

        $rb = Robotstxt::getByIdShop($id_shop);
        $rb->content = $robotsValues;
        $rb->id_shop = $id_shop;

        if($rb->save()){
            $rbr = new RobotstxtRevision();
            $rbr->id_robotstxt = $rb->id;
            $rbr->id_shop = $id_shop;
            $rbr->revision = $robotsValues;
            $rbr->id_employee = $this->context->employee->id;
            $rbr->save();

            @file_put_contents(
                Dilios\Diliosrobotspro\Classes\DiliosRobot::getRobotsFileByShop($id_shop), 
                $robotsValues
            );
        }
    }

    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookDisplayBackOfficeHeader()
    {
        if (Tools::getValue('configure') == $this->name) {
            $store_url = $this->context->link->getBaseLink();
            Media::addJsDef([
                'add_site_map_name' => $this->l('Add sitemap'),
                'site_map_url' => $store_url.$this->context->shop->id.'_index_sitemap.xml',
            ]);
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    public function hookModuleRoutes() {
        return array(
            'module-diliosrobotspro-robots' => array( //Prestashop will use this pattern to compare addresses: module-{module_name}-{controller_name}
                'controller' => 'robots', //module controller name
                'rule' => 'robots.txt', //the desired page URL
                'keywords' => array(),
                'params' => array(
                    'fc' => 'module',
                    'module' => 'diliosrobotspro', //module name
                )
            ),
        );
    }
}
