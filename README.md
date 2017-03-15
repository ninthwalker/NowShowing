plexReport
================

A dockerized version of bstascavage's original plexReport (https://github.com/bstascavage/plexReport)

## Introduction
This docker generates an email summary of new additions to Plex to send to your users

## Supported Platforms
* unRAID, Other Linux

## Supported Email Clients
* Gmail

## Supported Plex Agents
* themoviedb
* Freebase
* thetvdb.org

## Prerequisites

The following are needed to run this docker:

1.  Plex.
2.  themoviedb set as your Agent for your Movie section on your Plex server.
3.  thetvdb.org set as your Agent for your TV section on your Plex server.
4.  A Gmail account to forward the email (Gmail is the only supported provider, so if you use another, YMMV).

## Installation (unRAID)

Preferred installation method: From the Community Applications 'APPS' section in unRAID.  
You can also install by adding the following template repository to unraid:  
https://github.com/ninthwalker/docker-templates/

After installing, run the following commands from the command line:
(This initial_setup only has to be done once. Reinstalls of the docker do not require it)

`docker exec -it plexReport ./initial_setup.sh`

Follow Prompts.  

plexReport can be run with the following command from unraid:  

`docker exec plexReport plexreport [-options]`

You can now edit the `config.yaml` (and optionally `email_body.erb`) with your own settings in your appdata dir.  
See `/config/config.yaml.example` and below for details.

To schedule the report to occur regulary please use the new cron system for unRAID 6:

Edit the "plexreport_schedule.cron" file found in the plexreport appdata folder with your own time/date.
Copy that file to the following location. Each time unraid is started it will load your plexreport_schedule.

`/boot/config/plugins/dynamix/`

To have it added immediately without restarting unRAID, at the command prompt type `update_cron`.

See this page for help creating a time/date in cron: http://abunchofutils.com/u/computing/cron-format-helper/
    
## Config

By default, the config file is located in `/config/config.yaml`.  If you need to change any information for the program, or to add more optional config parameters, see below for the config file format:

###### email_body.erb

This file can be edited with CSS/HTML if you want to modify the look of the email.

###### email
`title` - Banner title for the email body.  Required.

`language` - The language of the email body. You need to use ISO 639-1 code ('fr', 'en', 'de'). If a content is not available in the specified language, the script will fall back to english. Defaults to 'en'. Optional.

###### plex
`server` - IP address of your Plex server.  Defaults to 'localhost'.  Optional.

`api_key` - Your Plex API key.  Required.

`sections` - Array of sections to report on.  If field is not set, will report on all TV and movie sections.  Format is ['section1', 'section2'].  Optional.

###### mail
`address` - Address of your smtp relay server.  (ie smtp.gmail.com).  Required.

`port` - Mail port to use.  Default is 25.  (Use 587 for gmail.com).  Required

`username` - Email address to send the email from.  Required.

`password` - Password for hte email set above.  Required.

`from` - Display name of the sender.  Required.

`subject` - Subject of the email.  Note that the script will automatically add a date to the end of the subject.  Required.

`recipients_email` - Email addresses of any additional recipients, outside of your Plex friends.  Optional.

`recipients` - Plex usernames of any Plex friends to be notified.  To be used with the -n option.  Optional

## Command-line Options

Once installed, you can run the script by simply running `plexreport` from within the docker image container. 

If you need to reconfigure the program configs, first delete the existing config files and rerun '. /initial_setup.sh`.  All commandline options can be seen by running `plexreport --help`

##### Options:
`-n, --no-plex-email` - Do not send emails to Plex friends.  Can be used with the `recipients_email` and `recipients` config file option to customize email recipients.

`-l, --add-library-names` - Adding the Library name in front of the movie/tv show.  To be used with custom Libraries

`-t, --test-email` - Send email only to the Plex owner (ie yourself).  For testing purposes

`-d, --detailed-email` - Send more details in the email, such as movie ratings, actors, etc

## Images

New Episodes:
![alt tag](http://i.imgur.com/hWzHl2x.png)

New Seasons:
![alt tag](http://i.imgur.com/sBy62Ty.png)

New Movies:
![alt tag](http://i.imgur.com/E3Q85uU.png)

New Movies (detailed view):
![alt tag](http://i.imgur.com/9BHiQHW.png)
