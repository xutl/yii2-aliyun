<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace xutl\aliyun;

use yii\helpers\Json;
use yii\httpclient\RequestEvent;
use yii\base\InvalidConfigException;

/**
 * Class Fc
 * @package xutl\aliyun
 */
class Fc extends BaseAcsClient
{
    /**
     * @var string
     */
    public $version = '2016-08-15';

    /**
     * @var string
     */
    protected $signatureMethod = self::SIGNATURE_METHOD_HMACSHA256;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (empty ($this->baseUrl)) {
            throw new InvalidConfigException ('The "baseUrl" property must be set.');
        }
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
        $headers['x-fc-invocation-type'] = 'Sync';
        $headers['Date'] = gmdate($this->dateTimeFormat);
        $headers['Content-Type'] = 'application/json';
        if ($content != null) {
            $headers["Content-MD5"] = base64_encode(md5($content, true));
        }
        $headers['Content-Length'] = mb_strlen($content);

        $signString = strtoupper($event->request->getMethod()) ;

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
        $url = $this->version . '/' . $event->request->getUrl();
        $signString .= '/' . $url;
        //签名
        if ($this->signatureMethod == self::SIGNATURE_METHOD_HMACSHA256) {
            $headers['Authorization'] = 'FC ' . $this->accessId . ':' . base64_encode(hash_hmac('sha256', $signString, $this->accessKey, true));
        } elseif ($this->signatureMethod == self::SIGNATURE_METHOD_HMACSHA1) {
            $headers['Authorization'] = 'FC ' . $this->accessId . ':' . base64_encode(hash_hmac('sha1', $signString, $this->accessKey, true));
        }
        $event->request->setUrl($url);
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
            if (strpos($key, 'x-fc-') === 0) {
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