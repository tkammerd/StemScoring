<html>
  <head>
    <title>Insert/Update a Design/Efficiency Score</title>
  </head>
  <body>
    <?php
      require "utilities.php";
      $team_id = $_POST["team_id"];
      $design_mass_KG = $_POST["design_mass_KG"];
      open_database($link_id);
      $query = "UPDATE teams SET designMassKG='$design_mass_KG' " .
        "WHERE teamID='$team_id'";
      $rows_changed = mysqli_query( $link_id, $query);
      $selectHTML = create_team_selection($link_id, $team_id, "team_id", false);
    ?>
    <iframe src="display_team_info.php?team_id=<?= $team_id ?>" width="75%" height="100%"style="float: right"></iframe>
    <form action="menu.php" method="post">
      Team: <?= $selectHTML ?>
    </form>
    <?php print create_updates_menu($team_id); ?>
  </body>
</html>