<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace xutl\aliyun\components;

use xutl\aliyun\BaseClient;

/**
 * Class Vpc
 * VPC 弹性Ip
 * @package xutl\aliyun
 */
class Vpc extends BaseClient
{
    /**
     * @var string
     */
    public $baseUrl = 'https://vpc.aliyuncs.com';

    /**
     * @var string
     */
    public $version = '2016-04-28';
}