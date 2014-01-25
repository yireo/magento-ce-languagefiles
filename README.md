# Magento CE language-files
This project contains language-files for usage with Magento Community
Edition, translated through the following Transifex project:
https://www.transifex.com/projects/p/magento-ce-1x/

## About this project
Transifex is a tool that allows for collaborated translations. Anybody can join in and start translating
things easily. We have worked together with the Transifex team to add support for Magento languages-files 
(CSV-formatted) as well, and this works great. However, to easily download the language-files for direct
use in your Magento project, ZIP-files need to be created and language-files need to be downloaded and 
renamed. This GitHub project does that gathering for you.

Note: These sources are automatically updated through a cronjob, every 8 hours.

## FAQ: How to use these translations?
Within this GitHub project, browse to the `packages` folder and download the package of
your required language. Next, upload this ZIP-package (for instance `magento-ce-1x-de_DE.zip`) to your
Magento site.

Within your Magento folders, locate the `app/locale/` folder. Your language should be listed here as a 
subfolder (for instance `app/locale/de_DE`). If the folder does not exist, create it. Copy all the
language-files of the ZIP-package to this folder.

## FAQ: How to help translating?
Go to https://www.transifex.com/projects/p/magento-ce-1x/ and open up a new Transifex account. Join one
of the language-teams. Once your request to join a team is approved, you can start translating.

## FAQ: How to clone this project?
Anyone is able to create a new Transifex project, upload the Magento language-files, and start
translating things within their own project. Likewise, you can also start your own GitHub project to
bundle these language-files and create packages. Clone this project. Next, copy the script `update/private.php.sample`
to `update/private.php` and enter your own Transifex credentials. Use `.gitignore` to exclude that file
from the project. Next, run the script `update/update.php` using a PHP command-line client.

## Limitations
Translation of email-templates (`app/locale/en_US/template/email`) is currently not supported by
Transifex, so therefor those files are not included here either. 
