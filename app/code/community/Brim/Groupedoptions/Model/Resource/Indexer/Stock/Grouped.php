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
 * @package     Mage_Catalog
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
/**
 * @category    Brim
 * @package     Brim_Groupedoptions
 * @copyright   Copyright (c) 2011-2012 Brim LLC. (http://www.brimllc.com)
 */
class Brim_Groupedoptions_Model_Resource_Indexer_Stock_Grouped
    extends Mage_CatalogInventory_Model_Mysql4_Indexer_Stock_Grouped
    // extends Mage_CatalogInventory_Model_Resource_Indexer_Stock_Grouped
{

	/**
	 * Overriding this method to remove the 'required option' constraint on the stock status. See line 60
	 * for the original clause.
	 * 
	 * (non-PHPdoc)
	 * @see app/code/core/Mage/CatalogInventory/Model/Mysql4/Indexer/Stock/Mage_CatalogInventory_Model_Mysql4_Indexer_Stock_Grouped#_getStockStatusSelect($entityIds, $usePrimaryTable)
	 */
	protected function _getStockStatusSelect($entityIds = null, $usePrimaryTable = false)
	{
		$adapter  = $this->_getWriteAdapter();
		$idxTable = $usePrimaryTable ? $this->getMainTable() : $this->getIdxTable();
		$select   = $adapter->select()
		->from(array('e' => $this->getTable('catalog/product')), array('entity_id'));
		$this->_addWebsiteJoinToSelect($select, true);
		$this->_addProductWebsiteJoinToSelect($select, 'cw.website_id', 'e.entity_id');
		$select->columns('cw.website_id')
		->join(
		array('cis' => $this->getTable('cataloginventory/stock')),
                '',
		array('stock_id'))
		->joinLeft(
		array('cisi' => $this->getTable('cataloginventory/stock_item')),
                'cisi.stock_id = cis.stock_id AND cisi.product_id = e.entity_id',
		array())
		->joinLeft(
		array('l' => $this->getTable('catalog/product_link')),
                'e.entity_id = l.product_id AND l.link_type_id=' . Mage_Catalog_Model_Product_Link::LINK_TYPE_GROUPED,
		array())
		->joinLeft(
		array('le' => $this->getTable('catalog/product')),
                'le.entity_id = l.linked_product_id',
		array())
		->joinLeft(
		array('i' => $idxTable),
                'i.product_id = l.linked_product_id AND cw.website_id = i.website_id AND cis.stock_id = i.stock_id',
		array())
		->columns(array('qty' => new Zend_Db_Expr('0')))
		->where('cw.website_id != 0')
		->where('e.type_id = ?', $this->getTypeId())
		->group(array('e.entity_id', 'cw.website_id', 'cis.stock_id'));

		// add limitation of status
		$psExpr = $this->_addAttributeToSelect($select, 'status', 'e.entity_id', 'cs.store_id');
		$psCond = $adapter->quoteInto($psExpr . '=?', Mage_Catalog_Model_Product_Status::STATUS_ENABLED);

		if ($this->_isManageStock()) {
			$statusExpr = new Zend_Db_Expr('IF(cisi.use_config_manage_stock = 0 AND cisi.manage_stock = 0,'
			. ' 1, cisi.is_in_stock)');
		} else {
			$statusExpr = new Zend_Db_Expr('IF(cisi.use_config_manage_stock = 0 AND cisi.manage_stock = 1,'
			. 'cisi.is_in_stock, 1)');
		}

        /* BEGIN Brim Grouped-Options Customizations */
		//$stockStatusExpr = new Zend_Db_Expr("LEAST(MAX(IF({$psCond} AND le.required_options = 0, i.stock_status, 0))" . ", {$statusExpr})");

		$stockStatusExpr = new Zend_Db_Expr("LEAST(MAX(IF({$psCond}, i.stock_status, 0))"
		. ", {$statusExpr})");
        /* END Brim Grouped-Options Customizations */

		$select->columns(array(
            'status' => $stockStatusExpr
		));

		if (!is_null($entityIds)) {
			$select->where('e.entity_id IN(?)', $entityIds);
		}

		return $select;
	}
}