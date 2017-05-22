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
 * Class Dns
 * @package xutl\aliyun
 */
class Dns extends Rpc
{
    /**
     * @var string 网关地址
     */
    public $baseUrl = 'https://alidns.aliyuncs.com/';

    /**
     * @var string Api接口版本
     */
    public $version = '2015-01-09';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    /**
     * 获取域名列表
     * @param int $pageNumber 当前页数，起始值为1，默认为1
     * @param int $pageSize 分页查询时设置的每页行数，最大值100，默认为20
     * @param string $keyWord 关键字，按照”%KeyWord%”模式搜索，不区分大小写
     * @param string $groupId 域名分组ID，如果不填写则默认为全部分组
     * @return Response
     */
    public function domains($pageNumber = 1, $pageSize = 100, $keyWord = null, $groupId = null)
    {
        $params = [
            'Action' => 'DescribeDomains',
            'PageNumber' => $pageNumber,
            'PageSize' => $pageSize,
        ];
        if (!empty($keyWord)) {
            $params['KeyWord'] = $keyWord;
        }
        if (!empty($groupId)) {
            $params['GroupId'] = $groupId;
        }

        return $this->get('', $params);
    }
}