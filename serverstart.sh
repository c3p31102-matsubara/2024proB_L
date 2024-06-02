#!/bin/sh
PSID=$(pstree | grep -e "httpd" | grep -e "=" | grep -v "grep" | awk '{print $2}' | sed 's/0*\([0-9]*[0-9]$\)/\1/g')
if [[ -z $PSID ]]; then
    httpd -f /Users/dynamiteopanty/Documents/Programming/2024proB_L/httpd.conf
    echo server is starting to run
    # mysqld --defaults-file=my.cnf
    brew services start mariadb
else
    echo "httpd was found already running"
fi
echo my ip is `ifconfig | grep -e 'inet 172.20' -e 'inet 192.168' | awk '{print $2}'`
open -a Safari http://localhost/
