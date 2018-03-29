#!/usr/bin/ruby
require 'rubygems'
require 'json'
require 'httparty'

# Class To interact with Plex.tv
#
# Author: Brian Stascavage
# Email: brian@stascavage.com
#
class PlexTv
    include HTTParty

    base_uri 'https://plex.tv/'
    format :xml

    def initialize(config)
        if !config.empty?
            if !config['token']['api_key'].empty?
                $token = config['token']['api_key']
            else
                if defined? $logger
                    $logger.error("Missing Plex token")
                end
            end
        end
    end

    def get(query, auth=nil, token_check=false)
        if !token_check
            new_query = query + "?X-Plex-Token=#{$token}"
        else
            new_query = query 
        end

        if auth.nil?
            response = self.class.get(new_query)
        else
            response = self.class.get(new_query, :basic_auth => auth)
        end

        if response.code != 200
            if response.code == 401
                puts "Invalid Plex.tv Credentials!"
                abort
            else
                puts "Cannot connect to Plex.tv! Check connection."
                abort
            end
        end
        return response
    end
end
