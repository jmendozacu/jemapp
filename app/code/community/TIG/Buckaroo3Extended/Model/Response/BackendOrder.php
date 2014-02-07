<?php
class TIG_Buckaroo3Extended_Model_Response_BackendOrder extends TIG_Buckaroo3Extended_Model_Response_Abstract
{
    protected function _success()
    {
        $this->_debugEmail .= "The request was successful \n";
		if(!$this->_order->getEmailSent())
        {
        	$this->_order->sendNewOrderEmail();
        }
        
		Mage::getSingleton('core/session')->addSuccess(
		    Mage::helper('buckaroo3extended')->__('Your order has been placed succesfully.')
		);
        $this->sendDebugEmail();
    }

    protected function _failed()
    {
        $this->_debugEmail .= 'The request failed \n';
        $this->restoreQuote();

        Mage::getSingleton('core/session')->addError(
            Mage::helper('buckaroo3extended')->__('Your payment was unsuccesful. Please try again or choose another payment method.')
        );

        if (Mage::getStoreConfig('buckaroo/buckaroo3extended_advanced/cancel_on_failed', $this->_order->getStoreId())) {
            $this->_order->cancel()->save();
        }

        $this->sendDebugEmail();
        Mage::throwException('An error occurred while processing the request');
    }

    protected function _error()
    {
        $this->_debugEmail .= "The request generated an error \n";
        Mage::getSingleton('core/session')->addError(
            Mage::helper('buckaroo3extended')->__('A technical error has occurred. Please try again. If this problem persists, please contact the shop owner.')
        );

        $this->_order->cancel()->save();
        
        $this->_debugEmail .= "I have cancelled the order! \n";

        $this->sendDebugEmail();
        Mage::throwException('An error occurred while processing the request');
    }

    protected function _neutral()
    {
        $this->_debugEmail .= "The request was neutral \n";

		Mage::getSingleton('core/session')->addSuccess(
		    Mage::helper('buckaroo3extended')->__(
		    	'Your order has been placed succesfully. You will recieve an e-mail containing further payment instructions shortly.'
		    )
		);

        $this->sendDebugEmail();
    }

    protected function _pendingPayment()
    {
        $this->_success();
    }

    protected function _incorrectPayment()
    {
        $this->_error();
    }

    protected function _verifyError()
    {
        $this->_debugEmail .= "The response could not be verified \n";
        Mage::getSingleton('core/session')->addNotice(
            Mage::helper('buckaroo3extended')->__('We are currently unable to retrieve the status of your transaction. If you do not recieve an e-mail regarding your order within 30 minutes, please contact the shop owner.')
        );

        $this->_order->cancel()->save();
        $this->_debugEmail .= "I have cancelled the order! \n";

        $this->sendDebugEmail();
        Mage::throwException('An error occurred while processing the request');
    }
}