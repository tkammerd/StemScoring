<html>
  <head>
    <title>Get Presentation Scores</title>
    <link rel="stylesheet" type="text/css" href="get_form_style.css" />
  </head>
  <body>
    <?php
      require "utilities.php";
      $team_id = $_POST["team_id"];
      $judge_num = $_POST["judge_num"];
      open_database($link_id);
      $selectHTML = create_team_selection($link_id, $team_id, "team_id", false);
      $introduction = $stem_explanations = $design_process = $oral_visual = 0;
      $result = mysqli_query($link_id, "SELECT * FROM presentation WHERE teamID='$team_id' " . 
        "AND judgeNum='$judge_num'");
      if ($cur_row = mysqli_fetch_array($result))
      {
        $introduction = $cur_row["introduction"];
        $stem_explanations = $cur_row["stemExplanations"];
        $design_process = $cur_row["designProcess"];
        $oral_visual = $cur_row["oralVisual"];
      }
    ?>
    <iframe src="display_team_info.php?team_id=<?= $team_id ?>" width="75%" height="100%"style="float: right"></iframe>
    <form action="get_presentation.php" method="post">
      Team: <?= $selectHTML ?><br />
      Judge Number: 
        <select name="judge_num" onchange="this.form.submit()">
          <option <?= ($judge_num == 1)?'selected="selected"':'' ?>>1</option>
          <option <?= ($judge_num == 2)?'selected="selected"':'' ?>>2</option>
          <option <?= ($judge_num == 3)?'selected="selected"':'' ?>>3</option>
        </select>
    </form>
    <form action="update_presentation.php" method="post">
      <input type="hidden" name="team_id" value="<?= $team_id ?>" />
      <input type="hidden" name="judge_num" value="<?= $judge_num ?>" />
      <table>
        <tr>
          <td>Introduction:</td>
          <td><input class="shortblank" type="text" name="introduction" value="<?= $introduction ?>" /></td>
        </tr>
        <tr>
          <td>STEM Explanations:</td>
          <td><input class="shortblank" type="text" name="stem_explanations" value="<?= $stem_explanations ?>" /></td>
        </tr>
        <tr>
          <td>Design/Process:</td>
          <td><input class="shortblank" type="text"   name="design_process" value="<?= $design_process ?>" /></td>
        </tr>
        <tr>
          <td>Oral and Visual Overall:</td>
          <td><input class="shortblank" type="text" name="oral_visual" value="<?= $oral_visual ?>" /></td>
        </tr>
        <tr>
          <td><input type="submit" value="Accept Presentation Scores" /></td>
          <td><input type="reset" value="Reset Presentation Scores" /></td>
        </tr>
    </form>
  </body>
</html>