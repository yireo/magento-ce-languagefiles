#!/bin/bash
#
# Simple script to print each CSV file of "reckognized" languages into the modman file
#

# Language definition (because we don't want to dump all)
IFS=' ' read -a LANGUAGES <<< $(cat <<EOF
de_DE
fr_FR
nl_NL
EOF
)

# Empty out modman
rm modman
touch modman

for LANGUAGE in "${LANGUAGES[@]}"; do
    for LANGUAGE_PATH in magento-ce-1x/$LANGUAGE/*.csv ; do 
        LANGUAGE_FILE=$(basename $LANGUAGE_PATH)
        echo "magento-ce-1x/$LANGUAGE/$LANGUAGE_FILE app/locale/$LANGUAGE/$LANGUAGE_FILE" >> modman
    done
done
