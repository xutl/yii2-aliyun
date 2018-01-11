<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace xutl\aliyun\components;

use xutl\aliyun\BaseClient;

/**
 * Class Domain
 *
 * 域名信息接口
 * @method checkDomain(array $params) 查询域名是否可注册
 *
 * 订单服务接口
 * @method queryOrder(array $params) 查询订单
 * @method createOrder(array $params) 创建订单
 *
 * 信息模板接口
 * @method saveContactTemplate(array $params) 保存信息模板
 * @method queryContactTemplate(array $params) 查询信息模板
 * @method saveContactTemplateCredential(array $params) 提交信息模板实名认证
 * @method deleteContactTemplate(array $params) 删除信息模板
 *
 * 域名管理接口
 * @method queryContact(array $params) 查询联系人信息
 * @method queryDomainList(array $params) QueryDomainList
 * @method queryDomainBySaleId(array $params) QueryDomainBySaleId
 * @method queryBatchTaskDetailList(array $params) 查询任务详情
 * @method queryBatchTaskList(array $params) QueryBatchTaskList
 * @method queryFailReasonList(array $params) QueryFailReasonList
 * @method saveTaskForModifyingDomainDns(array $params) 提交修改DNS任务
 * @method saveTaskForSubmittingDomainNameCredential(array $params) 提交域名实名认证任务
 * @method saveTaskForSubmittingDomainNameCredentialByTemplateId(array $params) 通过信息模板ID提交域名实名认证任务
 * @method saveTaskForUpdatingContactByTemplateId(array $params) 通过信息模板ID提交修改联系人信息任务
 *
 * @see https://help.aliyun.com/document_detail/42875.html
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