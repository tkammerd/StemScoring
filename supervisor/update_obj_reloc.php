<html>
  <head>
    <title>Insert/Update an Object Relocation Score</title>
  </head>
  <body>
    <?php
      require "utilities.php";
      $team_id = $_POST["team_id"];
      $trial_num = $_POST["trial_num"];
      $index_cards_status = $_POST["index_cards_status"];
      $masking_tape_status = $_POST["masking_tape_status"];
      $notebook_status = $_POST["notebook_status"];
      $half_liter_bottle_status = $_POST["half_liter_bottle_status"];
      $hacky_sack_status = $_POST["hacky_sack_status"];
      $ruler_status = $_POST["ruler_status"];
      $pencil_status = $_POST["pencil_status"];
      $cd_status = $_POST["cd_status"];
      $unident1_status = $_POST["unident1_status"];
      $unident2_status = $_POST["unident2_status"];
      $task_time = $_POST["task_time"];
      open_database($link_id);
      $result = mysqli_query($link_id, "SELECT * FROM obj_reloc WHERE teamID='$team_id' " . 
        "AND trialNum='$trial_num'");
      if (mysqli_num_rows($result) == 0)
      {
        $query = "INSERT INTO obj_reloc " . 
          "(teamID, trialNum, indexCardsStatus, maskingTapeStatus, notebookStatus, " .
          "halfLiterBottleStatus, hackySackStatus, rulerStatus, pencilStatus, cdStatus, " .
          "unident1Status, unident2Status, taskTimeMS) " .
          "VALUES ('$team_id', '$trial_num', '$index_cards_status', " .
          "'$masking_tape_status', '$notebook_status', '$half_liter_bottle_status', " .
          "'$hacky_sack_status', '$ruler_status', '$pencil_status', '$cd_status', " .
          "'$unident1_status', '$unident2_status', '$task_time' * 1000)";
        if (!($query_status = mysqli_query( $link_id, $query)))
          die("Insert failed: $query\n");
      }
      else
      {
        $query = "UPDATE obj_reloc " . 
          "SET indexCardsStatus='$index_cards_status', maskingTapeStatus='$masking_tape_status', " .
          "notebookStatus='$notebook_status', halfLiterBottleStatus='$half_liter_bottle_status', " .
          "hackySackStatus='$hacky_sack_status', rulerStatus='$ruler_status', " .
          "pencilStatus='$pencil_status', cdStatus='$cd_status', unident1Status='$unident1_status', " .
          "unident2Status='$unident2_status', taskTimeMS='$task_time' * 1000 " .
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