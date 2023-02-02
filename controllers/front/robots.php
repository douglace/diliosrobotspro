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

use Dilios\Diliosrobotspro\Classes\DiliosRobot;


class diliosrobotsproRobotsModuleFrontController extends ModuleFrontController {
    public function init() {
        parent::init();
        $this->saveCrawler();
        $content = DiliosRobot::getRobotsContentByShop($this->context->shop->id);
        header('Content-Type:text/html; charset=UTF-8');
        echo $content;
        exit;
    }

    public function saveCrawler(){
        $crl = new RobotstxtCrawled();
        $rb = Robotstxt::getByIdShop($this->context->shop->id);
        if(Validate::isLoadedObject($rb)) {
            $crl->id_robotstxt = $rb->id;
            $crl->id_shop = $this->context->shop->id;
            $crl->user_agent = $_SERVER['HTTP_USER_AGENT'];
            $crl->boot_name = $_SERVER['HTTP_USER_AGENT'];
            $crl->crawls = $_SERVER['HTTP_USER_AGENT'];
            $crl->save();
        }
        
    }
}