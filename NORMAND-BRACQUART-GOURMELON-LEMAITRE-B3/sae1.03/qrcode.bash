#!/bin/bash
for i in {1..13}
do
    egrep "^.{0,$((i-1))}$" $file | cut -f1 -d ','
    docker container run -ti -v $2:/work bigpapoo/sae103-qrcode qrencode -o code.png "https://bigbrain.biz/$iso"
done
