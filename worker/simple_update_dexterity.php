<html>
  <head>
    <title>Insert/Update a Dexterity Score</title>
  </head>
  <body>
    <?php
      require "utilities.php";
      $team_id = $_GET["team_id"];
      $trial_num = $_GET["trial_num"];
      $bolt1_points = $_GET["bolt1_points"];
      $bolt2_points = $_GET["bolt2_points"];
      $bolt3_points = $_GET["bolt3_points"];
      $trial_time = $_GET["trial_time"];
      open_database($link_id);
      if ($team_id && $team_id != "NEW")
      {
        $result = mysqli_query($link_id, "SELECT * FROM dexterity WHERE teamID='$team_id' " . 
          "AND trialNum='$trial_num'");
        if (mysqli_num_rows($result) == 0)
        {
          $query = "INSERT INTO dexterity " . 
            "(teamID, trialNum, bolt1Points, bolt2Points, bolt3Points, " .
              "trialTimeMS) " .
            "VALUES ('$team_id', '$trial_num', '$bolt1_points', '$bolt2_points', "
              . "'$bolt3_points', '$trial_time' * 1000)";
          if (!($query_status = mysqli_query( $link_id, $query)))
            die("Insert failed: $query\n");
        }
        else
        {
          $query = "UPDATE dexterity " . 
            "SET bolt1Points='$bolt1_points', bolt2Points='$bolt2_points', " .
              "bolt3Points='$bolt3_points', trialTimeMS='$trial_time' * 1000 " .
            "WHERE teamID='$team_id' AND trialNum='$trial_num'";
          $rows_changed = mysqli_query( $link_id, $query);
        }
      }
      $team_id = "";
    ?>
    <script type="text/javascript">
      window.open("simple_get_dexterity.php?team_id=<?= $team_id ?>&trial_num=<?= $trial_num ?>", "_self");
    </script>
  </body>
</html>