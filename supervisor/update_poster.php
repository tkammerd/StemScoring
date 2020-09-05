<html>
  <head>
    <title>Insert/Update a Poster Score</title>
  </head>
  <body>
    <?php
      require "utilities.php";
      $team_id = $_POST["team_id"];
      $judge_num = $_POST["judge_num"];
      $abstract = $_POST["abstract"];
      $design_and_features = $_POST["design_and_features"];
      $results_data_and_analysis = $_POST["results_data_and_analysis"];
      $layout = $_POST["layout"];
      open_database($link_id);
      $result = mysqli_query($link_id, "SELECT * FROM poster WHERE teamID='$team_id' " . 
        "AND judgeNum='$judge_num'");
      if (mysqli_num_rows($result) == 0)
      {
        $query = "INSERT INTO poster " . 
          "(teamID, judgeNum, abstract, designAndFeatures, resultsDataAndAnalysis, layout) " .
          "VALUES ('$team_id', '$judge_num', '$abstract', '$design_and_features', " .
            "'$results_data_and_analysis', '$layout')";
        if (!($query_status = mysqli_query( $link_id, $query)))
          die("Insert failed: $query\n");
      }
      else
      {
        $query = "UPDATE poster " . 
          "SET abstract='$abstract', designAndFeatures='$design_and_features', " .
            "resultsDataAndAnalysis='$results_data_and_analysis', layout='$layout' " .
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