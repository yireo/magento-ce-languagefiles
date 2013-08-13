#!/bin/bash
php ./update.php
git add ../magento-ce-17
git commit -m "Updated language-files from Transifex" ../magento-ce-17
git push origin master
