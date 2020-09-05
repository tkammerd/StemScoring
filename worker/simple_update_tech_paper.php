<html>
  <head>
    <title>Insert/Update a Tech Paper Score</title>
  </head>
  <body>
    <?php
      require "utilities.php";
      $team_id = $_GET["team_id"];
      $judge_num = $_GET["judge_num"];
      $discussion = $_GET["discussion"];
      $stem_concepts_analysis = $_GET["stem_concepts_analysis"];
      $quality_thoroughness = $_GET["quality_thoroughness"];
      $conventions = $_GET["conventions"];
      open_database($link_id);
      if ($team_id && $team_id != "NEW")
      {
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
      }
      $team_id = "";
    ?>
    <script type="text/javascript">
      window.open("simple_get_tech_paper.php?team_id=<?= $team_id ?>&trial_num=<?= $trial_num ?>", "_self");
    </script>
  </body>
</html>