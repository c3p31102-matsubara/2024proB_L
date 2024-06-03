#!/bin/sh
httpd_PSID=$(pstree | grep -e "httpd" | grep -e "=" | grep -v "grep" | awk '{print $2}' | sed 's/0*\([0-9]*[0-9]$\)/\1/g')
if [[ -z $httpd_PSID ]]; then
    httpd -f /Users/dynamiteopanty/Documents/Programming/2024proB_L/httpd.conf &
    echo server is starting to run
else
    echo "httpd was found already running"
fi
mysqld_PSID=$(pstree | grep -e "mysqld" | grep -e "=" | grep -v "grep" | awk '{print $2}' | sed 's/0*\([0-9]*[0-9]$\)/\1/g')
if [[ -z $mysqld_PSID ]]; then
    mysqld --defaults-file=my.cnf &
    echo databse is starting to run
else
    echo "mysqld was found already running"
fi
echo my ip is $(ifconfig | grep -e 'inet 172.20' -e 'inet 192.168' | awk '{print $2}')
open -a Safari http://localhost/
