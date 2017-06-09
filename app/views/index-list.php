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

.table {
	padding:2px;
}

</style>
</head>
<body>
	<div class="main_content">
		<table class="table" border="1">
			<tr>
				<td>name</td>
				<td>content</td>
				<td>sex</td>
				<td>birthday</td>
			</tr>
			[foreach]($list as $key => $value)
			<tr>
				<td>{{$value['name']}}</td>
				<td>{{$value['content']}}</td>
				<td>
				[if]($value['sex'] == 1) 
					male
				[else]
					female
				[endif]
				</td>
				<td>{{$value['birthday']}}</td>
			</tr>
			[endforeach]
		</table>
	</div>
</body>
</html> 