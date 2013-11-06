<!--Scripts-->
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
	<!-- script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script -->
	<script type="text/javascript" src="js/bootstrap.js"></script>
	<script type="text/javascript" src="js/bootstrap-datepicker.js"></script>
	<script type="text/javascript" src="js/bootstrap-tooltip.js"></script>
	<script type="text/javascript" src="js/locales/bootstrap-datepicker.id.js"></script>
	<script type="text/javascript" src="js/locales/bootstrap-formhelpers-selectbox.js"></script>
	<!-- script type="text/javascript" src="js/jquery.shorten.js"></script >
	<script type="text/javascript" src="js/readmore.js"></script -->
	<script type="text/javascript" src="plugin/tinymce/tiny_mce.js"></script>
	<!-- START
		get datepicker 
		- datepicker.css
		- bootstrap-datepicker.js
		- bootstrap-datepicker.id.js
	-->
	<script>
		$('#tglSurat').datepicker({
		format: 'dd/mm/yyyy',
		language: 'id',
		});
	</script>
	<!-- END -->
	
	<!-- TOOLTIPS -->
	<script>
		$('[rel=tooltip]').tooltip() 
	</script>	
	<!-- END -->
	
	<!-- script language="javascript">
		$(document).ready(function() {
			$(".naskah").shorten();
		});
	</script>	
	
	<script>
		$('article').readmore({maxHeight: 80});
	</script -->	
	
	<!-- TinyMCE -->
	<script type="text/javascript">
		tinyMCE.init({
			// General options
			mode : "textareas",
			oninit : "setPlainText",
			theme : "advanced",
			skin : "o2k7",
			plugins : "htmlcharcount,wordcount,autolink,lists,pagebreak,style,table,save,paste,visualchars,autosave,autoresize",

			// Theme options
			theme_advanced_buttons1 : "cut,paste,undo,redo,|,bold,italic,underline,|,bullist,numlist,|,fontsizeselect",
			
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,

			// Example word content CSS (should be your site CSS) this one removes paragraph margins
			content_css : "css/custom.css",

			// Drop lists for link/image/media/template dialogs
			template_external_list_url : "lists/template_list.js",
			external_link_list_url : "lists/link_list.js",
			external_image_list_url : "lists/image_list.js",
			media_external_list_url : "lists/media_list.js",

			// Replace values for the template plugin
			template_replace_values : {
				username : "Some User",
				staffid : "991234"
			}
		});
	</script>
	<!-- /TinyMCE -->