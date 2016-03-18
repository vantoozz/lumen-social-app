## Lumen Social App

[![Build Status](https://travis-ci.org/vantoozz/lumen-social-app.svg?branch=master)](https://travis-ci.org/vantoozz/lumen-social-app)
[![Codacy Badge](https://api.codacy.com/project/badge/grade/16a339606e9e4fd6896bdcd6a06be201)](https://www.codacy.com/app/vantoozz/lumen-social-app)
[![Coverage Status](https://coveralls.io/repos/vantoozz/lumen-social-app/badge.svg?branch=master&service=github)](https://coveralls.io/github/vantoozz/lumen-social-app?branch=master)
[![Dependency Status](https://www.versioneye.com/user/projects/56442f7c22c568001e000062/badge.svg?style=flat)](https://www.versioneye.com/user/projects/56442f7c22c568001e000062)

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/2b9bb454-77ff-4494-82dc-715f67c92802/big.png)](https://insight.sensiolabs.com/projects/2b9bb454-77ff-4494-82dc-715f67c92802)

Social app on Lumen framework

### nginx config

```
server {
        listen   80;
        listen 443 ssl;

        server_name lumen.local;

        ssl_certificate /etc/nginx/ssl/lumen.crt;
        ssl_certificate_key /etc/nginx/ssl/lumen.key;

        access_log off;
        error_log /path/to/lumen.error.log;

        charset utf-8;

        set $project_root /path/to/lumen;

        root $project_root/cdn/lumen;

        location ~* /\.(ht|svn|git) {
                access_log off;
                log_not_found off;
                deny all;
        }

        location / {
                try_files $uri @public;
        }

        location @public {
            root $project_root/public;
            try_files $uri @lumen;
        }

        location ~\/static\/(\d+\/)(.+)$ {
            try_files $uri /static/$2;
        }

        location @lumen {
                fastcgi_pass 127.0.0.1:9056;
                fastcgi_param PLATFORM NIKINOTE;
                include fastcgi_params;
                fastcgi_param SCRIPT_FILENAME $project_root/app.php;
        }
}
```
-----
### vk app settings
![vk app setings](https://i.gyazo.com/c690b4856c8dcd5526805f2c4cd4af29.png)
