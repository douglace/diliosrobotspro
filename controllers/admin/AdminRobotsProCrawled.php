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



class AdminRobotsProCrawledController extends ModuleAdminController {

    /**
     * @var RobotstxtCrawled
     */
    public $object;

    public function __construct()
    {
        $this->table = 'robotstxt_crawled';
        $this->className = 'RobotstxtCrawled';
        $this->lang = false;
        $this->bootstrap = true;

        $this->deleted = false;
        $this->allow_export = true;
        $this->list_id = 'robotstxt_crawled';
        $this->identifier = 'id_robotstxt_crawled';
        $this->_defaultOrderBy = 'id_robotstxt_crawled';
        $this->_defaultOrderWay = 'ASC';
        $this->context = Context::getContext();

        $this->addRowAction('view');

        $this->_where .= ' AND a.id_shop='.$this->context->shop->id;
        parent::__construct();

       

        $this->fields_list = array(
            'id_robotstxt_crawled'=>array(
                'title' => $this->trans('ID', [], 'Modules.Diliosrobotspro.Admin'),
                'align'=>'center',
                'class'=>'fixed-width-xs'
            ),
            'user_agent'=>array(
                'title'=>$this->trans('User Agent', [], 'Modules.Diliosrobotspro.Admin'),
                'width'=>'auto'
            ),
            'boot_name'=>array(
                'title'=>$this->trans('Boot name', [], 'Modules.Diliosrobotspro.Admin'),
                'width'=>'auto'
            ),
            'crawls'=>array(
                'title'=>$this->trans('Crawl', [], 'Modules.Diliosrobotspro.Admin'),
                'width'=>'auto'
            ),
            'date_add'=>array(
                'title'=>$this->trans('Creation date', [], 'Modules.Diliosrobotspro.Admin'),
                'type'=>'datetime',
                'width'=>'auto',
            )
        );
    }


    public function renderView() {
        if(!Validate::isLoadedObject($this->object)) {
            return;
        }
        
       $this->context->smarty->assign([
            'obj' => $this->object,
        ]);
        $this->setTemplate('crawled.tpl');
        return parent::renderview();
    }

}