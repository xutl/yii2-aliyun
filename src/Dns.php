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
    public $baseUrl = 'https://alidns.aliyuncs.com';

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
     * 获取域名分组列表
     * @param int $pageNumber
     * @param int $pageSize
     * @return array
     */
    public function domainGroups($pageNumber = 1, $pageSize = 100)
    {
        $params = [
            'Action' => 'DescribeDomainGroups',
            'PageNumber' => $pageNumber,
            'PageSize' => $pageSize,
        ];
        return $this->get('', $params);
    }

    /**
     * 获取域名列表
     * @param int $pageNumber 当前页数，起始值为1，默认为1
     * @param int $pageSize 分页查询时设置的每页行数，最大值100，默认为20
     * @param string $keyWord 关键字，按照”%KeyWord%”模式搜索，不区分大小写
     * @param string $groupId 域名分组ID，如果不填写则默认为全部分组
     * @return array
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

    /**
     * 添加解析记录
     * @param string $domainName
     * @param string $pr 主机记录，如果要解析@.exmaple.com，主机记录要填写"@”，而不是空
     * @param string $type 解析记录类型
     * @param string $value 记录值
     * @param int $ttl 生存时间，默认为600秒（10分钟）
     * @param int $priority MX记录的优先级，取值范围[1,10]，记录类型为MX记录时，此参数必须
     * @param string $line 解析线路，默认为default。
     * @return array
     */
    public function addDomainRecord($domainName, $pr, $type, $value, $ttl = 600, $priority = 10, $line = 'default')
    {
        $params = [
            'Action' => 'AddDomainRecord',
            'DomainName' => $domainName,
            'RR' => $pr,
            'Type' => $type,
            'Value' => $value,
            'TTL' => $ttl,
            'Priority' => $priority,
            'Line' => $line
        ];
        return $this->get('', $params);
    }

    /**
     * 修改解析记录
     * @param string $recordId
     * @param string $pr 主机记录，如果要解析@.exmaple.com，主机记录要填写"@”，而不是空
     * @param string $type 解析记录类型
     * @param string $value 记录值
     * @param int $ttl 生存时间，默认为600秒（10分钟）
     * @param int $priority MX记录的优先级，取值范围[1,10]，记录类型为MX记录时，此参数必须
     * @param string $line 解析线路，默认为default。
     * @return array
     */
    public function updateDomainRecord($recordId, $pr, $type, $value, $ttl = 600, $priority = 10, $line = 'default')
    {
        $params = [
            'Action' => 'UpdateDomainRecord',
            'RecordId' => $recordId,
            'RR' => $pr,
            'Type' => $type,
            'Value' => $value,
            'TTL' => $ttl,
            'Priority' => $priority,
            'Line' => $line
        ];
        return $this->get('', $params);
    }

    /**
     * 删除解析记录
     * @param string $recordId
     * @return array
     */
    public function deleteDomainRecord($recordId)
    {
        $params = [
            'Action' => 'DeleteDomainRecord',
            'RecordId' => $recordId,
        ];
        return $this->get('', $params);
    }

    /**
     * 设置解析记录状态
     * @param string $recordId 解析记录的ID，此参数在添加解析时会返回，在获取域名解析列表时会返回
     * @param bool $status Enable: 启用解析 Disable: 暂停解析
     * @return array
     */
    public function setDomainRecordStatus($recordId, $status)
    {
        $params = [
            'Action' => 'DeleteDomainRecord',
            'RecordId' => $recordId,
            'Status' => $status == true ? 'Enable' : 'Disable',
        ];
        return $this->get('', $params);
    }
}