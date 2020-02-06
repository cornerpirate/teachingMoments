<h3>Welcome to Cornerpirate's Introduction to SQL Injection</h3>

<script>
var xhttp = new XMLHttpRequest();
xhttp.onreadystatechange = function() {
  if (this.readyState == 4 && this.status == 200) {
    alert(this.responseText);
  }
};

function resetdb() {
    xhttp.open("GET", "resetdb.php", true);
    xhttp.send();
}
</script>

<p>Reset the database: <button onclick="resetdb();">RESET</button>
</br>
</br>

<table>
	<tr>
		<td>Task</td>
		<td>Level</td>
		<td>Link</td>
	</tr>
    <tr>
		<td>Error Based Injecting into String Field</td>
		<td>Easy</td>
		<td><a href="/error_injecting_into_string.php">Error Based String</a></td>
	</tr>
	<tr>
		<td>Error Based Injecting into Numeric Field</td>
		<td>Easy</td>
		<td><a href="/error_injecting_into_numeric.php">Error Based Numeric</a></td>
	</td>
	<tr>
		<td>Authentication Bypass</td>
		<td>Easy</td>
		<td><a href="/auth_bypass.php">Auth Bypass</a></td>
	</tr>

</table>
