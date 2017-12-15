<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace xutl\aliyun;

use yii\di\ServiceLocator;
use yii\base\InvalidConfigException;

/**
 * Class Aliyun
 * @package xutl\aliyun
 */
class Aliyun extends ServiceLocator
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
     * Aliyun constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->preInit($config);
        parent::__construct($config);
    }

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
     * 预处理组件
     * @param array $config
     */
    public function preInit(&$config)
    {
        // merge core components with custom components
        foreach ($this->coreComponents() as $id => $component) {
            if (!isset($config['components'][$id])) {
                $config['components'][$id] = $component;
            } elseif (is_array($config['components'][$id]) && !isset($config['components'][$id]['class'])) {
                $config['components'][$id]['class'] = $component['class'];
            }
        }
    }

    /**
     * 获取云推送实例
     * @return object|CloudPush
     * @throws InvalidConfigException
     */
    public function getCloudPush()
    {
        return $this->get('cloudPush');
    }

    /**
     * 获取CDN实例
     * @return object|HttpDns
     * @throws InvalidConfigException
     */
    public function getCdn()
    {
        return $this->get('cdn');
    }

    /**
     * 获取Domain实例
     * @return object|Domain
     * @throws InvalidConfigException
     */
    public function getDomain()
    {
        return $this->get('domain');
    }

    /**
     * 获取Dns实例
     * @return object|Dns
     * @throws InvalidConfigException
     */
    public function getDns()
    {
        return $this->get('dns');
    }

    /**
     * 获取HttpDns实例
     * @return object|HttpDns
     * @throws InvalidConfigException
     */
    public function getHttpDns()
    {
        return $this->get('httpDns');
    }

    /**
     * 获取Live实例
     * @return object|Live
     * @throws InvalidConfigException
     * @return boolean whether service exist.
     */
    public function getLive()
    {
        return $this->get('live');
    }

    /**
     * 获取邮件推送
     * @return object|Dm
     * @throws InvalidConfigException
     */
    public function getDm()
    {
        return $this->get('dm');
    }

    /**
     * 获取SMS实例
     * @return object|Sms
     * @throws InvalidConfigException
     */
    public function getSms()
    {
        return $this->get('sms');
    }

    /**
     * 获取 内容安全实例
     * @return object|Green
     * @throws InvalidConfigException
     */
    public function getGreen()
    {
        return $this->get('green');
    }

    /**
     * 获取函数计算
     * @return object|Fc
     * @throws InvalidConfigException
     */
    public function getFc(){
        return $this->get('fc');
    }

    /**
     * Returns the configuration of aliyun components.
     * @see set()
     */
    public function coreComponents()
    {
        return [
            'cloudPush' => ['class' => 'xutl\aliyun\CloudPush'],
            'cdn' => ['class' => 'xutl\aliyun\Cdn'],
            'domain' => ['class' => 'xutl\aliyun\Domain'],
            'dns' => ['class' => 'xutl\aliyun\Dns'],
            'httpDns' => ['class' => 'xutl\aliyun\Dns'],
            'live' => ['class' => 'xutl\aliyun\Live'],
            'green' => ['class' => 'xutl\aliyun\Green'],
            'dm' => ['class' => 'xutl\aliyun\Dm'],
            'sms' => ['class' => 'xutl\aliyun\Sms'],
            'fc' => ['class' => 'xutl\aliyun\Fc'],
        ];
    }
}