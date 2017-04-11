
# NowShowing

<img src="https://raw.githubusercontent.com/ninthwalker/NowShowing/master/images/nowshowing-icon.png" alt="NowShowing" width="100px">

[![](https://images.microbadger.com/badges/image/ninthwalker/nowshowing.svg)](https://microbadger.com/images/ninthwalker/nowshowing "NowShowing")

## Description / Background
NowShowing is the successor of the popular plexReport docker. The original brainchild of bstascavage (https://github.com/bstascavage/plexReport). Further developed by NinthWalker & enhanced by GroxyPod, NowShowing adds additional improvements and features in a friendly, easy to install docker.

## Introduction
The NowShowing docker provides a summary of new media that has recently been added to Plex, giving the Plex owner the option of delivering the information in two ways:
1) An email summary sent to all or selected users of the Plex Server
2) A webpage for users to visit

[Web Page Example](https://github.com/ninthwalker/NowShowing/master/README.md#webpage)  
[Email Example](https://github.com/ninthwalker/NowShowing/master/README.md#email-1)  

## Supported Platforms
* unRAID v6.3.x (Fully Supported)
* unRAID v6.x (Supported, but docker template may appear different)
* Linux platforms with Docker support (Supported, with a few manual settings like ENV Variables & Time)

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
3.  Plex Movie or TheMovieDB set as your Agent for Movie sections on the Plex server
4.  TheTVDB set as your Agent for TV sections on the Plex server
5.  An Email account that supports SSL SMTP

## Installation on unRAID
### Preferred Method: Community Applications
#### Step 1: Click on Apps & type in "NowShowing"
![alt text](http://i.imgur.com/g6SOvdC.png "unRAID CA Install Step 01")

#### Step 2: Click Add
![alt text](http://i.imgur.com/0TWEMpw.png "unRAID CA Install Step 02")

#### Step 3: Select your branch
Selecting Default will install the latest stable version of NowShowing.  
Selecting dev will install the latest development version of NowShowing.
![alt text](http://i.imgur.com/SL7UXWX.png "unRAID CA Install Step 03")

#### Step 4: Fill out the template
![alt text](http://i.imgur.com/fNOKcMP.png "unRAID CA Install Step 04")

#### Step 4a: Advanced Section - Optional
Optional - Enter in Plex Token instead of using sername/password  
Optional - Modify the UID/GID
![alt text](http://i.imgur.com/Q4cP3WC.png "unRAID CA Install Step 04a")

#### Step 5: Installation is complete
Your installation of NowShowing is complete. If you desire to change any of the config files they can be found at your /config install path.

You can also install by adding the following template repository to unraid:  
https://github.com/ninthwalker/docker-templates/

You can now edit the `advanced.yaml` (and optionally `email_body.erb` & `web_email_body.erb`) with your own settings in your appdata dir. See `/config/advanced.yaml.example` and below for details.

By default, the email will be sent out every Friday at 10:30am, and the web report will be generated once a day at 11:30pm local time. To change the schedules, enter in your own cron time in the advanced.yaml file. Restart the docker to have the changes take effect. See this page for help creating a time/date in cron: https://crontab.guru/

# Advanced Settings

Modify the below settings for advanced features and options

## Advanced Config

By default, the advanced config file is located in `/config/advanced.yaml`.  If you need to change any information for the program, or to add more optional config parameters, see below for the format:

###### email_body.erb
This file can be edited with CSS/HTML if you want to modify the look of the email.

###### web_email_body.erb
This file can be edited with CSS/HTML if you want to modify the look of the webpage.
Alternatively, edit the CSS or Javascript found in the 'www' folder.

###### email
`title` - Banner title for the email body.

`image` - 'Enter a URL or local path to image here'.

`footer` - 'Email footer tagline'. Optional.

`language` - The language of the email body. You need to use ISO 639-1 code ('fr', 'en', 'de'). If a content is not available in the specified language, the script will fall back to english. Defaults to 'en'. Optional.

###### web
`title_image` - 'Enter a URL or local path to image here'. This is the main image across the curtain background.

`logo` - 'Enter a URL or local path to image here'. This is the small logo in the left of the banner as you scroll.

`headline_title` - Top subtitle under main title image. This comes before the scrolling headliners below. Required.

`headliners` - Words you would like to rotate through after the headline_title. 'Screams, Thrills, Laughs'. Optional.

`footer` - 'Web footer tagline'. Optional.

`language` - Same as in the email section above. Optional

###### plex
`plex_user_emails` - To be used in conjuntion with the recipients and recipients_email options below to customize who the emails go to. 'yes' will send to plex users emails. 'no' will **NOT** send to plex user emails and only send to emails and users in the recipients fields below.

###### mail
`from` - Display name of the sender. Required.

`subject` - Subject of the email. Note that the script will automatically add a date to the end of the subject.  Required.

`recipients_email` - Enter additional emails to send to, besides your Plex friends. Format is ['bob@example.com', 'sally@example.com']   Optional.

`recipients` - Plex usernames of any Plex friends to be notified. Used if the 'plex_user_emails' is set to 'no'. Optional    
['PLEX_USER'] is yourself. Format is ['PLEX_USER', 'myFriend1', 'myFriend2']

##### report
`interval` - Number of days to search back on for reporting. Valid numbers are 1 to 7.

`report_type` - The report to generate. Options are 'emailonly', 'webonly' 'both'.

`email_report_time` - Time to send email report. In Cron format. See https://crontab.guru for help.

`web_report_time` - Time to create webpage report. In Cron format. See https://crontab.guru for help.
 
`extra_details` - Adds extra info when available like Ratings, Cast, Release Date, etc. 'yes' or 'no'
 
`test` - Creates website and sends email only to self. For testing. Options are 'disable' or 'enable'. Uses email_report_time.


## Command-line Options

If you need to reconfigure the program configs, first delete the existing config files, then change the variables in the unRAID template and restart the docker. These command line options below are not normally needed and are only for further testing or troubleshooting. You will either need to docker exec into the docker or run docker exec non interactively at the command line.

command line syntax: `nowshowing [report type] [options]`

##### Report Types
`combinedreport` For Both email and web  
`emailreport` For only email  
`webreport` For only web  

##### Options:
`-n, --no-plex-email` - Do not send emails to Plex friends.  Can be used with the `recipients_email` and `recipients` config file option to customize email recipients.

`-t, --test-email` - Send email only to the Plex owner (ie yourself).  For testing purposes

`-d, --detailed-email` - Send more details in the email, such as movie ratings, actors, etc

## Images

#### Webpage:  
![alt tag](http://i.imgur.com/PvUO2aM.jpg)


#### Email:  
![alt tag](http://i.imgur.com/35IddDh.png)  
