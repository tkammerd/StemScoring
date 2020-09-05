<html>
  <head>
    <title>Insert/Update a Dexterity Score</title>
  </head>
  <body>
    <?php
      require "utilities.php";
      $team_id = $_POST["team_id"];
      $trial_num = $_POST["trial_num"];
      $bolt1_points = $_POST["bolt1_points"];
      $bolt2_points = $_POST["bolt2_points"];
      $bolt3_points = $_POST["bolt3_points"];
      $trial_time = $_POST["trial_time"];
      open_database($link_id);
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
      $selectHTML = create_team_selection($link_id, $team_id, "team_id", false);
    ?>
    <iframe src="display_team_info.php?team_id=<?= $team_id ?>" width="75%" height="100%"style="float: right"></iframe>
    <form action="menu.php" method="post">
      Team: <?= $selectHTML ?>
    </form>
    <?php print create_updates_menu($team_id); ?>
  </body>
</html>