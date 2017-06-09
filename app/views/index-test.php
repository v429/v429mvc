<html>
<head>
<title>{{$user['name']}}</title>
<style>
.main_content {
	width:50%; 
	height:200px; 
	border: 1px solid red; 
	margin:auto;
	padding:5rem
}

</style>
</head>
<body>
	<div class="main_content">
		<p>user name is : <input type="text" value="{{$user['name']}}" / ></p>
		<p>user sex is :
			[if]($user['sex'] == 1)
				male
			[else]
				female
			[endif]	
		</p>
		[if]($user['name'])
			{{$user['name']}}
		[elseif]($user['content'])
			user sex 
		[endif]
	</div>
</body>
</html> 