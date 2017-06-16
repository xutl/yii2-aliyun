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

    public $regionId = 'cn-hangzhou';

    /**
     * 文本垃圾检测
     */
    public function textScan()
    {
        $task1 = array(
            'dataId' => uniqid(),
            'content' => '你真棒'
        );
        $data = json_encode(array(
                "tasks" => array($task1),
                "scenes" => array("antispam")
            )
        );

        return $this->api('green/text/scan', 'POST', $data);
    }

    /**
     * 关键词检测
     */
    public function keywordScan()
    {

    }
}