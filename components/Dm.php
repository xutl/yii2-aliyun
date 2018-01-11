<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace xutl\aliyun\components;

use xutl\aliyun\BaseClient;

/**
 * Class Dm
 *
 * @method SingleSendMail(array $params) 单一发信接口，支持发送触发和批量邮件
 * @method BatchSendMail(array $params) 批量发信接口，支持通过调用模板的方式发送批量邮件
 *
 * @see https://help.aliyun.com/document_detail/29434.html
 * @package xutl\aliyun
 */
class Dm extends BaseClient
{
    /**
     * @var string 网关地址
     */
    public $baseUrl = 'https://dm.aliyuncs.com';

    /**
     * @var string Api接口版本
     */
    public $version = '2015-11-23';
}