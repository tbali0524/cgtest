#!/usr/bin/env bash
# CLI argument errors
echo "---------- cgtest SELF TEST"
echo "---------- 01"
php cgtest.php --config=-cgtest.php --config=.cgtest.full.php
echo "---------- 02"
php cgtest.php --config=-cgtest.php --config=.cgtest.full.php --no-ansi
echo "---------- 03"
php cgtest.php --lang=verbose
echo "---------- 04"
php cgtest.php --test-case=1 --test-case=3
echo "---------- 05"
php cgtest.php --test-case=999
echo "---------- 06"
php cgtest.php --create=1 --create=3
echo "---------- 07"
php cgtest.php --ansi --no-ansi
echo "---------- 08"
php cgtest.php --verbose --quiet
echo "---------- 09"
php cgtest.php --no-such-arg
echo "---------- 10"
php cgtest.php --lang-versions --clean
echo "---------- 11"
php cgtest.php --clean --dry-run
echo "---------- 12"
php cgtest.php --dry-run --run-only
echo "---------- 13"
php cgtest.php --create=0
echo "---------- 14"
php cgtest.php --create=1 --alt
echo "---------- 15"
php cgtest.php --create=1
echo "---------- 16"
php cgtest.php --lang=lua,php no-such-sourcefile
# getting info
echo "---------- 17"
php cgtest.php --version
echo "---------- 18"
php cgtest.php --version --no-ansi
echo "---------- 19"
php cgtest.php --help
echo "---------- 20"
php cgtest.php --help --no-ansi
echo "---------- 21"
php cgtest.php --show-defaults
echo "---------- 22"
php cgtest.php --lang-versions --quiet --alt --lang=php,perl,ocaml,no-such-lang
echo "---------- 23"
php cgtest.php --lang-versions --stats --lang=php,perl,ocaml,no-such-lang
echo "---------- 24"
php cgtest.php --lang-versions
# invoking tests
echo "---------- 25"
php cgtest.php --stats --dry-run --lang=c,lua,php,ocaml,no-such-lang
echo "---------- 26"
php cgtest.php --quiet --dry-run --no-ansi --lang=c,lua,php,ocaml,no-such-lang
echo "---------- 27"
php cgtest.php --stats --run-only --lang=c,lua,php
echo "---------- 28"
php cgtest.php --quiet --run-only --lang=c,lua,php,ocaml,no-such-lang
echo "---------- 29"
php cgtest.php --stats --lang=c,lua,php,ocaml,no-such-lang
echo "---------- 30"
php cgtest.php --test-case=3 --stats --lang=c,lua,ocaml,no-such-lang
echo "---------- 31"
php cgtest.php --alt --lang=c,lua,php,ocaml,no-such-lang
echo "---------- 32"
php cgtest.php --clean --quiet
# not in a cgtest project folder
echo "---------- 33"
cd "php"
php ../cgtest.php
cd ".."
echo "---------- cgtest SELF TEST DONE"
