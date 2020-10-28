<?php
/**
 * Created by PhpStorm.
 * User: admin
 * CreateTime: 2020/10/26 上午11:40
 */
namespace RemoteClient\RemoteSoap;
use Illuminate\Config\Repository;

class RemoteSoapClient
{
    protected $config = null;
    protected $redundantData = '';
    protected $url = '';
    protected $mobile = null;
    protected $platform = '';
    protected $ifMultiple = null;
    protected $appid = '';
    protected $appSecret = '';
    public function __construct(Repository $config)
    {
        $this->config = $config;
        $this->appid = $this->config->get('remote.appid');
        $this->appSecret = $this->config->get('remote.appSecret');
        $this->url = $this->config->get('remote.url');
    }
    /**
     * @param int|array     $mobile          要发送的手机号
     * @param string        $platform        来源
     * @param bool          $ifMultiple      是否多个数据
     * @return $this
     */
    public function setSendData($mobile,$platform,$ifMultiple = false)
    {
        $this->mobile = $mobile;
        $this->platform = $platform;
        $this->ifMultiple = $ifMultiple;
        return $this;
    }

    /**
     * 冗余数据
     * @param $data
     * @return $this
     */
    public function redundantData($data)
    {
        $this->redundantData = $data;
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
            $authvalues = new \SoapVar(array('auth_data' => json_encode($this->authParam())), SOAP_ENC_OBJECT);
            $header = new \SoapHeader('urn:soap', 'auth', $authvalues, false, SOAP_ACTOR_NEXT);
            $client->__setSoapHeaders(array($header));
            $return = $client->store(json_encode(['mobile'=>$this->mobile,'platform'=>$this->platform]), $this->ifMultiple);
            return $return;
        }catch (\SoapFault $e){
            throw new \Exception($e->faultstring);
        }
    }

    /**
     * @return array
     */
    private function authParam()
    {
        $params = [
            'format' => 'json',
            'sign_method' => 'md5',
            'v' => 'v1.0',
            'app_id' => $this->appid,
            'time' => time(),
        ];
        $params['sign'] = $this->_getSign($params,$this->appSecret );
        return $params;
    }

    /**
     * 获取签名
     * @param $params
     * @param $secretKey
     * @return string
     */
    protected function _getSign($params, $secretKey)
    {
        ksort($params);
        $stringToBeSigned = $secretKey;
        foreach ($params as $k => $v) {
            if (is_string($v) && '@' !== substr($v, 0, 1)) {
                $stringToBeSigned .= "$k$v";
            }
        }
        unset($k, $v);
        $stringToBeSigned .= $secretKey;

        return strtoupper(md5($stringToBeSigned));
    }
}