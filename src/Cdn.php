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
 * Class Cdn
 * @package xutl\aliyun
 */
class Cdn extends Rpc
{

    const OBJECT_TYPE_FILE = 'File';
    const OBJECT_TYPE_DIRECTORY = 'Directory';

    /**
     * @var string 网关地址
     */
    public $baseUrl = 'https://cdn.aliyuncs.com/';

    /**
     * @var string Api接口版本
     */
    public $version = '2014-11-11';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    /**
     * 刷新缓存
     * @param string $ObjectPath 输入示例：abc.com/image/1.png，多个URL之间需要用换行符（\n或\r\n）分隔
     * @param string $ObjectType 可选， 刷新的类型， 其值可以为File | Directory，默认是File。
     * @return Response
     */
    public function refreshObjectCaches($ObjectPath, $ObjectType = self::OBJECT_TYPE_FILE)
    {
        return $this->get('', [
            'Action' => 'RefreshObjectCaches',
            'ObjectPath' => $ObjectPath,
            'ObjectType' => $ObjectType,
        ]);
    }

    /**
     * 预热接口
     * @param string $ObjectPath 输入示例：abc.com/image/1.png，多个URL之间需要用换行符（\n或\r\n）分隔
     * @return Response
     */
    public function PushObjectCache($ObjectPath)
    {
        return $this->get('', [
            'Action' => 'PushObjectCache',
            'ObjectPath' => $ObjectPath,
        ]);
    }

    /**
     * 检测IP信息
     * @param string $ip 指定IP地址，不支持批量
     * @return Response
     */
    public function ipInfo($ip)
    {
        return $this->get('', [
            'Action' => 'DescribeIpInfo',
            'IP' => $ip,
        ]);
    }
}