Pleas mark stuff thats depraciated with @old

to do
    chat
    showtour

Video
    youtube
    vimeo
    tape.tv
    dailymotion
    myvideo
    twitch


    

todo
    //@sec = important security stuff
    //@speed = possile improvements for speeding up the script

    docu
        usage of document.cookie
            session

    api
        typecasting
        api.query(data {if string->utf8} return if string -> JSescape);

    dock envelop -> commit icon bei nachricht active class gleich turquise

    group admin

    all onclick to bind(?)

    fix raster

    add protection to userPicture element, groupicture element etc

    item.share icon -> blue-retweet

    add type xml and json to openlink



Recent:

      SEO
      
      Mobile Site
        Guestpage
        leftNav

        appCenter
            example Application

      Style
            Hover
                .button, .anti-button

        Files
            encrypt files                                           x
        
        Security
            3rd Party Applications like Youtube                     x   
            groups.getData


	Update from running version:
		UPDATE userpw cypher sha512(md5(pass)+username)
		
		Add standard rsa private and public key? -> useless ?!
		
	


	caching
		buddylist
		friends you may know
		youtube titles
		youTubeIdToTitle()
		getUserFavs



JS nice to have

    desktop: Search onmouseover

    Scrollbars

    Projects
        foldable

    Responsive
        Apps - hexaflip
        http://oxism.com/hexaflip/

    Elements and Folders display #hashtag
        https://github.com/robbz/twitterwalljs

		
Components

    GUI
        Draggable Applications
        Dock
        Dashboard

    Filesystem
        Open Files and Links

______
Entropy

Main DB
Installations(id, host, pubKey, version)


universe
    on install/update:
        creates keypair
        sends host, pubkey, version to main DB
        saves pubkey in file, privkey encrypted in file or db or other secure solution
        receives list of hosts and stores them as a file
______ 
                            






Files and the use of them (outdated)

        modified during installation
            inc/config/dbConfig
            
            inc/config.php
            


	PHP
		inc/config.php		//mysql server confid
		inc/functions.php 	//collection of all functions used by the universeOS usually included in every file
		
		doit.php			//one of the biggest problems, the doit.php is used to show everything that diddn't fit
							//elsewhere. Now we have the mess. It is seperated by a big switch case which separes it
							//into > 30 actions (e.g "addFolder", "addElement", "deleteFolder", "addGroup")
							
							//all the actions will be seperated in located in /actions/folders or actions/groups etc.
			
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
		inc/guest.css		//all the css stuff
	
	Will be deleted:
		function checkAuthorisation => deprecated?
		function showYoutubeVideo => no use at all
		
		
		
	Index.php
		if !login
			guest.php
		
		if login
			js function reload()
				reload.php