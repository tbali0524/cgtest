#!/usr/bin/env bash
DIR=$( dirname $(readlink -f "$0") )
php "$DIR"/../cgtest.php --config="$DIR"/../.cgtest.full.php --lang-versions --quiet "$@"
