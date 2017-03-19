NowShowing
================

## Description / Background
NowShowing is the sucessor of Ninthwalker's dockerized version plexReport. The original brainchild of bstascavage (https://github.com/bstascavage/plexReport).
While working on the plexReport docker, a user by the name of GroxyPod made some modifications to the docker and showed them to Ninthwalker. From there, they decided 
to create a sucessor to plexReport: NowShowing. This new docker aims to take the great work already done by bstascavage and Ninthwalker and improve upon it.

## Introduction
The NowShowing docker provides a summary of new media that has recently been added to Plex, giving the server operator the option of delivering the information in two ways:
1) An email summary sent to all or selected users of the Plex Server
2) A webpage for use with reverse proxies, such as Nginx

## Supported Platforms
* unRAID v6.3.2

## Working, but Unsupported Platforms
* Linux platforms with Docker support
* unRAID v6.3.1 or lower

## Supported Email Clients
* Gmail
* Zoho

## Working, but Unsupported Platforms
* Any Email provider with SSL SMTP support (Not Gmail or Zoho)

## Supported Plex Agents
* Plex Movie
* TheMovieDB
* TheTVDB

## Prerequisites
1.  Plex
2.  TheMovieDB set as your Agent for Movie sections on the Plex server
3.  TheTVDB set as your Agent for TV sections on the Plex server
4.  A Email account that supports SSL SMTP

## Installation on unRAID
### Preferred Method: Community Applications
#### Step 1: Click on Apps
>![alt text](http://i.imgur.com/Bo36OG1.png "unRAID CA Install Step 01")
#### Step 2: Type in NowShowing and then press enter
>![alt text](http://i.imgur.com/kULWSDj.png "unRAID CA Install Step 02")
#### Step 3: Click Add
>![alt text](http://i.imgur.com/0N13iIn.png "unRAID CA Install Step 03")
#### Step 4: Select your branch
Selecting Default will install the latest stable version of NowShowing.  
Selecting dev will install the latest development version of NowShowing.
>![alt text](http://i.imgur.com/Ci8oPUW.png "unRAID CA Install Step 04")
#### Step 5: Fill out the template
>![alt text](http://i.imgur.com/rtlePWD.png "unRAID CA Install Step 05a")
>![alt text](http://i.imgur.com/s7B6YQn.png "unRAID CA Install Step 05b")
>![alt text](http://i.imgur.com/QbGMU0l.png "unRAID CA Install Step 05c")
#### Step 6: Installation is complete
Your installation of NowShowing is complete. If you desire to change any of the config files they can be found at your /config install path.








You can also install by adding the following template repository to unraid:  
https://github.com/ninthwalker/docker-templates/



NowShowing can be run with the following command from unraid:  

`docker exec NowShowing report [-options]`

You can now edit the `config.yaml` (and optionally `email_body.erb` & `web_email_body.erb`) with your own settings in your appdata dir.  
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

###### web_email_body.erb

This file can be edited with CSS/HTML if you want to modify the look of the webpage.

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

`password` - Password for the email set above.  Required.

`from` - Display name of the sender.  Required.

`subject` - Subject of the email. Note that the script will automatically add a date to the end of the subject. Required.

`recipients_email` - Email addresses of any additional recipients, outside of your Plex friends.  Optional.

`recipients` - Plex usernames of any Plex friends to be notified.  To be used with the -n option.  Optional

## Command-line Options

Once installed, you can run the script by simply running `report` from within the docker image container. 

If you need to reconfigure the program configs, first delete the existing config files, then change the variables in the unRAID template and restart the docker..  All commandline options can be seen by running `report --help`

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
