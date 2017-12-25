<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace xutl\aliyun;

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
     */
    public function RequestEvent(RequestEvent $event)
    {
        $params = $event->request->getData();
    }
}
