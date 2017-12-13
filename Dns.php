<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace xutl\aliyun;

/**
 * Class Dns
 *
 * 域名管理
 * @method addDomain(array $params) 添加域名
 * @method deleteDomain(array $params) 删除域名
 * @method describeDomains(array $params) 获取域名列表
 * @method describeDomainInfo(array $params) 获取域名信息
 * @method describeDomainWhoisInfo(array $params) 获取域名 Whois 信息
 * @method modifyHichinaDomainDNS(array $params) 修改万网域名 DNS
 * @method getMainDomainName(array $params) 获取主域名名称
 * @method describeDomainLogs(array $params) 获取域名操作日志
 *
 * 云解析产品管理接口
 * @method describeDnsProductInstances(array $params) 获取云解析收费版本产品列表
 * @method changeDomainOfDnsProduct(array $params) 更换云解析产品绑定的域名
 *
 * 域名分组
 * @method addDomainGroup(array $params) 添加域名分组
 * @method updateDomainGroup(array $params) 修改域名分组
 * @method deleteDomainGroup(array $params) 删除域名分组
 * @method changeDomainGroup(array $params) 更换域名分组
 * @method describeDomainGroups(array $params) 获取域名分组列表
 *
 * 域名找回接口
 * @method retrievalDomainName(array $params) 发起找回域名
 * @method applyForRetrievalDomainName(array $params) 申请由管理员找回
 * @method checkDomainRecord(array $params) 检测解析记录是否生效
 *
 * 解析管理接口
 * @method addDomainRecord(array $params) 添加解析记录
 * @method deleteDomainRecord(array $params) 删除解析记录
 * @method updateDomainRecord(array $params) 修改解析记录
 * @method setDomainRecordStatus(array $params) 设置解析记录状态
 * @method describeDomainRecords(array $params) 获取解析记录列表
 * @method describeDomainRecordInfo(array $params) 获取解析记录信息
 * @method describeSubDomainRecords(array $params) 获取子域名的解析记录列表
 * @method deleteSubDomainRecords(array $params) 删除主机记录对应的解析记录
 * @method describeRecordLogs(array $params) 获取域名的解析操作日志
 *
 * 解析负载均衡接口
 * @method setDNSSLBStatus(array $params) 开启、关闭解析负载均衡
 * @method describeDNSSLBSubDomains(array $params) 获取解析负载均衡的子域名列表
 * @method updateDNSSLBWeight(array $params) 修改解析负载均衡权重
 *
 * 批量管理接口
 * @method deleteBatchDomains(array $params) 批量删除域名
 * @method addBatchDomainRecords(array $params) AddBatchDomainRecords
 * @method updateBatchDomainRecords(array $params) 批量修改解析记录
 * @method deleteBatchDomainRecords(array $params) 批量删除解析记录
 * @method describeBatchResult(array $params) 查询批量操作结果
 *
 * @see https://help.aliyun.com/document_detail/29739.html
 * @package xutl\aliyun
 */
class Dns extends BaseClient
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