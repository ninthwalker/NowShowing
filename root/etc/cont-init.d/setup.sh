#!/usr/bin/with-contenv sh

# Added to check if new web_email_body.erb file exists, if not, add.
if [ -f /config/web_email_body.erb ]; then
  echo    # new line, do nothing
else
 # copy new file
 cp /opt/config/web_email_body.erb /config/
 chmod -R 666 /config/web_email_body.erb
fi

#creates config file from ENV variables
if [ -f /config/config.yaml ]; then
  echo "Config files detected. Using existing config"
  echo    # move to a new line
else
 # begin initial setup
 cp /opt/config/* /config/
 chmod -R 666 /config/*
 /usr/local/sbin/plexreport-setup
 echo "Setup complete! Please read directions for running this on a schedule."
fi

