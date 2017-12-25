<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace xutl\aliyun;

use yii\base\Exception;
use yii\helpers\Url;
use yii\httpclient\Client;
use yii\httpclient\RequestEvent;
use yii\base\InvalidConfigException;

/**
 * Class Api
 * @package xutl\aliyun
 */
class Api extends Client
{
    const SIGNATURE_METHOD_HMACSHA1 = 'HMAC-SHA1';
    const SIGNATURE_METHOD_HMACSHA256 = 'HMAC-SHA256';

    public $appKey;

    public $appSecret;

    /**
     * @var string
     */
    protected $signatureMethod = self::SIGNATURE_METHOD_HMACSHA1;

    /**
     * @var string
     */
    protected $dateTimeFormat = 'D, d M Y H:i:s \G\M\T';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (empty ($this->appKey)) {
            throw new InvalidConfigException ('The "appKey" property must be set.');
        }
        if (empty ($this->appSecret)) {
            throw new InvalidConfigException ('The "appSecret" property must be set.');
        }

        $this->responseConfig['format'] = Client::FORMAT_JSON;
        $this->on(Client::EVENT_BEFORE_SEND, [$this, 'RequestEvent']);
    }

    /**
     * 请求事件
     * @param RequestEvent $event
     * @return void
     * @throws Exception
     */
    public function RequestEvent(RequestEvent $event)
    {
        $headers = $event->request->getHeaders();
        $headers->add('X-Ca-Key', $this->appKey);
        $headers->add('X-Ca-Timestamp', strval(time() * 1000));
        $headers->add('X-Ca-Nonce', uniqid());
        $headers->add('X-Ca-Version', 1);
        if (YII_ENV_DEV || YII_ENV_TEST) {
            $headers->add('X-Ca-Stage', 'TEST');
        } else {
            $headers->add('X-Ca-Stage', 'RELEASE');
        }
        $headers->add('Accept', 'application/json');
        $headers['Date'] = gmdate($this->dateTimeFormat);

        $method = strtoupper($event->request->getMethod());
        if ($method == 'POST') {
            $content = $event->request->getData();
            if ($content != null) {
                $headers->add("Content-MD5", base64_encode(md5($content, true)));
            }
        }
        $signString = $method . "\n";
        if ($headers->has('Accept')) {
            $signString = $signString . $headers->get('Accept');
        }
        $signString = $signString . "\n";
        if ($headers->has('Content-MD5')) {
            $signString = $signString . $headers->get('Content-MD5');
        }
        $signString = $signString . "\n";
        if ($headers->has('Content-Type')) {
            $signString = $signString . $headers->get('Content-Type');
        }
        $signString = $signString . "\n";
        if ($headers->has('Date')) {
            $signString = $signString . $headers->get('Date');
        }
        $signString = $signString . "\n" . $this->buildCanonicalHeaders($headers->toArray());
        $signString .= '/' . ltrim($this->composeUrl($event->request->getUrl(), $event->request->getData()), '/');

        echo $signString;
        if ($this->signatureMethod == self::SIGNATURE_METHOD_HMACSHA256) {
            $sign = base64_encode(hash_hmac('sha256', $signString, $this->appSecret, true));
        } elseif ($this->signatureMethod == self::SIGNATURE_METHOD_HMACSHA1) {
            $sign = base64_encode(hash_hmac('sha1', $signString, $this->appSecret, true));
        } else {
            throw new Exception('Unsupported signature method.');
        }
        $headers->add('X-Ca-Signature', $sign);
        $event->request->setHeaders($headers);
    }

    /**
     * Composes URL from base URL and GET params.
     * @param string $url base URL.
     * @param array $params GET params.
     * @return string composed URL.
     */
    protected function composeUrl($url, $params = [])
    {
        if (!empty($params)) {
            if (strpos($url, '?') === false) {
                $url .= '?';
            } else {
                $url .= '&';
            }
            $url .= http_build_query($params, '', '&', PHP_QUERY_RFC3986);
        }
        return $url;
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
            if (strpos($key, 'X-Ca-') === 0) {
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
