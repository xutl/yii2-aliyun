<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace xutl\aliyun;

use Yii;
use yii\base\Component;
use yii\base\Exception;
use yii\httpclient\Client;
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
     * 获取Http Client
     * @return Client
     */
    public function getHttpClient()
    {
        if (!is_object($this->_httpClient)) {
            $this->_httpClient = new Client([
                'baseUrl' => $this->baseUrl,
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
     * @throws Exception on failure.
     */
    protected function sendRequest($method, $url, array $params = [], array $headers = [])
    {
        $response = $request = $this->getHttpClient()->createRequest()
            ->setUrl($url)
            ->setMethod($method)
            ->setHeaders($headers)
            ->setData($params)
            ->send();
        if (!$response->isOk) {
            throw new Exception ('Http request failed.');
        }
        return $response;
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
     * send Request
     * @param $url
     * @param $method
     * @param array $params
     * @param array $headers
     * @return array
     */
    public function api($url, $method, array $params = [], array $headers = [])
    {

        try {
            return $this->sendRequest($method, $url, $params, $headers);
        } catch (\Exception $e) {
            sleep(10);
            return $this->sendRequest($method, $url, $params, $headers);
        }
    }
}