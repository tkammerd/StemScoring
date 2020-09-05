<html>
  <head>
    <title>Insert/Update a Tech Paper Score</title>
  </head>
  <body>
    <?php
      require "utilities.php";
      $team_id = $_POST["team_id"];
      $judge_num = $_POST["judge_num"];
      $discussion = $_POST["discussion"];
      $stem_concepts_analysis = $_POST["stem_concepts_analysis"];
      $quality_thoroughness = $_POST["quality_thoroughness"];
      $conventions = $_POST["conventions"];
      open_database($link_id);
      $result = mysqli_query($link_id, "SELECT * FROM tech_paper WHERE teamID='$team_id' " . 
        "AND judgeNum='$judge_num'");
      if (mysqli_num_rows($result) == 0)
      {
        $query = "INSERT INTO tech_paper " . 
          "(teamID, judgeNum, discussion, stemConceptsAnalysis, qualityThoroughness, conventions) " .
          "VALUES ('$team_id', '$judge_num', '$discussion', '$stem_concepts_analysis', " .
            "'$quality_thoroughness', '$conventions')";
        if (!($query_status = mysqli_query( $link_id, $query)))
          die("Insert failed: $query\n");
      }
      else
      {
        $query = "UPDATE tech_paper " . 
          "SET discussion='$discussion', stemConceptsAnalysis='$stem_concepts_analysis', " .
            "qualityThoroughness='$quality_thoroughness', conventions='$conventions' " .
          "WHERE teamID='$team_id' AND judgeNum='$judge_num'";
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