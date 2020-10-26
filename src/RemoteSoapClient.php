<?php
/**
 * Created by PhpStorm.
 * User: admin
 * CreateTime: 2020/10/26 上午11:40
 */

namespace RemoteClient\RemoteSoap;

class RemoteSoapClient
{
    protected $config = null;
    protected $authCode = '';
    protected $url = '';
    protected $sendData = '';
    protected $funcName = '';
    public function __construct(Repository $config)
    {
        $this->config = $config;
    }

    /**
     * Identity verification parameters
     * @param $authcode
     * @return $this
     */
    public function setAuthCode($authcode)
    {
        $this->authCode = $authcode;
        return $this;
    }

    /**
     * set url
     * @param $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Data to send
     * @param $sendData
     * @return $this
     */
    public function setSendData($sendData)
    {
        $this->sendData = $sendData;
        return $this;
    }

    /**
     * method to call
     * @param $funcName
     * @return $this
     */
    public function setFuncName($funcName)
    {
        $this->funcName = $funcName;
        return $this;
    }
    /**
     * soap client
     * @return mixed
     * @throws \Exception
     */
    public function remoteClient()
    {
        try {
            ini_set('soap.wsdl_cache_enabled', 0);
            $client = new \SoapClient($this->url.'?wsdl', array("exceptions" => 1));
            $authvalues = new \SoapVar(array('authcode' => $this->authCode,), SOAP_ENC_OBJECT);
            $header = new \SoapHeader('urn:soap', 'auth', $authvalues, false, SOAP_ACTOR_NEXT);
            $client->__setSoapHeaders(array($header));
            $return = $client->{$this->funcName}($this->sendData);
            return $return;
        }catch (\SoapFault $e){
            throw new \Exception($e->faultstring);
        }
    }
}
