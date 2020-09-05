<html>
  <head>
  </head>
  <body>
    <?php
    $cred_file = fopen("credentials.txt", "r");
    if (!$cred_file)
    {
      print "DEBUG: Unable to open credentials<br/>\n";
    }
    print "DEBUG: UID = " . posix_geteuid() . "<br/>\n";
    print "DEBUG: GID = " . posix_getegid() . "<br/>\n";
    print "DEBUG: Current User = <br\>\n>";
    print "<pre>\n";
    var_dump (posix_getpwuid(posix_geteuid()));
    print "</pre>\n";
    $user = trim(fgets($cred_file));
    $password = trim(fgets($cred_file));
    print "DEBUG: user = $user, password = $password<br/>\n";
    ?>
  </body>
</html>