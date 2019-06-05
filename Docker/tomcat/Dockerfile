FROM tomcat:8

MAINTAINER Prabhakaran Santhanam (psanthan@student.ethz.ch)

RUN mkdir /mc_data
RUN mkdir /certs
RUN apt-get update && apt-get install -y netcat

# COPY configuration.properties /mc_data

COPY setenv.sh /usr/local/tomcat/bin
COPY run.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/run.sh

COPY MC.war /usr/local/tomcat/webapps

CMD ["run.sh"]
