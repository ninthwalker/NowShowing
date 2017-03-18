#!/usr/bin/ruby
require 'rubygems'
require 'json'
require 'httparty'

# Class that interacts with themoviedb.org. 
# Note that themoviedb.org limits API calls to 30 every 10 seconds,
# hence the sleep in this code.
#
# Author: Brian Stascavage
# Email: brian@stascavage.com
#
class TheMovieDB
    include HTTParty

    base_uri 'https://api.themoviedb.org/3//'
    format :json

    def initialize(config)
    	$token = '974eddb0f95ce2b912b9b37a63358823'
        $retry_attempt = 0 
    end

    def get(query, args=nil)
        if args.nil?
          new_query = query + "?api_key=#{$token}"
        else
          new_query = query + "?api_key=#{$token}&#{args}"
        end

        response = self.class.get(new_query, :verify => false)

        if response.code != 200
            if response.nil?
                return 'nil'
            end
            while $retry_attempts < 3 do
                $logger.error("Could not connect to themoviedb.org.  Will retry in 30 seconds")
                sleep(30)
                $retry_attempts += 1
                $logger.debug("Retry attempt: #{$retry_attempts}")
                if self.get(query).code == 200
                    break
                end
            end
            if $retry_attempts >= 3
                $logger.error("Could not connect to themoviedb.org.  Exiting script")
                exit
            end
        end

    	sleep 0.6
        $retry_attempt = 0
        return response
    end
end
