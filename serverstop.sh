#!/bin/sh
PSID=$(pstree | grep -e "httpd" | grep -e "=" | grep -v "grep" | awk '{print $2}' | sed 's/0*\([0-9]*[0-9]$\)/\1/g')
if [[ ! -z $PSID ]]; then
    kill $PSID
    echo "stopped httpd running"
    brew services stop mariadb
else
    echo "httpd wasn't found running"
fi