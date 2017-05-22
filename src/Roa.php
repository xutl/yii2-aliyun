<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace xutl\aliyun;

use Yii;
use yii\helpers\Json;
use yii\httpclient\Client;
use yii\httpclient\Response;
use yii\httpclient\Exception;
use yii\base\InvalidConfigException;

/**
 * Class Roa
 * @package xutl\aliyun
 */
class Roa extends BaseApi
{
    /**
     * @var string
     */
    public $version;

    /**
     * @var string
     */
    protected $signatureMethod = 'HMAC-SHA1';

    /**
     * @var string
     */
    protected $signatureVersion = '1.0';

    /**
     * @var string
     */
    protected $dateTimeFormat = "D, d M Y H:i:s \G\M\T";

    private static $headerSeparator = "\n";

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        if (empty ($this->version)) {
            throw new InvalidConfigException ('The "version" property must be set.');
        }
    }

    /**
     * 获取Http Client
     * @return Client
     */
    public function getHttpClient()
    {
        if (!is_object($this->_httpClient)) {
            $this->_httpClient = new Client([
                'baseUrl' => $this->baseUrl,
                'requestConfig' => [
                    'options' => $this->requestOptions,
                    'format' => Client::FORMAT_JSON
                ],
                'responseConfig' => [
                    'format' => Client::FORMAT_JSON
                ],
            ]);
        }
        return $this->_httpClient;
    }

    /**
     * 请求Api接口
     * @param string $url
     * @param string $method
     * @param array $params
     * @param array $headers
     * @return array
     */
    public function api($url, $method, array $params = [], array $headers = [])
    {
        $headers['Accept'] = 'application/json';
        $headers['Content-Type'] = 'application/json';
        $headers['Date'] = gmdate($this->dateTimeFormat);
        $headers['x-acs-version'] = $this->version;
        $headers['x-acs-signature-nonce'] = uniqid();
        $headers['x-acs-signature-version'] = $this->signatureVersion;
        $headers['x-acs-signature-method'] = $this->signatureMethod;
        $headers["Content-MD5"] = base64_encode(md5(Json::encode($params), true));
        return parent::api($url, $method, $params, $headers);
    }

    /**
     * Sends HTTP request.
     * @param string $method request type.
     * @param string $url request URL.
     * @param array $params request params.
     * @param array $headers additional request headers.
     * @return array response.
     * @throws Exception
     */
    protected function sendRequest($method, $url, array $params = [], array $headers = [])
    {
        $signString = strtoupper($method) . self::$headerSeparator;
        if (isset($headers["Accept"])) {
            $signString = $signString . $headers["Accept"];
        }
        $signString = $signString . self::$headerSeparator;

        if (isset($headers["Content-MD5"])) {
            $signString = $signString . $headers["Content-MD5"];
        }
        $signString = $signString . self::$headerSeparator;

        if (isset($headers["Content-Type"])) {
            $signString = $signString . $headers["Content-Type"];
        }
        $signString = $signString . self::$headerSeparator;

        if (isset($headers["Date"])) {
            $signString = $signString . $headers["Date"];
        }
        $signString = $signString . self::$headerSeparator;

        $signString = $signString . $this->buildCanonicalHeaders($headers);

        ksort($params);
        $queryString = $this->composeUrl($url, $params);
        $signString .= $queryString;
        //签名
        $headers["Authorization"] = "acs " . $this->accessId . ":" . base64_encode(hash_hmac('sha1', $signString, $this->accessKey, true));
        return parent::sendRequest($method, $url, $params, $headers);
    }

    private function buildCanonicalHeaders($headers)
    {
        $sortMap = array();
        foreach ($headers as $headerKey => $headerValue) {
            $key = strtolower($headerKey);
            if (strpos($key, "x-acs-") === 0) {
                $sortMap[$key] = $headerValue;
            }
        }
        ksort($sortMap);
        $headerString = "";
        foreach ($sortMap as $sortMapKey => $sortMapValue) {
            $headerString = $headerString . $sortMapKey . ":" . $sortMapValue . self::$headerSeparator;
        }
        return $headerString;
    }
}