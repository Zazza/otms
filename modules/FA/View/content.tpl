<script type="text/javascript">
$(document).ready(function(){
	if ($("#arfiles").width()) {
		$("#arfiles").prepend('<div id="fa-uploader"></div>');
		createUploaderFA();
	}
})

function createUploaderFA() {
	var uploader = new qqfa.FileUploader({
		element: document.getElementById('fa-uploader'),
		action: '{{ registry.uri }}ajax/fa/',
		params: {
			action: 'save'
		},
        onComplete: function(id, fileName, responseJSON){
            $('#' + id + '').fadeOut('slow');

            addElementFA(parseInt($('#fa_lastIdRow').val()) + id + 1, fileName);
            
            $('#fa_empty').fadeOut('medium');
        }
	})
};

function addElementFA(id, fileName) {
    var file = "<input type='hidden' name='attaches[]' value='" + fileName + "' /><p><img border='0' src='{{ registry.uri }}img/paper-clip-small.png' alt='attach' style='position: relative; top: 4px; left: 1px' />" + fileName + "</p>";

    $("#attach_files").append(file);
};
</script>
