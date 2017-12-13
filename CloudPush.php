<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace xutl\aliyun;

/**
 * 移动推送
 *
 * App 相关
 * @method listSummaryApps() APP概览列表
 *
 * 推送相关
 * @method pushMessageToAndroid(array $params) 推消息给Android设备
 * @method pushMessageToiOS(array $params) 推消息给iOS设备
 * @method pushNoticeToAndroid(array $params) 推通知给Android设备
 * @method pushNoticeToiOS(array $params) 推通知给iOS设备
 * @method push(array $params) 推送高级接口
 * @method cancelPush(array $params) 取消定时推送任务
 *
 * 查询相关
 * @method listPushRecords(array $params) 查询推送列表
 * @method queryPushStatByApp(array $params) APP维度推送统计
 * @method queryPushStatByMsg(array $params) 任务维度推送统计
 * @method queryDeviceStat(array $params) 设备新增与留存
 * @method queryUniqueDeviceStat(array $params) 去重设备统计
 * @method queryDeviceInfo(array $params) 查询设备详情
 * @method checkDevices(array $params) 批量检查设备有效性
 *
 * TAG相关
 * @method bindTag(array $params) 绑定TAG
 * @method queryTags(array $params) 查询TAG
 * @method unbindTag(array $params) 解绑TAG
 * @method listTags(array $params) TAG列表
 * @method removeTag(array $params) 删除TAG
 *
 * Alias相关
 * @method bindAlias(array $params) 绑定别名
 * @method queryAliases(array $params) 查询别名
 * @method unbindAlias(array $params) 解绑别名
 *
 * @package xutl\aliyun
 */
class CloudPush extends BaseClient
{
    /**
     * @var string
     */
    public $baseUrl = 'https://cloudpush.aliyuncs.com';

    /**
     * @var string
     */
    public $version = '2016-08-01';

    /**
     * 通过__call转发请求
     * @param  string $name 方法名
     * @param  array $arguments 参数
     * @return array
     */
    public function __call($name, $arguments)
    {
        $action = ucfirst($name);
        $params = [];
        if (is_array($arguments) && !empty($arguments)) {
            $params = (array)$arguments[0];
        }
        $params['Action'] = $action;
        return $this->_dispatchRequest($params);
    }

    /**
     * 发起接口请求
     * @param  array $params 接口参数
     * @return array
     */
    protected function _dispatchRequest($params)
    {
        $response = $this->createRequest()
            ->setMethod('POST')
            ->setData($params)
            ->send();
        return $response->data;
    }
}