<?php
/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This software is designed to work with Magento community edition and
 * its use on an edition other than specified is prohibited. aheadWorks does not
 * provide extension support in case of incorrect edition use.
 * =================================================================
 *
 * @category   AW
 * @package    AW_Customsmtp
 * @version    1.0.8
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE.txt
 */


class AW_Customsmtp_Model_Source_Mode extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    const OFF     = 'off';
    const ON      = 'immediately';
    const CORE    = 'core';

    /**
     * Retrieve all attribute options
     *
     * @return array
     */
    public function getAllOptions()
    {
        return $this->toOptionArray();
    }

    public function toOptionArray()
    {
        return array(
            array('value' => self::OFF,  'label' => Mage::helper('customsmtp')->__('Don\'t Send')),
            array('value' => self::ON,   'label' => Mage::helper('customsmtp')->__('Send From Custom SMTP Server')),
            array('value' => self::CORE, 'label' => Mage::helper('customsmtp')->__('Send From localhost')),
        );
    }
}