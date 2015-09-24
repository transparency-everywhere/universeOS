
var crawl = new function(){
    this.init = function(){
        var grid = {width: 5, height:  4, top: 6, left: 3, hidden: true};
        if(proofLogin())
            grid = {width: 8, height:  8, top: 2, left: 2, hidden: true};
        this.applicationVar = new application('crawler');
        this.applicationVar.create('Crawler', 'html', "<div id='crawlFrame'></div>", grid);
        
        
	this.tabs = new tabs('#crawlFrame');
        this.tabs.init();
        
        
        var html;
        
        html = this.generateHeader('');
        
        var results = {'folders':[], 'collections':[], 'files':[], 'youtube':[],'wikipedia':[]};
        
        html += '<div class="searchFrame">';
        
        html += this.generateNav(results);
        
        html += this.generateFrame(this.pushSettingsButtonToResult(results));
        
        html += '</div>';
        
        
	this.tabs.addTab('Home', '', html);
        telescope.initHandlers();
    };
    
    this.showStartCrawlForm = function(){
        
        //chose type of crawling
            //website
                //countertype
                    //num
                        //start
                        //stop
                        //additionValue
                    //date
                        //format
                        //start
                        //stop
                        //additionValue in seconds
                        
                        
        
        
        //chose output file
        //chose item file type
            //csv/linebreak/json
        
        
    };
    this.crawl = function(){
        
    };
    
    this.show = function(){
        applications.show('telescope');
    };
    
    
        
};