<html>
  <head>
    <title>Choose Scoring Option</title>
  </head>
  <body>
    <?php
      require "utilities.php";
      open_database($link_id);
      /*
      $result = mysqli_query($link_id, "SELECT * FROM teams LIMIT 1");
      if ($cur_row = mysqli_fetch_array($result))
        $team_id = $cur_row["teamID"];
      */
      $team_id = "";
    ?>
    <h1>Prosthetic Arm Competition Scoring System</h1>
    <p>
      <a href="supervisor/start.php">Supervisory System</a>
    </p>
    Volunteer Entry Forms:
    <ul>
      <li><a href="worker/simple_get_dist_acc.php?team_id=<?= $team_id ?>&trial_num=1">Distance Accuracy Scores</a></li>
    </ul>    
    <ul>
      <li><a href="worker/simple_get_dexterity.php?team_id=<?= $team_id ?>&trial_num=1">Dexterity Scores</a></li>
    </ul>    
    <ul>
      <li><a href="worker/simple_get_obj_reloc.php?team_id=<?= $team_id ?>&trial_num=1">Object Relocation Scores</a></li>
    </ul>    
    <ul>
      <li><a href="worker/simple_get_design_efficiency.php?team_id=<?= $team_id ?>&trial_num=1">Design Efficiency Scores</a></li>
    </ul>    
    <ul>
      <li><a href="worker/simple_get_tech_paper.php?team_id=<?= $team_id ?>&trial_num=1">Technical Paper Scores</a></li>
    </ul>    
    <ul>
      <li><a href="worker/simple_get_poster.php?team_id=<?= $team_id ?>&trial_num=1">Poster/Display Scores</a></li>
    </ul>    
    <ul>
      <li><a href="worker/simple_get_presentation.php?team_id=<?= $team_id ?>&trial_num=1">Presentation Scores</a></li>
    </ul>    
  </body>
</html>