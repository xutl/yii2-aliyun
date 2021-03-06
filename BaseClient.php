<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace xutl\aliyun;

use yii\di\Instance;
use yii\httpclient\Client;
use yii\base\InvalidConfigException;
use yii\httpclient\RequestEvent;

/**
 * Class BaseClient
 * @package xutl\aliyun
 */
abstract class BaseClient extends Client
{
    const SIGNATURE_METHOD_HMACSHA1 = 'HMAC-SHA1';
    const SIGNATURE_METHOD_HMACSHA256 = 'HMAC-SHA256';

    /**
     * @var string 阿里云AccessKey ID
     */
    public $accessId;

    /**
     * @var string AccessKey
     */
    public $accessKey;

    /**
     * @var string
     */
    public $version;

    /**
     * @var string 区域ID
     */
    public $regionId;

    /**
     * @var string
     */
    public $securityToken;

    /**
     * @var string
     */
    protected $signatureMethod = self::SIGNATURE_METHOD_HMACSHA1;

    /**
     * @var string
     */
    protected $signatureVersion = '1.0';

    /**
     * @var string
     */
    protected $dateTimeFormat = 'Y-m-d\TH:i:s\Z';

    /**
     * @var string|Aliyun
     */
    private $aliyun = 'aliyun';

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        if (empty ($this->version)) {
            throw new InvalidConfigException ('The "version" property must be set.');
        }
        $this->aliyun = Instance::ensure($this->aliyun, Aliyun::class);
        if (empty ($this->accessId)) {
            $this->accessId = $this->aliyun->accessId;
        }
        if (empty ($this->accessKey)) {
            $this->accessKey = $this->aliyun->accessKey;
        }
        $this->responseConfig['format'] = Client::FORMAT_JSON;
        $this->on(Client::EVENT_BEFORE_SEND, [$this, 'RequestEvent']);
    }

    /**
     * 请求事件
     * @param RequestEvent $event
     * @return void
     */
    public function RequestEvent(RequestEvent $event)
    {
        $params = $event->request->getData();
        $params['Version'] = $this->version;
        $params['Format'] = 'JSON';
        $params['AccessKeyId'] = $this->accessId;
        $params['SignatureMethod'] = $this->signatureMethod;
        $params['Timestamp'] = gmdate($this->dateTimeFormat);
        $params['SignatureVersion'] = $this->signatureVersion;
        $params['SignatureNonce'] = uniqid();
        if ($this->regionId) {
            $params['RegionId'] = $this->regionId;
        }
        if ($this->securityToken) {
            $params['SecurityToken'] = $this->securityToken;
        }

        //参数排序
        ksort($params);
        $query = http_build_query($params, null, '&', PHP_QUERY_RFC3986);
        $source = strtoupper($event->request->getMethod()) . '&%2F&' . $this->percentEncode($query);

        //签名
        if ($this->signatureMethod == self::SIGNATURE_METHOD_HMACSHA256) {
            $params['Signature'] = base64_encode(hash_hmac('sha256', $source, $this->accessKey . '&', true));
        } elseif ($this->signatureMethod == self::SIGNATURE_METHOD_HMACSHA1) {
            $params['Signature'] = base64_encode(hash_hmac('sha1', $source, $this->accessKey . '&', true));
        }

        $event->request->setData($params);
    }

    /**
     * 参数转码
     * @param string $str
     * @return mixed|string
     */
    protected function percentEncode($str)
    {
        $res = urlencode($str);
        $res = preg_replace('/\+/', '%20', $res);
        $res = preg_replace('/\*/', '%2A', $res);
        $res = preg_replace('/%7E/', '~', $res);
        return $res;
    }

    /**
     * 通过__call转发请求
     * @param string $name 方法名
     * @param array $arguments 参数
     * @return array
     */
    public function __call($name, $arguments)
    {
        $action = ucfirst($name);
        $params = [];
        if (is_array($arguments) && !empty($arguments)) {
            $params = (array)$arguments[0];
        }
        $params['Action'] = $action;
        return $this->_dispatchRequest($params);
    }

    /**
     * 发起接口请求
     * @param array $params 接口参数
     * @return array
     */
    protected function _dispatchRequest($params)
    {
        $response = $this->createRequest()
            ->setMethod('POST')
            ->setData($params)
            ->send();
        return $response->data;
    }
}