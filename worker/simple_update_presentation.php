<html>
  <head>
    <title>Insert/Update a Presentation Score</title>
  </head>
  <body>
    <?php
      require "utilities.php";
      $team_id = $_GET["team_id"];
      $judge_num = $_GET["judge_num"];
      $introduction = $_GET["introduction"];
      $stem_explanations = $_GET["stem_explanations"];
      $design_process = $_GET["design_process"];
      $oral_visual = $_GET["oral_visual"];
      open_database($link_id);
      if ($team_id && $team_id != "NEW")
      {
        $result = mysqli_query($link_id, "SELECT * FROM presentation WHERE teamID='$team_id' " . 
          "AND judgeNum='$judge_num'");
        if (mysqli_num_rows($result) == 0)
        {
          $query = "INSERT INTO presentation " . 
            "(teamID, judgeNum, introduction, stemExplanations, designProcess, oralVisual) " .
            "VALUES ('$team_id', '$judge_num', '$introduction', '$stem_explanations', " .
              "'$design_process', '$oral_visual')";
          if (!($query_status = mysqli_query( $link_id, $query)))
            die("Insert failed: $query\n");
        }
        else
        {
          $query = "UPDATE presentation " . 
            "SET introduction='$introduction', stemExplanations='$stem_explanations', " .
              "designProcess='$design_process', oralVisual='$oral_visual' " .
            "WHERE teamID='$team_id' AND judgeNum='$judge_num'";
          $rows_changed = mysqli_query( $link_id, $query);
        }
      }
      $team_id = "";
    ?>
    <script type="text/javascript">
      window.open("simple_get_presentation.php?team_id=<?= $team_id ?>&trial_num=<?= $trial_num ?>", "_self");
    </script>
  </body>
</html>