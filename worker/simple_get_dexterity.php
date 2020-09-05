<html>
  <head>
    <title>Get Dexterity Scores</title>
    <link rel="stylesheet" type="text/css" href="get_form_style.css" />
  </head>
  <body>
    <?php
      require "utilities.php";
      $team_id = $_GET["team_id"];
      open_database($link_id);
      if ($team_id && $team_id != "NEW")
      {
        $trial_num = $_GET["trial_num"];
        $selectHTML = create_team_selection($link_id, $team_id, "team_id", true, false);
        $bolt1_points = $bolt2_points = $bolt3_points = 0;
        $trial_time = 0;
      }
      else
      {
        $trial_num = "1";
        $selectHTML = create_team_selection($link_id, "", "team_id", true, false);        
      }
      $result = mysqli_query($link_id, "SELECT * FROM dexterity WHERE teamID='$team_id' " . 
        "AND trialNum='$trial_num'");
      if ($cur_row = mysqli_fetch_array($result))
      {
        $bolt1_points = $cur_row["bolt1Points"];
        $bolt2_points = $cur_row["bolt2Points"];
        $bolt3_points = $cur_row["bolt3Points"];
        $trial_time = $cur_row["trialTimeMS"] / 1000;
      }
    ?>
    <h3>Update Dexterity Scores</h3>
    <form action="simple_get_dexterity.php" method="get">
      Team: <?= $selectHTML ?><br />
      Trial Number: 
        <select name="trial_num" onchange="this.form.submit()">
          <option <?= ($trial_num == 1)?'selected="selected"':'' ?>>1</option>
          <option <?= ($trial_num == 2)?'selected="selected"':'' ?>>2</option>
        </select>
    </form>
    <form action="simple_update_dexterity.php" method="get">
      <input type="hidden" name="team_id" value="<?= $team_id ?>" />
      <input type="hidden" name="trial_num" value="<?= $trial_num ?>" />
      <table>
        <tr>
          <td>Bolt 1 Points:</td>
          <td><input class="shortblank" type="text" name="bolt1_points" value="<?= $bolt1_points ?>" /></td>
        </tr>
        <tr>
          <td>Bolt 2 Points:</td>
          <td><input class="shortblank" type="text" name="bolt2_points" value="<?= $bolt2_points ?>" /></td>
        </tr>
        <tr>
          <td>Bolt 3 Points:</td>
          <td><input class="shortblank" type="text" name="bolt3_points" value="<?= $bolt3_points ?>" /></td>
        </tr>
        <tr>
          <td>Trial Time:</td>
          <td><input class="shortblank" type="text" name="trial_time" value="<?= $trial_time ?>" /></td>
        </tr>
        <tr>
          <td><input type="submit" value="Accept Dexterity Scores" /></td>
          <td><input type="reset" value="Reset Dexterity Scores" />
        </tr>
    </table>
  </form>
  </body>
</html>