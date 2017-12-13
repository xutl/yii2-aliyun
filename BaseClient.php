<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace xutl\aliyun;

use yii\httpclient\Client;
use yii\base\InvalidConfigException;
use yii\httpclient\RequestEvent;

/**
 * Class BaseClient
 * @package xutl\aliyun
 */
class BaseClient extends Client
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
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (empty ($this->accessId)) {
            throw new InvalidConfigException ('The "accessId" property must be set.');
        }
        if (empty ($this->accessKey)) {
            throw new InvalidConfigException ('The "accessKey" property must be set.');
        }
        if (empty ($this->version)) {
            throw new InvalidConfigException ('The "version" property must be set.');
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
        if($this->regionId){
            $params['RegionId'] = $this->regionId;
        }
        $params['Format'] = 'JSON';
        $params['AccessKeyId'] = $this->accessId;
        $params['SignatureMethod'] = $this->signatureMethod;
        $params['Timestamp'] = gmdate($this->dateTimeFormat);
        $params['SignatureVersion'] = $this->signatureVersion;
        $params['SignatureNonce'] = uniqid();

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
}