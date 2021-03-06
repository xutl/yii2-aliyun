<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace xutl\aliyun\components;

use xutl\aliyun\BaseAcsClient;

/**
 * 内容安全API
 *
 * @see https://help.aliyun.com/document_detail/53412.html
 * @package xutl\aliyun
 */
class Green extends BaseAcsClient
{
    /**
     * @var string 网关地址
     */
    public $baseUrl = 'https://green.cn-hangzhou.aliyuncs.com';

    /**
     * @var string 绿网接口版本，当前版本为：2017-01-12
     */
    public $version = '2017-01-12';

    /**
     * @var string 可用区
     */
    public $regionId = 'cn-hangzhou';

    /**
     * 同步图片鉴黄暴恐
     * @param array $tasks
     * @return mixed
     */
    public function imageScan($tasks = [])
    {
        foreach ($tasks as $key => $val) {
            if (!isset($val['dataId'])) $tasks[$key]['dataId'] = uniqid();
        }
        $response = $this->post('/green/image/scan', [
            "tasks" => $tasks,
            "scenes" => [
                'porn','terrorism'
            ]
        ]);
        return $response->data;
    }

    /**
     * 同步图像OCI识别
     * @param array $tasks
     * @return mixed
     */
    public function imageOci($tasks = [])
    {
        foreach ($tasks as $key => $val) {
            if (!isset($val['dataId'])) $tasks[$key]['dataId'] = uniqid();
        }
        $response = $this->post('/green/image/scan', [
            "tasks" => $tasks,
            "scenes" => [
                'oci'
            ]
        ]);
        return $response->data;
    }

    /**
     * 同步图像人脸识别
     * @param array $tasks
     * @return mixed
     */
    public function imageFace($tasks = [])
    {
        foreach ($tasks as $key => $val) {
            if (!isset($val['dataId'])) $tasks[$key]['dataId'] = uniqid();
        }
        $response = $this->post('/green/image/scan', [
            "tasks" => $tasks,
            "scenes" => [
                'sface'
            ]
        ]);
        return $response->data;
    }

    /**
     * 文本垃圾检测
     * @param array $tasks
     * @return array
     */
    public function textScan($tasks = [])
    {
        foreach ($tasks as $key => $val) {
            if (!isset($val['dataId'])) $tasks[$key]['dataId'] = uniqid();
        }
        $response = $this->post('/green/text/scan',  [
            "tasks" => $tasks,
            "scenes" => [
                'antispam'
            ]
        ]);
        return $response->data;
    }

    /**
     * 关键词检测
     * @param array $tasks
     * @return array
     */
    public function keywordScan($tasks = [])
    {
        foreach ($tasks as $key => $val) {
            if (!isset($val['dataId'])) $tasks[$key]['dataId'] = uniqid();
        }
        $response = $this->post('/green/text/scan',  [
            'tasks' => $tasks,
            'scenes' => [
                'keyword'
            ]
        ]);
        return $response->data;
    }
}