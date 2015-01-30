Update
    Delete Users where cypher is md5 or sha512

    check if our server serves svg files as image/svg, not as text/plain (regbox - loading bubbles)


Recent:
        Playlists
            createFile
            createPlaylist
            
    
        Open Designtaks:
            Guestpage
                Login & Start

        reload
            Ignore notifications
                notifications.ignore in notifications.js

        update all icons

        

        update Font to ubuntu
        


	Update from running version:
		DB
			user
				+cypher
				
			elements
				+originalTitle
				+language
				
			+signatures
			+salts
			
		_____
                    ALTER TABLE  `buddylist` ADD PRIMARY KEY (  `owner` ,  `buddy` ) ;

		UPDATE userpw cypher sha512(md5(pass)+username)
		
		Add standard rsa private and public key? 
		
	


	caching
		buddylist
		friends you may know
		youtube titles
		youTubeIdToTitle()
		getUserFavs
		
		
Files and the use of them

	PHP
		inc/config.php		//mysql server confid
		inc/functions.php 	//collection of all functions used by the universeOS usually included in every file
		
		doit.php			//one of the biggest problems, the doit.php is used to show everything that diddn't fit
							//elsewhere. Now we have the mess. It is seperated by a big switch case which separes it
							//into > 30 actions (e.g "addFolder", "addElement", "deleteFolder", "addGroup")
							
							//all the actions will be seperated in located in /actions/folders or actions/groups etc.
							
		guest.php 			//contains view for not registered user
							
		profile.php			//userprofile
		group.php			//group profile
		
		
		
		Modules and Apps
		modules/
				suggestions/
							dockSearch.php
				desktop/
							dashboard.php
	
	
	
		SEO/FB etc...
		openFileFromlink.php		//opens js openFile() function to open a file if universeOS.org?file=xy is called

		out/						//this folder contains all handlers for sharing files from the universe
		out/index.php				//contains universeFileBrowser
		out/download/index.php		//download handler
		
	
	
	JavaScript
		inc/functions.js	//all the js stuff
	
	CSS
		inc/style.css		//all the css stuff
	
	Will be deleted
		function checkAuthorisation => deprecated?
		function showYoutubeVideo => no use at all
		
		
		
	Index.php
		if !login
			guest.php
			
			modules/reader.php
			
		
		if login
			js function reload()
				reload.php
			
	
	
