<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace xutl\aliyun\components;

use xutl\aliyun\BaseClient;

/**
 * Class Vod
 * 视频点播
 *
 * 视频上传接口
 * @method createUploadVideo(array $params) 获取视频上传地址和凭证
 * @method refreshUploadVideo(array $params) 刷新视频上传凭证
 * @method createUploadImage(array $params) 获取图片上传地址和凭证
 * @method putObject(array $params) 使用简单方式上传Object
 * @method postObject(array $params) 使用表单方式上传Object
 * @method appendObject(array $params) 使用追加写方式上传Object
 * @method initiateMultipartUpload(array $params) 初始化MultipartUpload事件
 * @method uploadPart(array $params) 分块上传文件
 * @method completeMultipartUpload(array $params) 完成整个文件的MultipartUpload上传
 * @method abortMultipartUpload(array $params) 取消MultipartUpload事件
 * @method listMultipartUploads(array $params) 罗列出所有执行中的MultipartUpload事件
 * @method listParts(array $params) 罗列出指定Upload ID所属的所有已经上传成功Part
 * @method getObjectMeta(array $params) 获取Object的Meta信息
 * @method headObject(array $params) 获取Object的Meta信息，同GetObjectMeta
 *
 * 视频播放接口
 * @method getPlayInfo(array $params) 获取视频播放地址
 * @method getVideoPlayAuth(array $params) 获取视频播放凭证
 *
 * 视频管理接口
 * @method getVideoInfo(array $params) 获取视频信息
 * @method updateVideoInfo(array $params) 修改视频信息
 * @method deleteVideo(array $params) 删除视频
 * @method getVideoList(array $params) 获取视频信息列表
 * @method getMezzanineInfo(array $params) 获取源文件地址
 * @method deleteStream(array $params) 删除媒体流
 *
 * 视频分类接口
 * @method addCategory(array $params) 创建分类
 * @method updateCategory(array $params) 修改分类
 * @method deleteCategory(array $params) 删除分类
 * @method getCategories(array $params) 获取分类及其子分类
 *
 * @package xutl\aliyun
 */
class Vod extends BaseClient
{
    /**
     * @var string
     */
    public $baseUrl = 'https://vod.cn-shanghai.aliyuncs.com';

    /**
     * @var string
     */
    public $version = '2017-03-21';
}