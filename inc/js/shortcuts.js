

var shortcuts = new function(){
              	
    this.getData = function(folder_id){
        
    };
    this.create = function(parentType, parentId, type, typeId, title, callback){
        api.query('api/shortcuts/create/', {parent_type:parentType, parent_id: parentId, type: type, type_id: typeId, title: title},callback);
    };
    this.pdateShortcut = function(){
        
    };
    this.showChooseShortcutTypeForm = function(parentType, parentId){
      
        
        var formModal = new gui.modal();
        var title;
        switch(parentType){
            case 'folder':
                var folderData = folders.getData(parentId);
                title = folderData.name;
                break;
            case 'element':
                var elementData = elements.getData(parentId);
                title = elementData.title;
                break;
        }
        
        var fieldArray = [];
        var options = [];
        options['headline'] = '';
        options['buttonTitle'] = 'Continue';
        options['noButtons'] = true;
        
        
        
        var captions = ['element', 'folder'];
        var type_ids = ['element', 'folder'];
        
        
        
        var field0 = [];
        field0['caption'] = 'Type';
        field0['inputName'] = 'type';
        field0['type'] = 'dropdown';
        field0['values'] = type_ids;
        field0['captions'] = captions;
        field0['required'] = true;
        fieldArray[0] = field0;
        
        var modalOptions = {};
        modalOptions['buttonTitle'] = 'Next';
        modalOptions['action'] = function(){
            shortcuts.showCreateShortcutForm($('#createShortcutFormContainer #type').val(), parentType, parentId);
        };
        formModal.init('Create Shortcut in '+parentType+' '+title, '<div id="createShortcutFormContainer"></div>', modalOptions);
        gui.createForm('#createShortcutFormContainer',fieldArray, options);  
    };
    this.showCreateShortcutForm = function(selectionType, parentType, parentId){
        
        
        
        var formModal = new gui.modal();
        var title;
        switch(parentType){
            case 'folder':
                var folderData = folders.getData(parentId);
                title = folderData.name;
                break;
            case 'element':
                var elementData = elements.getData(parentId);
                title = elementData.title;
                break;
        }
        
        
        var title = 'some Title';
        
        var fieldArray = [];
        var options = [];
        options['headline'] = '';
        options['buttonTitle'] = 'Save';
        options['noButtons'] = true;
        
        var field0 = [];
        field0['caption'] = 'Title';
        field0['inputName'] = 'title';
        field0['type'] = 'text';
        field0['required'] = true;
        field0['value'] = title;
        fieldArray[0] = field0;
        
        var field1 = [];
        field1['caption'] = 'Linked Item';
        field1['inputName'] = 'privacy';
        field1['type'] = selectionType;
        field1['folder'] = 1; //universe home folder
        fieldArray[1] = field1;
        
        
        var modalOptions = {};
        modalOptions['buttonTitle'] = 'Create Shortcut';
        
        modalOptions['action'] = function(){
            var callback = function(){
                gui.alert('The shortcut has been added');
                $('.blueModal').remove();
                alert('filesystem should be reloaded now');
                //filesystem.tabs.updateTabContent(1 , filesystem.generateFullFileBrowser(folderData['folder']));
            };
            shortcuts.create(parentType, parentId, selectionType, $('#createShortcutFormContainer .choosenTypeId').val(), $('#createShortcutFormContainer #title').val(),callback);
        };
        
        formModal.init('Create Shortcut in '+parentType+' '+title, '<div id="createShortcutFormContainer"></div>', modalOptions);
        gui.createForm('#createShortcutFormContainer',fieldArray, options);
        
    };
    
};
