<html>
  <head>
    <title>Get Tech Paper Scores</title>
    <link rel="stylesheet" type="text/css" href="get_form_style.css" />
  </head>
  <body>
    <?php
      require "utilities.php";
      $team_id = $_GET["team_id"];
      open_database($link_id);
      if ($team_id && $team_id != "NEW")
      {
        $judge_num = $_GET["judge_num"];
        $selectHTML = create_team_selection($link_id, $team_id, "team_id", true, false);
        $discussion = $stem_concepts_analysis = $quality_thoroughness = $conventions = 0;
      }
      else
      {
        $judge_num = "1";
        $selectHTML = create_team_selection($link_id, "", "team_id", true, false);        
      }
      $result = mysqli_query($link_id, "SELECT * FROM tech_paper WHERE teamID='$team_id' " . 
        "AND judgeNum='$judge_num'");
      if ($cur_row = mysqli_fetch_array($result))
      {
        $discussion = $cur_row["discussion"];
        $stem_concepts_analysis = $cur_row["stemConceptsAnalysis"];
        $quality_thoroughness = $cur_row["qualityThoroughness"];
        $conventions = $cur_row["conventions"];
      }
    ?>
    <h3>Update Technical Paper Scores</h3>
    <form action="simple_get_tech_paper.php" method="get">
      Team: <?= $selectHTML ?><br />
      Judge Number: 
        <select name="judge_num" onchange="this.form.submit()">
          <option <?= ($judge_num == 1)?'selected="selected"':'' ?>>1</option>
          <option <?= ($judge_num == 2)?'selected="selected"':'' ?>>2</option>
          <option <?= ($judge_num == 3)?'selected="selected"':'' ?>>3</option>
        </select>
    </form>
    <form action="simple_update_tech_paper.php" method="get">
      <input type="hidden" name="team_id" value="<?= $team_id ?>" />
      <input type="hidden" name="judge_num" value="<?= $judge_num ?>" />
      <table>
        <tr>
          <td>Discussion:</td>
          <td><input class="shortblank" type="text" name="discussion" value="<?= $discussion ?>" /></td>
        </tr>
        <tr>
          <td>STEM Concepts &<br />Analysis:</td>
          <td><input class="shortblank" type="text" name="stem_concepts_analysis" value="<?= $stem_concepts_analysis ?>" /></td>
        </tr>
        <tr>
          <td>Quality &<br />Thoroughness:</td>
          <td><input class="shortblank" type="text" name="quality_thoroughness" value="<?= $quality_thoroughness ?>" /></td>
        </tr>
        <tr>
          <td>Conventions:</td>
          <td><input class="shortblank" type="text" name="conventions" value="<?= $conventions ?>" /></td>
        </tr>
        <tr>
          <td><input type="submit" value="Accept Technical Paper Scores" /></td>
          <td><input type="reset" value="Reset Technical Paper Scores" /></td>
        </tr>
      </table>
    </form>
  </body>
</html>