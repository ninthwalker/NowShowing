FROM alpine:3.5
MAINTAINER ninthwalker

ENV UPDATED_ON 28APR2018
ENV NOWSHOWING_VERSION 2.0.3

VOLUME /config
EXPOSE 6878 

#copy app and s6-overlay files
COPY root/ s6-overlay/ /

# Install permanent packages
RUN apk add --no-cache ruby ruby-json ruby-io-console curl-dev lighttpd php7-cgi php7-json php7-curl php7-mbstring busybox-suid fail2ban ca-certificates wget tzdata shadow && \

# Install temporary build dependencies
apk add --no-cache --virtual build-dependencies \
ruby-dev \
ruby-bundler \
libc-dev \
make \
gcc && \

# Create default user & lighttpd path
groupmod -g 1000 users && \
useradd -u 99 -U -d /config -s /bin/false xyz && \
groupmod -o -g 100 xyz && \
usermod -G users xyz && \
mkdir /run/lighttpd /var/run/fail2ban && \

# fail2ban setup - remove alpine default jail & copy in our jail settings and filter
rm /etc/fail2ban/jail.d/* && \
cp /opt/f2b/fail2ban.local /opt/f2b/jail.local /etc/fail2ban/ && \
cp /opt/f2b/nowshowing.conf /etc/fail2ban/filter.d/ && \
cp /opt/f2b/iptables-common.conf /etc/fail2ban/action.d/ && \

# smtp.rb mail fix
cp /opt/smtp.rb /usr/lib/ruby/2.3.0/net/ && \

# Insall NowShowing app dependencies
bundle config --global silence_root_warning 1 && \
cd /opt/gem ; bundle install && \

# Remove temp files
apk del --purge build-dependencies

# Start s6 init & webserver
ENTRYPOINT ["/init"]
CMD ["lighttpd", "-D", "-f", "/etc/lighttpd/lighttpd.conf"]
