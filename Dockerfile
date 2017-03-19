FROM alpine:3.5
MAINTAINER ninthwalker <ninthwalker@gmail.com>

ENV UPDATED_ON 18MAR2017

VOLUME /config
EXPOSE 6878 

#copy app and s6-overlay files
COPY root/ s6-overlay/ /

RUN apk add --no-cache ruby ruby-json ruby-io-console curl-dev tzdata shadow && \
apk add --no-cache --virtual build-dependencies \
ruby-dev \
ruby-bundler \
libc-dev \
make \
gcc && \
useradd -u 99 -U -d /config -s /bin/false xyz && \
usermod -G users xyz && \
bundle config --global silence_root_warning 1 && \
cd /opt/gem ; bundle install && \
apk del --purge build-dependencies

ENTRYPOINT ["/init"]
CMD ["ruby", "-run", "-e", "httpd", "/config/www", "-p", "6878"]
