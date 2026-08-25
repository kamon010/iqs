<html>
<head>
	<title>ThaiCreate.Com</title>
	<meta charset="utf-8">
</head>
<body>
	<script type="text/javascript" src="ckeditor.js"></script>
	<script type="text/javascript" src="../js/jquery.js" ></script>


		<!-- Sample 1 -->
		<textarea cols="80" id="txtDescription1" name="txtDescription1" rows="10"></textarea>
		<button id='send'>asdfasdf</button>

		<script>

			var editor = CKEDITOR.replace( 'txtDescription1');

			$('#send').click(function() {
				var value = editor.getData();
				
				alert(value);
			});

		</script>

</body>
</html>
