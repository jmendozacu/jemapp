<?php
/**
 * MageWorx
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MageWorx EULA that is bundled with
 * this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.mageworx.com/LICENSE-1.0.html
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@mageworx.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension
 * to newer versions in the future. If you wish to customize the extension
 * for your needs please refer to http://www.mageworx.com/ for more information
 * or send an email to sales@mageworx.com
 *
 * @category   MageWorx
 * @package    MageWorx_CustomerCredit
 * @copyright  Copyright (c) 2013 MageWorx (http://www.mageworx.com/)
 * @license    http://www.mageworx.com/LICENSE-1.0.html
 */
 
/**
 * Customer Credit extension
 *
 * @category   MageWorx
 * @package    MageWorx_CustomerCredit
 * @author     MageWorx Dev Team <dev@mageworx.com>
 */
 
class MageWorx_CustomerCredit_Adminhtml_Import_CreditController extends MageWorx_CustomerCredit_Controller_Adminhtml_Import
{
    /**
     * Index action.
      * @return void
     */
    public function indexAction() {
        $maxUploadSize = Mage::helper('importexport')->getMaxUploadSize();
        $this->_getSession()->addNotice(
            $this->__('Total size of uploadable files must not exceed %s', $maxUploadSize)
        );
        $this->_initAction()
            ->_title($this->__('Customer Credit Import'))
            ->_addBreadcrumb($this->__('Customer Credit Import'), $this->__('Customer Credit Import'));
       
        $this->renderLayout();
    }
  
    public function runGenerateAction() {
        $import = Mage::getModel('customercredit/import_credit')->run();
        
        $result = array();
        $result['text'] = $this->__('Import customer %1$s credits, processed %3$s of %2$s records (%4$s%%)...', $import->customerEmail,$import->totalRecords, $import->currentInc, round($import->currentInc/$import->totalRecords*100, 2));
        $next = $import->currentInc+1;
        if($next<=$import->totalRecords) {
            array_push($this->errors,$import->errors);
            $result['url'] = $this->getUrl('*/*/runGenerate/', array('next'=>$next));
        } else {
            $result['stop']= 1;
            $result['url'] = $this->getUrl('*/*/index/');
            $error = $this->errors;
            Mage::getSingleton('admin/session')->setCustomerCreditImportFileContent(null);
            Mage::getSingleton('admin/session')->addSuccess($error);
        }
        foreach ($this->errors as $error) {
            Mage::getSingleton('admin/session')->addError($error);
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }
}