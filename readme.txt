Pleas mark stuff thats depraciated with @old

Video
    youtube
    vimeo
    tape.tv
    dailymotion
    myvideo
    twitch


Update

    Delete Users where cypher is md5 or sha512

    Add session for each user
        --->    registration get client identifier

    

todo
    docu
        usage of document.cookie
            session

    get rid of
        mysql_query
        $.post, $.ajax

    api
        api.query(data {if string->utf8} return if string -> JSescape);



    score
        positive ergebnisse #313131;
        negative in #607d8b

    dock envelop -> commit icon bei nachricht active class gleich turquise

    group admin

    all onclick to bind(?)

    group profile button feedback
        button feedback

    fix raster

    add protection to userPicture element, groupicture element etc

    item.share icon -> blue-retweet

    fade in applications

    pabst rightclick .rightclick, data-type data-typeId

    add type xml and json to openlink

    registration
        hints for stronger passwords

    group admin
        change group picture

Recent:
      Style
            Hover
                .button, .anti-button
                fenster: close, minimize, maximize

            Background
                
                

        Files
            encrypt files                                           x
        
        Security
            3rd Party Applications like Youtube                     x   
            groups.getData


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



Files and the use of them (outdated

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