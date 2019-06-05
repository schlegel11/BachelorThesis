#!/bin/bash

JAVA_OPTS="-server -Dfile.encoding=UTF8 -Djava.awt.headless=true -Xms1g -Xmx1g -Dmc_logging_folder=/tmp -Dmc_logging_console_level=DEBUG -Dmc_logging_rolling_file_level=INFO -XX:+UseConcMarkSweepGC -Djava.net.preferIPv4Stack=true -Djava.net.preferIPv4Addresses=true -Dmc.configuration=/mc_data/configuration.properties -Dmc_website.configuration=/mc_data/mc_config/web_configuration.properties"
