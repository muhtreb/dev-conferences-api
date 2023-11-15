#!/bin/bash

bin/console do:da:cr --env=test
bin/console do:sc:up --force --env=test
bin/console do:fi:lo --env=test --no-interaction