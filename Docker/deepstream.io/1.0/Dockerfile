FROM centos:7

RUN yum install -y wget

RUN wget https://bintray.com/deepstreamio/rpm/rpm -O /etc/yum.repos.d/bintray-deepstreamio-rpm.repo

RUN yum install -y deepstream.io-3.1.0-1

RUN yum install -y nc

# Installing Plugins
RUN deepstream install storage mongodb

COPY ./run.sh /usr/local/bin/
COPY deepstream-permissions.yml /etc/deepstream/deepstream-permissions.yml
RUN chmod +x /usr/local/bin/run.sh

# Config should be part of volume
COPY ./config.yml /etc/deepstream/config.yml

RUN mkdir /etc/deepstream/certs

# Expose ports
EXPOSE 6020
EXPOSE 6021

# Define default command.
CMD [ "run.sh" ]
