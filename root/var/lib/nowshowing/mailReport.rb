#!/usr/bin/ruby

require 'rubygems'
require 'mail'
require 'time'

require_relative '/var/lib/nowshowing/plexTv'

# Class for sending out the email notification.
#
# Author: Brian Stascavage
# Email: brian@stascavage.com
# Modified by: ninthwalker
#
class MailReport
    def initialize(advanced, options)
	$advanced = advanced
	$plexEmails = options[:emails]
	$testEmail = options[:test_email]
        if !$advanced['mail']['port'].nil?
            $port = $advanced['mail']['port']
        else
            $port = 587
        end

        if !$advanced['mail']['subject'].nil?
            $subject = $advanced['mail']['subject']
        else
            $subject = "Plex Summary "
        end
    end

    # Method for pulling the email information from the config and emailing all Plex users
	# set auth to nil, and use ruby smtp.rb modified with auto-detection code.
    def sendMail(body)
        options = { :address              => $advanced['mail']['address'],
                    :port                 => $port,
                    :domain               => ENV['HOSTNAME'],
                    :user_name            => $advanced['mail']['username'],
                    :password             => $advanced['mail']['password'],
                    :authentication       => nil,
                    :enable_starttls_auto => true  }
            Mail.defaults do
            delivery_method :smtp, options
        end

        users = Array.new

        # Logic for pulling the email accounts from Plex.tv and/or the
	# config file
	plexTv = PlexTv.new($advanced)

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
	    if !$advanced['mail']['recipients'].nil? || !$advanced['mail']['recipients_email'].nil?
	        if !$advanced['mail']['recipients_email'].nil?
	            $advanced['mail']['recipients_email'].each do | recipient |
		        users.push(recipient)
	            end
	        end
	        if !$advanced['mail']['recipients'].nil?
		    $advanced['mail']['recipients'].each do | recipient |
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
     
	#used to send individual email. Now it bcc's one email
        #users.each do | user |
            mail = Mail.new do
                from "#{$advanced['mail']['from']} <#{$advanced['mail']['username']}>"
                bcc users
                subject $advanced['mail']['subject'] + " " + (I18n.l Time.now.to_date)
                content_type 'text/html; charset=UTF-8'
                body body
            end
            begin
              mail.deliver!
			rescue => e
			  $logger.info("SMTP mailing failed!\n#{e.message}#{e.backtrace}")
			end
        #end
    end
end
