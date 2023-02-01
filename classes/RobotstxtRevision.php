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

class RobotstxtRevision extends ObjectModel
{
    /**
     * @param int $id_robotstxt
     */
    public $id_robotstxt;

    /**
     * @param int $id_shop
     */
    public $id_shop;

    /**
     * @param int $id_employee
     */
    public $id_employee;

    /**
     * @param string $revision
     */
    public $revision;

    /**
     * @param string $date_add
     */
    public $date_add;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'robotstxt_revisions',
        'primary' => 'id_robotstxt_revision',
        'multilang' => false,
        'fields' => array(
            'id_robotstxt' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isInt',
                'required' => true,
            ),
            'id_shop' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isInt',
                'required' => true,
            ),
            'id_employee' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isInt',
                'required' => true,
            ),
            'revision' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isString',
                'required' => true,
            ),
            'date_add' => array(
                'type' => self::TYPE_DATE,
                'validate' => 'isDate'
            )
        ),
    );
}