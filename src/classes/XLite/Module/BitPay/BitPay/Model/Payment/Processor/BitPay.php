<?php

namespace XLite\Module\BitPay\BitPay\Model\Payment\Processor;

class BitPay extends \XLite\Model\Payment\Base\Online
{
    /**
     * Currency gateway (only USD)
     */
    const CURRENCY = 'USD';

    /**
     * URL's gateway definition
     */
    const CHECKOUT_API_URL = 'https://webservice.paymentxp.com/wh/EnterPayment.aspx';
    const TRANSACTION_TYPE = 'CreditCardHosted';

    /**
     * Get settings widget or template
     *
     * @return string Widget class name or template path
     */
    public function getSettingsWidget()
    {
        return 'modules/BitPay/BitPay/config.tpl';
    }

    /**
     * Check - payment method is configured or not
     *
     * @param \XLite\Model\Payment\Method $method Payment method
     *
     * @return boolean
     */
    public function isConfigured(\XLite\Model\Payment\Method $method)
    {
        return parent::isConfigured($method)
            && $method->getSetting('connection') === 'connected';
    }

    protected function doInitialPayment()
    {
        return '';
    }

    /**
     * Returns the list of settings available for this payment processor
     *
     * @return array
     */
    public function getAvailableSettings()
    {
        return array(
            'riskSpeed'
        );
    }

    /**
     * Get return request owner transaction or null
     *
     * @return \XLite\Model\Payment\Transaction|void
     */
    public function getReturnOwnerTransaction()
    {
        $ReferenceNumber = \XLite\Core\Request::getInstance()->ReferenceNumber;

        return \XLite\Core\Database::getRepo('XLite\Model\Payment\Transaction')
            ->find($ReferenceNumber);
    }

    /**
     * Get allowed currencies
     *
     * @param \XLite\Model\Payment\Method $method Payment method
     *
     * @return array
     */
    protected function getAllowedCurrencies(\XLite\Model\Payment\Method $method)
    {
        return array_merge(
            parent::getAllowedCurrencies($method),
            array(self::CURRENCY)
        );
    }

    /**
     * Get payment method admin zone icon URL
     *
     * @param \XLite\Model\Payment\Method $method Payment method
     *
     * @return string
     */
    public function getAdminIconURL(\XLite\Model\Payment\Method $method)
    {
        return true;
    }

    /**
     * Get redirect form URL
     *
     * @return string
     */
    protected function getFormURL()
    {
        return self::CHECKOUT_API_URL;
    }

    /**
     * Get redirect form fields list
     *
     * @return array
     */
    protected function getFormFields()
    {
        $order = $this->getOrder();

        $fields = array(
            'TransactionType' => self::TRANSACTION_TYPE,
            'MerchantID' => $this->getSetting('merchantID'),
            'MerchantKey' => $this->getSetting('merchantKey'),
            'TransactionAmount' => $this->transaction->getValue(),
            'ReferenceNumber' => $this->getSetting('orderPrefix') . $this->transaction->getTransactionId(),
            'EmailAddress' => $this->getProfile()->getLogin(),
            'ClientIPAddress' => $this->getClientIP(),
            'ProductDescription' => 'Order #' . $this->getOrder()->getOrderNumber(),
            'PostBackURL' => $this->getReturnURL(),
        );

        if ($billingAddress = $this->getProfile()->getBillingAddress()) {
            $fields += array(
                'BillingNameFirst' => $billingAddress->getFirstname(),
                'BillingNameLast' => $billingAddress->getLastname(),
                'BillingFullName' => $billingAddress->getFirstname()
                    . ' ' . $billingAddress->getLastname(),
                'BillingAddress' => $billingAddress->getStreet(),
                'BillingZipCode' => $billingAddress->getZipcode(),
                'BillingCity' => $billingAddress->getCity(),
                'BillingState' => $billingAddress->getState()->getCode(),
                'BillingCountry' => $billingAddress->getCountry()->getCode(),
                'PhoneNumber' => $billingAddress->getPhone(),
            );
        }

        if ($shippingAddress = $this->getProfile()->getShippingAddress()) {

            $fields += array(

                'ShippingAddress1' => $shippingAddress->getStreet(),
                'ShippingAddress2' => '',
                'ShippingCity' => $shippingAddress->getCity(),
                'ShippingState' => $shippingAddress->getState()->getCode(),
                'ShippingZipCode' => $shippingAddress->getZipcode(),
                'ShippingCountry' => $shippingAddress->getCountry()->getCode(),
            );
        }

        return $fields;
    }

    /**
     * Get setting value by name
     *
     * @param string $name Name
     *
     * @return mixed
     */
    protected function getSetting($name)
    {
        return (parent::getSetting($name))
            ?: $this->getBitPayPaymentMethod()->getSetting($name);
    }

    /**
     * Get payment method
     *
     * @return \XLite\Model\Payment\Method
     */
    protected function getBitPayPaymentMethod()
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Payment\Method')
            ->findOneBy(
                array(
                    'service_name' => \XLite\Module\MPS\MeritusPayment\Main::METHOD_SERVICE_NAME
                )
            );
    }
}
