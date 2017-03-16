#!/usr/bin/ruby
require 'rubygems'
require 'json'
require 'httparty'

# Class that interacts with omdbapi.com.
#
# Author: Brian Stascavage
# Email: brian@stascavage.com
#
class OMDB
    include HTTParty

    base_uri 'http://www.omdbapi.com//'
    format :json

    def initialize
    end

    def get(query, args=nil)
        new_query = '?i=' + query + '&plot=short&r=json'

        response = self.class.get(new_query, :verify => false)

        if response.code != 200
            if response.nil?
                return 'nil'
            end
            while retry_attempts < 3 do
                $logger.error("Could not connect to omdb.  Will retry in 30 seconds")
                sleep(30)
                $retry_attempts += 1
                $logger.debug("Retry attempt: #{$retry_attempts}")
                if self.get(query).code == 200
                    break
                end
            end
            if retry_attempts >= 3
                $logger.error "Could not connect to omdb.  Exiting script."
                exit
            end
        end
        return response
    end
end
