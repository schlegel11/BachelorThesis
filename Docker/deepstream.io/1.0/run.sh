#!/bin/bash

while ! nc -vz mongodbservice 27017; do sleep 1; done
deepstream