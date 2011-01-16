(function() {
	var
	submitOkay = true,
	createPerma = function(val) {
		var remove = /(\'|\"|\.|,|~|!|\?|&lt;|&gt;|@|#|\$|%|\^|&amp;|\*|\(|\)|\+|=|\/|\\|\||\{|\}|\[|\]|-|--)/ig;
		val = val.replace(remove, '');
		val = val.replace(/\s\s/g, ' ').replace(/\s/g, '-').toLowerCase();
		return val;
	},
	titleKeyDown = function(e) {
		$('#perma').val(createPerma($('#title').val()));
	},
	formSubmit = function(e) {
		if (!submitOkay) {
			e.preventDefault();
		}
	},
	titleBlur = function(e) {
		titleKeyUp();
		perma = $('#perma').val();
		$.ajax({
			url:'/api/?type=json&method=content.getContent&perma=' + perma,
			dataType:'json',
			success:checkPerma
		});
	},
	checkPerma = function(data) {
		if (data.body.count > 0) {
			submitOkay = false;
			$('#perma').addClass('error');
		} else {
			submitOkay = true;
			$('#perma').removeClass('error');
		}
	},
	uploadFile = function(e) {
		var
		control = $(this).attr('id'),
		uploadId = (new Date()).getTime() + '_upload',
		$parent = $(this).parent();
		$('<iframe id="' + uploadId + '" style="display:none;"></iframe>').appendTo('body');
		$uploadForm = $('<form action="/admin/upload/' + control + '/" method="post" target="' + uploadId + '" id="form_' + uploadId + '" enctype="multipart/form-data"><input type="hidden" name="uploadId" value="' + uploadId + '" /></form>');
		$parent.append('<span class="uploader" id="loader_' + uploadId + '">Uploading...</span>');
		$(this).appendTo($uploadForm);
		$uploadForm.submit();
	},
	uploadComplete = function(data) {
		$loader = $('#loader_' + data.uploadId);
		$hidden = $('#' + data.control + '_file');
		switch (data.status) {
			case 'FAIL':
				$loader.replaceWith('<span class="error">Error uploading file</span>');
				break;
			case 'OK':
				$loader.replaceWith('<a href="' + data.file + '" target="_blank">' + data.file + '</a>');
				$hidden.val(data.file);
				break;
		}
	},
	init = function() {
		$('#title').keydown(titleKeyDown).blur(titleBlur);
		$('[type="file"]').change(uploadFile);
		$('#body form').submit(formSubmit);
		$('#body .date').datepicker();
		window.uploadComplete = uploadComplete;
	};
	$(init);
})();