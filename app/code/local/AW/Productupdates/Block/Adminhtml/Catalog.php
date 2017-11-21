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


class AW_Productupdates_Block_Adminhtml_Catalog extends Mage_Adminhtml_Block_Catalog_Product_Edit
{

    protected function _prepareLayout()
    {
        $this->setChild(
            'send_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setData(
                array(
                    'label' => $this->__('Save & Submit Notifications'),
                    'onclick' => "productForm.submit($('product_edit_form').action+'send/1')"
                )
            )
        );

        $product = $this->getProduct();
        $store = $this->getRequest()->getParam('store', false);
        if (!$store) {
            $store = Mage::app()->getStore()->getId();
        }

        $title = trim($product->getNotificationTitle());
        if (empty($title)) {
            $title = $this->helper('productupdates')->config('configuration/notificationtitle', $store);
            $product->setNotificationTitle($title);
        }
        $text = trim($product->getNotificationText());
        if (empty($text)) {
            $text = $this->helper('productupdates')->config('configuration/notificationtext', $store);
            $product->setNotificationText($text);
        }

        return parent::_prepareLayout();
    }

    public function getSaveButtonHtml()
    {
        return $this->getChildHtml('save_button') . $this->getChildHtml('send_button');
    }

}