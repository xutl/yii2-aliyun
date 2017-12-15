# Aliyun Extension for Yii 2

适用于 Yii2 的 Aliyun SDK。使用了DI实现的，可自行继承扩展。

[![Latest Stable Version](https://poser.pugx.org/xutl/yii2-aliyun/v/stable.png)](https://packagist.org/packages/xutl/yii2-aliyun)
[![Total Downloads](https://poser.pugx.org/xutl/yii2-aliyun/downloads.png)](https://packagist.org/packages/xutl/yii2-aliyun)
[![Reference Status](https://www.versioneye.com/php/xutl:yii2-aliyun/reference_badge.svg)](https://www.versioneye.com/php/xutl:yii2-aliyun/references)
[![Build Status](https://img.shields.io/travis/xutl/yii2-aliyun.svg)](http://travis-ci.org/xutl/yii2-aliyun)
[![Dependency Status](https://www.versioneye.com/php/xutl:yii2-aliyun/dev-master/badge.png)](https://www.versioneye.com/php/xutl:yii2-aliyun/dev-master)
[![License](https://poser.pugx.org/xutl/yii2-aliyun/license.svg)](https://packagist.org/packages/xutl/yii2-aliyun)


Installation
------------

Next steps will guide you through the process of installing  using [composer](http://getcomposer.org/download/). Installation is a quick and easy three-step process.

### Step 1: Install component via composer

Either run

```
composer require --prefer-dist xutl/yii2-aliyun
```

or add

```json
"xutl/yii2-aliyun": "~2.0.0"
```

to the `require` section of your composer.json.

### Step 2: Configuring your application

Add following lines to your main configuration file:

```php
'components' => [
    'aliyun' => [
        'class' => 'xutl\aliyun\Sms',  
        'accessId' => '123456',
        'accessKey' => '654321', 
        'components' => [
             //各子组件配置，如果无需配置不写即可。也可动态注入配置。
            //etc
        ]
    ],
],
```

### Use 

使用方式非常简单

```php
$aliyun = Yii::$app->aliyun;

$cloudPush = $aliyun->getCloudPush();

// 查看文档 https://help.aliyun.com/knowledge_detail/48085.html 请求参数中的 `Action` 省略，其他的照着协商就发包了。
$res = $cloud->pushMessageToAndroid([
    'AppKey'=>'123456',
    'Target' => 'ALL',
    'TargetValue' => 'ALL',
    'Title' => 'Hello',
    'Body' => 'Hello World!',
]);

var_dump($res->isOk);
print_r($res->data);

//其他接口类似调用方式

//如果扩展 暂不支持的接口，直接继承 `\xutl\aliyun\BaseClient` 和 `\xutl\aliyun\BaseAcsClient` 基类即可自带 认证。你只需扩展方法即可。 
```


## License

This is released under the MIT License. See the bundled [LICENSE.md](LICENSE.md)
for details.