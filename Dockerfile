FROM alpine:3.5
MAINTAINER ninthwalker <ninthwalker@gmail.com>

ENV UPDATED_ON 15MAR2017

VOLUME /config
EXPOSE 6878 

#copy app and s6-overlay files
COPY root/ s6-overlay/ /
WORKDIR /config

RUN apk add --no-cache ruby ruby-json ruby-io-console curl-dev
RUN apk add --no-cache --virtual build-dependencies \
ruby-dev \
ruby-bundler \
make \
gcc && \
bundle config --global silence_root_warning 1 && \
cd /opt/gem ; bundle install

ENTRYPOINT ["/init"]
CMD ["ruby", "-run", "-e", "httpd", ".", "-p", "6878"]
