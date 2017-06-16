<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace xutl\aliyun;

use Yii;
use yii\helpers\Json;
use yii\base\Component;
use yii\httpclient\Client;
use yii\httpclient\Exception;
use yii\httpclient\Response;
use yii\helpers\ArrayHelper;
use yii\base\InvalidConfigException;

/**
 * Class Roa
 * @package xutl\aliyun
 */
class Roa extends Component
{
    /**
     * @var string 接口版本
     */
    public $version;

    /**
     * @var string
     */
    public $baseUrl;

    /**
     * @var Client
     */
    public $_httpClient;

    /**
     * @var string 阿里云AccessKey ID
     */
    public $accessId;

    /**
     * @var string AccessKey
     */
    public $accessKey;

    public $regionId;

    /**
     * @var array 请求选项
     * @see https://github.com/yiisoft/yii2-httpclient/blob/master/docs/guide/usage-request-options.md
     */
    public $requestOptions = [];

    /**
     * @var array Uri中的参数占位
     */
    private $pathParameters = [];

    /**
     * @var string 签名方法，目前只支持: HMAC-SHA1
     */
    protected $signatureMethod = 'HMAC-SHA1';

    /**
     * @var string 签名版本，目前取值：1.0
     */
    protected $signatureVersion = '1.0';

    /**
     * @var string GMT日期格式
     */
    protected $dateTimeFormat = "D, d M Y H:i:s \G\M\T";

    private static $headerSeparator = "\n";

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        if (empty ($this->baseUrl)) {
            throw new InvalidConfigException ('The "baseUrl" property must be set.');
        }
        if (empty ($this->accessId)) {
            throw new InvalidConfigException ('The "accessId" property must be set.');
        }
        if (empty ($this->accessKey)) {
            throw new InvalidConfigException ('The "accessKey" property must be set.');
        }
        if (empty ($this->version)) {
            throw new InvalidConfigException ('The "version" property must be set.');
        }
        $this->requestOptions = ArrayHelper::merge([
            'timeout' => 5,
            'sslVerifyPeer' => false,
        ], $this->requestOptions);
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
                    'options' => $this->requestOptions
                ],
                'responseConfig' => [
                    'format' => Client::FORMAT_JSON
                ],
            ]);
        }
        return $this->_httpClient;
    }

    /**
     * Sends HTTP request.
     * @param string $method request type.
     * @param string $uri request URI.
     * @param string $content HTTP message raw content.
     * @param array $headers additional request headers.
     * @return array response.
     * @throws Exception
     */
    protected function sendRequest($method, $uri, $content, array $headers = [])
    {
        $response = $this->getHttpClient()->createRequest()
            ->setUrl($uri)
            ->setMethod($method)
            ->setHeaders($headers)
            ->setContent($content)
            ->send();
        if (!$response->isOk) {
            throw new Exception($response->content, $response->statusCode);
        }
        return $response->getData();
    }

    /**
     * send Request
     * @param string $uri
     * @param string $method
     * @param array $params request params.
     * @param array $headers additional request headers.
     * @return array response.
     */
    public function api($uri, $method, array $params = [], array $headers = [])
    {
        $content = Json::encode($params);
        $headers = $this->prepareHeader($uri, $method, $content, $headers);
        $signString = $method . self::$headerSeparator;
        if (isset($headers["Accept"])) {
            $signString = $signString . $headers['Accept'];
        }
        $signString = $signString . self::$headerSeparator;
        if (isset($headers["Content-MD5"])) {
            $signString = $signString . $headers['Content-MD5'];
        }
        $signString = $signString . self::$headerSeparator;
        if (isset($headers["Content-Type"])) {
            $signString = $signString . $headers['Content-Type'];
        }
        $signString = $signString . self::$headerSeparator;
        if (isset($headers["Date"])) {
            $signString = $signString . $headers['Date'];
        }
        $signString = $signString . self::$headerSeparator;
        $uri = $this->replaceOccupiedParameters($uri);
        $signString = $signString . $this->buildCanonicalHeaders($headers);
        $signString .= $uri;

        $headers['Authorization'] = 'acs ' . $this->accessId . ':' . base64_encode(hash_hmac('sha1', $signString, $this->accessKey, true));

        return $this->sendRequest($method, $uri, $content, $headers);
    }


    /**
     * 预处理请求头
     * @param string $uri
     * @param string $method
     * @param string $content
     * @param array $headers
     * @return array
     */
    private function prepareHeader($uri, $method, $content = null, array $headers = [])
    {
        $headers["x-sdk-client"] = "yii2/2.0.0";
        $headers['x-acs-version'] = $this->version;//接口版本
        $headers['x-acs-signature-nonce'] = uniqid();//随机字符串，用来避免回放攻击
        $headers['x-acs-signature-version'] = $this->signatureVersion;//签名版本，目前取值：1.0
        $headers['x-acs-signature-method'] = $this->signatureMethod;//签名方法，目前只支持: HMAC-SHA1
        $headers["x-acs-region-id"] = $this->regionId;
        $headers['Date'] = gmdate($this->dateTimeFormat);//GMT日期格式，例如：Tue, 17 Jan 2017 10:16:36 GMT
        $headers['Accept'] = 'application/json';

        if ($content != null) {
            $headers["Content-MD5"] = base64_encode(md5($content, true));
        }
        $headers['Content-Type'] = 'application/octet-stream;charset=utf-8';
        return $headers;
    }

    /**
     * 替换Uri中的参数
     * @param string $uri
     * @return mixed
     */
    private function replaceOccupiedParameters($uri)
    {
        $result = $uri;
        foreach ($this->pathParameters as $pathParameterKey => $apiParameterValue) {
            $target = "[" . $pathParameterKey . "]";
            $result = str_replace($target, $apiParameterValue, $result);
        }
        return $result;
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
            $headerString = $headerString . $sortMapKey . ':' . $sortMapValue . self::$headerSeparator;
        }
        return $headerString;
    }

    /**
     * 获取Uri参数
     * @return array
     */
    public function getPathParameters()
    {
        return $this->pathParameters;
    }

    /**
     * 设置Uri参数
     * @param string $name
     * @param string $value
     */
    public function putPathParameter($name, $value)
    {
        $this->pathParameters[$name] = $value;
    }
}