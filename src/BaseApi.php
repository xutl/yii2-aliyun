<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace xutl\aliyun;

use Yii;
use yii\base\Component;
use yii\httpclient\Client;
use yii\httpclient\Exception;
use yii\helpers\ArrayHelper;
use yii\httpclient\Response;
use yii\base\InvalidConfigException;

/**
 * Class BaseApi
 * @package xutl\aliyun
 */
class BaseApi extends Component
{
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

    /**
     * @var array 请求选项
     * @see https://github.com/yiisoft/yii2-httpclient/blob/master/docs/guide/usage-request-options.md
     */
    public $requestOptions = [];

    /**
     * @inheritdoc
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
     * @param string $url request URL.
     * @param array $params request params.
     * @param array $headers additional request headers.
     * @return array response.
     * @throws Exception
     */
    protected function sendRequest($method, $url, array $params = [], array $headers = [])
    {
        $response = $request = $this->getHttpClient()->createRequest()
            ->setUrl($url)
            ->setMethod($method)
            ->setHeaders($headers)
            ->setData($params)
            ->send();
        if ($response->isOk) {
            throw new Exception($response->content, $response->statusCode);
        }
        return $response->getData();
    }

    /**
     * 合并基础URL和参数
     * @param string $url base URL.
     * @param array $params GET params.
     * @return string composed URL.
     */
    protected function composeUrl($url, array $params = [])
    {
        if (strpos($url, '?') === false) {
            $url .= '?';
        } else {
            $url .= '&';
        }
        $url .= http_build_query($params, '', '&', PHP_QUERY_RFC3986);
        return $url;
    }

    /**
     * send Get Request
     * @param string $url
     * @param array $params
     * @param array $headers
     * @return array response.
     */
    protected function get($url, array $params = [], array $headers = [])
    {
        return $this->api($url, 'GET', $params, $headers);
    }

    /**
     * send Post Request
     * @param string $url
     * @param array $params
     * @param array $headers
     * @return array response.
     */
    protected function post($url, array $params = [], array $headers = [])
    {
        return $this->api($url, 'POST', $params, $headers);
    }

    /**
     * send Request
     * @param string $url
     * @param string $method
     * @param array $params
     * @param array $headers
     * @return array response.
     */
    public function api($url, $method, array $params = [], array $headers = [])
    {
        return $this->sendRequest($method, $url, $params, $headers);
    }
}