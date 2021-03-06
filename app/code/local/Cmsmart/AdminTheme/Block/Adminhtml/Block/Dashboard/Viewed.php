<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2013 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Adminhtml dashboard bottom tabs
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */

class Cmsmart_AdminTheme_Block_Adminhtml_Block_Dashboard_Viewed extends Mage_Adminhtml_Block_Widget_Tabs
{		
    public function __construct()
    {
        parent::__construct();
        $this->setId('grid_tab2');
        $this->setDestElementId('grid_tab_content2');
        $this->setTemplate('widget/tabshoriz1.phtml');
    }

    /**
     * Prepare layout for dashboard bottom tabs
     *
     * To load block statically:
     *     1) content must be generated
     *     2) url should not be specified
     *     3) class should not be 'ajax'
     * To load with ajax:
     *     1) do not load content
     *     2) specify url (BE CAREFUL)
     *     3) specify class 'ajax'
     *
     * @return Mage_Adminhtml_Block_Dashboard_Grids
     */
    protected function _prepareLayout()
    {
        // load other tabs with ajax
        $this->addTab('reviewed_products', array(
            'label'     => $this->__('Most Viewed Products'),
            'url'       => $this->getUrl('*/*/productsViewed', array('_current'=>true)),
            'class'     => 'ajax'
        ));


        return parent::_prepareLayout();
    }
}
