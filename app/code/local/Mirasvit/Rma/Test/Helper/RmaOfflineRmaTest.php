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
 * @version   2.4.5
 * @build     1677
 * @copyright Copyright (C) 2017 Mirasvit (http://mirasvit.com/)
 */



class Mirasvit_Rma_Test_Helper_RmaOfflineRmaTest extends Mirasvit_Rma_Test_Helper_RmaPHPUnit
{
    /**
     * @test
     */
    public function createOrUpdateRmaFromPostTest()
    {
        $data = include $this->fixtureFolder.'admin/order_offlineorder_data_new.php';
        $data = $this->applyCustomerToData($data);

        $this->helper->setData($data);
        $this->helper->validate($data);
        $createdRma = Mage::helper('rma/rma_save_user')->createOrUpdateRmaUser($this->helper, $this->user);

        /** @var Mirasvit_Rma_Model_Rma $rma */
        $rma = Mage::getModel('rma/rma')->load($createdRma->getId());

        $this->assertNotEquals(null, $rma->getId());
        $this->assertEquals($rma->getId(), $createdRma->getId());
        $itemsCount = $rma->getItemsCollection()->count() + $rma->getOfflineItemCollection()->count();
        $this->assertEquals(5, $itemsCount);
    }

    /**
     * @test
     * @loadFixture data
     */
    public function createRmaFromPostTest()
    {
        $order = Mage::getModel('sales/order')->getCollection()->getFirstItem();
        $orderItem = Mage::getModel('sales/order_item')->getCollection()
            ->addFieldToFilter('order_id', $order->getId())->getFirstItem();
        $data = $this->getOrderFixture($order->getId(), $orderItem->getId());

        $dataProcessor = Mage::helper('rma/rma_save_postDataProcessor');
        $dataProcessor->setData($data);
        $createdRma = Mage::helper('rma/rma_save_customer')->createOrUpdateRmaCustomer(
            $dataProcessor,
            $this->customer
        );

        $rma = Mage::getModel('rma/rma')->load($createdRma->getId());

        $this->assertNotEquals(null, $rma->getId());
        $this->assertEquals($rma->getId(), $createdRma->getId());
        $itemsCount = $rma->getItemsCollection()->count() + $rma->getOfflineItemCollection()->count();
        $this->assertEquals(4, $itemsCount);
    }

    /**
     * @param int $orderId
     * @param int $itemId
     *
     * @return array
     */
    protected function getOrderFixture($orderId, $itemId)
    {
        return array(
            'form_key' => 'z2ADiowHt92YdmHn',
            'form_uid' => '93',
            'order_id' => array(
                0 => $orderId,
                1 => 'test front offline 1',
            ),
            'offline' => array(
                'order_name' => array(
                    '0' => 'test front offline 1',
                ),
            ),
            'items' => array(
                $orderId => array(
                    $itemId => array(
                        'is_return' => '1',
                        'qty_requested' => '1',
                        'order_item_id' => $itemId,
                        'name' => '',
                        'reason_id' => '3',
                        'condition_id' => '3',
                    ),
                ),
            ),
            'offline_items' => array(
                'test front offline 1' => array(
                    'item1' => array(
                        'is_return' => '1',
                        'qty_requested' => '3',
                        'name' => '',
                        'receipt_number' => 'test front offline 1',
                        'reason_id' => '1',
                        'condition_id' => '1',
                    ),
                    'item2' => array(
                        'is_return' => '1',
                        'qty_requested' => '5',
                        'name' => '',
                        'receipt_number' => 'test front offline 1',
                        'reason_id' => '3',
                        'condition_id' => '2',
                    ),
                    'item3' => array(
                        'is_return' => '1',
                        'qty_requested' => '1',
                        'name' => '',
                        'receipt_number' => 'test front offline 1',
                        'reason_id' => '3',
                        'condition_id' => '2',
                    ),
                ),
            ),
            'comment' => '',
        );
    }
}
