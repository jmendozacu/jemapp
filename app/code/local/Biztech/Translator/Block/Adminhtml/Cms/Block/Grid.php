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
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright  Copyright (c) 2006-2014 X.commerce, Inc. (http://www.magento.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Adminhtml cms blocks grid
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Biztech_Translator_Block_Adminhtml_Cms_Block_Grid extends Mage_Adminhtml_Block_Cms_Block_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('cmsBlockGrid');
        $this->setDefaultSort('block_identifier');
        $this->setDefaultDir('ASC');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('cms/block')->getCollection();
        /* @var $collection Mage_Cms_Model_Mysql4_Block_Collection */
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $baseUrl = $this->getUrl();

        $this->addColumn('title', array(
            'header'    => Mage::helper('cms')->__('Title'),
            'align'     => 'left',
            'index'     => 'title',
        ));

        $this->addColumn('identifier', array(
            'header'    => Mage::helper('cms')->__('Identifier'),
            'align'     => 'left',
            'index'     => 'identifier'
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header'        => Mage::helper('cms')->__('Store View'),
                'index'         => 'store_id',
                'type'          => 'store',
                'store_all'     => true,
                'store_view'    => true,
                'sortable'      => false,
                'filter_condition_callback'
                                => array($this, '_filterStoreCondition'),
            ));
        }

        $this->addColumn('is_active', array(
            'header'    => Mage::helper('cms')->__('Status'),
            'index'     => 'is_active',
            'type'      => 'options',
            'options'   => array(
                0 => Mage::helper('cms')->__('Disabled'),
                1 => Mage::helper('cms')->__('Enabled')
            ),
        ));

        $this->addColumn('creation_time', array(
            'header'    => Mage::helper('cms')->__('Date Created'),
            'index'     => 'creation_time',
            'type'      => 'datetime',
        ));

        $this->addColumn('update_time', array(
            'header'    => Mage::helper('cms')->__('Last Modified'),
            'index'     => 'update_time',
            'type'      => 'datetime',
        ));

        return parent::_prepareColumns();
    }

    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }

        $this->getCollection()->addStoreFilter($value);
    }
    protected function _prepareMassaction()
    {
        $storeId = Mage::app()->getRequest()->getParam('store', 0);
        $configLang = Mage::getStoreConfig('translator/translator_general/languages', Mage::app()->getRequest()->getParam('store', 0));

        $language = Mage::helper('translator')->getLanguage($storeId);

        $localeCode = Mage::getStoreConfig('general/locale/code', $storeId);
        $lang = '';

        $arr = explode('_',$localeCode);
        $language = $arr[0];
        if(in_array($language, array_keys(Mage::helper('translator/languages')->getLanguages()))){
            $lang = $language;
        }
        else{
            $lang['message'] = Mage::helper('translator')->__('Select language for this store in System->Configuration->Translator');
        }


        $fullNameLanguage = Mage::helper('translator')->getLanguageFullNameByCode($language, $storeId);
        $languages = Mage::helper('translator/languages')->getLanguages(true);
        if($fullNameLanguage){
            $languages = array($lang => $fullNameLanguage) + $languages;
        }
        else{
            $languages = array('' => Mage::helper('translator')->__('Select language or adjust in config')) + $languages;
        }
        $this->setMassactionIdField('cms_block_id');
        $this->getMassactionBlock()->setFormFieldName('block_id');

        if(Mage::helper('translator')->isEnable()){
            $this->getMassactionBlock()->addItem('translator', array(
                'label'    => Mage::helper('translator')->__('Translate Selected CMS Block'),
                'url'      => $this->getUrl('adminhtml/translator/masstranslatecmsblock'),
                'additional' => array(
                    'store_id' => array(
                        'name' => 'store_id',
                        'type' => 'hidden',
                        'class' => 'required-entry',
                        'value' => $storeId
                    ),
                    'lang_to' => array(
                        'name' => 'lang_to',
                        'type' => 'select',
                        'label' => Mage::helper('catalog')->__('Translate To'),
                        'values' => $languages
                    )
                )

            ));
        }
        return $this;
    }

    /**
     * Row click url
     *
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('block_id' => $row->getId()));
    }

}
