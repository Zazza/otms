<script type="text/javascript">
$("{{ obj }}").droppable({
    tolerance: "touch",
    accept: ".fm_pre",
    drop: function(event, ui) {
        $(ui.helper).hide();
        
        var fname = ui.draggable.text();
        var ext = fname.substr(fname.lastIndexOf(".")+1, fname.length-fname.lastIndexOf(".")-1);
        ext = ext.toLowerCase();
        
        if ( (ext == "gif") || (ext == "png") || (ext == "jpg") || (ext == "jpeg") ) {
            $("#jHtmlArea").htmlarea('pasteHTML', '<a href="{{ path }}' + ui.draggable.text() + '"><img src="{{ dir }}' + ui.draggable.text() + '" alt="image" border="0" /></a>');
        } else {
            $("#jHtmlArea").htmlarea('pasteHTML', '<a href="{{ path }}' + ui.draggable.text() + '">' + ui.draggable.text() + '</a>');
        }
    }
});
</script>