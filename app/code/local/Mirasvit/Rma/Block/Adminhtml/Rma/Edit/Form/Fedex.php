<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at http://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   RMA
 * @version   2.4.0
 * @build     1607
 * @copyright Copyright (C) 2016 Mirasvit (http://mirasvit.com/)
 */



class Mirasvit_Rma_Block_Adminhtml_Rma_Edit_Form_Fedex extends Mirasvit_Rma_Block_Adminhtml_Rma_Edit_Form
{
    /*
     *  Constructs block with FedEx label generation popup.
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('mst_rma/rma/edit/form/fedex.phtml');
    }


    /**
     * Returns array of currently allowed FedEx container types
     *
     * @param string $method - current FedEx shipping method.
     * @param Mage_Sales_Model_Order $order - base order for current RMA.
     *
     * @return array
     */
    public function getContainers($method, $order)
    {
        return Mage::helper('rma/fedex')->getContainers($method, $order);
    }

    /**
     * @return string
     */
    public function getShippingMethod(){
        $shippingMethod = Mage::helper('rma/fedex')->getDefaultFedexMethod();
        if ($order = $this->getOrder()) {
            $orderShipping = $order->getShippingMethod();
            if (strpos($orderShipping, 'fedex')) {
                $shippingMethod = $orderShipping;
            }
        }
        return $shippingMethod;
    }


    /**
     * @return Mage_Sales_Model_Order
     */
    public function getOrder()
    {
        if($order = $this->getRma()->getOrders()->getLastItem()) {
            if ($order && $order->getId()) {
                return $order;
            }
        }
    }
}