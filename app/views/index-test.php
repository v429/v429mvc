<html>
<head>
<title>{{$user['name']}}</title>
</head>
<body>
	<h1>{{$user['content']}}</h1>
	
	<div class="haha" style="width:50%; height:200px; border: 1px solid red">
		<p>user name is : <input type="text" value="{{$user['name']}}" / ></p>
		<p>user sex is :<?php if($user['sex'] == 1) { ?> male <?php }else { ?> female <?php } ?></p>
	</div>
</body>
</html>