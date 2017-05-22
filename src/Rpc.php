<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace xutl\aliyun;

use Yii;
use yii\httpclient\Response;
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
    protected $signatureMethod ='HMAC-SHA1';

    /**
     * @var string
     */
    protected $signatureVersion ='1.0';

    /**
     * @var string
     */
    protected $dateTimeFormat ='Y-m-d\TH:i:s\Z';

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
     * 请求Api接口
     * @param string $url
     * @param string $method
     * @param array $params
     * @param array $headers
     * @return Response
     */
    public function api($url, $method, array $params = [], array $headers = [])
    {
        $params['Version'] = $this->version;
        $params['Format'] = 'JSON';
        $params['AccessKeyId'] = $this->accessId;
        $params['SignatureMethod'] = $this->signatureMethod;
        $params['Timestamp'] = gmdate($this->dateTimeFormat);
        $params['SignatureVersion'] = $this->signatureVersion;
        $params['SignatureNonce'] = uniqid();

        //参数排序
        ksort($params);
        $query = http_build_query($params, null, '&', PHP_QUERY_RFC3986);
        $source = strtoupper($method) . '&%2F&' . $this->percentEncode($query);

        //签名
        $params['Signature'] = base64_encode(hash_hmac('sha1', $source, $this->accessKey . '&', true));

        return parent::api($url, $method, $params, $headers);
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