<html>
<head>
<title>add user</title>
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
	<form action="#" method="post">
		<table class="table" border="1">
			 <tr>
			 	<td>user name: </td>
			 	<td><input type="text" name="name" ></td>
			 </tr>
			 <tr>
			 	<td>content: </td>
			 	<td><input type="text" name="content" ></td>
			 </tr>
			 <tr>
			 	<td>sex: </td>
			 	<td>
			 		<select name="sex">
			 			<option value="1">male</option>
			 			<option value="2">female</option>
			 		</select>
			 	</td>
			 </tr>
			 <tr>
			 	<td>birthday: </td>
			 	<td><input type="text" name="birthday" ></td>
			 </tr>
			 <tr><td colspan="2"><input type="submit" value="add" name="sub-add-user" /></td></tr>
		</table>
		</form>
	</div>
</body>
</html> 