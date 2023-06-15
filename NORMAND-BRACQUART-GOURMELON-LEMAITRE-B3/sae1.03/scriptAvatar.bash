#!/bin/bash
#docker run --rm -ti -v $(pwd):/work sae103-imagick ./scriptAvatar.bash
mkdir pp
for file in avatar/*.svg; do
    filename=$(basename "$file" .svg)
    convert "$file" -colorspace gray -shave 45x45 -resize 200x200 "pp/$filename.png"
done