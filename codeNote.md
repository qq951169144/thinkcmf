代码笔记
===============

###thinkcmf方面
* navigation系列标签必须所有父节点和子节点的样式保持一致

* 模板配置文件(json) 需要在前台显示的一定要加上is_public:1 例子：
````
    {
      "name": "头部配置文件",
      "action": "public/header",
      "description": "头部配置文件",
      "order": 1,
      "is_public": 1,
      "more": {
        "vars": {
          "logo": {
            "title": "网站logo",
            "value": "",
            "type": "image",
            "tip": "选择图片做logo"
          },
          "site_name": {
            "title": "网站名称",
            "value": "山岚烟雨",
            "type": "text",
            "tip": "网站名称"
          }
        }
      }
    }
````