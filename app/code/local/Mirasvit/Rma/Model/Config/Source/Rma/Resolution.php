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



class Mirasvit_Rma_Model_Config_Source_Rma_Resolution
{

    /**
     * @return array
     */
    public function toArray()
    {
        $options = array();
        $resolutions = Mage::getModel('rma/resolution')->getCollection()
            ->addFieldToFilter('is_active', true);

        foreach ($resolutions as $resolution) {
            $options[$resolution->getCode()] = $resolution->getName();
        }

        return $options;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $result = array();
        foreach ($this->toArray() as $k => $v) {
            $result[] = array('value' => $k, 'label' => $v);
        }

        return $result;
    }


}