<html>
  <head>
    <title>Insert/Update a Design/Efficiency Score</title>
  </head>
  <body>
    <?php
      require "utilities.php";
      $team_id = $_GET["team_id"];
      $design_mass_KG = $_GET["design_mass_KG"];
      open_database($link_id);
      if ($team_id && $team_id != "NEW")
      {
        $query = "UPDATE teams SET designMassKG='$design_mass_KG' " .
          "WHERE teamID='$team_id'";
        $rows_changed = mysqli_query( $link_id, $query);
      }
      $team_id = "";
    ?>
    <script type="text/javascript">
      window.open("simple_get_design_efficiency.php?team_id=<?= $team_id ?>&trial_num=<?= $trial_num ?>", "_self");
    </script>
  </body>
</html>