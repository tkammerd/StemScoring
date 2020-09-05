<html>
  <head>
    <title>Display Updates Menu</title>
  </head>
  <body>
    <?php
      require "utilities.php";
      $team_id = $_POST["team_id"];
      open_database($link_id);
      $result = mysqli_query( $link_id, "SELECT * from teams WHERE teamID='$team_id'");
      $cur_row = mysqli_fetch_array($result);
      $team_school = $cur_row['teamSchool'];
      $team_name = $cur_row['teamName'];
      $team_level = $cur_row['teamLevel'];
      $selectHTML = create_team_selection($link_id, $team_id, "team_id", false);
    ?>
    <iframe src="display_team_info.php?team_id=<?= $team_id ?>" width="75%" height="100%"style="float: right"></iframe>
    <form action="menu.php" method="post">
      Desired Team: <?= $selectHTML ?>
    </form>
    <?php print create_updates_menu($team_id); ?>
  </body>
</html>