# kriss/composer-assets-plugin

[中文](./README_CN.md)

Purpose of the plugin:

Static resources (such as jquery, bootstrap, etc.) are required in PHP projects,

I don't want to use NPM (it may not be NodeJs in the online environment),

I don't want to download the corresponding resources from NPM or GitHub and put them back into the project (it will pollute the Git record, and there may be developers who may have changed such resources)


Logic: Using the Composer plugin, download resources to a folder in the project through the configured URL, and specify the extraction of partial files

## Usage

### 1. Installation dependencies

```bash
composer require kriss/composer-assets-plugin
```

### 2. Configure the resources that need to be downloaded

Configure 'composer. json' in the root directory of the project and add the following configuration (without comments):

```json5
{
  "extra": {
    "assets-dir": "public/assets", // Relative to the vendor directory
    "assets-pkgs": [ // array
      {
        // An example of any URL, from github (currently only supports resources of the. zip/. tar. gz/. tgz class)
        "url": "https://github.com/baidu/amis/releases/download/v2.2.0/sdk.tar.gz",
        "save_path": "amis@2.2.0", // 将保存到 public/assets/amis@2.2.0 下
      },
      {
        // Example of any URL, from NPM
        "url": "https://registry.npmjs.org/amis/-/amis-2.2.0.tgz",
        "save_path": "amis@2.2.0",
      },
      {
        // Example of NPM (recommended)
        "type": "npm",
        "name": "amis", // npm package name
        "version": "2.2.0",
        "only_files": [ // Specify which files are required. After the change, the downloaded directory needs to be deleted, otherwise it cannot be updated
          "sdk/thirds",
          "sdk/helper.css",
          "sdk/iconfont.css",
          "sdk/sdk.js"
        ],
        // "save_path": "amis/2.2.0", // Can override the default NPM save path address
      },
      {
        // Example of Github (using source code)
        "type": "github",
        "name": "baidu/amis", // github repo
        "version": "v2.2.0" // github tag
      }
    ]
  }
}
```

### 3. Execute Download

```bash
composer assets-download
# or
composer install # trigger from event
```
