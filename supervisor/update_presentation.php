<html>
  <head>
    <title>Insert/Update a Presentation Score</title>
  </head>
  <body>
    <?php
      require "utilities.php";
      $team_id = $_POST["team_id"];
      $judge_num = $_POST["judge_num"];
      $introduction = $_POST["introduction"];
      $stem_explanations = $_POST["stem_explanations"];
      $design_process = $_POST["design_process"];
      $oral_visual = $_POST["oral_visual"];
      open_database($link_id);
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
      $selectHTML = create_team_selection($link_id, $team_id, "team_id", false);
    ?>
    <iframe src="display_team_info.php?team_id=<?= $team_id ?>" width="75%" height="100%"style="float: right"></iframe>
    <form action="menu.php" method="post">
      Team: <?= $selectHTML ?>
    </form>
    <?php print create_updates_menu($team_id); ?>
  </body>
</html>