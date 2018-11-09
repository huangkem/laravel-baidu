# laravel-baidu

本项目代码大部分参考[overtrue/laravel-wechat](https://github.com/overtrue/wechat)

百度 SDK for Laravel 5 / Lumen， 基于 [liqunx/baidu](https://github.com/liqunx/baidu)


## 框架要求

Laravel/Lumen >= 5.1

## 安装（第一版还没发布，见谅~~~）

```shell
composer require "liqunx/laravel-baidu:*"
```

## 配置

### Laravel 应用

1. 在 `config/app.php` 注册 ServiceProvider 和 Facade (Laravel 5.5 无需手动注册)

```php
'providers' => [
    // ...
    Liqunx\LaravelBaidu\ServiceProvider::class,
],
'aliases' => [
    // ...
    'Baidu' => Liqunx\LaravelBaidu\Facade::class,
],
```

2. 创建配置文件：

```shell
php artisan vendor:publish --provider="Liqunx\LaravelBaidu\ServiceProvider"
```

3. 修改应用根目录下的 `config/baidu.php` 中对应的参数即可。

4. 每个模块基本都支持多账号(坑爹的，没具体测试，可能有bug)，默认为 `default`。

### Lumen 应用

1. 在 `bootstrap/app.php` 中 82 行左右：

```php
$app->register(Liqunx\LaravelBaidu\ServiceProvider::class);
```

2. 如果你习惯使用 `config/baidu.php` 来配置的话，将 `vendor/liqunx/laravel-baidu/src/config.php` 拷贝到`项目根目录/config`目录下，并将文件名改成`baidu.php`。

## 使用

### 我们有以下方式获取 SDK 的服务实例

##### 使用外观

```php
  $aip = Baidu::aip(); // ai模块
  // 其他模块添加中。。。惊不惊喜，意不意外
  
  // 均支持传入配置账号名称
  Baidu::aip('foo'); // `foo` 为配置文件中的名称，默认为 `default`
  //...
```


更多 SDK 的具体使用请参考：啥都没得参考，自己看源码吧，文档我也还没写呢~~

## License

MIT
