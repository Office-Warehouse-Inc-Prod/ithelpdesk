<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
<script src="../js/jquery-3.5.1.js"></script>
<script src="../js/jquery.timeago.js"></script>


</head>
<body>



<time class='timeago' datetime='2021-03-02 08:43:00'></time>
	
</body>
</html>

<script type="text/javascript">

    var today = new Date();



	$(document).ready(function() {
  $("time.timeago").timeago();
});
</script>