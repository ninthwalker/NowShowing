FROM alpine:3.5
MAINTAINER ninthwalker <ninthwalker@gmail.com>

ENV UPDATED_ON 18MAR2017

VOLUME /config
EXPOSE 6878 
WORKDIR /config/webroot

#copy app and s6-overlay files.
COPY root/ s6-overlay/ /

RUN apk add --no-cache ruby ruby-json ruby-io-console curl-dev
RUN apk add --no-cache --virtual build-dependencies \
ruby-dev \
ruby-bundler \
libc-dev \
make \
gcc && \
bundle config --global silence_root_warning 1 && \
cd /opt/gem ; bundle install && \
apk del build-dependencies && \
cd 

ENTRYPOINT ["/init"]
CMD ["ruby", "-run", "-e", "httpd", ".", "-p", "6878"]
