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
use yii\base\InvalidParamException;
use yii\helpers\ArrayHelper;

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
     * @var array 服务配置
     */
    private $_services = [];

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
        $this->_services = [
            'cloudPush' => ['class' => 'xutl\aliyun\CloudPush'],
            'cdn' => ['class' => 'xutl\aliyun\Cdn'],
            'domain' => ['class' => 'xutl\aliyun\Domain'],
            'dns' => ['class' => 'xutl\aliyun\Dns'],
            'httpDns' => ['class' => 'xutl\aliyun\Dns'],
            'live' => ['class' => 'xutl\aliyun\Live'],
            'green' => ['class' => 'xutl\aliyun\Green'],
        ];
    }

    /**
     * 设置
     * @param array $services list of $services
     */
    public function setServices(array $services)
    {
        $this->_services = ArrayHelper::merge($this->_services, $services);
    }

    /**
     * 获取接口列表
     * @return BaseClient[]|BaseAcsClient list of services.
     * @throws InvalidConfigException
     */
    public function getServices()
    {
        $services = [];
        foreach ($this->_services as $id => $service) {
            $services[$id] = $this->getService($id);
        }
        return $services;
    }

    /**
     * 获取指定网关
     * @param string $id service id.
     * @return BaseClient|BaseAcsClient service instance.
     * @throws InvalidConfigException
     */
    public function getService($id)
    {
        if (!array_key_exists($id, $this->_services)) {
            throw new InvalidParamException("Unknown service '{$id}'.");
        }
        if (!is_object($this->_services[$id])) {
            $this->_services[$id] = $this->createService($id, $this->_services[$id]);
        }
        return $this->_services[$id];
    }

    /**
     * 检查指定网关是否存在
     * @param string $id service id.
     * @return boolean whether service exist.
     */
    public function hasService($id)
    {
        return array_key_exists($id, $this->_services);
    }

    /**
     * 从配置创建网关实例
     * @param string $id api service id.
     * @param array $config service instance configuration.
     * @return object|BaseClient|BaseAcsClient service instance.
     * @throws InvalidConfigException
     */
    protected function createService($id, $config)
    {
        $config['id'] = $id;
        return Yii::createObject($config);
    }
}