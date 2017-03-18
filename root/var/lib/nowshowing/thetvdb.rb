#!/usr/bin/ruby
require 'rubygems'
require 'json'
require 'httparty'

# Class that interacts with thetvdb.org.  
#
# Author: Brian Stascavage
# Email: brian@stascavage.com
#
class TheTVDB
    include HTTParty

    token = '71362BFFDCA2C8CD'
    base_uri "http://thetvdb.com/api/#{token}//"

    def initialize
        $retry_attempts = 0
    end

    def get(query, args=nil)
        begin
            response = self.class.get(query, :verify => false)
        rescue EOFError
            $logger.error("thetvdb.org is providing wrong headers.  Blah!  Retrying.")
            while $retry_attempts < 3 do
                $logger.error("Could not connect to thetvdb.com.  Will retry in 30 seconds")
                sleep(30)
                $retry_attempts += 1
                $logger.debug("Retry attempt: #{$retry_attempts}i for query #{query}")
                if self.get(query).code == 200
                    break
                end
            end
            if $retry_attempts >= 5
                $logger.error("Could not connect to thetvdb.  Exiting script.  If you are constantly seeing this, please turn on debugging and open an issue.")
                $logger.debug("Failed to connect to thetvdb for query: #{query}")
                exit
            end

            $retry_attempts = 0
            return nil
        end
        $logger.debug("Response from thetvdb for query #{query}: Code: #{response.code}.")

        if response.code != 200
            if response.nil?
                return 'nil'
            end
            while $retry_attempts < 3 do
                $logger.error("Could not connect to thetvdb.com.  Will retry in 30 seconds")
                sleep(30)
                $retry_attempts += 1
                $logger.debug("Retry attempt: #{$retry_attempts} for query #{query}")
                if self.get(query).code == 200
                    break
                end
            end
            if $retry_attempts >= 5
                $logger.error("Could not connect to thetvdb.  Exiting script.  If you are constantly seeing this, please turn on debugging and open an issue.")
                $logger.debug("Failed to connect to thetvdb for query: #{query}")
                exit
            end
        end

        $retry_attempts = 0
        return response
    end
end
