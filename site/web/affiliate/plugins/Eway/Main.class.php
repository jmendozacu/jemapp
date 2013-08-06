<?php
/**
 *   @copyright Copyright (c) 2009 Quality Unit s.r.o.
 *   @author Juraj Simon
 *   @package PostAffiliatePro
 *   @since Version 1.0.0
 *
 *   Licensed under the Quality Unit, s.r.o. Standard End User License Agreement,
 *   Version 1.0 (the "License"); you may not use this file except in compliance
 *   with the License. You may obtain a copy of the License at
 *   http://www.qualityunit.com/licenses/license
 *
 */

/**
 * @package PostAffiliatePro plugins
 */
class Eway_Main extends Gpf_Plugins_Handler {

    /**
     * @return Eway_Main
     */
    public static function getHandlerInstance() {
        return new Eway_Main();
    }
    
    public function initSettings($context) {
        $context->addDbSetting(Eway_Config::CUSTOM_SEPARATOR, '');
        $context->addDbSetting(Eway_Config::CUSTOM_FIELD_NUMBER, '1');
        $context->addDbSetting(Eway_Config::RESPONSE_TYPE, 'http');
    }
}
?>