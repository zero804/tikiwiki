#!/bin/sh
# $Id$

# Usage:
# from db/convertscripts, run:
# sh convertsqls.sh
#
# If you don't have php-cli the, you can use:
# sh convertsqls.sh domain.com/subdomain/
#

VERSION="3.9"

if [ "$1" = "-h" ]; then
	echo "Usage: ./convertsqls.sh <host> <tikiversion> or .//convertsqls.sh -h for this help"
	echo "       where <host> is the virtualhost/root/ for your tiki, IF NOT SET just runs php from the command line instead"
	echo "       and <tikiversion> is the tikiwiki version (automatically set to $VERSION if omitted)"
	exit 0
fi

TIKISERVER=""
if [ -z $1]; then 
TIKISERVER=$1
fi

if [ "$2" ] ; then
  VERSION=$2
fi

cp ../tiki.sql ../tiki-$VERSION-mysql.sql
cp ../tiki.sql ../tiki-$VERSION-mysqli.sql
# /* the scripts use mysql.sql */

if [$TIKISERVER = ""]; then
echo "Local run of php ..."
php -f mysql3topgsql72.php > pgsql72.sql.tmp
php -f mysql3tosybase.php > sybase.sql.tmp
php -f mysql3tosqlite.php > sqlite.sql.tmp
php -f mysql3tooci8.php > oci8.sql.tmp
else
wget -O pgsql72.sql.tmp "http://$TIKISERVER/db/convertscripts/mysql3topgsql72.php?version=$VERSION" 
wget -O sybase.sql.tmp "http://$TIKISERVER/db/convertscripts/mysql3tosybase.php?version=$VERSION" 
wget -O sqlite.sql.tmp "http://$TIKISERVER/db/convertscripts/mysql3tosqlite.php?version=$VERSION"
wget -O oci8.sql.tmp "http://$TIKISERVER/db/convertscripts/mysql3tooci8.php?version=$VERSION" 
fi

rm -f *.sql.tmp 
rm -f ../tiki-$VERSION-pgsql.sql ../tiki-$VERSION-sybase.sql ../tiki-$VERSION-sqlite.sql ../tiki-$VERSION-oci8.sql

mv $VERSION.to_pgsql72.sql ../tiki-$VERSION-pgsql.sql
mv $VERSION.to_sybase.sql ../tiki-$VERSION-sybase.sql
mv $VERSION.to_sqlite.sql ../tiki-$VERSION-sqlite.sql
mv $VERSION.to_oci8.sql ../tiki-$VERSION-oci8.sql


echo "Done."
exit 0
