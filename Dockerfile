FROM alpine:3.5
MAINTAINER ninthwalker <ninthwalker@gmail.com>

ENV UPDATED_ON 15MAR2017
#ENV BUNDLER_VERSION 1.12.3

VOLUME /config
EXPOSE 6878

RUN apk add --no-cache ruby ruby-json ruby-io-console

#copy app and s6-overlay files..
COPY root/ s6-overlay/ /
WORKDIR /config

RUN apk add --no-cache --virtual build-dependencies \
curl-dev \
ruby-dev \
ruby-bundler \
libc-dev \
make \
gcc && \
cd /opt/gem ; bundle install && \
apk del build-dependencies

#gem install bundler -v $BUNDLER_VERSION --no-ri --no-rdoc && \
#bundle config --global silence_root_warning 1 && \

ENTRYPOINT ["/init"]
CMD ["ruby", "-run", "-e", "httpd", ".", "-p", "6878"]
