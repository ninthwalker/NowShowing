
# NowShowing

<img src="https://github.com/ninthwalker/NowShowing/blob/master/images/nowshowing-icon.png" alt="NowShowing" width="100px"/>

[![](https://images.microbadger.com/badges/image/ninthwalker/nowshowing:dev.svg)](https://microbadger.com/images/ninthwalker/nowshowing:edge "NowShowing")

## Description / Background
NowShowing is the sucessor of the popular plexReport docker. The original brainchild of bstascavage (https://github.com/bstascavage/plexReport). Further developed by NinthWalker & enhanced by GroxyPod, NowShowing adds additional improvements and features in a friendly, easy to install docker.

## Introduction
The NowShowing docker provides a summary of new media that has recently been added to Plex, giving the server operator the option of delivering the information in two ways:
1) An email summary sent to all or selected users of the Plex Server
2) A webpage for users to visit

## Supported Platforms

* unRAID v6.3.x (Full Supported)
* unRAID v6.x (Template Options may appear different)
* Linux platforms with Docker support (with a few manual changes like ENV Variables & Time)

## Supported Email Clients
* Gmail
* Zoho
* Most Email providers with SSL SMTP support (Not all tested)

## Supported Plex Agents
* Plex Movie
* TheMovieDB
* TheTVDB

## Prerequisites
1.  Plex
2.  Docker
3.  TheMovieDB set as your Agent for Movie sections on the Plex server
4.  TheTVDB set as your Agent for TV sections on the Plex server
5.  An Email account that supports SSL SMTP

## Installation on unRAID
### Preferred Method: Community Applications
#### Step 1: Click on Apps
>![alt text](http://i.imgur.com/Bo36OG1.png "unRAID CA Install Step 01")

#### Step 2: Enter NowShowing and then hit enter
>![alt text](http://i.imgur.com/b9d4S94.png "unRAID CA Install Step 02")

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


NowShowing can be run manually with the following command from unraid:  

`docker exec NowShowing [report type] [-options]`

You can now edit the `advanced.yaml` (and optionally `email_body.erb` & `web_email_body.erb`) with your own settings in your appdata dir.  
See `/config/config.yaml.example` and below for details.

The email and web report will be generated and sent out by default every Friday at 10:30am local time.
To change the schedule, edit the "nowshowing_schedule.cron" file found in the NowShowing config folder with your own time/date.
Restart the docker to have the changes take effect.
See this page for help creating a time/date in cron: https://crontab.guru/

# Advanced Settings

Modify the below settings for advanced features and options

## Advanced Config

By default, the config file is located in `/config/advanced.yaml`.  If you need to change any information for the program, or to add more optional config parameters, see below for the config file format:

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

`report_type` - Choose the type of reports to run. Format is ['report type'].  Optional.
Valid report types are: ['email'] or ['web'] or ['both']

## Command-line Options

If you need to reconfigure the program configs, first delete the existing config files, then change the variables in the unRAID template and restart the docker..  All command line options can be seen by running `combinedreport --help`

##### Report Type:
`combinedreport` - Creates both the Email and Wbe reports

`emailreport` - Creates only the email report. No webpage.

`webreport` - Creates only the web report. No email.

Set the report type in the nowshowing_schedule.cron to customize what reports get created and when.

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
