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
     * 获取Dns实例
     * @return object|HttpDns
     * @throws InvalidConfigException
     */
    public function getDns()
    {
        return Yii::createObject([
            'class' => 'xutl\aliyun\DNS',
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
}