
            

            /* Simple AJAX Code-Kit (SACK) */
            /* �2005 Gregory Wild-Smith */
            /* www.twilightuniverse.com */
            /* Software licenced under a modified X11 licence, see documentation or authors website for more details */

            function sack(file){
                    this.AjaxFailedAlert = "Your browser does not support the enhanced functionality of this website, and therefore you will have an experience that differs from the intended one.\n";
                    this.requestFile = file;
                    this.method = "POST";
                    this.URLString = "";
                    this.encodeURIString = true;
                    this.execute = false;

                    this.onLoading = function() { };
                    this.onLoaded = function() { };
                    this.onInteractive = function() { };
                    this.onCompletion = function() { };

                    this.createAJAX = function() {
                            try {
                                    this.xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
                            } catch (e) {
                                    try {
                                            this.xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                                    } catch (err) {
                                            this.xmlhttp = null;
                                    }
                            }
                            if(!this.xmlhttp && typeof XMLHttpRequest != "undefined")
                                    this.xmlhttp = new XMLHttpRequest();
                            if (!this.xmlhttp){
                                    this.failed = true; 
                            }
                    };

                    this.setVar = function(name, value){
                            if (this.URLString.length < 3){
                                    this.URLString = name + "=" + value;
                            } else {
                                    this.URLString += "&" + name + "=" + value;
                            }
                    }

                    this.encVar = function(name, value){
                            var varString = encodeURIComponent(name) + "=" + encodeURIComponent(value);
                    return varString;
                    }

                    this.encodeURLString = function(string){
                            varArray = string.split('&');
                            for (i = 0; i < varArray.length; i++){
                                    urlVars = varArray[i].split('=');
                                    if (urlVars[0].indexOf('amp;') != -1){
                                            urlVars[0] = urlVars[0].substring(4);
                                    }
                                    varArray[i] = this.encVar(urlVars[0],urlVars[1]);
                            }
                    return varArray.join('&');
                    }

                    this.runResponse = function(){
                            eval(this.response);
                    }

                    this.runAJAX = function(urlstring){
                            this.responseStatus = new Array(2);
                            if(this.failed && this.AjaxFailedAlert){ 
                                    alert(this.AjaxFailedAlert); 
                            } else {
                                    if (urlstring){ 
                                            if (this.URLString.length){
                                                    this.URLString = this.URLString + "&" + urlstring; 
                                            } else {
                                                    this.URLString = urlstring; 
                                            }
                                    }
                                    if (this.encodeURIString){
                                            var timeval = new Date().getTime(); 
                                            this.URLString = this.encodeURLString(this.URLString);
                                            this.setVar("rndval", timeval);
                                    }
                                    if (this.element) { this.elementObj = document.getElementById(this.element); }
                                    if (this.xmlhttp) {
                                            var self = this;
                                            if (this.method == "GET") {
                                                    var totalurlstring = this.requestFile + "?" + this.URLString;
                                                    this.xmlhttp.open(this.method, totalurlstring, true);
                                            } else {
                                                    this.xmlhttp.open(this.method, this.requestFile, true);
                                            }
                                            if (this.method == "POST"){
                                                    try {
                                                            this.xmlhttp.setRequestHeader('Content-Type','application/x-www-form-urlencoded')  
                                                    } catch (e) {}
                                            }

                                            this.xmlhttp.send(this.URLString);
                                            this.xmlhttp.onreadystatechange = function() {
                                                    switch (self.xmlhttp.readyState){
                                                            case 1:
                                                                    self.onLoading();
                                                            break;
                                                            case 2:
                                                                    self.onLoaded();
                                                            break;
                                                            case 3:
                                                                    self.onInteractive();
                                                            break;
                                                            case 4:
                                                                    self.response = self.xmlhttp.responseText;
                                                                    self.responseXML = self.xmlhttp.responseXML;
                                                                    self.responseStatus[0] = self.xmlhttp.status;
                                                                    self.responseStatus[1] = self.xmlhttp.statusText;
                                                                    self.onCompletion();
                                                                    if(self.execute){ self.runResponse(); }
                                                                    if (self.elementObj) {
                                                                            var elemNodeName = self.elementObj.nodeName;
                                                                            elemNodeName.toLowerCase();
                                                                            if (elemNodeName == "input" || elemNodeName == "select" || elemNodeName == "option" || elemNodeName == "textarea"){
                                                                                    self.elementObj.value = self.response;
                                                                            } else {
                                                                                    self.elementObj.innerHTML = self.response;
                                                                            }
                                                                    }
                                                                    self.URLString = "";
                                                            break;
                                                    }
                                            };
                                    }
                            }
                    };
            this.createAJAX();
            }



            /************************************************************************************************************
            Tab view
            Copyright (C) October 2005  DTHMLGoodies.com, Alf Magne Kalleland

            This library is free software; you can redistribute it and/or
            modify it under the terms of the GNU Lesser General Public
            License as published by the Free Software Foundation; either
            version 2.1 of the License, or (at your option) any later version.
            s
            This library is distributed in the hope that it will be useful,
            but WITHOUT ANY WARRANTY; without even the implied warranty of
            MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
            Lesser General Public License for more details.

            You should have received a copy of the GNU Lesser General Public
            License along with this library; if not, write to the Free Software
            Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA

            Dhtmlgoodies.com., hereby disclaims all copyright interest in this script
            written by Alf Magne Kalleland.

            Alf Magne Kalleland, 2009
            Owner of DHTMLgoodies.com

            ************************************************************************************************************/

                    var textPadding = 3; // Padding at the left of tab text - bigger value gives you wider tabs
                    var strictDocType = false;
                    var tabView_maxNumberOfTabs = 6;	// Maximum number of tabs

                    /* Don't change anything below here */
                    var dhtmlgoodies_tabObj = new Array();
                    var activeTabIndex = new Array();
                    var MSIE = navigator.userAgent.indexOf('MSIE')>=0?true:false;

                    var regExp = new RegExp(".*MSIE ([0-9]\.[0-9]).*","g");
                    var navigatorVersion = navigator.userAgent.replace(regExp,'$1');

                    var ajaxObjects = new Array();
                    var tabView_countTabs = new Array();
                    var tabViewHeight = new Array();
                    var tabDivCounter = 0;
                    var closeImageHeight = 8;	// Pixel height of close buttons
                    var closeImageWidth = 8;	// Pixel height of close buttons


                    function setPadding(obj,padding){
                            var span = obj.getElementsByTagName('SPAN')[0];
                            span.style.paddingLeft = padding + 'px';
                            span.style.paddingRight = padding + 'px';
                    }
                    function showTab(parentId,tabIndex)
                    {
                            var parentId_div = parentId + "_";
                            if(!document.getElementById('tabView' + parentId_div + tabIndex)){
                                    return;
                            }
                            if(activeTabIndex[parentId]>=0){
                                    if(activeTabIndex[parentId]==tabIndex){
                                            return;
                                    }

                                    var obj = document.getElementById('tabTab'+parentId_div + activeTabIndex[parentId]);

                                    obj.className='tabInactive';
                                    document.getElementById('tabView' + parentId_div + activeTabIndex[parentId]).style.display='none';
                            }

                            var thisObj = document.getElementById('tabTab'+ parentId_div +tabIndex);

                            thisObj.className='tabActive';

                            document.getElementById('tabView' + parentId_div + tabIndex).style.display='block';
                            activeTabIndex[parentId] = tabIndex;


                            var parentObj = thisObj.parentNode;
                            var aTab = parentObj.getElementsByTagName('DIV')[0];
                            countObjects = 0;
                            var startPos = 2;
                            var previousObjectActive = false;
                            while(aTab){
                                    if(aTab.tagName=='DIV'){
                                            if(previousObjectActive){
                                                    previousObjectActive = false;
                                                    startPos-=2;
                                            }
                                            if(aTab==thisObj){
                                                    startPos-=2;
                                                    previousObjectActive=true;
                                                    setPadding(aTab,textPadding+1);
                                            }else{
                                                    setPadding(aTab,textPadding);
                                            }

                                            aTab.style.left = startPos + 'px';
                                            countObjects++;
                                            startPos+=2;
                                    }
                                    aTab = aTab.nextSibling;
                            }

                            return;
                    }

                    function tabClick()
                    {
                            var idArray = this.id.split('_');
                            showTab(this.parentNode.parentNode.id,idArray[idArray.length-1].replace(/[^0-9]/gi,''));

                    }

                    function rolloverTab()
                    {
                            if(this.className.indexOf('tabInactive')>=0){
                            }

                    }
                    function rolloutTab()
                    {
                            if(this.className ==  'inactiveTabOver'){
                            }

                    }

                    function hoverTabViewCloseButton()
                    {
                            this.src = this.src.replace('close.gif','close_over.gif');
                    }

                    function stopHoverTabViewCloseButton()
                    {
                            this.src = this.src.replace('close_over.gif','close.gif');
                    }

                    function initTabs(mainContainerID,tabTitles,activeTab,width,height,closeButtonArray,additionalTab)
                    {
                            if(!closeButtonArray)closeButtonArray = new Array();

                            if(!additionalTab || additionalTab=='undefined'){
                                    dhtmlgoodies_tabObj[mainContainerID] = document.getElementById(mainContainerID);
                                    width = width + '';
                                    if(width.indexOf('%')<0)width= width + 'px';
                                    dhtmlgoodies_tabObj[mainContainerID].style.width = width;

                                    height = height + '';
                                    if(height.length>0){
                                            if(height.indexOf('%')<0)height= height + 'px';
                                            dhtmlgoodies_tabObj[mainContainerID].style.height = height;
                                    }


                                    tabViewHeight[mainContainerID] = height;

                                    var tabDiv = document.createElement('DIV');
                                    var firstDiv = dhtmlgoodies_tabObj[mainContainerID].getElementsByTagName('DIV')[0];

                                    dhtmlgoodies_tabObj[mainContainerID].insertBefore(tabDiv,firstDiv);
                                    tabDiv.className = 'dhtmlgoodies_tabPane';
                                    tabView_countTabs[mainContainerID] = 0;

                            }else{
                                    var tabDiv = dhtmlgoodies_tabObj[mainContainerID].getElementsByTagName('DIV')[0];
                                    var firstDiv = dhtmlgoodies_tabObj[mainContainerID].getElementsByTagName('DIV')[1];
                                    height = tabViewHeight[mainContainerID];
                                    activeTab = tabView_countTabs[mainContainerID];


                            }



                            for(var no=0;no<tabTitles.length;no++){
                                    var aTab = document.createElement('DIV');
                                    aTab.id = 'tabTab' + mainContainerID + "_" +  (no + tabView_countTabs[mainContainerID]);
                                    aTab.onmouseover = rolloverTab;
                                    aTab.onmouseout = rolloutTab;
                                    aTab.onclick = tabClick;
                                    aTab.className='tabInactive';
                                    tabDiv.appendChild(aTab);
                                    var span = document.createElement('SPAN');
                                    span.innerHTML = tabTitles[no];
                                    span.style.position = 'relative';
                                    aTab.appendChild(span);

                                    if(closeButtonArray[no]){
                                            var closeButton = document.createElement('IMG');
                                            closeButton.src = 'images/close.gif';
                                            closeButton.height = closeImageHeight + 'px';
                                            closeButton.width = closeImageHeight + 'px';
                                            closeButton.setAttribute('height',closeImageHeight);
                                            closeButton.setAttribute('width',closeImageHeight);
                                            closeButton.style.position='absolute';
                                            closeButton.style.top = '6px';
                                            closeButton.style.right = '0px';
                                            closeButton.onmouseover = hoverTabViewCloseButton;
                                            closeButton.onmouseout = stopHoverTabViewCloseButton;

                                            span.innerHTML = span.innerHTML + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

                                            var deleteTxt = span.innerHTML+'';

                                            closeButton.onclick = function(){ deleteTab(this.parentNode.innerHTML) };
                                            span.appendChild(closeButton);
                                    }

                            }

                            var tabs = dhtmlgoodies_tabObj[mainContainerID].getElementsByTagName('DIV');
                            var divCounter = 0;
                            for(var no=0;no<tabs.length;no++){
                                    if(tabs[no].className=='dhtmlgoodies_aTab' && tabs[no].parentNode.id == mainContainerID){
                                            if(height.length>0)tabs[no].style.height = height;
                                            tabs[no].style.display='none';
                                            tabs[no].id = 'tabView' + mainContainerID + "_" + divCounter;
                                            divCounter++;
                                    }
                            }
                            tabView_countTabs[mainContainerID] = tabView_countTabs[mainContainerID] + tabTitles.length;
                            showTab(mainContainerID,activeTab);

                            return activeTab;
                    }

                    function showAjaxTabContent(ajaxIndex,parentId,tabId)
                    {
                            var obj = document.getElementById('tabView'+parentId + '_' + tabId);
                            obj.innerHTML = ajaxObjects[ajaxIndex].response;
                    }

                    function resetTabIds(parentId)
                    {
                            var tabTitleCounter = 0;
                            var tabContentCounter = 0;


                            var divs = dhtmlgoodies_tabObj[parentId].getElementsByTagName('DIV');


                            for(var no=0;no<divs.length;no++){
                                    if(divs[no].className=='dhtmlgoodies_aTab'){
                                            divs[no].id = 'tabView' + parentId + '_' + tabTitleCounter;
                                            tabTitleCounter++;
                                    }
                                    if(divs[no].id.indexOf('tabTab')>=0){
                                            divs[no].id = 'tabTab' + parentId + '_' + tabContentCounter;
                                            tabContentCounter++;
                                    }


                            }

                            tabView_countTabs[parentId] = tabContentCounter;
                    }


                    function createNewTab(parentId,tabTitle,tabContent,tabContentUrl,closeButton)
                    {
                            if(tabView_countTabs[parentId]>=tabView_maxNumberOfTabs)return;	// Maximum number of tabs reached - return
                            var div = document.createElement('DIV');
                            div.className = 'dhtmlgoodies_aTab';
                            dhtmlgoodies_tabObj[parentId].appendChild(div);

                            var tabId = initTabs(parentId,Array(tabTitle),0,'','',Array(closeButton),true);
                            if(tabContent)div.innerHTML = tabContent;
                            if(tabContentUrl){
                                    var ajaxIndex = ajaxObjects.length;
                                    ajaxObjects[ajaxIndex] = new sack();
                                    ajaxObjects[ajaxIndex].requestFile = tabContentUrl;	// Specifying which file to get

                                    ajaxObjects[ajaxIndex].onCompletion = function(){ showAjaxTabContent(ajaxIndex,parentId,tabId); };	// Specify function that will be executed after file has been found
                                    ajaxObjects[ajaxIndex].runAJAX();		// Execute AJAX function

                            }

                    }

                    function getTabIndexByTitle(tabTitle)
                    {
                            var regExp = new RegExp("(.*?)&nbsp.*$","gi");
                            tabTitle = tabTitle.replace(regExp,'$1');
                            for(var prop in dhtmlgoodies_tabObj){
                                    var divs = dhtmlgoodies_tabObj[prop].getElementsByTagName('DIV');
                                    for(var no=0;no<divs.length;no++){
                                            if(divs[no].id.indexOf('tabTab')>=0){
                                                    var span = divs[no].getElementsByTagName('SPAN')[0];
                                                    var regExp2 = new RegExp("(.*?)&nbsp.*$","gi");
                                                    var spanTitle = span.innerHTML.replace(regExp2,'$1');

                                                    if(spanTitle == tabTitle){

                                                            var tmpId = divs[no].id.split('_');
                                                            return Array(prop,tmpId[tmpId.length-1].replace(/[^0-9]/g,'')/1);
                                                    }
                                            }
                                    }
                            }

                            return -1;

                    }

                    /* Call this function if you want to display some content from external file in one of the tabs
                    Arguments: Title of tab and relative path to external file */

                    function addAjaxContentToTab(tabTitle,tabContentUrl)
                    {
                            var index = getTabIndexByTitle(tabTitle);
                            if(index!=-1){
                                    var ajaxIndex = ajaxObjects.length;

                                    tabId = index[1];
                                    parentId = index[0];


                                    ajaxObjects[ajaxIndex] = new sack();
                                    ajaxObjects[ajaxIndex].requestFile = tabContentUrl;	// Specifying which file to get

                                    ajaxObjects[ajaxIndex].onCompletion = function(){ showAjaxTabContent(ajaxIndex,parentId,tabId); };	// Specify function that will be executed after file has been found
                                    ajaxObjects[ajaxIndex].runAJAX();		// Execute AJAX function

                            }
                    }



                    function deleteTab(tabLabel,tabIndex,parentId)
                    {

                            if(tabLabel){
                                    var index = getTabIndexByTitle(tabLabel);
                                    if(index!=-1){
                                            deleteTab(false,index[1],index[0]);
                                    }

                            }else if(tabIndex>=0){
                                    if(document.getElementById('tabTab' + parentId + '_' + tabIndex)){
                                            var obj = document.getElementById('tabTab' + parentId + '_' + tabIndex);
                                            var id = obj.parentNode.parentNode.id;
                                            obj.parentNode.removeChild(obj);
                                            var obj2 = document.getElementById('tabView' + parentId + '_' + tabIndex);
                                            obj2.parentNode.removeChild(obj2);
                                            resetTabIds(parentId);
                                            activeTabIndex[parentId]=-1;
                                            showTab(parentId,'0');
                                    }
                            }





                    }
                    
    jQuery.fn.loadOuter = function( url, callback )
    {
        var toLoad = $(this);
        $.get(url, function( data ) {
            toLoad.replaceWith( data );
            if (callback != null && callback != undefined)
                callback();
        });
    }


