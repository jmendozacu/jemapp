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



class Mirasvit_Rma_Test_Helper_NotAllowedOfflineRmaTest extends Mirasvit_Rma_Test_Helper_RmaPHPUnit
{
    protected function setUp()
    {
        parent::setUp();

        $this->mockConfigMethod(array(
            'getGeneralIsOfflineOrdersAllowed' => 0,
        ));
    }

    /**
     * We should not have errors during this test.
     *
     * @test
     */
    public function createOrUpdateRmaFromPostTest()
    {
        $data = include $this->fixtureFolder.'admin/offlineorder_data_new.php';
        $data = $this->applyCustomerToData($data);

        $this->helper->setData($data);
        $this->helper->validate($data);
        $createdRma = Mage::helper('rma/rma_save_user')->createOrUpdateRmaUser($this->helper, $this->user);

        $rma = Mage::getModel('rma/rma')->load($createdRma->getId());

        $this->assertNotEquals(null, $rma->getId());
        $this->assertEquals($rma->getId(), $createdRma->getId());
        $this->assertEquals(3, $rma->getOfflineItemCollection()->count());
    }
}