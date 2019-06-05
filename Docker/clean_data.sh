#!/bin/bash

cd mc_global/mc_data/
rm -r FileStorage/ MediaCache/ MediaUpload/ logs/ templates/
cd ..
cd mongo_data/
rm -r *
cd ..
cd ..
