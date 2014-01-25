#!/bin/bash
#
# Very basic script to ZIP all files and copy the archives to /packages/
#

basedir="../magento-ce-1x"
cd $basedir
for i in *; do 
    zipfile="magento-ce-1x_"$i;
    zip -qr9 $zipfile $i
done

mv *.zip ../packages/
