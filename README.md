# wula-installer

用于安装基于[wula](https://github.com/ninggf/wula)炮架的模块，扩展，模板，assets文件等.

## extra 配置
如果你修改了wula炮架的默认目录，你需要修改wula的`composer.json`文件中的以下部分:
```json
{
  ....
  "extra":{
    "wula":{
      "wwwroot":"wwwroot dir name",
      "modules-dir":"modules dir name",
      "assets-dir":"assets dir name",
      "themes-dir":"themes dir name",
      "extensions-dir":"extensions dir name"
    }
  }
  ...
}
```

## composer.json 配置

```json
{
  "type":"wula-[module|extension|asset|theme]"
}
```

> 注:
> 目前 `type`的值只能是:
> - wula-module: 模块
> - wula-extension: 扩展
> - wula-asset：静态资源
> - wula-theme: 模板