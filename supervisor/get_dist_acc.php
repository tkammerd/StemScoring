<html>
  <head>
    <title>Get Distance/Accuracy Scores</title>
    <link rel="stylesheet" type="text/css" href="get_form_style.css" />
  </head>
  <body>
    <?php
      require "utilities.php";
      $team_id = $_POST["team_id"];
      $trial_num = $_POST["trial_num"];
      open_database($link_id);
      $selectHTML = create_team_selection($link_id, $team_id, "team_id", false);
      $zone1_bags = $zone2_bags = $zone3_bags = $zone4_bags = $zone5_bags = 0;
      $trial_time = 0;
      $result = mysqli_query($link_id, "SELECT * FROM dist_acc WHERE teamID='$team_id' " . 
        "AND trialNum='$trial_num'");
      if ($cur_row = mysqli_fetch_array($result))
      {
        $zone1_bags = $cur_row["zone1Bags"];
        $zone2_bags = $cur_row["zone2Bags"];
        $zone3_bags = $cur_row["zone3Bags"];
        $zone4_bags = $cur_row["zone4Bags"];
        $zone5_bags = $cur_row["zone5Bags"];
        $trial_time = $cur_row["trialTimeMS"] / 1000;
      }
    ?>
    <iframe src="display_team_info.php?team_id=<?= $team_id ?>" width="75%" height="100%"style="float: right"></iframe>
    <form action="get_dist_acc.php" method="post">
      Team: <?= $selectHTML ?><br />
      Trial Number: 
        <select name="trial_num" onchange="this.form.submit()">
          <option <?= ($trial_num == 1)?'selected="selected"':'' ?>>1</option>
          <option <?= ($trial_num == 2)?'selected="selected"':'' ?>>2</option>
        </select>
    </form>
    <form action="update_dist_acc.php" method="post">
      <input type="hidden" name="team_id" value="<?= $team_id ?>" />
      <input type="hidden" name="trial_num" value="<?= $trial_num ?>" />
      <table>
        <tr>
          <td>Zone 1 Bags:</td>
          <td><input class="shortblank" type="text" name="zone1_bags" value="<?= $zone1_bags ?>" /></td>
        </tr>
        <tr>
          <td>Zone 2 Bags:</td>
          <td><input class="shortblank" type="text" name="zone2_bags" value="<?= $zone2_bags ?>" /></td>
        </tr>
        <tr>
          <td>Zone 3 Bags:</td>
          <td><input class="shortblank" type="text" name="zone3_bags" value="<?= $zone3_bags ?>" /></td>
        </tr>
        <tr>
          <td>Zone 4 Bags:</td>
          <td><input class="shortblank" type="text" name="zone4_bags" value="<?= $zone4_bags ?>" /></td>
        </tr>
        <tr>
          <td>Zone 5 Bags:</td>
          <td><input class="shortblank" type="text" name="zone5_bags" value="<?= $zone5_bags ?>" /></td>
        </tr>
        <tr>
          <td>Trial Time:</td>
          <td><input class="shortblank" type="text" name="trial_time" value="<?= $trial_time ?>" /></td>
        </tr>
        <tr>
          <td><input type="submit" value="Accept Distance/ Accuracy Scores" /></td>
          <td><input type="reset" value="Reset Distance/ Accuracy Scores" />
        </tr>
      </table>
    </form>
  </body>
</html>