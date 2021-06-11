FROM ubuntu:bionic

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update
RUN apt-get -y install software-properties-common
RUN add-apt-repository ppa:janssenlab/software
RUN apt-get update
RUN apt-get -y install gpg bellmansgapc php

COPY index.php .
COPY elmamun.gap .
WORKDIR .
CMD [ "php", "./index.php" ]
