//        This file is published by transparency - everywhere with the best deeds.
//        Check transparency - everywhere.com for further information.
//        Licensed under the CC License, Version 4.0 (the "License");
//        you may not use this file except in compliance with the License.
//        You may obtain a copy of the License at
//        
//        https://creativecommons.org/licenses/by/4.0/legalcode
//        
//        Unless required by applicable law or agreed to in writing, software
//        distributed under the License is distributed on an "AS IS" BASIS,
//        WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
//        See the License for the specific language governing permissions and
//        limitations under the License.
//        @author nicZem for Tranpanrency - everywhere.com


//scetch
        

var appCenter = new function(){
    this.init = function(){
        
        var     output = '<div>';
                    output += "<div class='leftNav dark' style='top: 0; background: #37474f;'>";
                    output +=    '<ul>';
                    output +=       '<li data-function="appCenter.showApplicationOverview();"><span class="icon blue-gear"></span><span class="icon white-gear white"></span>Start Page</li>';
                    output +=       '<li data-function="appCenter.showApplicationOverview({type:\'user\'});"><span class="icon blue-user"></span><span class="icon white-user white"></span>My Applications</li>';
                    output +=       '<li data-function="appCenter.showApplicationOverview();"><span class="icon blue-file"></span><span class="icon white-file white"></span>See All</li>';
                    output +=       '<li data-function="appCenter.showCreateApplicationForm();"><span class="icon blue-file"></span><span class="icon white-file white"></span>Create New</li>';
                    output +=   '</ul>';
                    output += '</div>';
                    output += "<div class='frameRight' style='top:0px;' id='appCenterFrame'>";
                    output += '</div>';
                output += '</div>';
                
                
                
        this.applicationVar = new application('appCenter');
	this.applicationVar.create('App Center', 'html', output,{width: 6, height:  7, top: 2, left: 3});
	this.showApplicationOverview();
        
        $('.leftNav li').click(function(){
            var functionName = $(this).attr('data-function');
            $(this).parent().children('li').removeClass('active');
            $(this).addClass('active');
            eval(functionName);
        });
    };
    this.createApp = function(parameters, cb){
        api.query('api/appCenter/createApp/',parameters, cb);
    };
    this.showCreateApplicationForm = function(){
        
        var appData = api.query('api/appCenter/prepareAppCreation/', {});
        
        
        
        applications.show('appCenter');
        var fieldArray = [];
        var options = [];
        options['headline'] = 'Create App';
        options['buttonTitle'] = 'Save';
        options['noButtons'] = true;
        //Realname, City, Hometown, Birthdate, School, University, Work
        
        fieldArray.push();
        fieldArray.push({
            caption: 'Title',
            inputName: 'title',
            type: 'text',
            caption_position: 'left'
        });
        fieldArray.push({
            caption: 'Version',
            inputName: 'version',
            type: 'text',
            caption_position: 'left'
        });
        fieldArray.push({
            caption: 'Description',
            inputName: 'description',
            type: 'text',
            caption_position: 'left'
        });
        fieldArray.push({
            caption: 'Entry Point',
            inputName: 'entryPoint',
            type: 'text',
            caption_position: 'left'
        });
        fieldArray.push({
            caption: 'Icon',
            inputName: 'icon',
            type: 'html',
            caption_position: 'left',
            value: "<a href='#' class='pull-left attachIcon'><i class='icon icon-paperclip'></i></a>"
        });
        fieldArray.push({
            caption: 'Archive',
            inputName: 'archive',
            type: 'html',
            caption_position: 'left',
            value: "<a href='#' class='pull-left attachArchive'><i class='icon icon-paperclip'></i></a>"
        });
        fieldArray.push({
            caption: '',
            inputName: 'buttons',
            type: 'html',
            value: '<div id=\'buttons\'><input type=\'submit\' value=\'save\' class=\'button pull-right\'></div>'
        });
        
        
        //application type
        //php nodejs ruby python
        
        //dependencies
        
        //Use Packet/Dependency manager: npm ore composer
        
        //privacy
        
        $('#appCenterFrame').html('<div id="createApplicationFormContainer"></div>');
        
       // formModal.init('Update Profile', '<div id="updateProfileFormContainer"></div>', modalOptions);
        gui.createForm('#createApplicationFormContainer',fieldArray, options);
        
        
        $('#createApplicationFormContainer .attachArchive').bind('click', function(){
            filesystem.attachItem($('#createApplicationFormContainer .attachArchive .icon-paperclip'), {objectType:'archive', objectId:appData.archiveId}, true);
        });
        
        $('#createApplicationFormContainer .attachIcon').bind('click', function(){
            filesystem.attachItem($('#createApplicationFormContainer .attachIcon .icon-paperclip'), {objectType:'archive', objectId:appData.archiveId}, true);
        });
        
        
        
        
        
        var self = this;
        $('#createApplicationFormContainer .dynForm').submit(function(e){
            e.preventDefault();
            self.createApp({parameters: {'appId':appData.id ,'title': $('#title').val(), 'version':$('#version').val(), 'description':$('#description').val(), 'entryPoint':$('#entryPoint').val(), 'archiveFileId': $('#createApplicationFormContainer .attachArchive .itemThumb').attr('data-itemid'), 'iconFileId': $('#createApplicationFormContainer .attachIcon .itemThumb').attr('data-itemid')}}, function(){
                gui.alert('The app has been added');
            });
        });
        
    };
    this.getApplicationList = function(){
        return api.query('api/appCenter/getApps/', {});
    };
    this.getAppData = function(id){
        var result;
        $.each(this.getApplicationList(), function(index, value){
            console.log(parseInt(value['id']) == id);
            if(parseInt(value['id']) == id){
                result = value;
            }
        });
        return result;
    };
    
    this.getAppsForUser = function(){
        return api.query('api/appCenter/getAppsForUser/', {});
    };
    this.getAppDetailsForUser = function(){
        return api.query('api/appCenter/getAppDetailsForUser/', {});
    };
    //links app to user
    this.addAppToUserDashboard = function(appId, cb){
        return api.query('api/appCenter/addAppToUserDashboard/', {parameters: {app_id: appId}}, cb);
    };
    //removes app from user
    this.removeAppFromUserDashboard = function(appId, cb){
        return api.query('api/appCenter/removeAppFromUserDashboard/', {parameters: {app_id: appId}}, cb);
    };
    this.showApplicationOverview = function(filter){
            var applicationList = this.getApplicationList();
            var userApplications = this.getAppsForUser();
            
            var items = [];
            if(typeof filter === 'undefined'){
                items = applicationList;
            }else if(filter.type === 'user'){
                $.each(applicationList, function(index, value){
                    if($.inArray(value.id, userApplications) !== -1){
                        items.push(value);
                    }
                });
            }
        
            var listItems = [];
            var self = this;
            $.each(items, function(index, value){
                var listText = '';
                    if(value.icon_file_id != 0)
                        listText += '<img src=\"'+filesystem.getFilePath(value.icon_file_id)+'\"/>';
                    listText += '<h2>'+value.title+'</h2>';
                    listText += '<p>'+value.description+'</p>';
                
                var button = '';
                console.log($.inArray(value.id, self.getAppsForUser()));
                if($.inArray(value.id, self.getAppsForUser()) === -1)
                    button = '<button class="button addApplication" data-app-id="'+value.id+'">Add to Dashboard</button>';
                else
                    button = '<button class="button removeApplication" data-app-id="'+value.id+'">Remove from Dashboard</button>';
               listItems.push({'text':listText, 'buttons':button});
            });
            var html = gui.generateGrayList(listItems);
            
        $('#appCenterFrame').html('<div id="applicationListContainer">'+html+'</div>');
        
        $('#applicationListContainer li .addApplication').bind('click',function(){
            appCenter.addAppToUserDashboard($(this).attr('data-app-id'),function(){
                gui.alert('the app has been added reload appCenter + dashboard!');
            });
        });
        $('#applicationListContainer li .removeApplication').bind('click',function(){
            appCenter.removeAppFromUserDashboard($(this).attr('data-app-id'),function(){
                gui.alert('the app has been removed reload appCenter + dashboard!');
            });
        });
    };
    
    this.loadApplication = function(app_id){
        var appData = this.getAppData(app_id);
        var path = './upload/'+folders.getPath(appData['folder_id'])+'/main/'+appData['entry_point'];
        
        console.log(path);
        
        this.applicationVar = new application(camelize(appData['title']));
	this.applicationVar.create(appData['title'], 'appCenterApplication', {path:path},{width: 6, height:  7, top: 2, left: 3});
	this.showApplicationOverview();
        universe.applications.push(application);
    };
    
    this.show = function(){
        applications.show('App Center');
    };
};
