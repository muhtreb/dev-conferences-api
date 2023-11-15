#!/bin/bash

bin/console do:da:cr --env=test --quiet
bin/console do:sc:up --force --env=test --complete
bin/console do:fi:lo --env=test --no-interaction