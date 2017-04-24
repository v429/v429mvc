<html>
<head>
<title><?php echo $user['name']; ?></title>
</head>
<body>
	<p><?php echo $user['content']; ?></p>
	
	<div class="haha" style="width:100%; height:200px; border: 1px solid red">
		<p>user name is : <input type="text" value="<?php echo $user['name']; ?>" / ></p>
		<p>user sex is : <?php if ($user['sex'] == 1) echo 'male'; else echo 'female'; ?> </p>
	</div>
</body>
</html>