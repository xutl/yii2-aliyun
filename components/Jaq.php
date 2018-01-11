<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace xutl\aliyun\components;

use xutl\aliyun\BaseClient;

/**
 * Class Jaq
 * 移动安全
 *
 * @method DiyShield(array $params) 应用加固接口
 * @method GetShieldResult(array $params) 查询应用加固结果接口
 * @method ScanVuln(array $params) 漏洞扫描接口
 * @method ScanFake(array $params) 仿冒检测接口
 * @method ScanMalware(array $params) 恶意代码扫描接口
 * @method GetRiskDetail(array $params) 查询扫描详细信息接口
 *
 * @package xutl\aliyun
 */
class Jaq extends BaseClient
{
    /**
     * @var string
     */
    public $baseUrl = 'https://jaq.aliyuncs.com';

    /**
     * @var string
     */
    public $version = '2014-11-11';

    public $regionId = 'cn-hangzhou';
}