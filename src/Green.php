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
     * 文本垃圾检测
     * @param string $content
     * @throws Exception
     */
    public function textScan($content)
    {
        $response = $this->api('/green/text/scan', 'POST', [
            "tasks" => [
                [
                    'dataId' => uniqid(),
                    'content' => $content
                ]
            ],
            "scenes" => [
                'antispam'
            ]
        ]);
        print_r($response);
        return $this->getResponse($response);
    }

    /**
     * 关键词检测
     * @param string $content
     * @return array
     */
    public function keywordScan($content)
    {
        return $this->api('/green/text/scan', 'POST', [
            "tasks" => [
                [
                    'dataId' => uniqid(),
                    'content' => $content
                ]
            ],
            "scenes" => [
                'keyword'
            ]
        ]);
    }

    /**
     * @param array $response
     * @throws Exception
     */
    public function getResponse($response)
    {
        if (200 == $response['code']) {
            $taskResults = $response['data'];
            foreach ($taskResults as $taskResult) {
                if (200 == $taskResult['code']) {
                    $sceneResults = $taskResult['results'];
                    foreach ($sceneResults as $sceneResult) {
                        $scene = $sceneResult['scene'];
                        $suggestion = $sceneResult['suggestion'];
                        //根据scene和suggetion做相关的处理
                        //do something
                        print_r($scene);
                        print_r($suggestion);
                    }
                } else {
                    throw new Exception($taskResult['msg'], $taskResult['code']);
                }
            }
        } else {
            throw new Exception($response['msg'], $response['code']);
        }
    }
}