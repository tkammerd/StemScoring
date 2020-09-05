<html>
  <head>
    <title>Add/Update a team</title>
    <link rel="stylesheet" type="text/css" href="get_form_style.css" />
  </head>
  <body>
    <?php
      require "utilities.php";
      $team_id = $_POST["team_id"];
      if (!($team_id > 0))
        $team_id = "NEW";
      open_database($link_id);
      $selectHTML = create_team_selection($link_id, $team_id, "team_id", true);
      $cur_school = "";
      $cur_name = "";
      $cur_level = "";
      if ($team_id > 0)
      {
        $result = mysqli_query($link_id, "SELECT * FROM teams WHERE teamID='$team_id'");
        if ($cur_row = mysqli_fetch_array($result))
        {
          $cur_school = $cur_row["teamSchool"];
          $cur_name = $cur_row["teamName"];
          $cur_level = $cur_row["teamLevel"];
        }
      }
	  ?>
    <iframe src="display_team_info.php?team_id=<?= $team_id ?>" width="75%" height="100%"style="float: right"></iframe>
    <form action="get_team_info.php" method="post">
      Desired Team: <?= $selectHTML ?>
    </form>
    <form action="update_team.php" method="post">
      <input type="hidden" name="team_id" value="<?= $team_id ?>" />
      <table>
        <tr>
          <td>School:</td><td colspan="2"><input type="text" name="team_school" value="<?= $cur_school ?>" /></td>
        </tr>
        <tr>
          <td>Team Name:</td><td colspan="2"><input type="text" name="team_name" value="<?= $cur_name ?>" /></td>
        </tr>
        <tr>
          <td>Level:</td>
          <td><input type="radio" name="team_level" value="high school" 
            <?php print ($cur_level=="high school")?'checked="checked"':""; ?> />  High School</td>
          <td><input type="radio" name="team_level" value="middle school" 
            <?php print ($cur_level=="middle school")?'checked="checked"':""; ?> />Middle School</td>
        </tr>
        <tr>
          <td colspan="2"><input type="submit" value="Accept Team Information" /></td>
          <td><input type="reset" value="Reset Team Information" /></td>
        </tr>
      </table>
    </form>
  </body>
</html>
