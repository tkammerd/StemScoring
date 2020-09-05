<html>
  <head>
    <title>Get Poster Scores</title>
    <link rel="stylesheet" type="text/css" href="get_form_style.css" />
  </head>
  <body>
    <?php
      require "utilities.php";
      $team_id = $_POST["team_id"];
      $judge_num = $_POST["judge_num"];
      open_database($link_id);
      $selectHTML = create_team_selection($link_id, $team_id, "team_id", false);
      $abstract = $design_and_features = $results_data_and_analysis = $layout = 0;
      $result = mysqli_query($link_id, "SELECT * FROM poster WHERE teamID='$team_id' " . 
        "AND judgeNum='$judge_num'");
      if ($cur_row = mysqli_fetch_array($result))
      {
        $abstract = $cur_row["abstract"];
        $design_and_features = $cur_row["designAndFeatures"];
        $results_data_and_analysis = $cur_row["resultsDataAndAnalysis"];
        $layout = $cur_row["layout"];
      }
    ?>
    <iframe src="display_team_info.php?team_id=<?= $team_id ?>" width="75%" height="100%"style="float: right"></iframe>
    <form action="get_poster.php" method="post">
      Team: <?= $selectHTML ?><br />
      Judge Number: 
        <select name="judge_num" onchange="this.form.submit()">
          <option <?= ($judge_num == 1)?'selected="selected"':'' ?>>1</option>
          <option <?= ($judge_num == 2)?'selected="selected"':'' ?>>2</option>
          <option <?= ($judge_num == 3)?'selected="selected"':'' ?>>3</option>
        </select>
    </form>
    <form action="update_poster.php" method="post">
      <input type="hidden" name="team_id" value="<?= $team_id ?>" />
      <input type="hidden" name="judge_num" value="<?= $judge_num ?>" />
      <table>
        <tr>
          <td>Abstract:</td>
          <td><input class="shortblank" type="text" name="abstract" value="<?= $abstract ?>" /></td>
        </tr>
        <tr>
          <td>Design & Features:</td>
          <td><input class="shortblank" type="text" name="design_and_features" value="<?= $design_and_features ?>" /></td>
        </tr>
        <tr>
          <td>Results Data & Analysis:</td>
          <td><input class="shortblank" type="text" name="results_data_and_analysis" value="<?= $results_data_and_analysis ?>" /></td>
        </tr>
        <tr>
          <td>Layout:</td>
          <td><input class="shortblank" type="text" name="layout" value="<?= $layout ?>" /></td>
        </tr>
        <tr>
          <td><input type="submit" value="Accept Poster Scores" /></td>
          <td><input type="reset" value="Reset Poster Scores" />
        </tr>
      </table>
    </form>
  </body>
</html>