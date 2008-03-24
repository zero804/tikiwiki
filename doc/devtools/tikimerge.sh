#!/bin/sh
# $Header: /cvsroot/tikiwiki/tiki/doc/devtools/tikimerge.sh,v 1.12.2.1 2007-10-16 18:17:35 mose Exp $
#
# NOTE: Since 1.9 release, merge only concerns BRANCH-1-9 to HEAD
#
# That script is done for fast merging fixes that are done on branch
# refer to http://tikiwiki.org/tiki-index.php?page=CvsBranch18
# for explanaitoin of the process
#
# !   !   !   !   W A R N I N G   !   !   !   !   
# this script is very experimental and is not very clean nor finished.
# use it only if you know what all means
# remove echo if you are confident in automation
# but keep in mind that environment preservation requires consideration 
# so : Don't break the CVS !! :)
#
# -- mose
# 
# !   !   !   !   !   !   !   !   !   !   !   !
# 
# run it in branch to merge change to head instantly

# Use it giving file name(s) as arguments: file name(s) can be particular files
# or "." (default) for all files

echo "Deprecated. Use tikimerge_110to111.sh instead."
exit 0

EXCLUDE="lang"
if [ -z $1 ]; then
	FILES="."
else
	FILES=$*
fi

echo "# Start of block you can just copy-paste"
echo "# or adapt (especially comment of commit)"
echo "# NOTE: Since 1.9 release, merge only concerns BRANCH-1-9 to HEAD"
echo ""
echo "# the merge should be done on tiki and not tikiwiki"
echo ""

echo "cvs -q up -d -r BRANCH-1-9 $FILES"
echo "cvs -q tag -r BRANCH-1-9 -F BRANCH-1-9-HEAD $FILES"
echo "cvs -q up -dA $FILES"
for i in $FILES; do
	echo "cvs -q up -dkk -j MERGE-1-9-HEAD -j BRANCH-1-9-HEAD $i"
done
for i in $EXCLUDE; do
	echo "rm -rf $i"
	echo "cvs -q up -dA $i"
done
echo "grep -r '<<<<<<<' $FILES"
echo "cvs ci -m'Instant-Auto-Merge from BRANCH-1-9 to HEAD' $FILES"
echo "cvs -q tag -r BRANCH-1-9-HEAD -F MERGE-1-9-HEAD $FILES"

echo 
echo "# Done."
exit 0

