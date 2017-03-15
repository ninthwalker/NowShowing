#!/usr/bin/ruby
require 'rubygems'
require 'mail'
require 'time'

require_relative '/var/lib/plexReport/plexTv'

# Class for sending out the email notification.
#
# Author: Brian Stascavage
# Email: brian@stascavage.com
#
class MailReport
    def initialize(config, options)
        $config = config
	$plexEmails = options[:emails]
	$testEmail = options[:test_email]
        if !$config['mail']['port'].nil?
            $port = $config['mail']['port']
        else
            $port = 25
        end

        if !$config['mail']['subject'].nil?
            $subject = $config['mail']['subject']
        else
            $subject = "Plex Summary "
        end
    end

    # Method for pulling the email information from the config and emailing all Plex users
    def sendMail(body)
        options = { :address              => $config['mail']['address'],
                    :port                 => $port,
                    :domain               => 'otherdomain.com',
                    :user_name            => $config['mail']['username'],
                    :password             => $config['mail']['password'],
                    :authentication       => 'plain',
                    :enable_starttls_auto => true  }
            Mail.defaults do
            delivery_method :smtp, options
        end

        users = Array.new

        # Logic for pulling the email accounts from Plex.tv and/or the
	# config file
	plexTv = PlexTv.new($config)

	if !$testEmail
      	    if $plexEmails
                plex_users = plexTv.get('/pms/friends/all')

                if plex_users.nil? || plex_users.empty?
                    $logger.info("No Plex friends found.")  
                else                
                    plex_users['MediaContainer']['User'].each do | user |
			if !user['email'].empty?
                            users.push(user['email'])
			end
                    end
                end
	    end
	    if !$config['mail']['recipients'].nil? || !$config['mail']['recipients_email'].nil?
	        if !$config['mail']['recipients_email'].nil?
	            $config['mail']['recipients_email'].each do | recipient |
		        users.push(recipient)
	            end
	        end
	        if !$config['mail']['recipients'].nil?
		    $config['mail']['recipients'].each do | recipient |
		        plex_users = plexTv.get('/pms/friends/all')
                        plex_users['MediaContainer']['User'].each do | user |
		            if user['username'] == recipient
                                users.push(user['email'])
			    end
                        end
		    end
	        end
	    end
	end

        #Get owner's email as well and add it to the list of recpients
        users.push(plexTv.get('/users/account')['user']['email'][0])

        users.each do | user |
            mail = Mail.new do
                from "#{$config['mail']['from']} <#{$config['mail']['username']}>"
                to user
                subject $config['mail']['subject'] + " " + (I18n.l Time.now.to_date)
                content_type 'text/html; charset=UTF-8'
                body body
            end

            mail.deliver!
        end
    end
end
