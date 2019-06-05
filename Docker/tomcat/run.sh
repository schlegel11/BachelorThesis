#!/bin/bash

while ! nc -vz deepstreamservice 6020; do sleep 2; done
catalina.sh jpda run
