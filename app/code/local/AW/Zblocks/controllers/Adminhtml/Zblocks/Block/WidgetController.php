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
 * @package    AW_Zblocks
 * @version    2.5.4
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE.txt
 */


class AW_Zblocks_Adminhtml_Zblocks_Block_WidgetController extends Mage_Adminhtml_Controller_Action
{
    const DEFAULT_ACTION_NAME = 'chooser';
    const RESOURCE_NAME = 'admin/cms/zblocks';
    
    public function chooserAction()
    {
        $uniqId = $this->getRequest()->getParam('uniq_id');
        $pagesGrid = $this->getLayout()->createBlock('zblocks/adminhtml_cms_block_widget_chooser', '', array(
            'id' => $uniqId,
        )); 
        $this->getResponse()->setBody($pagesGrid->toHtml());
    }

    protected function _isAllowed()
    {
        $isAllowed = false;
        $actionName = $this->getRequest()->getActionName();
        if ($actionName == self::DEFAULT_ACTION_NAME) {
            $isAllowed = Mage::getSingleton('admin/session')->isAllowed(self::RESOURCE_NAME);
        }
        return $isAllowed;
    }
}
