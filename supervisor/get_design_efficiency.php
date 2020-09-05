<html>
  <head>
    <title>Get Design/Efficiency Score</title>
    <link rel="stylesheet" type="text/css" href="get_form_style.css" />
  </head>
  <body>
    <?php
      require "utilities.php";
      $team_id = $_POST["team_id"];
      open_database($link_id);
      $selectHTML = create_team_selection($link_id, $team_id, "team_id", false);
      $design_mass_KG = 0;
      $result = mysqli_query($link_id, "SELECT * FROM teams WHERE teamID='$team_id'");
      if ($cur_row = mysqli_fetch_array($result))
      {
        $design_mass_KG = $cur_row["designMassKG"];
      }
    ?>
    <iframe src="display_team_info.php?team_id=<?= $team_id ?>" width="75%" height="100%"style="float: right"></iframe>
    <form action="get_design_efficiency.php" method="post">
      Team: <?= $selectHTML ?><br />
    </form>
    <form action="update_design_efficiency.php" method="post">
      <input type="hidden" name="team_id" value="<?= $team_id ?>" />
      <table>
        <tr>
          <td>Design Mass (KG):</td>
          <td><input class="shortblank" type="text" name="design_mass_KG" value="<?= $design_mass_KG ?>" /></td>
        </tr>
        <tr>
          <td><input type="submit" value="Accept Design/ Efficiency Scores" /></td>
          <td><input type="reset" value="Reset Design/ Efficiency Scores" />
        </tr>
      </table>
    </form>
  </body>
</html>