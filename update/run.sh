#!/bin/bash
php ./update.php
./package.sh
git add ../magento-ce-17
git add ../packages
git commit -m "Updated language-files from Transifex" ../magento-ce-17
git push origin master
