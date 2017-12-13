<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace xutl\aliyun;

use yii\base\InvalidConfigException;
use yii\helpers\Json;
use yii\httpclient\Client;
use yii\httpclient\RequestEvent;

/**
 * Class Green
 * @package xutl\aliyun
 */
class Green extends Client
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
     * @var string 网关地址
     */
    public $baseUrl = 'http://green.cn-hangzhou.aliyuncs.com';

    /**
     * @var string 绿网接口版本，当前版本为：2017-01-12
     */
    public $version = '2017-01-12';

    /**
     * @var string 可用区
     */
    public $regionId = 'cn-hangzhou';

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
     * 同步图片鉴黄暴恐
     * @param array $tasks
     * @return mixed
     */
    public function imageScan($tasks = [])
    {
        foreach ($tasks as $key => $val) {
            if (!isset($val['dataId'])) $tasks[$key]['dataId'] = uniqid();
        }
        $response = $this->post('/green/image/scan', [
            "tasks" => $tasks,
            "scenes" => [
                'porn','terrorism'
            ]
        ]);
        return $response->data;
    }

    /**
     * 同步图像OCI识别
     * @param array $tasks
     * @return mixed
     */
    public function imageOci($tasks = [])
    {
        foreach ($tasks as $key => $val) {
            if (!isset($val['dataId'])) $tasks[$key]['dataId'] = uniqid();
        }
        $response = $this->post('/green/image/scan', [
            "tasks" => $tasks,
            "scenes" => [
                'oci'
            ]
        ]);
        return $response->data;
    }

    /**
     * 同步图像人脸识别
     * @param array $tasks
     * @return mixed
     */
    public function imageFace($tasks = [])
    {
        foreach ($tasks as $key => $val) {
            if (!isset($val['dataId'])) $tasks[$key]['dataId'] = uniqid();
        }
        $response = $this->post('/green/image/scan', [
            "tasks" => $tasks,
            "scenes" => [
                'sface'
            ]
        ]);
        return $response->data;
    }

    /**
     * 文本垃圾检测
     * @param array $tasks
     * @return array
     */
    public function textScan($tasks = [])
    {
        foreach ($tasks as $key => $val) {
            if (!isset($val['dataId'])) $tasks[$key]['dataId'] = uniqid();
        }
        $response = $this->post('/green/text/scan',  [
            "tasks" => $tasks,
            "scenes" => [
                'antispam'
            ]
        ]);
        return $response->data;
    }

    /**
     * 关键词检测
     * @param array $tasks
     * @return array
     */
    public function keywordScan($tasks = [])
    {
        foreach ($tasks as $key => $val) {
            if (!isset($val['dataId'])) $tasks[$key]['dataId'] = uniqid();
        }
        $response = $this->post('/green/text/scan',  [
            'tasks' => $tasks,
            'scenes' => [
                'keyword'
            ]
        ]);
        return $response->data;
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