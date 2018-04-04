<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace xutl\aliyun;

use yii\di\Instance;
use yii\helpers\Json;
use yii\httpclient\Client;
use yii\httpclient\RequestEvent;
use yii\base\InvalidConfigException;

/**
 * Class BaseAcsClient
 * @package xutl\aliyun
 */
abstract class BaseAcsClient extends Client
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
    protected $dateTimeFormat = 'D, d M Y H:i:s \G\M\T';

    /**
     * @var string 接口版本
     */
    public $version;

    /**
     * @var string 可用区
     */
    public $regionId;

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
        $content = Json::encode($params);
        $headers["x-sdk-client"] = "Yii2/2.0.0";
        $headers['x-acs-version'] = $this->version;
        $headers['x-acs-signature-nonce'] = uniqid();
        $headers['x-acs-signature-version'] = $this->signatureVersion;
        $headers['x-acs-signature-method'] = $this->signatureMethod;
        $headers["x-acs-region-id"] = $this->regionId;
        $headers['Date'] = gmdate($this->dateTimeFormat);
        $headers['Accept'] = 'application/json';
        if ($content != null) {
            $headers["Content-MD5"] = base64_encode(md5($content, true));
        }
        //$headers['Content-Type'] = 'application/octet-stream;charset=utf-8';

        $signString = strtoupper($event->request->getMethod()) . "\n";
        if (isset($headers["Accept"])) {
            $signString = $signString . $headers['Accept'];
        }
        $signString = $signString . "\n";
        if (isset($headers["Content-MD5"])) {
            $signString = $signString . $headers['Content-MD5'];
        }
        $signString = $signString . "\n";
        if (isset($headers["Content-Type"])) {
            $signString = $signString . $headers['Content-Type'];
        }
        $signString = $signString . "\n";
        if (isset($headers["Date"])) {
            $signString = $signString . $headers['Date'];
        }
        $signString = $signString . "\n" . $this->buildCanonicalHeaders($headers);
        $signString .= $event->request->getUrl();
        //签名
        if ($this->signatureMethod == self::SIGNATURE_METHOD_HMACSHA256) {
            $headers['Authorization'] = 'acs ' . $this->accessId . ':' . base64_encode(hash_hmac('sha256', $signString, $this->accessKey, true));
        } elseif ($this->signatureMethod == self::SIGNATURE_METHOD_HMACSHA1) {
            $headers['Authorization'] = 'acs ' . $this->accessId . ':' . base64_encode(hash_hmac('sha1', $signString, $this->accessKey, true));
        }
        $event->request->setContent($content);
        $event->request->setHeaders($headers);
    }

    /**
     * 构建规范 Headers
     * @param array $headers
     * @return string
     */
    private function buildCanonicalHeaders(array $headers)
    {
        $sortMap = [];
        foreach ($headers as $headerKey => $headerValue) {
            $key = strtolower($headerKey);
            if (strpos($key, 'x-acs-') === 0) {
                $sortMap[$key] = $headerValue;
            }
        }
        ksort($sortMap);
        $headerString = '';
        foreach ($sortMap as $sortMapKey => $sortMapValue) {
            $headerString = $headerString . $sortMapKey . ':' . $sortMapValue . "\n";
        }
        return $headerString;
    }
}