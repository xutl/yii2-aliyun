<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace xutl\aliyun\components;

use xutl\aliyun\BaseClient;

/**
 * Class SCDN
 * SCDN（Secure Content Delivery Network），即拥有安全防护能力的CDN服务，提供稳定加速的同时，智能预判攻击行为，通过智能的调度系统将
 * DDoS、CC恶意请求切换至高防IP完成清洗，而真正用户的请求则正常从加速节点获取资源，达到 加速 和 安全 兼顾的目标。
 * @package xutl\aliyun
 */
class Scdn extends BaseClient
{
    /**
     * @var string
     */
    public $baseUrl = 'https://scdn.aliyuncs.com';

    /**
     * @var string
     */
    public $version = '2017-11-15';
}