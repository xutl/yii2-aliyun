<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace xutl\aliyun;

/**
 * Class CloudAuth
 * 实人认证 服务端SDK
 *
 * @method getStatus(array $params) 查询认证状态
 * @method getVerifyToken(array $params) 发起认证请求
 * @method submitMaterials(array $params) 提交认证资料
 * @method getMaterials(array $params) 获取认证资料
 * @method compareFaces(array $params) 人脸比对验证
 *
 * @package xutl\aliyun
 */
class CloudAuth extends BaseClient
{
    /**
     * @var string
     */
    public $baseUrl = 'https://cloudauth.aliyuncs.com';

    /**
     * @var string
     */
    public $version = '2017-10-10';
}