<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace xutl\aliyun\components;

use xutl\aliyun\BaseClient;

/**
 * Class Sms
 *
 * @see
 * @package xutl\aliyun
 */
class Sms extends BaseClient
{
    /**
     * @var string 网关地址
     */
    public $baseUrl = 'https://dysmsapi.aliyuncs.com/';

    /**
     * @var string Api接口版本
     */
    public $version = '2017-05-25';
}