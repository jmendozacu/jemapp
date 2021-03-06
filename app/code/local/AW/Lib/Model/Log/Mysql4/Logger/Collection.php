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
 * @package    AW_Storecredit
 * @version    1.0.5
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE.txt
 */


class AW_Lib_Model_Log_Mysql4_Logger_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('aw_lib/log_logger');
    }
    
     /**
     * Add filter to find records older than specified date
     *
     * @param Zend_Date $Date
     *
     * @return AW_Lib_Model_Log_Mysql4_Logger_Collection
     */
    public function addOlderThanFilter(Zend_Date $Date)
    {
        $this->getSelect()->where('date<?', $Date->toString(Varien_Date::DATETIME_INTERNAL_FORMAT));
        return $this;
    }
}