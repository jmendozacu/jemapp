<?php

include_once "Mage/Adminhtml/controllers/System/Convert/ProfileController.php";

class CommerceExtensions_Advancedcustomerimportexport_Adminhtml_AdvancedcustomerimportexportController extends Mage_Adminhtml_System_Convert_ProfileController
{
	
    protected $_publicActions = array('download');
	
    protected function _initProfile($idFieldName = 'id')
    {
        $this->_title($this->__('System'))
             ->_title($this->__('Import and Export'))
             ->_title($this->__('Profiles'));

        $profileId = (int) $this->getRequest()->getParam($idFieldName);
        $profile = Mage::getModel('advancedcustomerimportexport/profile');

        if ($profileId) {
            $profile->load($profileId);
            if (!$profile->getId()) {
                Mage::getSingleton('adminhtml/session')->addError(
                    $this->__('The profile you are trying to save no longer exists'));
                $this->_redirect('*/*');
                return false;
            }
        }

        Mage::register('current_convert_profile', $profile);

        return $this;
    }
    
    public function indexAction()
    {
        $this->_title($this->__('System'))
            ->_title($this->__('Import and Export'))
            ->_title($this->__('CommerceExtensions Customer Import/Export'));

        if ($this->getRequest()->getQuery('ajax'))
        {
            $this->_forward('grid');
            return;
        }
        $this->loadLayout();

        $this->_setActiveMenu('system/convert');

        $this->_addContent(
            $this->getLayout()->createBlock('advancedcustomerimportexport/system_convert_gui', 'convert_profile')
        );

        $this->_addBreadcrumb(Mage::helper('advancedcustomerimportexport')->__('Import/Export'), Mage::helper('adminhtml')->__('Import/Export'));
        $this->_addBreadcrumb(Mage::helper('advancedcustomerimportexport')->__('Profiles'), Mage::helper('adminhtml')->__('Profiles'));

        $this->renderLayout();
    }

    public function gridAction()
    {
        $this->getResponse()->setBody($this->getLayout()->createBlock('advancedcustomerimportexport/system_convert_gui_grid')->toHtml());
    }

    public function editAction()
    {
        $this->_initProfile();
        $this->loadLayout();

        $profile = Mage::registry('current_convert_profile');

        $data = Mage::getSingleton('adminhtml/session')->getConvertProfileData(true);

        if (!empty($data))
        {
            $profile->addData($data);
        }

        $this->_title($profile->getId() ? $profile->getName() : $this->__('New Profile'));

        $this->_setActiveMenu('system/convert');

        $this->_addContent(
            $this->getLayout()->createBlock('advancedcustomerimportexport/system_convert_gui_edit')
        );

        $this->_addLeft($this->getLayout()->createBlock('advancedcustomerimportexport/system_convert_gui_edit_tabs'));

        $this->renderLayout();
    }

    public function uploadAction()
    {
        $this->_initProfile();
        $profile = Mage::registry('current_convert_profile');
    }

    public function uploadPostAction()
    {
        $this->_initProfile();
        $profile = Mage::registry('current_convert_profile');
    }

    public function downloadAction()
    {
        $filename = $this->getRequest()->getParam('file');
		$filepath = Mage::getBaseDir(). DS ."var". DS ."export". DS . $filename; 
		if (!$filename || strpos($filename, '..')!==false || $filename[0]==='.')
        {
            return;
        }
		if ($filename) {
            try {
                $this->_prepareDownloadResponse($filename, array('type' => 'filename', 'value' => $filepath));

            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
		return;
    }

    protected function _isAllowed()
    {
        #return Mage::getSingleton('admin/session')->isAllowed('advancedcustomerimportexport/system/convert/gui');
  	    #return Mage::getSingleton('admin/session')->isAllowed('system/convert/advancedcustomerimportexport');
  	    return Mage::getSingleton('admin/session')->isAllowed('advancedcustomerimportexport');
    }
}
