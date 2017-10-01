# 战网安全令在线版 / Battle.net Authenticator Online

作者:**竹井詩織里**<br>
功能:支持CN/US/EU开头的战网安全令颁发、还原、8位密钥查看，支持暴雪一键安全令功能(即免输安全令一键登录)。<br>
感谢:安全令解析的相关代码修改自[Gilles Crettenand](https://github.com/krtek4)的<a href="https://github.com/krtek4/php-bma">php-bma</a>项目，再次表示感谢.

# 版本更新日志
V0.9：发布于2013-06-20，完成了最基础的用户功能、安全令功能，测试版上线。<br>
V1.0：发布于2014-02-26，修复数个Bug，实装了发送账号提示相关邮件功能。<br>
V1.1：发布于2015-06-22，修复数个Bug，新增使用非对称加密技术，保证HTTP环境下密码传输过程的安全性。<br>
V1.2：发布于2016-06-28，新增战网一键安全令功能，更方便，更安全，但对服务器有一定的压力，后续调整了查询时间，降低了压力。<br>
V2.0：发布于2017-07-24，完全重构了php层的代码，使用Laravel 5.4作为框架，添加Redis作为缓存数据库的功能，增加了小程序所需的接口。<br>

#运行要求
服务器系统：推荐使用Linux服务器，Windows服务器可能导致.env文件加载不完全或乱码等问题。<br>
服务端：建议使用nginx 1.12.0或更新的服务端程序，亦可使用Apache服务端(2.4.9测试通过)。<br>
php版本：至少为5.6.4，推荐7.0及以上，需安装php-gd、php-mysql、php-pdo、php-curl等相关运行库，且需要有php-cli运行环境。<br>
数据存储：用于存储用户数据的Mysql(必须)，用于存储Session和缓存的相关数据的Redis(推荐)，建议两者同时使用。<br>
其他要求：需安装Composer，请参考[Composer中国镜像安装教程](https://pkg.phpcomposer.com/#how-to-install-composer)，另外需要你有一定的计算机相关知识，知道怎么检查错误、生成密钥、对项目初始化等。

## 安装步骤
	1. 按照运行要求搭建好服务器环境；
	2. 使用OpenSSL生成非对称加密所需的公钥值和私钥key文件，将key文件改名后放到storage目录下；
	3. 复制.env.samle，将其重命名为.env.production，修改其中的内容，相关配置的注释都已经在.env内，修改完成后，将整个目录上传到服务器；
	4. 将网站根目录指向public文件夹；
	5. 在Linux环境下，运行"sh install.sh"，即可自动安装，包括数据库导入亦可一并完成，如果未显示"Db insert success."或实际数据库中没有成功导入，请使用SQL工具自行导入database.sql到数据库，Windows环境请参考install.sh文件自行安装，你也可以使用"installWithoutDbInsert.sh"不导入数据库；
	6. 根据不同的服务端使用不同的.htaccess文件，Apache和nginx的相关文件均在public目录下，重命名为.htaccess后使用，nginx需要在网站server配置文件中include相关.htaccess；
	7. 在Linux环境下，理论上在第5步操作中能智能地完成第6步的操作，复制对应的.htaccess到public目录中，请检查是否安装正确；
	8. 如果页面空白、报错或发现页面在循环重定向，请设置.env文件的debug值为true，查看问题原因并修复之；
	9. 立即注册与.env中设置的管理员账号相同的账号；
	10. 运行"php artisan key:generate"，重新生成新的APP_KEY值。

## 站点信息
[https://myauth.us/](https://myauth.us/)是作者基于本项目搭建的站点。<br>
The site [https://myauth.us/](https://myauth.us/) based on this project.<br>
如欲捐赠，请参访[https://myauth.us/donate.php](https://myauth.us/donate.php)页面。 

## 相关小程序信息
![image](https://github.com/ymback/Battle.net-Authenticator-Wechat-Mini-Program/blob/master/QRCODE.jpg)<br>
使用微信扫描上方小程序码，即可使用。<br>
小程序相关代码已经开源，请参访
[https://github.com/ymback/Battle.net-Authenticator-Wechat-Mini-Program](https://github.com/ymback/Battle.net-Authenticator-Wechat-Mini-Program)查看相关信息。

# License
```text
    Battle.net Authenticator Online
    Copyright (C) 2013 Shiori Takei

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
```
