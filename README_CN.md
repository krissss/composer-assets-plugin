# kriss/composer-assets-plugin

[英文](./README.md)

插件目的：
php 项目中需要用到静态资源（比如 jquery、bootstrap 等），
不想使用 npm（可能线上环境没有 nodejs），
不想从 npm 或 github 将对应的资源下载再放到项目中（会污染 git 记录，还可能存在开发者会更改了这类资源）

逻辑：使用 composer 插件，通过配置的 url 将资源下载到项目的某个文件夹下，并且可以指定提取部分文件

## Usage

### 1. 安装依赖

```bash
composer require kriss/composer-assets-plugin
```

### 2. 配置需要下载的资源

配置项目根目录下的 `composer.json`，添加如下配置（去掉注释）：

```json5
{
  "extra": {
    "assets-dir": "public/assets", // 相对于 vendor 目录
    "assets-pkgs": [ // 数组
      {
        // 任意 url 的例子，从 github（目前仅支持 .zip/.tar.gz/.tgz 类的资源）
        "url": "https://github.com/baidu/amis/releases/download/v2.2.0/sdk.tar.gz",
        "save_path": "amis@2.2.0", // 将保存到 public/assets/amis@2.2.0 下
      },
      {
        // 任意 url 的例子，从 npm
        "url": "https://registry.npmjs.org/amis/-/amis-2.2.0.tgz",
        "save_path": "amis@2.2.0",
      },
      {
        // npm 的例子（建议）
        "type": "npm",
        "name": "amis", // npm package name
        "version": "2.2.0",
        "only_files": [ // 指定只要哪些文件
          "sdk/thirds",
          "sdk/helper.css",
          "sdk/iconfont.css",
          "sdk/sdk.js"
        ],
        // "save_path": "amis/2.2.0", // 可以覆盖默认的 npm 保存路径地址
      },
      {
        // github 的例子（取的是源码）
        "type": "github",
        "name": "baidu/amis", // github repo
        "version": "v2.2.0" // github tag
      }
    ]
  }
}
```

### 3. 执行下载

```bash
composer assets-download
```
