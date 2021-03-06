<?php
/**
 * Octagono Ecommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.octagonoecommerce.com.br/eula-licenca-usuario-final.html
 *
 *
 * @category   Bradesco
 * @package    Octagono_Bradesco
 * @copyright  Copyright (c) 2009-2011 - Octagono Ecommerce - www.octagonoecommerce.com.br
 * @license    http://www.octagonoecommerce.com.br/eula-licenca-usuario-final.html
 */
abstract class Octagono_Bradesco_Model_Api_Abstract extends Varien_Object
{
    const PAYMENT_TYPE_SALE = 'Sale';
    const PAYMENT_TYPE_ORDER = 'Order';
    const PAYMENT_TYPE_AUTH = 'Authorization';

    const REFUND_TYPE_FULL = 'Full';
    const REFUND_TYPE_PARTIAL = 'Partial';

    const COMPLETE = 'Complete';
    const NOTCOMPLETE = 'NotComplete';

    const USER_ACTION_COMMIT = 'commit';
    const USER_ACTION_CONTINUE = 'continue';

    public function getServerName()
    {
        if (!$this->hasServerName()) {
            $this->setServerName($_SERVER['SERVER_NAME']);
        }
        return $this->getData('server_name');
    }

    public function getConfigData($key, $default=false)
    {
        if (!$this->hasData($key)) {
             $value = Mage::getStoreConfig('Bradesco/standard/'.$key);
             if (is_null($value) || false===$value) {
                 $value = $default;
             }
            $this->setData($key, $value);
        }
        return $this->getData($key);
    }

    public function getSession()
    {
        return Mage::getSingleton('Bradesco/session');
    }

    public function getUseSession()
    {
        if (!$this->hasData('use_session')) {
            $this->setUseSession(true);
        }
        return $this->getData('use_session');
    }

    public function getSessionData($key, $default=false)
    {
        if (!$this->hasData($key)) {
            $value = $this->getSession()->getData($key);
            if ($this->getSession()->hasData($key)) {
                $value = $this->getSession()->getData($key);
            } else {
                $value = $default;
            }
            $this->setData($key, $value);
        }
        return $this->getData($key);
    }

    public function setSessionData($key, $value)
    {
        if ($this->getUseSession()) {
            $this->getSession()->setData($key, $value);
        }
        $this->setData($key, $value);
        return $this;
    }

    public function getSandboxFlag()
    {
        return $this->getConfigData('sandbox_flag', true);
    }

    public function getApiUsername()
    {
        return $this->getConfigData('api_username');
    }

    public function getApiPassword()
    {
        return $this->getConfigData('api_password');
    }

    public function getApiSignature()
    {
        return $this->getConfigData('api_signature');
    }

    public function getButtonSourceEc()
    {
        return $this->getConfigData('button_source_ec', 'Varien_Cart_EC_US');
    }

    public function getButtonSourceDp()
    {
        return $this->getConfigData('button_source_dp', 'Varien_Cart_DP_US');
    }

    public function getUseProxy()
    {
        return $this->getConfigData('use_proxy', false);
    }

    public function getProxyHost()
    {
        return $this->getConfigData('proxy_host', '127.0.0.1');
    }

    public function getProxyPort()
    {
        return $this->getConfigData('proxy_port', '808');
    }

    public function getDebug()
    {
        return $this->getConfigData('debug_flag', true);
    }

    /**
     * the page where buyers will go if there are API error
     *
     * @return string
     */
    public function getApiErrorUrl()
    {
        return Mage::getUrl($this->getConfigData('api_error_url', 'Bradesco/express/error'));
    }

    /**
     * the page where buyers return to after they are done with the payment review on Bradesco
     *
     * @return string
     */
    public function getReturnUrl()
    {
        return Mage::getUrl($this->getConfigData('api_return_url', 'Bradesco/express/return'));
    }

    /**
     * The page where buyers return to when they cancel the payment review on Bradesco
     *
     * @return string
     */
    public function getCancelUrl()
    {
        return Mage::getUrl($this->getConfigData('api_cancel_url', 'Bradesco/express/cancel'));
    }

    /**
     * Decide whether to return from Bradesco EC before payment was made or after
     *
     * @return string
     */
    public function getUserAction()
    {
        return $this->getSessionData('user_action', self::USER_ACTION_CONTINUE);
    }

    public function setUserAction($data)
    {
        return $this->setSessionData('user_action', $data);
    }

    /**
     * Bradesco API token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->getSessionData('token');
    }

    public function setToken($data)
    {
        return $this->setSessionData('token', $data);
    }

    public function getTransactionId()
    {
        return $this->getSessionData('transaction_id');
    }

    public function setTransactionId($data)
    {
        return $this->setSessionData('transaction_id', $data);
    }

    public function getAuthorizationId()
    {
        return $this->getSessionData('authorization_id');
    }

    public function setAuthorizationId($data)
    {
        return $this->setSessionData('authorization_id', $data);
    }

    public function getPayerId()
    {
        return $this->getSessionData('payer_id');
    }

    public function setPayerId($data)
    {
        return $this->setSessionData('payer_id', $data);
    }

    /**
     * Complete type code (Complete, NotComplete)
     *
     * @return string
     */
    public function getCompleteType()
    {
        return $this->getSessionData('complete_type');
    }

    public function setCompleteType($data)
    {
        return $this->setSessionData('complete_type', $data);
    }

    /**
     * Has to be one of the following values: Sale or Order or Authorization
     *
     * @return string
     */
    public function getPaymentType()
    {
        return $this->getSessionData('payment_type');
    }

    public function setPaymentType($data)
    {
        return $this->setSessionData('payment_type', $data);
    }

    /**
     * Total value of the shopping cart
     *
     * Includes taxes, shipping costs, discount, etc.
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->getSessionData('amount');
    }

    public function setAmount($data)
    {
	    $data = sprintf('%.2f', $data);
        return $this->setSessionData('amount', $data);
    }

    public function getCurrencyCode()
    {
        //return $this->getSessionData('currency_code', 'USD');
        return $this->getSessionData('currency_code', Mage::app()->getStore()->getBaseCurrencyCode());
    }

    public function setCurrencyCode($data)
    {
        return $this->setSessionData('currency_code', $data);
    }

    /**
     * Refund type ('Full', 'Partial')
     *
     * @return string
     */
    public function getRefundType()
    {
        return $this->getSessionData('refund_type');
    }

    public function setRefundType($data)
    {
        return $this->setSessionData('refund_type', $data);
    }

    public function getError()
    {
        return $this->getSessionData('error');
    }

    public function setError($data)
    {
        return $this->setSessionData('error', $data);
    }

    public function getCcTypeName($ccType)
    {
        $types = array('AE'=>Mage::helper('Bradesco')->__('Amex'), 'VI'=>Mage::helper('Bradesco')->__('Visa'), 'MC'=>Mage::helper('Bradesco')->__('MasterCard'), 'DI'=>Mage::helper('Bradesco')->__('Discover'));
        return isset($types[$ccType]) ? $types[$ccType] : false;
    }

    public function unsError()
    {
        return $this->setSessionData('error', null);
    }
}

