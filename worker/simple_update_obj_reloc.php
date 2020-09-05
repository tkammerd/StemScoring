<html>
  <head>
    <title>Insert/Update a Dexterity Score</title>
  </head>
  <body>
    <?php
      require "utilities.php";
      $team_id = $_GET["team_id"];
      $trial_num = $_GET["trial_num"];
      $index_cards_status = $_GET["index_cards_status"];
      $masking_tape_status = $_GET["masking_tape_status"];
      $notebook_status = $_GET["notebook_status"];
      $half_liter_bottle_status = $_GET["half_liter_bottle_status"];
      $hacky_sack_status = $_GET["hacky_sack_status"];
      $ruler_status = $_GET["ruler_status"];
      $pencil_status = $_GET["pencil_status"];
      $cd_status = $_GET["cd_status"];
      $unident1_status = $_GET["unident1_status"];
      $unident2_status = $_GET["unident2_status"];
      $task_time = $_GET["task_time"];
      open_database($link_id);
      if ($team_id && $team_id != "NEW")
      {
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
      }
      $team_id = "";
    ?>
    <script type="text/javascript">
      window.open("simple_get_obj_reloc.php?team_id=<?= $team_id ?>&trial_num=<?= $trial_num ?>", "_self");
    </script>
  </body>
</html>