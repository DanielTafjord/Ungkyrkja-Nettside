# Ungkyrkja-Nettside

# TODO

John Ingve:
- add something for "min profil" for users
- implement grupper.php
- add easter egg

Daniel:
- fix upload.php
- fix bilder.php
- add error reporting on stuff

# Using error class

```PHP
error::report('File name', 'Error message', 'Error type', 'IP', 'date');
```
<p>Add this</p>
```PHP
include('error.php');
```
<p>Example:</p>
```PHP
error::report($_SERVER['PHP_SELF'], 'Could not connect to database', 'Fatal', $_SERVER['REMOTE_ADDR'], date('Y-m-d h:i:sa'));
```
