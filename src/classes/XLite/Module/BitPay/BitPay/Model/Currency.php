<?php

namespace XLite\Module\BitPay\BitPay\Model;

/**
 * Currency
 *
 * @since 1.0.7
 */
class Currency extends \XLite\Model\Currency implements \XLite\Base\IDecorator
{

    /**
     * Return the currency symbol
     *
     * @return string
     */
    public function getSymbol()
    {
        return ($this->getCode() == 'BTC') ? '$' : parent::getSymbol();
    }
}
