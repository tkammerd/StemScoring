<html>
  <head>
    <title>Verify Empty Entire Database</title>
    <script type="text/javascript">
      var answer = prompt("Are you sure that you want to delete everything in the Prosthetic Arm Competition database? ");
      answer = answer.toUpperCase();
      window.open("empty_database.php?answer=" + answer, "_self");
    </script>
  </head>
  <body>
  </body>
</html>