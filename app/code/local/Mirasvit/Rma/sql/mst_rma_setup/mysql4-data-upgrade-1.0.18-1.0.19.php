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



$rmaItems = Mage::getModel('rma/item')->getCollection();

/** @var Mirasvit_Rma_Model_Item $item */
foreach ($rmaItems as $item) {
    if ($item->getOrderId()) {
        continue;
    }
    $orderId = $item->getOrderItem()->getOrderId();
    $item->setOrderId($orderId)->save();
}