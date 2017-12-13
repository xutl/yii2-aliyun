<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace xutl\aliyun;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

/**
 * Class Aliyun
 * @package xutl\aliyun
 */
class Aliyun extends Component
{
    /**
     * @var string 阿里云AccessKey ID
     */
    public $accessId;

    /**
     * @var string AccessKey
     */
    public $accessKey;

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
    }

    /**
     * 获取云推送实例
     * @return object|CloudPush
     * @throws InvalidConfigException
     */
    public function getCloudPush()
    {
        return Yii::createObject([
            'class' => 'xutl\aliyun\CloudPush',
            'accessId' => $this->accessId,
            'accessKey' => $this->accessKey
        ]);
    }

    /**
     * 获取CDN实例
     * @return object|HttpDns
     * @throws InvalidConfigException
     */
    public function getCdn()
    {
        return Yii::createObject([
            'class' => 'xutl\aliyun\Cdn',
            'accessId' => $this->accessId,
            'accessKey' => $this->accessKey
        ]);
    }

    /**
     * 获取Domain实例
     * @return object|Domain
     * @throws InvalidConfigException
     */
    public function getDomain()
    {
        return Yii::createObject([
            'class' => 'xutl\aliyun\Domain',
            'accessId' => $this->accessId,
            'accessKey' => $this->accessKey
        ]);
    }

    /**
     * 获取Dns实例
     * @return object|Dns
     * @throws InvalidConfigException
     */
    public function getDns()
    {
        return Yii::createObject([
            'class' => 'xutl\aliyun\Dns',
            'accessId' => $this->accessId,
            'accessKey' => $this->accessKey
        ]);
    }

    /**
     * 获取HttpDns实例
     * @return object|HttpDns
     * @throws InvalidConfigException
     */
    public function getHttpDns()
    {
        return Yii::createObject([
            'class' => 'xutl\aliyun\HttpDns',
            'accessId' => $this->accessId,
            'accessKey' => $this->accessKey
        ]);
    }

    /**
     * 获取Live实例
     * @return object|Live
     * @throws InvalidConfigException
     */
    public function getLive()
    {
        return Yii::createObject([
            'class' => 'xutl\aliyun\Live',
            'accessId' => $this->accessId,
            'accessKey' => $this->accessKey
        ]);
    }
}