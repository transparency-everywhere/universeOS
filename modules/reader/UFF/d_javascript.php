<?php
//this file is used to generate the javascript functions for each UFF
//its necessary because the dhtmlgoodes tab script(-.-)



$id = $_GET['fileId'];
?>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script>
        


        function setCaretPosition(ctrl, pos)
        {

                if(ctrl.setSelectionRange)
                {
                        ctrl.focus();
                        ctrl.setSelectionRange(pos,pos);
                }
                else if (ctrl.createTextRange) {
                        var range = ctrl.createTextRange();
                        range.collapse(true);
                        range.moveEnd('character', pos);
                        range.moveStart('character', pos);
                        range.select();
                }
        }

        
        $(parent.document).ready(function(){
                //load
                $.get('../../../doit.php?action=loadUff&id=<?=$id;?>', function(uffContent) {
                    parent.initUffReader('<?=$id;?>', uffContent, "false");
                });
                
                
        });
        
    </script>