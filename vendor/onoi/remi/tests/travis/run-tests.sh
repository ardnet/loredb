#! /bin/bash
set -ex

BASE_PATH=$(pwd)

if [ "$TYPE" == "coverage" ]
then
	composer phpunit -- --coverage-clover $BASE_PATH/build/coverage.clover
else
	composer phpunit
fi
