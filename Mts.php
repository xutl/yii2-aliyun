<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace xutl\aliyun;

use yii\base\InvalidConfigException;

/**
 * Class Mts
 * 媒体转码
 * @package xutl\aliyun
 */
class Mts extends BaseClient
{
    /**
     * @var string Api接口版本
     */
    public $version = '2014-06-18';


    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        if (empty ($this->baseUrl)) {
            throw new InvalidConfigException ('The "baseUrl" property must be set.');
        }
    }
}