## Lumen Social App

[![Build Status](https://travis-ci.org/vantoozz/lumen-social-app.svg?branch=master)](https://travis-ci.org/vantoozz/lumen-social-app)

Social app on Lumen framework

### nginx config

```
server {
        listen   80;
        server_name lumen.local;

        access_log off;
        error_log /path/to/lumen.error.log;

        charset utf-8;

        set $project_root /path/to/lumen;

        root $project_root/cdn/lumen;

        location ~* /\.(ht|svn|git) {
                access_log off;
                log_not_found off;
                deny  all;
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

                fastcgi_connect_timeout 60;
                fastcgi_send_timeout 180;
                fastcgi_read_timeout 180;
                fastcgi_buffer_size 128k;
                fastcgi_buffers 4 256k;
                fastcgi_busy_buffers_size 256k;
                fastcgi_temp_file_write_size 256k;
                fastcgi_intercept_errors on;
        }
}
```
-----
### vk app settings
![vk app setings](https://i.gyazo.com/c690b4856c8dcd5526805f2c4cd4af29.png)
