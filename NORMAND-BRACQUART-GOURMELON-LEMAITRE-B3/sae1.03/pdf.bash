#!/bin/bash

if [[ -e "pdf/" ]]; then
  rm -fr pdf
fi
cp regions.conf regions2.conf
tr -s ' ' '_' < regions2.conf

mkdir pdf

for i in page_html/*.html
do
codeISO=$(basename -s .html page_html/*.html | cut -d'.' -f1)
nomRegion=$(egrep "$codeISO" regions2.conf | cut -d"," -f2)
docker run --rm -ti -v $(pwd):/work sae103-html2pdf "html2pdf page_html/$codeISO.html pdf/$codeISO"-"$nomRegion.pdf"
done