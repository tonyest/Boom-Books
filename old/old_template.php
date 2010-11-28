<?PHP include '/include/functions.php');?>
<br/>
<form class="form-user" action="submit_names.asp" method="post">
	First name: <input type="text" name="firstname" ><br/>
	Last name: <input type="text" name="lastname"/>
	<input type="submit" value="Submit" />
</form>

<?PHP

$first=$_POST['firstname'];
$last=$_POST['lastname'];

//definte insertion query
$query = "INSERT INTO contacts VALUES ('',$first,$last)";
myquery($query);

?>
<?
//define reporting query
$query="SELECT * FROM contacts";
$result=myquery($query);

$num=mysql_numrows($result);

echo "<b><center>Database Output</center></b><br><br>";

$i=0;
while ($i < $num) {

$first=mysql_result($result,$i,"first");
$last=mysql_result($result,$i,"last");

echo "<b>$first $last</b><br><hr><br>";

$i++;
}

?>