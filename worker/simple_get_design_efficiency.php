<html>
  <head>
    <title>Get Design/Efficiency Score</title>
    <link rel="stylesheet" type="text/css" href="get_form_style.css" />
  </head>
  <body>
    <?php
      require "utilities.php";
      $team_id = $_GET["team_id"];
      open_database($link_id);
      if ($team_id && $team_id != "NEW")
      {
        $selectHTML = create_team_selection($link_id, $team_id, "team_id", true, false);
        $design_mass_KG = 0;
      }
      else
      {
        $selectHTML = create_team_selection($link_id, "", "team_id", true, false);        
      }
      $result = mysqli_query($link_id, "SELECT * FROM teams WHERE teamID='$team_id'");
      if ($cur_row = mysqli_fetch_array($result))
      {
        $design_mass_KG = $cur_row["designMassKG"];
      }
    ?>
    <h3>Update Design/Efficiency Scores</h3>
    <form action="simple_get_design_efficiency.php" method="get">
      Team: <?= $selectHTML ?><br />
    </form>
    <form action="simple_update_design_efficiency.php" method="get">
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