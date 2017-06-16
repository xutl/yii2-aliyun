<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace xutl\aliyun;

use yii\httpclient\Exception;
use yii\httpclient\Response;
use yii\base\InvalidConfigException;

/**
 * Class Green
 * @package xutl\aliyun
 */
class Green extends Roa
{
    /**
     * @var string 网关地址
     */
    public $baseUrl = 'http://green.cn-hangzhou.aliyuncs.com';

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
     * @throws Exception
     */
    public function imageScan($tasks = [])
    {
        foreach ($tasks as $key => $val) {
            if (!isset($val['dataId'])) $tasks[$key]['dataId'] = uniqid();
        }
        $response = $this->api('/green/image/scan', 'POST', [
            "tasks" => $tasks,
            "scenes" => [
                'porn','terrorism'
            ]
        ]);
        if (200 == $response['code']) {
            return $response['data'];
        } else {
            throw new Exception($response['msg'], $response['code']);
        }
    }

    /**
     * 同步图像OCI识别
     * @param array $tasks
     * @return mixed
     * @throws Exception
     */
    public function imageOci($tasks = [])
    {
        foreach ($tasks as $key => $val) {
            if (!isset($val['dataId'])) $tasks[$key]['dataId'] = uniqid();
        }
        $response = $this->api('/green/image/scan', 'POST', [
            "tasks" => $tasks,
            "scenes" => [
                'oci'
            ]
        ]);
        if (200 == $response['code']) {
            return $response['data'];
        } else {
            throw new Exception($response['msg'], $response['code']);
        }
    }

    /**
     * 同步图像人脸识别
     * @param array $tasks
     * @return mixed
     * @throws Exception
     */
    public function imageFace($tasks = [])
    {
        foreach ($tasks as $key => $val) {
            if (!isset($val['dataId'])) $tasks[$key]['dataId'] = uniqid();
        }
        $response = $this->api('/green/image/scan', 'POST', [
            "tasks" => $tasks,
            "scenes" => [
                'sface'
            ]
        ]);
        if (200 == $response['code']) {
            return $response['data'];
        } else {
            throw new Exception($response['msg'], $response['code']);
        }
    }

    /**
     * 文本垃圾检测
     * @param array $tasks
     * @return array
     * @throws Exception
     */
    public function textScan($tasks = [])
    {
        foreach ($tasks as $key => $val) {
            if (!isset($val['dataId'])) $tasks[$key]['dataId'] = uniqid();
        }
        $response = $this->api('/green/text/scan', 'POST', [
            "tasks" => $tasks,
            "scenes" => [
                'antispam'
            ]
        ]);
        if (200 == $response['code']) {
            return $response['data'];
        } else {
            throw new Exception($response['msg'], $response['code']);
        }
    }

    /**
     * 关键词检测
     * @param array $tasks
     * @return array
     * @throws Exception
     */
    public function keywordScan($tasks = [])
    {
        foreach ($tasks as $key => $val) {
            if (!isset($val['dataId'])) $tasks[$key]['dataId'] = uniqid();
        }
        $response = $this->api('/green/text/scan', 'POST', [
            'tasks' => $tasks,
            'scenes' => [
                'keyword'
            ]
        ]);
        return $this->getResponse($response);
    }

    /**
     * @param array $response
     * @return mixed
     * @throws Exception
     */
    public function getResponse($response)
    {
        if (200 == $response['code']) {
            $taskResults = $response['data'];
            return $taskResults;
        } else {
            throw new Exception($response['msg'], $response['code']);
        }
    }
}