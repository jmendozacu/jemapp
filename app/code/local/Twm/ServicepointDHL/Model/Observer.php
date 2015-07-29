<?php

class Twm_ServicepointDHL_Model_Observer extends Mage_Sales_Model_Quote_Address
{

    public function __construct()
    {

    }

    public function saveShippingMethod($evt)
    {
        $shippingMethodChoice = $evt->getRequest()->getParam('shipping_method', false);
        if (strpos($shippingMethodChoice,'servicepointdhl') !== false) {
            $code = explode('_', $shippingMethodChoice, 2);
            $code = $code[1];

            //get shipping address via SOAP
            $model = Mage::getModel('servicepointdhl/carrier_shippingMethod');
            $dhlAddress = $model->getDHLAddress($code);
            if ($dhlAddress) {
                $quote = $evt->getQuote();

//                $quote->getShippingAddress()
//                    ->setPrefix($code)
//                    ->setFirstname($quote->getBillingAddress()->getFirstname())
//                    ->setLastname($quote->getBillingAddress()->getLastname())
//                    ->setCompany($dhlAddress['name'])
//                    ->setStreet($dhlAddress['add'])
//                    ->setPostcode($dhlAddress['zip'])
//                    ->setCity($dhlAddress['city'])
//                    ->setCountryId($dhlAddress['country'])
//                    ->setCollectShippingRates(true);
//                $quote->collectTotals()->save();
            }
        }
    }

}
