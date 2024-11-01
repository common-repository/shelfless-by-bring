#!/bin/sh
# Harvey Diaz | Tiqqe -- 04302021
#
echo "This script needs wget. It also requires root privilege (or through sudo) to move downloaded .phar file to /usr/local/bin, otherwise use the regular way of using .phar via the php command OR add \"`pwd`\" to your PATH. -- Harvey D.\n\n"
wget -c https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
php wp-cli.phar --info 2>&1

if [ -f "./wp-cli.phar" ]
then
    echo "File successfully downloaded.\n\n"
    chmod +x wp-cli.phar
else
    echo "File not downloaded or cannot be found."
    exit
fi

if [ `id -u` = 0 ]
then
    echo "Moving wp-cli.phar to /usr/local/bin..."
    err_msg=$( mv wp-cli.phar /usr/local/bin/wp 2>&1 )
    if [ $? != 0 ]
    then
        echo "Move failed with error: \n"
        echo $err_msg
        echo
    else
        echo "WP-CLI now ready. Run with wp or `which wp` <command>...\n"
    fi
else
    echo "WP-CLI now ready. Run with `which php` wp-cli.phar <command>...\n"
fi

exit
