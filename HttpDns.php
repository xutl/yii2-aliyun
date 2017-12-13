<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace xutl\aliyun;

/**
 * Class HttpDns
 *
 * @method getAccountInfo() 获取账户信息接口
 * @method getResolveStatistics(array $params) 获取解析统计信息接口
 * @method listDomains(array $params) 获取用户域名及解析次数接口
 * @method deleteDomain(array $params) 删除域名接口
 * @method addDomain(array $params) 添加域名接口
 *
 * @package xutl\aliyun
 */
class HttpDns extends BaseClient
{
    /**
     * @var string
     */
    public $baseUrl = 'https://httpdns-api.aliyuncs.com';

    /**
     * @var string
     */
    public $version = '2016-02-01';

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