Recent:

	JS
		updateDashbox(type)

	OO/Functions
		
		in work
			createGroup, addLink
	
		group
			createGroup etc.
			
		chat

	caching
		buddylist
		friends you may know
		youtube titles
		youTubeIdToTitle()
		getUserFavs
		
	security
	
	Bugs
		Session login prob
		
	Privacy
		die protectedfunktion muss zu einer undeleteablefunktion und dann muss für groupfiles und userfiles eine neue protectedfunktion geschrieben werden,
		da sich derzeit bei protectedten ordnern keinen neuen ordner von nichtadmins erstellen lassen.
		lsng:
			PROTECTED funktionen bleibt bestehen und es wird extra handler für SYSTEM geschrieben, welcher manuell bei userFiles,groupFiles und den userOrdnern(function createUser()) eingefügt wird.
		
	Install
		Vars
				URL
				db
				dbUser
				dbPass
		
	
	Welcome message
		Add sentence with calender after calender has been added to "the dock"
		
		klieines s schriftart scvheiße
		
		"An Element contains files and links which are connected with each other. They are listed in the filesystem to folders.<br><i><b>For example</b> you could create the image-element "My Nice Holiday In Nepal" and upload all your holiday pictures in it."
	

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
		openFileFromlink.php//opens js openFile() function to open a file if universeOS.org?file=xy is called
		out/				//in this folder is everything
	
	
	JavaScript
		inc/functions.js	//all the js stuff
	
	CSS
		inc/style.css		//all the css stuff
	
	Will be deleted
		function showActivity =>  deprecated due integration in showUserPicture()
		function checkAuthorisation => deprecated
		function showYoutubeVideo => no use at all
		
		
		
	Index.php
		if !login
			guest.php
			
			modules/reader.php
			
		
		if login
			js function reload()
				reload.php
			
	
	
