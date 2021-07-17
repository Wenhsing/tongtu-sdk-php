# 通途ERP2.0接口请求封装包

因为一些原因，之前的扩展包已经进入了历史的长河中，不过我自己又尝试了新的写法将这些请求封装了一下，以便自己之后学习。简介的话，看名字也就知道了，主要是为了对接通途ERP2.0相关接口而封装的一个 PHP 扩展包，其他的也就不多说了，就简单说一下使用方法吧。

## 安装

按照大多数 `composer` 包一样的安装方式：

```sh
composer require wenhsing/tongtu-sdk-php
```

## 在 Laravel 中使用

使用之前，需要设置通途的相关参数，如果你的是 Laravel 框架，那么安装好之后，可以通过下面的方式生成 `tongtu.php` 配置文件：

```sh
php artisan vendor:publish --tag="wenhsing-tongtu"
```

然后你就可以在你想要使用扩展的地方使用就好，例如下面进行订单查询：

```php
app('tongtu')->ordersQuery()->reuqest(['body' => [
    'accountCode' =>  'test',
    // 可以不需要，扩展包会自动添加
    // 'merchantId' =>  '000XXX',
    'orderStatus' =>  'waitPacking',
    'pageNo' =>  '1',
    'pageSize' =>  '100',
    'payDateFrom' =>  '2018-01-01 00:00:00',
    'payDateTo' =>  '2018-01-01 00:00:00',
]]);
```

## 在其他地方使用

```php
<?php

// 引入自动加载文件
require_once './vendor/autoload.php';

// 实例化Tongtu类和Config类，并传入配置
$c = new Wenhsing\Tongtu\Tongtu(new Wenhsing\Tongtu\Config([
    'enable' => true,
    'app_key' => '82b76df24da14895b21ed5efa80d35b8',
    'app_secret' => '096ab7aa62af4b308098c4ada5fb24435382508794c849cdb6f67517793c9b9d',
    'log' => [
        'name' => 'wenhsing',
        'outpath'  => './',
        'level' => \Monolog\Logger::DEBUG,
    ],
]));
// 获取app token
var_dump($c->appToken()->request());

```

## 其他

如果想要在运行的时候动态修改配置，可以使用下面的方式

例如上面的代码，可以在最后一句前设置一下，以便关闭通途，并报一个提示应用已关闭的异常

```php
$c->getConfig()->set('enable', false);
var_dump($c->appToken()->request());
```

或者，你可以再重新注入一个 `Config` 类

```php
var_dump($c->setConfig(new Wenhsing\Tongtu\Config(['enable' => false]))->appToken()->request());
```

> 注意： 封装的包可能有些因为通途官方有更新修改，导致接口不能使用，你可以选择自行修改，也可以联系我进行包升级，只不过后面的方式会慢一些。
