
# NowShowing v2

![](https://raw.githubusercontent.com/ninthwalker/NowShowing/v2/images/ns_v2_title.png)

[![Docker Automated build](https://img.shields.io/docker/automated/jrottenberg/ffmpeg.svg)](https://hub.docker.com/r/ninthwalker/nowshowing/) [![](https://images.microbadger.com/badges/image/ninthwalker/nowshowing.svg)](https://microbadger.com/images/ninthwalker/nowshowing "NowShowing") [![Build Passing](https://img.shields.io/badge/build-passing-brightgreen.svg)](https://hub.docker.com/r/ninthwalker/nowshowing/)  

## Description / Background
NowShowing is the successor of the popular plexReport docker. The original brainchild of [bstascavage](https://github.com/bstascavage/plexReport). Further developed by NinthWalker & enhanced by GroxyPod, NowShowing adds additional improvements and features in a friendly, easy to install docker.

## Introduction
The NowShowing docker provides a summary of new media that has recently been added to Plex, giving the Plex owner the option of delivering the information in two ways:
1) An email summary sent to all or selected users of the Plex Server
2) A web page for users to visit  

## New in v2:
### Complete code rewrite with tons of new features!
* Same great Email/webpage for Plex recently added as v1
* All new Web Interface for settings and customization
* Library Filtering!
* [Tautilli](https://github.com/Tautulli/Tautulli) Statistics integration
* New user Setup Wizard
* Announcement Emails
* Web based Log Viewer
* Easy Plex token retrieval
* Most email providers supported
* BCC users instead of multiple emails
* Web tools including On-Demand and Test reports



### [Screenshots](https://github.com/ninthwalker/NowShowing/wiki/Screenshots)

## Supported Platforms
* unRAID v6.3.x (Fully Supported)
* unRAID v6.x (Supported, but docker template may appear different)
* Other Docker platforms (Supported, but extra docker commandline options may be needed. [See Wiki](https://github.com/ninthwalker/NowShowing/wiki/Other-Docker-Platforms)

## Supported Email Clients
* Most Email providers with SSL SMTP support [see Wiki FAQ](https://github.com/ninthwalker/NowShowing/wiki/FAQ-&-Known-Issues)

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
[See unRAID Wiki](https://github.com/ninthwalker/NowShowing/wiki/unRAID-Instructions)

## Installation on other Docker platforms  
[See Other Docker platforms Wiki](https://github.com/ninthwalker/NowShowing/wiki/Other-Docker-Platforms)  

## Advanced settings, FAQ, Command-line options & other questions:  
[See Github Wiki](https://github.com/ninthwalker/NowShowing/wiki)  
