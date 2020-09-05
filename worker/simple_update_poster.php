<html>
  <head>
    <title>Insert/Update a Poster Score</title>
  </head>
  <body>
    <?php
      require "utilities.php";
      $team_id = $_GET["team_id"];
      $judge_num = $_GET["judge_num"];
      $abstract = $_GET["abstract"];
      $design_and_features = $_GET["design_and_features"];
      $results_data_and_analysis = $_GET["results_data_and_analysis"];
      $layout = $_GET["layout"];
      open_database($link_id);
      if ($team_id && $team_id != "NEW")
      {
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
      }
      $team_id = "";
    ?>
    <script type="text/javascript">
      window.open("simple_get_poster.php?team_id=<?= $team_id ?>&trial_num=<?= $trial_num ?>", "_self");
    </script>
  </body>
</html>