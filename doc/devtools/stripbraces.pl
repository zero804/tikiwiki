#!/usr/bin/perl
# (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
# 
# All Rights Reserved. See copyright.txt for details and a complete list of authors.
# Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
# $Id$

    $/ = undef;
    $_ = <>;
    # // s#{([^{}]*)}|("(\\.|[^"\\])*"|'(\\.|[^'\\])*'|.[^/"'\\]*)#$2#gs;
    s#{([^{}]*)}##gs;
    print;
