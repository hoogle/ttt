<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"> 
<html lang="en"> 
<head> 
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
    <meta http-equiv="Pragma" content="no-cache" /> 
    <title>testtttt</title> 
</head>

<body>
<pre>
<?php
$loc = "ja_JP.UTF-8";
setlocale(LC_ALL, $loc);
print_r(localeconv());
//printf("%f",-123.456);
echo strftime("%A, %d %B %Y") . "\n";
?>
</pre>
</body>
</html>
