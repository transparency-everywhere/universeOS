
var telescope = new function(){
    this.init = function(){
        var grid = {width: 5, height:  4, top: 6, left: 3, hidden: false};
        if(proofLogin())
            grid = {width: 8, height:  8, top: 1, left: 2, hidden: false};
        this.applicationVar = new application('telescope');
        this.applicationVar.create('Telescope', 'html', "<div id='telescopeFrame'></div>", grid);
        
        
	this.tabs = new tabs('#telescopeFrame');
        this.tabs.init();
	this.tabs.addTab('Home', '','this is a tab inside the telescope');
    };
    this.initHandlers = function(){
        $('#telescope .leftNav .categoryTitle').bind('click', function(){
           $(this).nextUntil('.categoryTitle').slideToggle();
        });
    };
    this.loadResults = function(query){
        var results = {};
        //universe
        
        
        //youtube
        results.youtube = handlers.youtube.query(query, 0, 50);
        
        //wiki
        results.wikipedia = handlers.wikipedia.query(query, 0, 50);
        
        return results;
    };
    this.showResultLength = function(length){
        if(length >= 50)
            return '&nbsp;<span class="label label-info">'+50+'+</span>';
        else{
            return '&nbsp;<span class="label label-info">'+length+'</span>';
        }
    };
    
    this.query = function(query){
        
        var results = this.loadResults(query);
        var html;
        
        html = '<header>';
            html += '<ul id="filterList">';
                html += '<li><span class="icon icon-link"></span></li>';
                html += '<li><span class="icon icon-image"></span></li>';
                html += '<li><span class="icon icon-youtube"></span></li>';
                html += '<li><span class="icon icon-file"></span></li>';
                html += '<li><span class="icon icon-user"></span></li>';
            html += '</ul>';
            html += '<input type="text" id="telescopeInput" placeholder="search" value="'+query+'">';
            html += '<span class="icon icon-search"></span>';
        html += '</header>';
        html += "<div class='leftNav dark' style='top: 65px; background: #37474f;border-top: 1px solid #dcdcdc;'>";
        html +=    '<ul>';
        html +=       '<li class="categoryTitle"><span class="icon blue-gear"></span>universeOS</li>';
        html +=       '<li><span class="icon blue-folder"></span>Folders</li>';
        html +=       '<li><span class="icon blue-archive"></span>Collections</li>';
        html +=       '<li><span class="icon blue-file"></span>Files</li>';
        html +=       '<li>&nbsp;</li>';
        html +=       '<li><span class="icon blue-eye"></span>Public</li>';
        html +=       '<li><span class="icon blue-eye"></span>Hidden</li>';
        html +=       '<li class="categoryTitle"><span class="icon blue-eye"></span>Web</li>';
        html +=       '<li><span class="icon blue-eye"></span>Youtube'+this.showResultLength(results.youtube.length)+'</li>';
        html +=       '<li><span class="icon blue-eye"></span>Wikipedia'+this.showResultLength(results.wikipedia.length)+'</li>';
        html +=       '<li class="categoryTitle"></li>';//empty title row at the end of navigation, otherwise the toggle function doesnt work                                                                                   
        html +=   '</ul>';
        html += '</div>';
        html += "<div class='frameRight' style='top:65px;border-top: 1px solid #dcdcdc;' id='settingsFrame'>";
        html += '</div>';
        this.tabs.addTab(query, '', html);
        this.initHandlers();
    };
        
};