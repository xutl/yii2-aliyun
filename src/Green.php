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
    public $baseUrl = 'http://green.cn-shanghai.aliyuncs.com';

    /**
     * @var string Api接口版本
     */
    public $version = '2017-01-12';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }


    public function a()
    {
        return $this->get('/green/text/scan', [

        ]);

    }
}