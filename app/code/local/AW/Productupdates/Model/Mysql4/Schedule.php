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
 * @package    AW_Productupdates
 * @version    2.1.0
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE.txt
 */


class AW_Productupdates_Model_Mysql4_Schedule extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {     
        $this->_init('productupdates/schedule', 'schedule_id');
    } 
    
    public function update($update, $where)
    {
        $this->_getWriteAdapter()->update($this->getMainTable(), $update, $where);
    }
    
    public function remove($where)
    {
         $this->_getWriteAdapter()->delete($this->getMainTable(), $where);
    }
    
    public function preparedQuery($sql, $bind)
    {
        $this->_getWriteAdapter()->query($sql, $bind);
    }
}