#!/bin/bash

bin/console do:sc:up --force --env=test
bin/console do:fi:lo --env=test