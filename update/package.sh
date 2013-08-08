#!/bin/bash
#
# Very basic script to ZIP all files and copy the archives to /packages/
#

basedir="../magento-ce-17"
cd $basedir
for i in *; do 
    zipfile="magento-ce-17_"$i;
    zip -r9 $zipfile $i
done

mv *.zip ../packages/
