#!/bin/bash
# Patch apllying tool template
# v0.1.2
# (c) Copyright 2013. Magento Inc.
#
# DO NOT CHANGE ANY LINE IN THIS FILE.

# 1. Check required system tools
_check_installed_tools() {
    local missed=""

    until [ -z "$1" ]; do
        type -t $1 >/dev/null 2>/dev/null
        if (( $? != 0 )); then
            missed="$missed $1"
        fi
        shift
    done

    echo $missed
}

REQUIRED_UTILS='sed patch'
MISSED_REQUIRED_TOOLS=`_check_installed_tools $REQUIRED_UTILS`
if (( `echo $MISSED_REQUIRED_TOOLS | wc -w` > 0 ));
then
    echo -e "Error! Some required system tools, that are utilized in this sh script, are not installed:\nTool(s) \"$MISSED_REQUIRED_TOOLS\" is(are) missed, please install it(them)."
    exit 1
fi

# 2. Determine bin path for system tools
CAT_BIN=`which cat`
PATCH_BIN=`which patch`
SED_BIN=`which sed`
PWD_BIN=`which pwd`
BASENAME_BIN=`which basename`

BASE_NAME=`$BASENAME_BIN "$0"`

# 3. Help menu
if [ "$1" = "-?" -o "$1" = "-h" -o "$1" = "--help" ]
then
    $CAT_BIN << EOFH
Usage: sh $BASE_NAME [--help] [-R|--revert] [--list]
Apply embedded patch.

-R, --revert    Revert previously applied embedded patch
--list          Show list of applied patches
--help          Show this help message
EOFH
    exit 0
fi

# 4. Get "revert" flag and "list applied patches" flag
REVERT_FLAG=
SHOW_APPLIED_LIST=0
if [ "$1" = "-R" -o "$1" = "--revert" ]
then
    REVERT_FLAG=-R
fi
if [ "$1" = "--list" ]
then
    SHOW_APPLIED_LIST=1
fi

# 5. File pathes
CURRENT_DIR=`$PWD_BIN`/
APP_ETC_DIR=`echo "$CURRENT_DIR""app/etc/"`
APPLIED_PATCHES_LIST_FILE=`echo "$APP_ETC_DIR""applied.patches.list"`

# 6. Show applied patches list if requested
if [ "$SHOW_APPLIED_LIST" -eq 1 ] ; then
    echo -e "Applied/reverted patches list:"
    if [ -e "$APPLIED_PATCHES_LIST_FILE" ]
    then
        if [ ! -r "$APPLIED_PATCHES_LIST_FILE" ]
        then
            echo "ERROR: \"$APPLIED_PATCHES_LIST_FILE\" must be readable so applied patches list can be shown."
            exit 1
        else
            $SED_BIN -n "/SUP-\|SUPEE-/p" $APPLIED_PATCHES_LIST_FILE
        fi
    else
        echo "<empty>"
    fi
    exit 0
fi

# 7. Check applied patches track file and its directory
_check_files() {
    if [ ! -e "$APP_ETC_DIR" ]
    then
        echo "ERROR: \"$APP_ETC_DIR\" must exist for proper tool work."
        exit 1
    fi

    if [ ! -w "$APP_ETC_DIR" ]
    then
        echo "ERROR: \"$APP_ETC_DIR\" must be writeable for proper tool work."
        exit 1
    fi

    if [ -e "$APPLIED_PATCHES_LIST_FILE" ]
    then
        if [ ! -w "$APPLIED_PATCHES_LIST_FILE" ]
        then
            echo "ERROR: \"$APPLIED_PATCHES_LIST_FILE\" must be writeable for proper tool work."
            exit 1
        fi
    fi
}

_check_files

# 8. Apply/revert patch
# Note: there is no need to check files permissions for files to be patched.
# "patch" tool will not modify any file if there is not enough permissions for all files to be modified.
# Get start points for additional information and patch data
SKIP_LINES=$((`$SED_BIN -n "/^__PATCHFILE_FOLLOWS__$/=" "$CURRENT_DIR""$BASE_NAME"` + 1))
ADDITIONAL_INFO_LINE=$(($SKIP_LINES - 3))p

_apply_revert_patch() {
    DRY_RUN_FLAG=
    if [ "$1" = "dry-run" ]
    then
        DRY_RUN_FLAG=" --dry-run"
        echo "Checking if patch can be applied/reverted successfully..."
    fi
    PATCH_APPLY_REVERT_RESULT=`$SED_BIN -e '1,/^__PATCHFILE_FOLLOWS__$/d' "$CURRENT_DIR""$BASE_NAME" | $PATCH_BIN $DRY_RUN_FLAG $REVERT_FLAG -p0`
    PATCH_APPLY_REVERT_STATUS=$?
    if [ $PATCH_APPLY_REVERT_STATUS -eq 1 ] ; then
        echo -e "ERROR: Patch can't be applied/reverted successfully.\n\n$PATCH_APPLY_REVERT_RESULT"
        exit 1
    fi
    if [ $PATCH_APPLY_REVERT_STATUS -eq 2 ] ; then
        echo -e "ERROR: Patch can't be applied/reverted successfully."
        exit 2
    fi
}

REVERTED_PATCH_MARK=
if [ -n "$REVERT_FLAG" ]
then
    REVERTED_PATCH_MARK=" | REVERTED"
fi

_apply_revert_patch dry-run
_apply_revert_patch

# 9. Track patch applying result
echo "Patch was applied/reverted successfully."
ADDITIONAL_INFO=`$SED_BIN -n ""$ADDITIONAL_INFO_LINE"" "$CURRENT_DIR""$BASE_NAME"`
APPLIED_REVERTED_ON_DATE=`date -u +"%F %T UTC"`
APPLIED_REVERTED_PATCH_INFO=`echo -n "$APPLIED_REVERTED_ON_DATE"" | ""$ADDITIONAL_INFO""$REVERTED_PATCH_MARK"`
echo -e "$APPLIED_REVERTED_PATCH_INFO\n$PATCH_APPLY_REVERT_RESULT\n\n" >> "$APPLIED_PATCHES_LIST_FILE"

exit 0


SUPEE-10975_CE_v1.9.3.10 | CE_1.9.3.10 | v1 | 91395a467666d7381ff4f9629f52a1d406eee65a | Thu Nov 8 13:45:47 2018 +0200 | ce-1.9.3.10-dev

__PATCHFILE_FOLLOWS__
diff --git app/code/core/Mage/Adminhtml/Block/Customer/Group/Edit.php app/code/core/Mage/Adminhtml/Block/Customer/Group/Edit.php
index ddef716f27f..8d3c526c280 100644
--- app/code/core/Mage/Adminhtml/Block/Customer/Group/Edit.php
+++ app/code/core/Mage/Adminhtml/Block/Customer/Group/Edit.php
@@ -49,6 +49,18 @@ class Mage_Adminhtml_Block_Customer_Group_Edit extends Mage_Adminhtml_Block_Widg
         }
     }
 
+    public function getDeleteUrl()
+    {
+        if (!Mage::getSingleton('adminhtml/url')->useSecretKey()) {
+            return $this->getUrl('*/*/delete', array(
+                $this->_objectId => $this->getRequest()->getParam($this->_objectId),
+                'form_key' => Mage::getSingleton('core/session')->getFormKey()
+            ));
+        } else {
+            parent::getDeleteUrl();
+        }
+    }
+
     public function getHeaderText()
     {
         if(!is_null(Mage::registry('current_group')->getId())) {
diff --git app/code/core/Mage/Adminhtml/Block/Newsletter/Template/Edit.php app/code/core/Mage/Adminhtml/Block/Newsletter/Template/Edit.php
index 86e5ceb346a..8283aded905 100644
--- app/code/core/Mage/Adminhtml/Block/Newsletter/Template/Edit.php
+++ app/code/core/Mage/Adminhtml/Block/Newsletter/Template/Edit.php
@@ -275,7 +275,7 @@ class Mage_Adminhtml_Block_Newsletter_Template_Edit extends Mage_Adminhtml_Block
      */
     public function getJsTemplateName()
     {
-        return addcslashes($this->getModel()->getTemplateCode(), "\"\r\n\\");
+        return addcslashes($this->escapeHtml($this->getModel()->getTemplateCode()), "\"\r\n\\");
     }
 
     /**
diff --git app/code/core/Mage/Adminhtml/controllers/Cms/BlockController.php app/code/core/Mage/Adminhtml/controllers/Cms/BlockController.php
index f10c2c1c7d6..fe56e01845c 100644
--- app/code/core/Mage/Adminhtml/controllers/Cms/BlockController.php
+++ app/code/core/Mage/Adminhtml/controllers/Cms/BlockController.php
@@ -34,6 +34,17 @@
  */
 class Mage_Adminhtml_Cms_BlockController extends Mage_Adminhtml_Controller_Action
 {
+    /**
+     * Controller predispatch method
+     *
+     * @return Mage_Adminhtml_Controller_Action
+     */
+    public function preDispatch()
+    {
+        $this->_setForcedFormKeyActions('delete');
+        return parent::preDispatch();
+    }
+
     /**
      * Init actions
      *
diff --git app/code/core/Mage/Adminhtml/controllers/Customer/GroupController.php app/code/core/Mage/Adminhtml/controllers/Customer/GroupController.php
index f94fd360a2a..7d9177ea381 100644
--- app/code/core/Mage/Adminhtml/controllers/Customer/GroupController.php
+++ app/code/core/Mage/Adminhtml/controllers/Customer/GroupController.php
@@ -33,6 +33,17 @@
  */
 class Mage_Adminhtml_Customer_GroupController extends Mage_Adminhtml_Controller_Action
 {
+    /**
+     * Controller predispatch method
+     *
+     * @return Mage_Adminhtml_Controller_Action
+     */
+    public function preDispatch()
+    {
+        $this->_setForcedFormKeyActions('delete');
+        return parent::preDispatch();
+    }
+
     protected function _initGroup()
     {
         $this->_title($this->__('Customers'))->_title($this->__('Customer Groups'));
diff --git app/code/core/Mage/Adminhtml/controllers/SitemapController.php app/code/core/Mage/Adminhtml/controllers/SitemapController.php
index 8b817fc1f43..4bb55c8dfa4 100644
--- app/code/core/Mage/Adminhtml/controllers/SitemapController.php
+++ app/code/core/Mage/Adminhtml/controllers/SitemapController.php
@@ -33,6 +33,17 @@
  */
 class Mage_Adminhtml_SitemapController extends  Mage_Adminhtml_Controller_Action
 {
+    /**
+     * Controller predispatch method
+     *
+     * @return Mage_Adminhtml_Controller_Action
+     */
+    public function preDispatch()
+    {
+        $this->_setForcedFormKeyActions('delete');
+        return parent::preDispatch();
+    }
+
     /**
      * Init actions
      *
diff --git app/code/core/Mage/Adminhtml/controllers/System/BackupController.php app/code/core/Mage/Adminhtml/controllers/System/BackupController.php
index 5e05f4066f3..0f2dad07a7f 100644
--- app/code/core/Mage/Adminhtml/controllers/System/BackupController.php
+++ app/code/core/Mage/Adminhtml/controllers/System/BackupController.php
@@ -369,7 +369,9 @@ class Mage_Adminhtml_System_BackupController extends Mage_Adminhtml_Controller_A
      */
     protected function _isAllowed()
     {
-        return Mage::getSingleton('admin/session')->isAllowed('system/tools/backup' );
+        return Mage::getSingleton('admin/session')->isAllowed('system/tools/backup')
+            && Mage::helper('core')->isModuleEnabled('Mage_Backup')
+            && !Mage::getStoreConfigFlag('advanced/modules_disable_output/Mage_Backup');
     }
 
     /**
diff --git app/code/core/Mage/Captcha/Model/Observer.php app/code/core/Mage/Captcha/Model/Observer.php
index 4b3e041b8c0..a42db5cc534 100644
--- app/code/core/Mage/Captcha/Model/Observer.php
+++ app/code/core/Mage/Captcha/Model/Observer.php
@@ -289,4 +289,57 @@ class Mage_Captcha_Model_Observer
         $captchaParams = $request->getPost(Mage_Captcha_Helper_Data::INPUT_NAME_FIELD_VALUE);
         return $captchaParams[$formId];
     }
+
+    /**
+     * Check Captcha On Share Wishlist Page
+     *
+     * @param Varien_Event_Observer $observer
+     * @return Mage_Captcha_Model_Observer
+     */
+    public function checkWishlistSharing($observer)
+    {
+        $formId = 'wishlist_sharing';
+        $captchaModel = Mage::helper('captcha')->getCaptcha($formId);
+        if ($captchaModel->isRequired()) {
+            $controller = $observer->getControllerAction();
+            $request = $controller->getRequest();
+            if (!$captchaModel->isCorrect($this->_getCaptchaString($request, $formId))) {
+                Mage::getSingleton('wishlist/session')->addError(Mage::helper('captcha')->__('Incorrect CAPTCHA.'));
+                $controller->setFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
+                Mage::getSingleton('wishlist/session')->setSharingForm($request->getPost());
+                $wishlistId = (int)$request->getParam('wishlist_id');
+                $controller->getResponse()
+                    ->setRedirect(Mage::getUrl('wishlist/index/share/wishlist_id/' . $wishlistId));
+            }
+        }
+        return $this;
+    }
+
+    /**
+     * Check Captcha On Email Product To A Friend Page
+     *
+     * @param Varien_Event_Observer $observer
+     * @return Mage_Captcha_Model_Observer
+     */
+    public function checkSendfriendSend($observer)
+    {
+        $formId = 'sendfriend_send';
+        $captchaModel = Mage::helper('captcha')->getCaptcha($formId);
+        if ($captchaModel->isRequired()) {
+            $controller = $observer->getControllerAction();
+            $request = $controller->getRequest();
+            if (!$captchaModel->isCorrect($this->_getCaptchaString($request, $formId))) {
+                Mage::getSingleton('catalog/session')->addError(Mage::helper('captcha')->__('Incorrect CAPTCHA.'));
+                $controller->setFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
+                Mage::getSingleton('catalog/session')->setFormData($request->getPost());
+                $id = (int)$request->getParam('id');
+                $catId = $request->getParam('cat_id');
+                if (null !== $catId) {
+                    $id .= '/cat_id/' . (int)$catId;
+                }
+                $controller->getResponse()->setRedirect(Mage::getUrl('*/*/send/id/' . $id));
+            }
+        }
+        return $this;
+    }
 }
diff --git app/code/core/Mage/Captcha/Model/Zend.php app/code/core/Mage/Captcha/Model/Zend.php
index 84a0849aeba..02dedfcbe48 100644
--- app/code/core/Mage/Captcha/Model/Zend.php
+++ app/code/core/Mage/Captcha/Model/Zend.php
@@ -116,7 +116,11 @@ class Mage_Captcha_Model_Zend extends Zend_Captcha_Image implements Mage_Captcha
      */
     public function isRequired($login = null)
     {
-        if ($this->_isUserAuth() || !$this->_isEnabled() || !in_array($this->_formId, $this->_getTargetForms())) {
+        $nonAuthForms = array('wishlist_sharing', 'sendfriend_send');
+
+        if ((!in_array($this->_formId, $nonAuthForms) && $this->_isUserAuth())
+            || !$this->_isEnabled() || !in_array($this->_formId, $this->_getTargetForms())
+        ) {
             return false;
         }
 
diff --git app/code/core/Mage/Captcha/etc/config.xml app/code/core/Mage/Captcha/etc/config.xml
index 33392b4db3a..8c2ed500e29 100644
--- app/code/core/Mage/Captcha/etc/config.xml
+++ app/code/core/Mage/Captcha/etc/config.xml
@@ -78,6 +78,14 @@
                     </captcha>
                 </observers>
             </controller_action_predispatch_customer_account_createpost>
+            <controller_action_predispatch_wishlist_index_send>
+                <observers>
+                    <captcha>
+                        <class>captcha/observer</class>
+                        <method>checkWishlistSharing</method>
+                    </captcha>
+                </observers>
+            </controller_action_predispatch_wishlist_index_send>
             <controller_action_predispatch_adminhtml_index_forgotpassword>
                 <observers>
                     <captcha>
@@ -122,6 +130,14 @@
                     </captcha_reset_attempt>
                 </observers>
             </admin_session_user_login_success>
+            <controller_action_predispatch_sendfriend_product_sendmail>
+                <observers>
+                    <captcha>
+                        <class>captcha/observer</class>
+                        <method>checkSendfriendSend</method>
+                    </captcha>
+                </observers>
+            </controller_action_predispatch_sendfriend_product_sendmail>
         </events>
     </global>
     <frontend>
@@ -206,6 +222,8 @@
                     <user_forgotpassword>1</user_forgotpassword>
                     <guest_checkout>1</guest_checkout>
                     <register_during_checkout>1</register_during_checkout>
+                    <wishlist_sharing>1</wishlist_sharing>
+                    <sendfriend_send>1</sendfriend_send>
                 </always_for>
             </captcha>
         </customer>
@@ -233,6 +251,12 @@
                     <register_during_checkout>
                         <label>Register during Checkout</label>
                     </register_during_checkout>
+                    <wishlist_sharing>
+                        <label>Share Wishlist</label>
+                    </wishlist_sharing>
+                    <sendfriend_send>
+                        <label>Email Product to a Friend</label>
+                    </sendfriend_send>
                 </areas>
             </frontend>
             <backend>
diff --git app/code/core/Mage/Catalog/Model/Api2/Product/Image/Rest/Admin/V1.php app/code/core/Mage/Catalog/Model/Api2/Product/Image/Rest/Admin/V1.php
index deb20d3c4a8..465b6abd5df 100644
--- app/code/core/Mage/Catalog/Model/Api2/Product/Image/Rest/Admin/V1.php
+++ app/code/core/Mage/Catalog/Model/Api2/Product/Image/Rest/Admin/V1.php
@@ -69,7 +69,9 @@ class Mage_Catalog_Model_Api2_Product_Image_Rest_Admin_V1 extends Mage_Catalog_M
 
             // try to create Image object to check if image data is valid
             try {
-                new Varien_Image($apiTempDir . DS . $imageFileName);
+                $filePath = $apiTempDir . DS . $imageFileName;
+                new Varien_Image($filePath);
+                Mage::getModel('core/file_validator_image')->validate($filePath);
             } catch (Exception $e) {
                 $ioAdapter->rmdir($apiTempDir, true);
                 $this->_critical($e->getMessage(), Mage_Api2_Model_Server::HTTP_INTERNAL_ERROR);
diff --git app/code/core/Mage/Catalog/Model/Product/Attribute/Media/Api.php app/code/core/Mage/Catalog/Model/Product/Attribute/Media/Api.php
index f5c8f900acf..c40cf54715c 100644
--- app/code/core/Mage/Catalog/Model/Product/Attribute/Media/Api.php
+++ app/code/core/Mage/Catalog/Model/Product/Attribute/Media/Api.php
@@ -155,7 +155,9 @@ class Mage_Catalog_Model_Product_Attribute_Media_Api extends Mage_Catalog_Model_
 
             // try to create Image object - it fails with Exception if image is not supported
             try {
-                new Varien_Image($tmpDirectory . DS . $fileName);
+                $filePath = $tmpDirectory . DS . $fileName;
+                new Varien_Image($filePath);
+                Mage::getModel('core/file_validator_image')->validate($filePath);
             } catch (Exception $e) {
                 // Remove temporary directory
                 $ioAdapter->rmdir($tmpDirectory, true);
diff --git app/code/core/Mage/Cms/Model/Wysiwyg/Images/Storage.php app/code/core/Mage/Cms/Model/Wysiwyg/Images/Storage.php
index 1407a2860c8..8e0ad13f2a6 100644
--- app/code/core/Mage/Cms/Model/Wysiwyg/Images/Storage.php
+++ app/code/core/Mage/Cms/Model/Wysiwyg/Images/Storage.php
@@ -137,7 +137,9 @@ class Mage_Cms_Model_Wysiwyg_Images_Storage extends Varien_Object
             $item->setUrl($helper->getCurrentUrl() . $item->getBasename());
 
             if ($this->isImage($item->getBasename())) {
-                $thumbUrl = $this->getThumbnailUrl($item->getFilename(), true);
+                $thumbUrl = $this->getThumbnailUrl(
+                    Mage_Core_Model_File_Uploader::getCorrectFileName($item->getFilename()),
+                    true);
                 // generate thumbnail "on the fly" if it does not exists
                 if(! $thumbUrl) {
                     $thumbUrl = Mage::getSingleton('adminhtml/url')->getUrl('*/*/thumbnail', array('file' => $item->getId()));
@@ -386,7 +388,9 @@ class Mage_Cms_Model_Wysiwyg_Images_Storage extends Varien_Object
         $height = $this->getConfigData('resize_height');
         $image->keepAspectRatio($keepRation);
         $image->resize($width, $height);
-        $dest = $targetDir . DS . pathinfo($source, PATHINFO_BASENAME);
+        $dest = $targetDir
+            . DS
+            . Mage_Core_Model_File_Uploader::getCorrectFileName(pathinfo($source, PATHINFO_BASENAME));
         $image->save($dest);
         if (is_file($dest)) {
             return $dest;
diff --git app/code/core/Mage/Core/etc/config.xml app/code/core/Mage/Core/etc/config.xml
index ff8a1477a87..ef082bfb4e9 100644
--- app/code/core/Mage/Core/etc/config.xml
+++ app/code/core/Mage/Core/etc/config.xml
+<?php