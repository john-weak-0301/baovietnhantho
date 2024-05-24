## Deploy

```
composer install --no-dev
npm run prod
```

Zip lai code, ignore thư mục `node_modules`

File `compress.sh`

```shell script
#!/bin/bash

name=$(date '+%Y-%m-%d')

tar --exclude-vcs -zcvf "baovietnhantho.com.vn-$name.tar.gz" ./baovietnhantho.com.vn
```
