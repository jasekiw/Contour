#!/bin/sh
# copy this script to the server in order to use deployment
# help: https://www.digitalocean.com/community/tutorials/how-to-set-up-automatic-deployment-with-git-with-a-vps
git --work-tree=/var/www/html/Evergreen_L5 --git-dir=/var/repo/contour.git checkout -f
cd /var/www/html/Evergreen_L5
composer update