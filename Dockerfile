FROM alpine:3.3
MAINTAINER ninthwalker <ninthwalker@gmail.com>

ENV UPDATED_ON 14MAR2017
ENV RUBY_PACKAGES ruby ruby-dev ruby-json ruby-io-console
ENV BUNDLER_VERSION 1.12.3

VOLUME /config
EXPOSE 6878

#copy app and s6-overlay files
COPY root/ s6-overlay/ /
WORKDIR /config

RUN apk add --no-cache \
$RUBY_PACKAGES \
curl-dev \
make \
gcc 

RUN \
cd /opt/gem && \
gem install bundler -v $BUNDLER_VERSION --no-ri --no-rdoc && \
bundle config --global silence_root_warning 1 && \
bundle install

ENTRYPOINT ["/init"]
CMD ["ruby", "-run", "-e", "httpd", ".", "-p", "6878"]
