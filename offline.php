<style>
#offlineWrap{
    position: absolute;
    width: 100%;
    top: 0px;
    left: 0px;
    bottom: 0px;
    background: #FFFFFF;
}

#offlineMain{
    position: absolute;
    width: 100%;
    height: 30%;
}

#offlineFooter{
    position: absolute;
    width: 100%;
    height: 100%;
    border-top: 1px solid #9c9894;
    font-size: 12pt;
    color:#FFFFFF;background: #4f85bb; /* Old browsers */
	background: -moz-linear-gradient(top, #4f85bb 0%, #4f85bb 100%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#4f85bb), color-stop(100%,#4f85bb)); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(top, #4f85bb 0%,#4f85bb 100%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(top, #4f85bb 0%,#4f85bb 100%); /* Opera 11.10+ */
	background: -ms-linear-gradient(top, #4f85bb 0%,#4f85bb 100%); /* IE10+ */
	background: linear-gradient(to bottom, #4f85bb 0%,#4f85bb 100%); /* W3C */
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#4f85bb', endColorstr='#4f85bb',GradientType=0 ); /* IE6-9 */
    z-index: 1;
}

#offlineFooter .item{
	margin: 70px;
    margin-left:2%;
    margin-top:0px;
}

#offlineFooter .item h2, #offlineFooter .item h3{
	float:right;
	clear:right;
	line-height: 62px;
}
#offlineFooter .item .itemContent{
	margin-left: 60px;
	float:left;
	overflow:auto;
    overflow-y: hidden;
    height: 60%;
}
#offlineFooter .item p{
	float:left;
}

#offlineFooter .item #offlinePageImList{
	clear: left; margin-left:20px; float: left;
	margin-top: 10px;
}

#offlineFooter .item #offlinePageImList li{
	float: left;
	margin-left: 5px;
	font-size: 14pt;
}

#offlineFooter .item #offlinePageImList li img{
	height: 12px;
	margin-bottom: -2px;
	margin-right:5px;
}
#offlineFooter .item #offlinePageImList li .btn{
	margin-top: -5px;
}



#offlineFooter .carousel-indicators li{
	float:left;
	list-style:none;
	margin-top: -14px;
	height: 16px;
	width: 16px;
	-webkit-border-radius: 16px;
	-moz-border-radius: 16px;
	border-radius: 16px;
	margin-left:5px;
        
        /*gradient*/
        background: rgb(113,119,124); /* Old browsers */
        /* IE9 SVG, needs conditional override of 'filter' to 'none' */
        background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iIzcxNzc3YyIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjE3JSIgc3RvcC1jb2xvcj0iIzUyNTc1YiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiMyODM0M2IiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
        background: -moz-linear-gradient(top, rgba(113,119,124,1) 0%, rgba(82,87,91,1) 17%, rgba(40,52,59,1) 100%); /* FF3.6+ */
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(113,119,124,1)), color-stop(17%,rgba(82,87,91,1)), color-stop(100%,rgba(40,52,59,1))); /* Chrome,Safari4+ */
        background: -webkit-linear-gradient(top, rgba(113,119,124,1) 0%,rgba(82,87,91,1) 17%,rgba(40,52,59,1) 100%); /* Chrome10+,Safari5.1+ */
        background: -o-linear-gradient(top, rgba(113,119,124,1) 0%,rgba(82,87,91,1) 17%,rgba(40,52,59,1) 100%); /* Opera 11.10+ */
        background: -ms-linear-gradient(top, rgba(113,119,124,1) 0%,rgba(82,87,91,1) 17%,rgba(40,52,59,1) 100%); /* IE10+ */
        background: linear-gradient(to bottom, rgba(113,119,124,1) 0%,rgba(82,87,91,1) 17%,rgba(40,52,59,1) 100%); /* W3C */
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#71777c', endColorstr='#28343b',GradientType=0 ); /* IE6-8 */
        
        /*box-shadow*/
        -moz-box-shadow: 0 0 5px #888;
        -webkit-box-shadow: 0 0 5px#888;
        box-shadow: 0 0 5px #888;
}


#offlineFooter .carousel-indicators .active{
	
	background: rgb(40,52,59); /* Old browsers */
        /* IE9 SVG, needs conditional override of 'filter' to 'none' */
        background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iIzI4MzQzYiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjgzJSIgc3RvcC1jb2xvcj0iIzUyNTc1YiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiM3MTc3N2MiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
        background: -moz-linear-gradient(top, rgba(40,52,59,1) 0%, rgba(82,87,91,1) 83%, rgba(113,119,124,1) 100%); /* FF3.6+ */
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(40,52,59,1)), color-stop(83%,rgba(82,87,91,1)), color-stop(100%,rgba(113,119,124,1))); /* Chrome,Safari4+ */
        background: -webkit-linear-gradient(top, rgba(40,52,59,1) 0%,rgba(82,87,91,1) 83%,rgba(113,119,124,1) 100%); /* Chrome10+,Safari5.1+ */
        background: -o-linear-gradient(top, rgba(40,52,59,1) 0%,rgba(82,87,91,1) 83%,rgba(113,119,124,1) 100%); /* Opera 11.10+ */
        background: -ms-linear-gradient(top, rgba(40,52,59,1) 0%,rgba(82,87,91,1) 83%,rgba(113,119,124,1) 100%); /* IE10+ */
        background: linear-gradient(to bottom, rgba(40,52,59,1) 0%,rgba(82,87,91,1) 83%,rgba(113,119,124,1) 100%); /* W3C */
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#28343b', endColorstr='#71777c',GradientType=0 ); /* IE6-8 */
        
        
        -moz-box-shadow: none;
        -webkit-box-shadow: none;
        box-shadow: none;

}

#offlineFooterBox{
    position: absolute;
    left: 1em;
    bottom: 1em;
}

#startPageBackgroundBox{
    position: absolute;
    top: 5%;
    left: 10%;
    width: 120px;
    height: 75px;
    background: #3da1fe;
    z-index: 2;
    border: 2px solid #9c9894;
    border-top: 0px;
    -webkit-border-bottom-right-radius: 15px;
    -webkit-border-bottom-left-radius: 15px;
    -moz-border-radius-bottomright: 15px;
    -moz-border-radius-bottomleft: 15px;
    border-bottom-right-radius: 15px;
    border-bottom-left-radius: 15px;
    margin-left: -60px;
}

#startPageLogo{
    position: absolute;
    top: 22px;
    z-index: 5;
    width: 50px;
    left: 2%;
	margin-left: 60px;
}

.offlinePageBox{
    width: 20%;
    margin-left: 10%;
    margin-top: 3%;
    float: left;
    color: #676767;
    font-size: 11pt;
    text-align: center;
    /*background: #F1F1F1;*/
}

.offlinePageBox > div > header{
    font-size: 13pt;
    margin-top: 03px;
    margin-bottom: 03px;
}
</style>
<div id="offlineWrap">
    <img src="gfx/logo.png" id="startPageLogo">
    <div id="offlineFooter">
        <div id="offlineFooterBox">
            <a href="http://wiki.universeos.org" target="blank" title="universe Wiki - everything you need to know" style="float: left;"><img src="./gfx/icons/wikipedia.png" height="32"></a>
        </div>
        <div id="myCarousel" class="carousel slide">
		  <ol class="carousel-indicators" style="position: absolute; right: 2%; top: 22px; margin-right: 50px;">
		    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
		    <li data-target="#myCarousel" data-slide-to="1"></li>
		    <li data-target="#myCarousel" data-slide-to="2"></li>
		    <li data-target="#myCarousel" data-slide-to="3"></li>
		    <li data-target="#myCarousel" data-slide-to="4"></li>
		  </ol> 
		  <!-- Carousel items -->
		  <div class="carousel-inner">
		    <div class="active item">
		    	<h2>universeOS</h2>
		    	<div class="itemContent">
	                <p>
	                	<img src="gfx/startPage/screen.jpg" style="width: 30%; float: left; margin-right:15px; margin-bottom:15px;">
	               		Enjoy all the functions of a social network, packed in a flexible, interactive platform and become part of a great project.
	                </p>
		    		<center style="width: 100%;">		
	                            <div class="pull-left" style="clear:left;">
	                                <img src="gfx/startPageIcons4/security.png" width="20%" class=""><br>
	                                Be Secure
	                            </div>
	                            <div class="pull-left">
	                                <img src="gfx/startPageIcons4/network.png" width="20%"><br>
	                                Collaborate
	                            </div>
	                            <div class="pull-left" style="">
	                                <img src="gfx/startPageIcons4/book.png" width="20%" class=""><br>
	                                Collect
	                            </div>
	                  </center>
                 </div>
            </div>
		    <div class="item">
		    	<h2>We stand up for your rights.</h2>
		    	<div class="itemContent">
		    		<p style="margin-bottom: 20px;">We are non-profit and growth oriented organisation. Therefore we are not using the path of least resistance but vouch for the users privacy.</p>
		    		<p style="margin-bottom: 20px;">
		    		  We use advertisement to cover the running costs and in addition to this, try to produce surplus for charitable projects.
		    		</p>
				</div>
            </div>
		    <div class="item">
		    	<h2>Manage your files.</h2>
		    	
		    	<div class="itemContent">
		    		<p><img style="border-style: initial; border-color: initial; cursor: default; outline-width: 1px; outline-style: solid; outline-color: black; border-width: 0px; float: left; margin-right: 15px; margin-bottom: 15px;" title="chat" src="http://www.transparency-everywhere.com/upload/chat.png" alt="chat" width="15%" />Use thousands of free publications.</p>
               	 	<p style="margin-bottom: 20px;">Share your work with the rest of the world, your friends or chosen groups.</p>
                	<p>Include your personal filesystem on your website. </p>
					<p>&nbsp;</p>
				</div>
		    </div>
		    <div class="item">
		    	<h2>universeOS - a safe place to talk</h2>
		    	<div class="itemContent">
		    	<img style="border-style: initial; border-color: initial; cursor: default; outline-width: 1px; outline-style: solid; outline-color: black; border-width: 0px; float: left; margin-left: 15px; margin-bottom: 15px;" title="chat" src="http://www.transparency-everywhere.com/upload/chat.png" alt="chat" width="20%" />
		    	<ul style="float:right; max-width: 75%; margin-bottom: 40px;">
		    			<li>The instant messenger allows the user to use an encrypted writing.</li>
		    			<li>You can have conversations without anyone, even us seeing what you are really writing.</li>
		    	</ul>
                        <p style="float: left;">
                           <ul id="offlinePageImList">
                                <li style="font-size: 14pt; margin-bottom: 5px;">Its that easy:</li>
                                <li style="clear: left;"><i class="btn  btn-small btn-info">1</i> Sign Up</li>
                                <li> <img src="gfx/icons/whiteArrowRight.png" /> <i class="btn btn-mini btn-info">2</i> Add your Buddy </li>
                                <li> <img src="gfx/icons/whiteArrowRight.png" /> <i class="btn btn-mini btn-info">3</i> Set a key</li>
                                <li> <img src="gfx/icons/whiteArrowRight.png" /> <i class="btn btn-mini btn-info">4</i> Communicate safely</li>
                           </ul>
                        </p>
			</div>
		    </div>
		  </div>
		  <!-- Carousel nav -->
		  <a class="carousel-control left" href="#myCarousel" data-slide="prev" style="left: 2%; top: 120px;">&lsaquo;</a>
		  <a class="carousel-control right" href="#myCarousel" data-slide="next" style="top: 120px">&rsaquo;</a>
		</div>
		<script>
		$('#').carousel();
		</script>
    </div>
</div>