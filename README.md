# gitHook

> 当进行开发的环境在本地，而运行的环境要在服务端时，每一次提交代码都需要在服务端pull一次。而利用git的hooks功能，能够让我们省去这一步，下面我就以码云的webhooks为例，实现服务端的代码自动同步部署。

## 了解 git 的 hooks

### 关于 git 钩子

Git 能在特定的重要动作发生时触发自定义脚本。 有两组这样的钩子：客户端的和服务器端的。 客户端钩子由诸如提交和合并这样的操作所调用，而服务器端钩子作用于诸如接收被推送的提交这样的联网操作。 你可以随心所欲地运用这些钩子。  

### 如何使用钩子

钩子都被存储在 Git 目录下的 ==hooks== 子目录中。 也即绝大部分项目中的 ==.git/hooks== 。 当你用 ==git init== 初始化一个新版本库时，Git 默认会在这个目录中放置一些示例脚本。这些脚本除了本身可以被调用外，它们还透露了被触发时所传入的参数。 所有的示例都是 shell 脚本，其中一些还混杂了 Perl 代码，不过，任何正确命名的可执行脚本都可以正常使用 —— 你可以用 Ruby 或 Python，或其它语言编写它们。 这些示例的名字都是以 ==.sample== 结尾，如果你想启用它们，得先移除这个后缀。

把一个正确命名且可执行的文件放入 Git 目录下的 hooks 子目录中，即可激活该钩子脚本。 这样一来，它就能被 Git 调用。 接下来，我们会讲解常用的钩子脚本类型。

具体使用可以参考官方文档：[Git Hookes](https://git-scm.com/book/en/v2/Customizing-Git-Git-Hooks)

## 了解 webhooks

钩子功能（callback），是帮助用户 push 了代码后，自动回调一个你设定的 http 地址。 这是一个通用的解决方案，用户可以自己根据不同的需求，来编写自己的脚本程序（比如发邮件，自动部署等）；目前，webhooks 支持多种触发方式，支持复选。

webhooks 的请求方式为POST请求，有两种数据格式可以选择，JSON 和 web 的 form参数，可以自行选择是否使用密码来确定请求。（注意：该密码是明文)

不同托管平台的POST数据格式都不太一样，不过也不会有太大影响，只是解析数据的时候注意就行了，下面是码云的 Push 操作回调的 json 数据：

```
{
    "before": "fb32ef5812dc132ece716a05c50c7531c6dc1b4d", 
    "after": "ac63b9ba95191a1bf79d60bc262851a66c12cda1", 
    "ref": "refs/heads/master", 
    "user_id": 13,
    "user_name": "123", 
    "user": {
      "name": "123",
      "username": "test123",
      "url": "https://gitee.com/oschina"
    }, 
    "repository": {
        "name": "webhook", 
        "url": "http://git.oschina.net/oschina/webhook", 
        "description": "", 
        "homepage": "https://gitee.com/oschina/webhook"
    }, 
    "commits": [
        {
            "id": "ac63b9ba95191a1bf79d60bc262851a66c12cda1", 
            "message": "1234 bug fix", 
            "timestamp": "2016-12-09T17:28:02 08:00", 
            "url": "https://gitee.com/oschina/webhook/commit/ac63b9ba95191a1bf79d60bc262851a66c12cda1", 
            "author": {
                "name": "123", 
                "email": "123@123.com", 
                "time": "2016-12-09T17:28:02 08:00"
            }
        }
    ], 
    "total_commits_count": 1, 
    "commits_more_than_ten": false, 
    "project": {
        "name": "webhook", 
        "path": "webhook", 
        "url": "https://gitee.com/oschina/webhook", 
        "git_ssh_url": "git@gitee.com:oschina/webhook.git", 
        "git_http_url": "https://gitee.com/oschina/webhook.git", 
        "git_svn_url": "svn://gitee.com/oschina/webhook", 
        "namespace": "oschina", 
        "name_with_namespace": "oschina/webhook", 
        "path_with_namespace": "oschina/webhook", 
        "default_branch": "master"
    }, 
    "hook_name": "push_hooks", 
    "password": "pwd"
}
```
其他的具体数据可以到各个官网查看：[码云](http://git.mydoc.io/?t=154711#text_154711)、[Coding](https://open.coding.net/webhooks/)、[GitHub](https://developer.github.com/webhooks/)


## Use（使用步骤）

1. git clone xxxx () 放在目标
2. 配置目录权限
    ```
    ## 如果需要日志, 修改日志权限
    mkdir logs
    chmod 777 -R logs
    ```
3. 修改目录权限
    
    ```
    chown -R www-data /var/www/githook # 这里请改成你创建的hook目录
    chown -R www-data /var/www/Project # 这里请改成你的项目目录
    ```
4. 设置目标仓库webhook
![webhook](https://image-static.segmentfault.com/205/931/2059310629-5a462fca45ba0_articlex)

 设置URL  
```URL:http://<domain>/githook/pull.php?[is_log=false][&path=xxx]```
* `is_log` 是否开启日志；默认false
* `path` 仓库位置；默认 '../{repositoryname}',`repositoryname`:仓库名；


### 注意事项

如果配置都没有问题，但是就是不会自动拉取，那应该是用户的权限配置问题，可以先查看运行php代码的具体用户是什么，然后为该用户开启权限。

```$xslt
system("whoami"); // 查看是哪个用户执行该命令
```