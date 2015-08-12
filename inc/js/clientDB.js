
var clientDB = new function(){
    this.databases = {};
    this.createDB = function(dbName){
        this.databases[dbName] = TAFFY();
    };
    this.insert = function(dbName, values){
        
        if(typeof this.databases[dbName] === 'undefined')
            clientDB.createDB(dbName);
        if($.isArray(values)){
            //each array insert value
            values.forEach(function(value){
                this.databases[dbName].insert(values);
            });
        }else{
            this.databases[dbName].insert(values);
        }
    };
    this.update = function(dbName, values){
        
    };
    this.savePipe = function(dbName, object){
        console.log(typeof object[0]);
        if(typeof object[0] === 'string')
            this.insert(dbName, object);
        else{
            $.each(object, function(index, singleValue){
                clientDB.insert(dbName, singleValue);
            });
        }
        return object;
    };
    this.select = function(dbName, object){
        if(typeof this.databases[dbName] === 'undefined')
            return null;
        
        
        return this.databases[dbName](object).last();
    };
};

