#!/usr/bin/ruby
require 'yaml'
require 'rubygems'
require 'json'
require 'httparty'

# Class To interact with Tautulli, for pulling statistics
# Author: Ninthwalker
# Full api path: http://ip:port + HTTP_ROOT + /api/v2?apikey=$apikey&cmd=$command

# key:
# m => pop movie
# v => pop tv
# a = > pop artist
# d => day movie
# D => day TV
# t => movie time
# T => TV time
# u => top user
# s => stream count
# c => Recently added counts
# A => totals (movie and tv)
# S => include songs in total

class Tautulli
    include HTTParty
    format :json
	
    def initialize
        $advanced = YAML.load_file('/config/cfg/advanced.yaml')
		$time  = $advanced['report']['interval']
        $server = $advanced['tautulli']['server']
		$port = $advanced['tautulli']['port']
		$api_key = $advanced['tautulli']['api_key']
		$httproot = "/" + $advanced['tautulli']['httproot']
		$https = $advanced['tautulli']['https']
		$stats = $advanced['tautulli']['stats']

	if !$server.nil?
            if $https == 'no'
                self.class.base_uri "http://#{$server}:#{$port}#{$httproot}/api/v2?apikey=#{$api_key}&cmd="
            else
                self.class.base_uri "https://#{$server}:#{$port}#{$httproot}/api/v2?apikey=#{$api_key}&cmd="
            end
        end
    end
	
    def test_connection
	  testConnection = self.class.get("arnold")
	  test = JSON.parse(testConnection.body)
	  @test_result = test["response"]["result"]
    end
	attr_reader :test_result
	
    def timeConvert h
      p, l = h.divmod(12)
      "#{l.zero? ? 12 : l}#{p.zero? ? ":00 A" : ":00 P"}M"
    end
	
	def get_popular_stats
	  getStats = self.class.get("get_home_stats&time_range=#{$time}&stats_type=0")
	  stats = JSON.parse(getStats.body)
	  begin
		  if $stats.include? "m"
			# most popular movie (by play count, by user) aka "Most Watched Movie"
			@pop_movie_id = stats["response"]["data"][1]["rows"][0]["rating_key"]
			@pop_movie = stats["response"]["data"][1]["rows"][0]["title"]
		  end
	  rescue => e
	      $logger.info("Popular Movie Stat failed")
	  end
	  
	  begin
		  if $stats.include? "v"
			# most popular tv show (by play count, by user) aka "Most Watched TV Show"
			@pop_tv_id = stats["response"]["data"][3]["rows"][0]["rating_key"]
			@pop_tv = stats["response"]["data"][3]["rows"][0]["title"]
		  end
	  rescue => e
	      $logger.info("Popular TV Stat failed")
	  end
	  
	  begin
		  if $stats.include? "a"
			# most popular artist (by play count, by user) aka "Most Listened to Artist"
			@pop_artist_id = stats["response"]["data"][5]["rows"][0]["rating_key"]
			@pop_artist = stats["response"]["data"][5]["rows"][0]["title"]
		  end
	  rescue => e
	      $logger.info("Popular Artist Stat failed")
	  end
	  
	  begin
		  if $stats.include? "s"
			# most concurrent streams
			@streams = stats["response"]["data"][9]["rows"][0]["count"]
		  end
	  rescue => e
	      $logger.info("Most Streams Stat failed")
	  end
	  
	  begin
		  if $stats.include? "u"
			# top user by duration for all content
			top_user = stats["response"]["data"][7]["rows"][0]["total_duration"]
			hr = top_user / (60 * 60)
			min = (top_user / 60) % 60
			sec = top_user % 60
			@friendly_top_user = "#{ hr } Hours & #{ min } Min's"
		  end
	  rescue => e
	      $logger.info("Top User Stat failed")
	  end
    end
	attr_reader :pop_movie_id
	attr_reader :pop_movie
	attr_reader :pop_tv
	attr_reader :pop_tv_id
	attr_reader :pop_artist_id
	attr_reader :pop_artist
	attr_reader :streams
	attr_reader :friendly_top_user
	
	def get_popular_day
      getDayOf = self.class.get("get_plays_by_dayofweek&time_range=#{$time}&y_axis=duration")
	  day = JSON.parse(getDayOf.body)
	  
	  begin
		  if $stats.include? "d"
			# most watched day for movies (by duration)
			movie_day = day["response"]["data"]["series"][1]["data"]
			movie_day_index = movie_day.index(movie_day.max)
			case movie_day_index
			when 0
			  @movie_day_name = "Sunday"
			when 1
			  @movie_day_name = "Monday"
			when 2
			  @movie_day_name = "Tuesday"
			when 3
			  @movie_day_name = "Wednesday"
			when 4
			  @movie_day_name = "Thursday"
			when 5
			  @movie_day_name = "Friday"
			when 6
			  @movie_day_name = "Saturday"
			end
		  end
	  rescue => e
	      $logger.info("Most Watched Day for Movies Stat failed")
	  end
	  
	  begin
		  if $stats.include? "D"
			# most watched day for TV
			tv_day = day["response"]["data"]["series"][0]["data"]
			tv_day_index = tv_day.index(tv_day.max)
			case tv_day_index
			when 0
			  @tv_day_name = "Sunday"
			when 1
			  @tv_day_name = "Monday"
			when 2
			  @tv_day_name = "Tuesday"
			when 3
			  @tv_day_name = "Wednesday"
			when 4
			  @tv_day_name = "Thursday"
			when 5
			  @tv_day_name = "Friday"
			when 6
			  @tv_day_name = "Saturday"
			end
		  end	
	  rescue => e
	      $logger.info("Most Watched Day for TV Stat failed")
	  end		  
	end	
	attr_reader :movie_day_name
	attr_reader :tv_day_name
	
    def get_popular_hour
	  getHourOf = self.class.get("get_plays_by_hourofday&time_range=#{$time}&y_axis=duration")
	  hour = JSON.parse(getHourOf.body)
	  
	  begin
		  if $stats.include? "t"
			# most popular hour for movies (by duratioon)
			movie_hour = hour["response"]["data"]["series"][1]["data"]
			movie_hour_index = movie_hour.index(movie_hour.max)
			@friendly_movie_time = timeConvert(movie_hour_index)
		  end
	  rescue => e
	      $logger.info("Popular Hour for Movies Stat failed")
	  end
	  
	  begin
		  if $stats.include? "T"
			# most popular hour for tv (by duration)
			tv_hour = hour["response"]["data"]["series"][0]["data"]
			tv_hour_index = tv_hour.index(tv_hour.max)
			@friendly_tv_time = timeConvert(tv_hour_index)
		  end
	  rescue => e
	      $logger.info("Popular Hour for TV Stat failed")
	  end
	end
	attr_reader :friendly_movie_time
	attr_reader :friendly_tv_time
	
    def get_libraries
	  getLibraries = self.class.get("get_libraries")
	  libraries = JSON.parse(getLibraries.body)
	  
	  begin
		  if $stats.include? "A"
			# movie library total
			movie_count = Array.new
			library_stats = libraries["response"]["data"]
				library_stats.each do |section|
					if section['section_type'] == "movie"
						movie_count.push(section['count'])
					end
				end
			@movie_count_sum = movie_count.map!(&:to_i).inject(:+)

			# tv library total (series)
			tv_count = Array.new
			library_stats = libraries["response"]["data"]
				library_stats.each do |section|
					if section['section_type'] == "show"
						tv_count.push(section['count'])
					end
				end
			@tv_count_sum = tv_count.map!(&:to_i).inject(:+)
		  end
	  rescue => e
	      $logger.info("Total Movie and TV Stats failed")
	  end
	  
	  begin
		  if $stats.include? "S"
				# music library total (songs)
			music_count = Array.new
			library_stats = libraries["response"]["data"]
				library_stats.each do |section|
					if section['section_type'] == "artist"
						music_count.push(section['child_count'])
					end
				end
			@music_count_sum = music_count.map!(&:to_i).inject(:+)
		  end
      rescue => e
	      $logger.info("Total Songs Stat failed")
	  end
	end
	attr_reader :movie_count_sum
	attr_reader :tv_count_sum
	attr_reader :music_count_sum

	# still want to add in a count of recently added movies/tv
	# for future reference:
	# sorts = .sort_by {|k,v| v}.reverse
    # average = .reduce(:+).to_f / movie_day.size
    # array string to integers = movie_count.map!(&:to_i).inject(:+)
end
