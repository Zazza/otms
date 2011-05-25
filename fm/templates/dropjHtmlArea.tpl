<script type="text/javascript">
$("#text_area").droppable({
    tolerance: "touch",
    accept: ".fm_pre",
    drop: function(event, ui) {
        $(ui.helper).hide();
        
        var fname = ui.draggable.text();
        var ext = fname.substr(fname.lastIndexOf(".")+1, fname.length-fname.lastIndexOf(".")-1);
        ext = ext.toLowerCase();
        
        if ( (ext == "gif") || (ext == "png") || (ext == "jpg") || (ext == "jpeg") ) {
            CKEDITOR.instances.jHtmlArea.setData(CKEDITOR.instances.jHtmlArea.getData() + '<a href="{{ path }}' + ui.draggable.text() + '"><img src="{{ dir }}' + ui.draggable.text() + '" alt="изображение" /></a>');
        } else {
            CKEDITOR.instances.jHtmlArea.setData(CKEDITOR.instances.jHtmlArea.getData() + '<a href="{{ dir }}' + ui.draggable.text() + '">' + ui.draggable.text() + '</a>');
        }
    }
});
</script>