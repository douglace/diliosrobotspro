<?php

namespace Dilios\Diliosrobotspro\Classes;

use Context;

class DiliosRobot {

    public static function getRobotsFileByShop($id_shop = null) {
        $id_shop = $id_shop ? $id_shop : Context::getContext()->shop->id;
        return _PS_MODULE_DIR_.'diliosrobotspro/robots/robots_'.$id_shop.'.txt';
    }

    public static function getRobotsContentByShop($id_shop = null) {
        return @file_get_contents(self::getRobotsFileByShop($id_shop));
    }
}