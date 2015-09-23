<?php
//this file is used to generate the javascript functions for each UFF
//its necessary because the dhtmlgoodes tab script(-.-)



$id = $_GET[fileId];
?>uffViewer_<?=$id;?>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script>
        //initialize
        $('.uffViewer_<?=$id;?>', parent.document).html( function(){
            $(this).ready(function(){
                //load
                $('.uffViewer_<?=$id;?>', parent.document).load('../../../doit.php?action=loadUff&id=<?=$id;?>');
            });
            
            $(this).keyup(function(){
                //post new value to doit
                var input = $('.uffViewer_<?=$id;?>', parent.document).html();
                $.post("../../../doit.php?action=writeUff", {
                       id:'<?=$id;?>',
                       input:input
                       });
            });
        });
    </script>