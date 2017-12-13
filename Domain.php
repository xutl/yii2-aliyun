<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace xutl\aliyun;

/**
 * Class Domain
 * @method checkDomain(array $params) 查询域名是否可注册
 *
 * @package xutl\aliyun
 */
class Domain extends BaseClient
{
    /**
     * @var string
     */
    public $baseUrl = 'https://alidns.aliyuncs.com';

    /**
     * @var string
     */
    public $version = '2015-01-09';


}