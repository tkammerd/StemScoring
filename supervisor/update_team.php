<html>
  <head>
    <title>Insert/Update a team record</title>
  </head>
  <body>
    <?php
      require "utilities.php";
      $team_id = $_POST["team_id"];
      $team_school = $_POST["team_school"];
      $team_name = $_POST["team_name"];
      $team_level = $_POST["team_level"];
      open_database($link_id);
      if ($team_school || $team_name || $team_level)
      {
        if ($team_id == 'NEW')
        {
          $query = "INSERT INTO teams (teamSchool, teamName, teamLevel) " .
            "VALUES ('$team_school', '$team_name', '$team_level')";
          if ($query_status = mysqli_query( $link_id, $query))
          {
            $team_id = mysqli_insert_id($link_id);
            for ($trial_num = 1; $trial_num <= 2; $trial_num++)
            {
              $query = "INSERT INTO dexterity (teamID, trialNum) VALUES ('$team_id', '$trial_num')";
              if (!($query_status = mysqli_query( $link_id, $query)))
                die("Insert failed: $query\n");
              $query = "INSERT INTO dist_acc (teamID, trialNum) VALUES ('$team_id', '$trial_num')";
              if (!($query_status = mysqli_query( $link_id, $query)))
                die("Insert failed: $query\n");
              $query = "INSERT INTO obj_reloc (teamID, trialNum) VALUES ('$team_id', '$trial_num')";
              if (!($query_status = mysqli_query( $link_id, $query)))
                die("Insert failed: $query\n");
            }
            for ($judge_num = 1; $judge_num <= 3; $judge_num++)
            {
              $query = "INSERT INTO poster (teamID, judgeNum) VALUES ('$team_id', '$judge_num')";
              if (!($query_status = mysqli_query( $link_id, $query)))
                die("Insert failed: $query\n");
              $query = "INSERT INTO presentation (teamID, judgeNum) VALUES ('$team_id', '$judge_num')";
              if (!($query_status = mysqli_query( $link_id, $query)))
                die("Insert failed: $query\n");
              $query = "INSERT INTO tech_paper (teamID, judgeNum) VALUES ('$team_id', '$judge_num')";
              if (!($query_status = mysqli_query( $link_id, $query)))
                die("Insert failed: $query\n");
            }
          }
        }
        else
        {
          $query = "UPDATE teams " . 
            "SET teamSchool='$team_school', teamName='$team_name', teamLevel='$team_level' " .
            "WHERE teamID='$team_id'";
          $rows_changed = mysqli_query( $link_id, $query);
        }
      }
      else
      {
        $result = mysqli_query($link_id, "SELECT * FROM teams ORDER BY teamSchool, teamLevel, teamName LIMIT 1");
        $cur_row = mysqli_fetch_array($result);
        $team_id = $cur_row["teamID"];
      }
      $selectHTML = create_team_selection($link_id, $team_id, "team_id", false);
    ?>
    <iframe src="display_team_info.php?team_id=<?= $team_id ?>" width="75%" height="100%"style="float: right"></iframe>
    <form action="menu.php" method="post">
      Desired Team: <?= $selectHTML ?>
    </form>
    <?php print create_updates_menu($team_id); ?>
  </body>
</html>