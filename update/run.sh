#!/bin/bash
php ./update.php
./package.sh
git add ../magento-ce-1x
git add ../packages
git commit -m "Updated language-files from Transifex" ../magento-ce-1x
git commit -m "Updated packages from Transifex" ../packages
git push origin master
