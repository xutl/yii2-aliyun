<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace xutl\aliyun;

use yii\httpclient\Response;
use yii\base\InvalidConfigException;

/**
 * Class Domain
 * @package xutl\aliyun
 */
class Domain extends Rpc
{
    /**
     * @var string 网关地址
     */
    public $baseUrl = 'https://domain.aliyuncs.com';

    /**
     * @var string Api接口版本
     */
    public $version = '2016-05-11';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    /**
     * 查询域名是否可注
     * @param string $domainName 域名名称
     * @return array
     */
    public function checkDomain($domainName)
    {
        return $this->get('', [
            'Action' => 'CheckDomain',
            'DomainName' => $domainName,
        ]);
    }

    /**
     * 查询域名Whois信息
     * @param string $domainName 域名名称
     * @return array
     */
    public function getWhoisInfo($domainName)
    {
        return $this->get('', [
            'Action' => 'GetWhoisInfo',
            'DomainName' => $domainName,
        ]);
    }
}