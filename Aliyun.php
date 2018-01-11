<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace xutl\aliyun;


use yii\di\ServiceLocator;
use yii\base\InvalidConfigException;
use xutl\aliyun\components\CloudAuth;
use xutl\aliyun\components\CloudPhoto;
use xutl\aliyun\components\CloudPush;
use xutl\aliyun\components\Dm;
use xutl\aliyun\components\Dns;
use xutl\aliyun\components\Domain;
use xutl\aliyun\components\Green;
use xutl\aliyun\components\HttpDns;
use xutl\aliyun\components\Jaq;
use xutl\aliyun\components\Live;
use xutl\aliyun\components\Mts;
use xutl\aliyun\components\Scdn;
use xutl\aliyun\components\Slb;
use xutl\aliyun\components\Sms;
use xutl\aliyun\components\Vod;
use xutl\aliyun\components\Vpc;

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
     * @var array aliyun parameters (name => value).
     */
    public $params = [];

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
     * 获取CDN实例
     * @return object|HttpDns
     * @throws InvalidConfigException
     */
    public function getCdn()
    {
        return $this->get('cdn');
    }

    /**
     * 获取实人认证实例
     * @return object|CloudAuth
     * @throws InvalidConfigException
     */
    public function getCloudAuth()
    {
        return $this->get('cloudAuth');
    }

    /**
     * 获取云相册实例
     * @return object|CloudPhoto
     * @throws InvalidConfigException
     */
    public function getCloudPhoto()
    {
        return $this->get('cloudPhoto');
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
     * 获取邮件推送
     * @return object|Dm
     * @throws InvalidConfigException
     */
    public function getDm()
    {
        return $this->get('dm');
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
     * 获取Domain实例
     * @return object|Domain
     * @throws InvalidConfigException
     */
    public function getDomain()
    {
        return $this->get('domain');
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
     * 获取HttpDns实例
     * @return object|HttpDns
     * @throws InvalidConfigException
     */
    public function getHttpDns()
    {
        return $this->get('httpDns');
    }

    /**
     * 获取移动安全实例
     * @return object|Jaq
     * @throws InvalidConfigException
     * @return boolean whether service exist.
     */
    public function getJaq()
    {
        return $this->get('jaq');
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
     * 获取 MTS 实例
     * @return object|Mts
     * @throws InvalidConfigException
     * @return boolean whether service exist.
     */
    public function getMts()
    {
        return $this->get('mts');
    }

    /**
     * 获取SLB实例
     * @return object|Scdn
     * @throws InvalidConfigException
     */
    public function getScdn()
    {
        return $this->get('scdn');
    }

    /**
     * 获取SLB实例
     * @return object|Slb
     * @throws InvalidConfigException
     */
    public function getSlb()
    {
        return $this->get('slb');
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
     * 获取Vod实例
     * @return object|Vod
     * @throws InvalidConfigException
     */
    public function getVod()
    {
        return $this->get('vod');
    }

    /**
     * 获取Vpc实例
     * @return object|Vpc
     * @throws InvalidConfigException
     */
    public function getVpc()
    {
        return $this->get('vpc');
    }

    /**
     * Returns the configuration of aliyun components.
     * @see set()
     */
    public function coreComponents()
    {
        return [
            'cdn' => ['class' => 'xutl\aliyun\components\Cdn'],
            'cloudAuth' => ['class' => 'xutl\aliyun\components\CloudAuth'],
            'cloudPhoto' => ['class' => 'xutl\aliyun\components\CloudPhoto'],
            'cloudPush' => ['class' => 'xutl\aliyun\components\CloudPush'],
            'dm' => ['class' => 'xutl\aliyun\components\Dm'],
            'dns' => ['class' => 'xutl\aliyun\components\Dns'],
            'domain' => ['class' => 'xutl\aliyun\components\Domain'],
            'green' => ['class' => 'xutl\aliyun\components\Green'],
            'httpDns' => ['class' => 'xutl\aliyun\components\Dns'],
            'jaq' => ['class' => 'xutl\aliyun\components\Jaq'],
            'live' => ['class' => 'xutl\aliyun\components\Live'],
            'mts' => ['class' => 'xutl\aliyun\components\Mts'],
            'slb' => ['class' => 'xutl\aliyun\components\Slb'],
            'scdn' => ['class' => 'xutl\aliyun\components\Scdn'],
            'sms' => ['class' => 'xutl\aliyun\components\Sms'],
            'vod' => ['class' => 'xutl\aliyun\components\Vod'],
            'vpc' => ['class' => 'xutl\aliyun\components\Vpc'],
        ];
    }
}