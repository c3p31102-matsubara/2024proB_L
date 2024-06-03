#!/bin/sh
httpd_PSID=$(pstree | grep -e "httpd" | grep -e "=" | grep -v "grep" | awk '{print $2}' | sed 's/0*\([0-9]*[0-9]$\)/\1/g')
if [[ ! -z $httpd_PSID ]]; then
    kill $httpd_PSID
    echo "stopped httpd running"
else
    echo "httpd wasn't found running"
fi
mysqld_PSID=$(pstree | grep -e "mysqld" | grep -e "=" | grep -v "grep" | awk '{print $2}' | sed 's/0*\([0-9]*[0-9]$\)/\1/g')
if [[ ! -z $mysqld_PSID ]]; then
    kill $mysqld_PSID
    echo "stopped mysqld running"
else
    echo "mysqld wasn't found running"
fi
