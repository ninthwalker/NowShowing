#!/usr/bin/with-contenv sh

#creates config file from ENV variables
if [ -f /config/config.yaml ]; then
  echo "Config files detected. Using existing config"
  echo    # move to a new line
else
 # begin initial setup
 cp /opt/config/* /config/
 chmod -R 666 /config/*
 /usr/local/sbin/config-setup
 echo "Setup complete! Please read directions for advanced settings and running this on a schedule."
fi

#read nowshowing_schedule.cron on container startup and add to crontab
echo "$(cat /config/nowshowing_schedule.cron)" > /crontab.tmp
crontab /crontab.tmp
rm -rf /crontab.tmp
crond -f
