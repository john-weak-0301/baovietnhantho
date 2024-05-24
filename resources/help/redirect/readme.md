## Hướng dẫn cài đặt redirect URL

## Bước 1

Login SSH vào server nginx proxy.

Copy tập tin `bvnt-redirect-links.conf` vào thư mục `/etc/nginx/`


Tại tập tin cấu hình nginx của site bảo việt (mặc định là):

`/etc/nginx/conf.d/default.conf` sửa đổi như sau:


```nginx
map_hash_bucket_size 2048;
types_hash_bucket_size 2048;

map $request_uri $redirect_uri {
    include /etc/nginx/bvnt-redirect-links.conf;
}

upstream frontend {
    ...
}

server {
    listen 443 ssl http2;
    ...

    if ($redirect_uri) {
        return 301 $redirect_uri;
    }

    location /dashboard {}
    ...
}

```

Diễn tả chi tiết:

1. Thêm đoạn code khai báo url ở đầu file:

```nginx
map_hash_bucket_size 2048;
types_hash_bucket_size 2048;

map $request_uri $redirect_uri {
    include /etc/nginx/bvnt-redirect-links.conf;
}
```

2. Thêm đoạn code chuyển hướng trong block `server`, nằm trên các vị trí `location`

```nginx 
if ($redirect_uri) {
    return 301 $redirect_uri;
}
```

## Bước 2

Kiểm tra trước khi reload server nginx:

Dưới quyền root, chạy lệnh

```
nginx -t

    nginx: the configuration file /etc/nginx/nginx.conf syntax is ok
    nginx: configuration file /etc/nginx/nginx.conf test is successful
```

Nếu OK như trên là được. Tiến hành reload `nginx`


```
sudo systemctl reload nginx
```

## Thêm 1 link

Trường hợp muốn thêm 1 liên kết redirect, tiến hành sửa đổi file 

`/etc/nginx/bvnt-redirect-links.conf` thêm liên kết vào cuối của file
với format như sau:


`{link cũ} {dấu cách} {link mới};` Lưu ý bắt buộc phải có dấu chấm phẩy `;` sau cùng. VD:

```nginx
/link-cu    /url/link-moi;
```

Sau khi thêm xong tiến hành kiểm tra cú pháp nginx nếu OK có thể tiến hành 
reload server.


```bash
nginx -t
sudo systemctl reload nginx
```
