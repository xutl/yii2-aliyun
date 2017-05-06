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
class Rpc extends BaseApi
{
    /**
     * @var string
     */
    public $version;

    /**
     * @var string
     */
    public $signatureMethod ='HMAC-SHA1';

    /**
     * @var string
     */
    public $signatureVersion ='1.0';

    /**
     * @var string
     */
    public $dateTimeFormat ='Y-m-d\TH:i:s\Z';

    /**
     * 请求Api接口
     * @param string $url
     * @param string $method
     * @param array $params
     * @param array $headers
     * @return array
     * @throws Exception
     */
    public function api($url, $method, array $params = [], array $headers = [])
    {
        $params = array_merge([
            'Version' => '2016-11-01',
            'accessKeyId' => '123456',
            'accessSecret' => '654321',
            'signatureMethod' => 'HMAC-SHA1',
            'signatureVersion' => '1.0',
            'dateTimeFormat' => 'Y-m-d\TH:i:s\Z',
        ], $params);
        return parent::api($url, $method, $params, $headers);
    }
}