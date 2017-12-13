<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace xutl\aliyun;

class DNS extends BaseClient
{
    /**
     * @var string
     */
    public $baseUrl = 'https://alidns.aliyuncs.com';

    /**
     * @var string
     */
    public $version = '2015-01-09';

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