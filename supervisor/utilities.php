<?php
  function open_database(&$link_id)
  {
    if (!($cred_file = fopen("../credentials.txt", "r")))
      $cred_file = fopen("credentials.txt", "r");
    $user = trim(fgets($cred_file));
    $password = trim(fgets($cred_file));
    if (!($link_id = mysqli_connect("localhost",  $user,  $password)))
      die("Unable to connect\n");
    $desired_db = "mesa_prosthetic_competition";
    if (!($db_select_status = (mysqli_query( $link_id, "USE " . $desired_db))))
      die("Unable to select $desired_db database\n");
  }
  
  function create_team_selection(&$link_id, $cur_team, $select_name, $include_NEW, $is_supervisor = true)
  {
      $result = mysqli_query( $link_id, "SELECT * FROM teams ORDER BY teamSchool, teamLevel, teamName");
      $selectHTML = '<select name="' . $select_name . '" onchange="this.form.submit()">';
      if ($include_NEW)
      {
        if ($is_supervisor)
          $selectHTML = $selectHTML . "\n" . '  <option value="NEW">NEW</option>' . "\n";
        else
          $selectHTML = $selectHTML . "\n" . '  <option value="NEW">Pick a Team</option>' . "\n";
      }
      $selectHTML = $selectHTML . '<option value="NEW"> </option>';
      while ($cur_row = mysqli_fetch_array($result))
      {
        $menu_text = $cur_row["teamSchool"] . '-' . $cur_row["teamName"]  . 
          '-' .  $cur_row["teamLevel"];
        $menu_value = $cur_row["teamID"];
        if ($cur_row["teamID"] == $cur_team)
          $menu_selected = 'selected="selected"';
        else
          $menu_selected = '';
        $selectHTML = $selectHTML . "\n" . '  <option value="' . $menu_value .
          '" ' . $menu_selected . '>' . $menu_text . '</option>';
      }
      $selectHTML = $selectHTML . "\n</select>";
      return $selectHTML;
  }
  
  function create_updates_menu($team_id)
  {
    $menu = <<<MENU
      <form action="get_team_info.php" method="post">
        <input type="hidden" name="team_id" value="$team_id" />
        <input type="submit" value="Team Information" />
      </form>
      <form action="get_dist_acc.php" method="post">
        <input type="hidden" name="team_id" value="$team_id" />
        <input type="hidden" name="trial_num" value="1" />
        <input type="submit" value="Distance/Accuracy Scores" />
      </form>
      <form action="get_dexterity.php" method="post">
        <input type="hidden" name="team_id" value="$team_id" />
        <input type="hidden" name="trial_num" value="1" />
        <input type="submit" value="Dexterity Scores" />
      </form>
      <form action="get_obj_reloc.php" method="post">
        <input type="hidden" name="team_id" value="$team_id" />
        <input type="hidden" name="trial_num" value="1" />
        <input type="submit" value="Object Relocation Scores" />
      </form>
      <form action="get_design_efficiency.php" method="post">
        <input type="hidden" name="team_id" value="$team_id" />
        <input type="submit" value="Design Efficiency Score" />
      </form>
      <form action="get_tech_paper.php" method="post">
        <input type="hidden" name="team_id" value="$team_id" />
        <input type="hidden" name="judge_num" value="1" />
        <input type="submit" value="Technical Paper Scores" />
      </form>
      <form action="get_poster.php" method="post">
        <input type="hidden" name="team_id" value="$team_id" />
        <input type="hidden" name="judge_num" value="1" />
        <input type="submit" value="Poster Scores" />
      </form>
      <form action="get_presentation.php" method="post">
        <input type="hidden" name="team_id" value="$team_id" />
        <input type="hidden" name="judge_num" value="1" />
        <input type="submit" value="Presentation Scores" />
      </form>
      <form action="reports.php" method="post">
        <input type="submit" value="Reports" />
      </form>
      <br /><br /><br />
      <form action="verify_empty_database.php" method="post">
        <input type="submit" style="background-color:red; color:yellow;" value="Empty Database" />
      </form>
MENU;
    return $menu;
  }
  
  function dist_acc_display($zone1_bags, $zone2_bags, $zone3_bags, $zone4_bags,
    $zone5_bags, $dist_acc_time_MS, &$best_score)
  {
    for ($trial_num = 1; $trial_num <= 2; $trial_num++)
    {
      if ($dist_acc_time_MS[$trial_num] != '')
      {
        $subtotal[$trial_num] = $zone1_bags[$trial_num] * 5 + 
          $zone2_bags[$trial_num] * 10 + $zone3_bags[$trial_num] * 15 + 
          $zone4_bags[$trial_num] * 20 + $zone5_bags[$trial_num] * 25;
        if ($dist_acc_time_MS[$trial_num] != 0)
          $score[$trial_num] = round($subtotal[$trial_num] / 
            ($dist_acc_time_MS[$trial_num] / 1000), 2);
        else
          $score[$trial_num] = 0;
        $rounded_dist_acc_time[$trial_num] = round($dist_acc_time_MS[$trial_num] / 1000, 2);
      }
    }
    if ($dist_acc_time_MS[1] == '' && $dist_acc_time_MS[2] == '')
      $best_score = '';
    else
      $best_score = max($score);
    $table_HTML = <<<TABLE_HTML
    <table>
      <tr>
        <th colspan="17">Distance Accuracy</th>
      </tr>
      <tr valign="bottom">
        <th colspan="8">Trial 1</th>
        <th colspan="8">Trial 2</th>
        <th rowspan="3">Best<br />Score</th>
      </tr>
      <tr valign="bottom">
        <th colspan="5"># of bean bags for each<br />Scoring Zone</th>
        <th rowspan="2">Subtotal</th>
        <th rowspan="2">Trial<br />Time<br />(00.00<br />seconds)</th>
        <th rowspan="2">Trial 1<br />Score</th>
        <th colspan="5"># of bean bags for each<br />Scoring Zone</th>
        <th rowspan="2">Subtotal</th>
        <th rowspan="2">Trial<br />Time<br />(00.00<br />seconds)</th>
        <th rowspan="2">Trial 2<br />Score</th>
      </tr>
      <tr valign="bottom">
        <th>1</th><th>2</th><th>3</th><th>4</th><th>5</th>
        <th>1</th><th>2</th><th>3</th><th>4</th><th>5</th>
      </tr>
      <tr>
        <td>$zone1_bags[1]</td><td>$zone2_bags[1]</td><td>$zone3_bags[1]</td>
        <td>$zone4_bags[1]</td><td>$zone5_bags[1]</td>
        <td>$subtotal[1]</td><td>$rounded_dist_acc_time[1]</td><td>$score[1]</td>
        <td>$zone1_bags[2]</td><td>$zone2_bags[2]</td><td>$zone3_bags[2]</td>
        <td>$zone4_bags[2]</td><td>$zone5_bags[2]</td>
        <td>$subtotal[2]</td><td>$rounded_dist_acc_time[2]</td><td>$score[2]</td>
        <td>$best_score</td>
      </tr>
    </table>
TABLE_HTML;
    return $table_HTML;
  }
  
  function dexterity_display($bolt1_points, $bolt2_points, $bolt3_points,  $dexterity_time_MS, &$best_point_time_ratio)
  {
    for ($trial_num = 1; $trial_num <= 2; $trial_num++)
    {
      if ($dexterity_time_MS[$trial_num] != '')
      {
        $total_points[$trial_num] = $bolt1_points[$trial_num] + 
          $bolt2_points[$trial_num] + $bolt3_points[$trial_num];
        if ($dexterity_time_MS[$trial_num] != 0)
        {
          $point_time_ratio[$trial_num] = round($total_points[$trial_num] / 
            ($dexterity_time_MS[$trial_num] / 1000), 2);
        }
        else
          $point_time_ratio[$trial_num] = 0;
        $rounded_dexterity_time[$trial_num] = round($dexterity_time_MS[$trial_num] / 1000, 2);
      }
    }
    if ($dexterity_time_MS[1] == '' && $dexterity_time_MS[2] == '')
      $best_point_time_ratio= '';
    else
      $best_point_time_ratio = max($point_time_ratio);
    $table_HTML = <<<TABLE_HTML
    <table>
      <tr>
        <th colspan="13">Dexterity</th>
      </tr>
      <tr valign="bottom">
        <th colspan="6">Trial 1</th>
        <th colspan="6">Trial 2</th>
        <th rowspan="2">Best<br />Point-Time<br />Ratio</th>
      </tr>
      <tr valign="bottom">
        <th>Bolt 1<br />points</th><th>Bolt 2<br />points</th><th>Bolt 3<br />points</th>
        <th>Total<br />points</th>
        <th>Trial<br />Time<br />seconds</th>
        <th>Point-Time<br />Ratio</th>
        <th>Bolt 1<br />points</th><th>Bolt 2<br />points</th><th>Bolt 3<br />points</th>
        <th>Total<br />points</th>
        <th>Trial<br />Time<br />seconds</th>
        <th>Point-Time<br />Ratio</th>
      </tr>
      <tr>
        <td>$bolt1_points[1]</td><td>$bolt2_points[1]</td><td>$bolt3_points[1]</td>
        <td>$total_points[1]</td><td>$rounded_dexterity_time[1]</td><td>$point_time_ratio[1]</td>
        <td>$bolt1_points[2]</td><td>$bolt2_points[2]</td><td>$bolt3_points[2]</td>
        <td>$total_points[2]</td><td>$rounded_dexterity_time[2]</td><td>$point_time_ratio[2]</td>
        <td>$best_point_time_ratio</td>
      </tr>
    </table>
TABLE_HTML;
    return $table_HTML;
  }

  function obj_reloc_display($index_cards_status, $masking_tape_status, $notebook_status, 
    $half_liter_bottle_status, $hacky_sack_status, $ruler_status, $pencil_status, $cd_status, 
    $unident1_status, $unident2_status, $obj_reloc_time_MS, $best_trial)
  {
    for ($trial_num = 1; $trial_num <= 2; $trial_num++)
    {
      if ($obj_reloc_time_MS[$trial_num] != '')
      {
        $subtotal[$trial_num] = 
          ($index_cards_status[$trial_num] +  $masking_tape_status[$trial_num] +
          $notebook_status[$trial_num]    + $half_liter_bottle_status[$trial_num] +
          $hacky_sack_status[$trial_num]  + $ruler_status[$trial_num] +
          $pencil_status[$trial_num]      + $cd_status[$trial_num] +
          $unident1_status[$trial_num]    + $unident2_status[$trial_num]);
        $score[$trial_num] = 
          round($subtotal[$trial_num] / ($obj_reloc_time_MS[$trial_num] / 1000), 2);
        $rounded_obj_reloc_time[$trial_num] = round($obj_reloc_time_MS[$trial_num] / 1000, 2);
      }
    }
    if ($obj_reloc_time_MS[1] == '' && $obj_reloc_time_MS[2] == '')
      $best_trial = '';
    else
      $best_trial = max($score);
    $table_HTML = <<<TABLE_HTML
    <table style="float: left">
      <tr>
        <th colspan="27">Object Relocation</th>
      </tr>
      <tr valign="bottom">
        <th colspan="13">Trial 1</th>
        <th colspan="13">Trial 2</th>
        <th rowspan="2">Best<br />Trial</th>
      </tr>
      <tr valign="bottom">
        <th><img src="../images/indexCardsHeader.png" /></th><th><img src="../images/maskingTapeHeader.png" /></th>
        <th><img src="../images/notebookHeader.png" /></th><th><img src="../images/halfLiterBottleHeader.png" /></th>
        <th><img src="../images/hackySackHeader.png" /></th><th><img src="../images/rulerHeader.png" /></th>
        <th><img src="../images/pencilHeader.png" /></th><th><img src="../images/cdHeader.png" /></th>
        <th><img src="../images/unident1Header.png" /></th><th><img src="../images/unident2Header.png" /></th>
        <th>subtotal</th><th>Task<br />Time<br />sec</th><th>Score</th>
        <th><img src="../images/indexCardsHeader.png" /></th><th><img src="../images/maskingTapeHeader.png" /></th>
        <th><img src="../images/notebookHeader.png" /></th><th><img src="../images/halfLiterBottleHeader.png" /></th>
        <th><img src="../images/hackySackHeader.png" /></th><th><img src="../images/rulerHeader.png" /></th>
        <th><img src="../images/pencilHeader.png" /></th><th><img src="../images/cdHeader.png" /></th>
        <th><img src="../images/unident1Header.png" /></th><th><img src="../images/unident2Header.png" /></th>
        <th>subtotal</th><th>Task<br />Time<br />sec</th><th>Score</th>
      </tr>
      <tr>
        <td>$index_cards_status[1]</td><td>$masking_tape_status[1]</td><td>$notebook_status[1]</td>
        <td>$half_liter_bottle_status[1]</td><td>$hacky_sack_status[1]</td><td>$ruler_status[1]</td>
        <td>$pencil_status[1]</td><td>$cd_status[1]</td><td>$unident1_status[1]</td>
        <td>$unident2_status[1]</td><td>$subtotal[1]</td>
        <td>$rounded_obj_reloc_time[1]</td><td>$score[1]</td>
        <td>$index_cards_status[2]</td><td>$masking_tape_status[2]</td><td>$notebook_status[2]</td>
        <td>$half_liter_bottle_status[2]</td><td>$hacky_sack_status[2]</td><td>$ruler_status[2]</td>
        <td>$pencil_status[2]</td><td>$cd_status[2]</td><td>$unident1_status[2]</td>
        <td>$unident2_status[2]</td><td>$subtotal[2]</td>
        <td>$rounded_obj_reloc_time[2]</td><td>$score[2]</td>
        <td>$best_trial</td>
      </tr>
    </table>
TABLE_HTML;
    return $table_HTML;
  }
  
  function design_efficiency_display($design_mass_KG, $total_performance_score, &$design_efficiency_score)
  {
    if ($design_mass_KG != '')
    {
      $design_efficiency_score = $total_performance_score / $design_mass_KG;
      $rounded_total_performance_score = round($total_performance_score, 2);
      $rounded_design_efficiency_score = round($design_efficiency_score, 2);
    }
    $table_HTML = <<<TABLE_HTML
    <table>
      <tr>
        <th colspan="7">Design Efficiency Score</th>
      </tr>
      <tr valign="bottom">
        <th>Design<br />Mass</th>
        <th>Total<br />Performance<br />Score</th>
        <th>Design<br />Efficiency<br />Score</th>
      </tr>
      <tr valign="bottom">
        <th>KG</th><th>Pts</th><th>Pts/KG</th>
      </tr>
      <tr>
        <td>$design_mass_KG</td><td>$rounded_total_performance_score</td><td>$rounded_design_efficiency_score</td>
      </tr>
    </table>
TABLE_HTML;
    return $table_HTML;
  }
  
  function tech_paper_display($discussion, $stem_concepts_analysis, $quality_thoroughness, $conventions, &$paper_points)
  {
    $num_judges = $tot_scores = 0;
    for ($judge_num = 1; $judge_num <= 3; $judge_num++)
    {
      if ($discussion[$judge_num] != '')
      {
        $judge[$judge_num] = $discussion[$judge_num] + 
          $stem_concepts_analysis[$judge_num] + 
          $quality_thoroughness[$judge_num] + 
          $conventions[$judge_num];
        $num_judges++;
        $tot_scores += $judge[$judge_num];
      }
    }
    if ($num_judges == 0)
      $paper_points = 0;
    else
      $paper_points = round($tot_scores / $num_judges, 2);
    $table_HTML = <<<TABLE_HTML
    <table>
      <tr>
        <th colspan="17">Technical Paper</th>
      </tr>
      <tr valign="bottom">
        <th colspan="5">Judge 1:</th>
        <th colspan="5">Judge 2:</th>
        <th colspan="5">Judge 3:</th>
        <th rowspan="3">#<br />Judges</th>
        <th rowspan="2">Paper<br />Points</th>
      </tr>
      <tr valign="bottom">
        <th><div class="vertical-text">Discussion</div></th>
        <th><div class="vertical-text">STEM Concepts & Analysis</div></th>
        <th><div class="vertical-text">Quality & Thoroughness</div></th>
        <th><div class="vertical-text">Conventions</div></th>
        <th><div class="vertical-text">Judge 1</div></th>
        <th><div class="vertical-text">Discussion</div></th>
        <th><div class="vertical-text">STEM Concepts & Analysis</div></th>
        <th><div class="vertical-text">Quality & Thoroughness</div></th>
        <th><div class="vertical-text">Conventions</div></th>
        <th><div class="vertical-text">Judge 2</div></th>
        <th><div class="vertical-text">Discussion</div></th>
        <th><div class="vertical-text">STEM Concepts & Analysis</div></th>
        <th><div class="vertical-text">Quality & Thoroughness</div></th>
        <th><div class="vertical-text">Conventions</div></th>
        <th><div class="vertical-text">Judge 3</div></th>
      </tr>
      <tr valign="bottom">
        <th>30</th><th>30</th><th>30</th><th>10</th><th>100</th>
        <th>30</th><th>30</th><th>30</th><th>10</th><th>100</th>
        <th>30</th><th>30</th><th>30</th><th>10</th><th>100</th>
        <th>100</th>
      </tr>
      <tr>
        <td>$discussion[1]</td>
        <td>$stem_concepts_analysis[1]</td>
        <td>$quality_thoroughness[1]</td>
        <td>$conventions[1]</td><td>$judge[1]</td>
        <td>$discussion[2]</td>
        <td>$stem_concepts_analysis[2]</td>
        <td>$quality_thoroughness[2]</td>
        <td>$conventions[2]</td><td>$judge[2]</td>
        <td>$discussion[3]</td>
        <td>$stem_concepts_analysis[3]</td>
        <td>$quality_thoroughness[3]</td>
        <td>$conventions[3]</td><td>$judge[3]</td>
        <td>$num_judges</td><td>$paper_points</td>
      </tr>
    </table>
TABLE_HTML;
    return $table_HTML;
  }

  function poster_display($abstract, $design_and_features, $results_data_and_analysis, $layout, &$poster_points)
  {
    $num_judges = $tot_scores = 0;
    for ($judge_num = 1; $judge_num <= 3; $judge_num++)
    {
      if ($abstract[$judge_num] != '')
      {
        $judge[$judge_num] = $abstract[$judge_num] + 
          $design_and_features[$judge_num] + 
          $results_data_and_analysis[$judge_num] + 
          $layout[$judge_num];
        $num_judges++;
        $tot_scores += $judge[$judge_num];
      }
    }
    if ($num_judges == 0)
      $poster_points = 0;
    else
      $poster_points = round($tot_scores / $num_judges, 2);
    $table_HTML = <<<TABLE_HTML
    <table>
      <tr>
        <th colspan="17">Poster/Display</th>
      </tr>
      <tr valign="bottom">
        <th colspan="5">Judge 1:</th>
        <th colspan="5">Judge 2:</th>
        <th colspan="5">Judge 3:</th>
        <th rowspan="3">#<br />Judges</th>
        <th rowspan="2">Poster/<br />Display<br />Points</th>
      </tr>
      <tr valign="bottom">
        <th><div class="vertical-text">Abstract</div></th>
        <th><div class="vertical-text">Design & Features</div></th>
        <th><div class="vertical-text">Results Data & Analysis</div></th>
        <th><div class="vertical-text">Layout</div></th>
        <th><div class="vertical-text">Judge 1</div></th>
        <th><div class="vertical-text">Abstract</div></th>
        <th><div class="vertical-text">Design & Features</div></th>
        <th><div class="vertical-text">Results Data & Analysis</div></th>
        <th><div class="vertical-text">Layout</div></th>
        <th><div class="vertical-text">Judge 2</div></th>
        <th><div class="vertical-text">Abstract</div></th>
        <th><div class="vertical-text">Design & Features</div></th>
        <th><div class="vertical-text">Results Data & Analysis</div></th>
        <th><div class="vertical-text">Layout</div></th>
        <th><div class="vertical-text">Judge 3</div></th>
      </tr>
      <tr valign="bottom">
        <th>20</th><th>15</th><th>30</th><th>10</th><th>75</th>
        <th>20</th><th>15</th><th>30</th><th>10</th><th>75</th>
        <th>20</th><th>15</th><th>30</th><th>10</th><th>75</th>
        <th>75</th>
      </tr>
      <tr>
        <td>$abstract[1]</td>
        <td>$design_and_features[1]</td>
        <td>$results_data_and_analysis[1]</td>
        <td>$layout[1]</td><td>$judge[1]</td>
        <td>$abstract[2]</td>
        <td>$design_and_features[2]</td>
        <td>$results_data_and_analysis[2]</td>
        <td>$layout[2]</td><td>$judge[2]</td>
        <td>$abstract[3]</td>
        <td>$design_and_features[3]</td>
        <td>$results_data_and_analysis[3]</td>
        <td>$layout[3]</td><td>$judge[3]</td>
        <td>$num_judges</td><td>$poster_points</td>
      </tr>
    </table>
TABLE_HTML;
    return $table_HTML;
  }

  function presentation_display($introduction, $stem_explanations, $design_process, $oral_visual, &$presentation_points)
  {
    $num_judges = $tot_scores = 0;
    for ($judge_num = 1; $judge_num <= 3; $judge_num++)
    {
      if ($introduction[$judge_num] != '')
      {
        $judge[$judge_num] = $introduction[$judge_num] + 
          $stem_explanations[$judge_num] + 
          $design_process[$judge_num] + 
          $oral_visual[$judge_num];
        $num_judges++;
        $tot_scores += $judge[$judge_num];
      }
    }
    if ($num_judges == 0)
      $presentation_points = 0;
    else
      $presentation_points = round($tot_scores / $num_judges, 2);
    $table_HTML = <<<TABLE_HTML
    <table>
      <tr>
        <th colspan="17">Presentation</th>
      </tr>
      <tr valign="bottom">
        <th colspan="5">Judge 1:</th>
        <th colspan="5">Judge 2:</th>
        <th colspan="5">Judge 3:</th>
        <th rowspan="3">#<br />Judges</th>
        <th rowspan="2">Presentation<br />Points</th>
      </tr>
      <tr valign="bottom">
        <th><div class="vertical-text">Introduction</div></th>
        <th><div class="vertical-text">STEM Explanations</div></th>
        <th><div class="vertical-text">Design/Process</div></th>
        <th><div class="vertical-text">Oral & Visual Overall</div></th>
        <th><div class="vertical-text">Judge 1</div></th>
        <th><div class="vertical-text">Introduction</div></th>
        <th><div class="vertical-text">STEM Explanations</div></th>
        <th><div class="vertical-text">Design/Process</div></th>
        <th><div class="vertical-text">Oral & Visual Overall</div></th>
        <th><div class="vertical-text">Judge 2</div></th>
        <th><div class="vertical-text">Introduction</div></th>
        <th><div class="vertical-text">STEM Explanations</div></th>
        <th><div class="vertical-text">Design/Process</div></th>
        <th><div class="vertical-text">Oral & Visual Overall</div></th>
        <th><div class="vertical-text">Judge 3</div></th>
      </tr>
      <tr valign="bottom">
        <th>10</th><th>25</th><th>25</th><th>15</th><th>75</th>
        <th>10</th><th>25</th><th>25</th><th>15</th><th>75</th>
        <th>10</th><th>25</th><th>25</th><th>15</th><th>75</th>
        <th>75</th>
      </tr>
      <tr>
        <td>$introduction[1]</td>
        <td>$stem_explanations[1]</td>
        <td>$design_process[1]</td>
        <td>$oral_visual[1]</td><td>$judge[1]</td>
        <td>$introduction[2]</td>
        <td>$stem_explanations[2]</td>
        <td>$design_process[2]</td>
        <td>$oral_visual[2]</td><td>$judge[2]</td>
        <td>$introduction[3]</td>
        <td>$stem_explanations[3]</td>
        <td>$design_process[3]</td>
        <td>$oral_visual[3]</td><td>$judge[3]</td>
        <td>$num_judges</td><td>$presentation_points</td>
      </tr>
    </table>
TABLE_HTML;
    return $table_HTML;
  }
  
  function report_beginning($report_name)
  {
    return <<<HTML_BEGIN
<html>
  <head>
    <title>$team_level_ucase $report_name Report</title>
    <style type="text/css">
      th    { 
              text-align: center; 
              font-weight: bold;
              border: 1px solid black;
              font-size: 100%;
            }
      td    { 
              text-align: right; 
              border: 1px solid black; 
              font-size: 100%;
            }
      .vert-headers { text-align: left; font-weight: bold; }
      table { border: 1px solid black;
              border-collapse: collapse;
            }
    </style>
  </head>
  <body>
HTML_BEGIN;
  }

  function reset_task_point_tables()
  {
    $query = <<<QUERY
    SELECT max(bestScore) FROM full_dist_acc  
      WHERE teamLevel = 'high school' INTO @maxDistAccScoreHS;
    SELECT max(bestScore) FROM full_dist_acc  
      WHERE teamLevel = 'middle school' INTO @maxDistAccScoreMS;

    SELECT max(bestScore) FROM full_obj_reloc  
      WHERE teamLevel = 'high school' INTO @maxObjRelocScoreHS;
    SELECT max(bestScore) FROM full_obj_reloc  
      WHERE teamLevel = 'middle school' INTO @maxObjRelocScoreMS;
    
    SELECT max(bestPointTimeRatio) FROM full_dexterity  
      WHERE teamLevel = 'high school' INTO @maxDexScoreHS;
    SELECT max(bestPointTimeRatio) FROM full_dexterity  
      WHERE teamLevel = 'middle school' INTO @maxDexScoreMS;

    DROP TABLE IF EXISTS distAccPoints;
    CREATE TABLE distAccPoints
    AS
    SELECT teamID, if(bestScore && @maxDistAccScoreHS != 0, 
      50*(bestScore/@maxDistAccScoreHS), 0) AS distAccTaskPoints 
      FROM full_dist_acc WHERE teamLevel='high school'
    UNION
    SELECT teamID, if(bestScore && @maxDistAccScoreMS != 0, 
      50*(bestScore/@maxDistAccScoreMS), 0) AS distAccTaskPoints 
      FROM full_dist_acc WHERE teamLevel='middle school';

	  DROP TABLE IF EXISTS objRelocPoints;
	  CREATE TABLE objRelocPoints
    AS
    SELECT teamID, if(bestScore && @maxObjRelocScoreHS != 0, 
      50*(bestScore/@maxObjRelocScoreHS), 0) AS objRelocTrialPoints 
      FROM full_obj_reloc WHERE teamLevel='high school'
    UNION
    SELECT teamID, if(bestScore && @maxObjRelocScoreMS != 0, 
      50*(bestScore/@maxObjRelocScoreHS), 0) AS objRelocTrialPoints 
      FROM full_obj_reloc WHERE teamLevel='middle school';
      
	  DROP TABLE IF EXISTS dexPoints;
	  CREATE TABLE dexPoints
    AS
    SELECT teamID, if(bestPointTimeRatio && @maxDexScoreHS != 0, 
      50*(bestPointTimeRatio/@maxDexScoreHS), 0) AS dexTrialPoints 
      FROM full_dexterity WHERE teamLevel='high school'
    UNION
    SELECT teamID, if(bestPointTimeRatio && @maxDexScoreHS != 0, 
      50*(bestPointTimeRatio/@maxDexScoreMS), 0) AS dexTrialPoints 
      FROM full_dexterity WHERE teamLevel='middle school';
      
	  DROP TABLE IF EXISTS techPaperPoints;
	  CREATE TABLE techPaperPoints
    AS
    SELECT teamID, if(paperPoints, paperPoints, 0) as paperPoints
      FROM full_tech_paper WHERE teamLevel='high school'
    UNION
    SELECT teamID, if(paperPoints, paperPoints, 0) as paperPoints 
      FROM full_tech_paper WHERE teamLevel='middle school';

	  DROP TABLE IF EXISTS posterPoints;
	  CREATE TABLE posterPoints
    AS
    SELECT teamID, if(posterPoints, posterPoints, 0) as posterPoints
      FROM full_poster WHERE teamLevel='high school'
    UNION
    SELECT teamID, if(posterPoints, posterPoints, 0) as posterPoints 
      FROM full_poster WHERE teamLevel='middle school';

	  DROP TABLE IF EXISTS presentationPoints;
	  CREATE TABLE presentationPoints
    AS
    SELECT teamID, if(presentationPoints, presentationPoints, 0) as presentationPoints 
      FROM full_presentation WHERE teamLevel='high school'
    UNION
    SELECT teamID, if(presentationPoints, presentationPoints, 0) as presentationPoints 
      FROM full_presentation WHERE teamLevel='middle school';
QUERY;
    return $query;
  }

  function set_up_views(&$link_id)
  {
    $new_task_point_tables = reset_task_point_tables();
    $query = <<<QUERY

    CREATE OR REPLACE VIEW full_dexterity
    AS
    SELECT 
      teams.teamID AS teamID,
      CONCAT(teams.teamSchool, " ", teams.teamName) AS fullTeamName,
      teams.teamLevel AS teamLevel,
      dexTrial1.bolt1Points as trial1Bolt1Points, 
      dexTrial1.bolt2Points as trial1Bolt2Points, 
      dexTrial1.bolt3Points as trial1Bolt3Points, 
      IFNULL(dexTrial1.bolt1Points, 0) + IFNULL(dexTrial1.bolt2Points, 0) + IFNULL(dexTrial1.bolt3Points, 0) AS trial1TotalPoints, 
      round(IFNULL(dexTrial1.trialTimeMS, 0) / 1000, 2) AS trial1TrialTime, 
      if(dexTrial1.trialTimeMS / 1000,
          round((IFNULL(dexTrial1.bolt1Points, 0) + IFNULL(dexTrial1.bolt2Points, 0) + IFNULL(dexTrial1.bolt3Points, 0)) /
                 (dexTrial1.trialTimeMS / 1000), 2),
          0)
          AS trial1PointTimeRatio,
      dexTrial2.bolt1Points as trial2Bolt1Points, 
      dexTrial2.bolt2Points as trial2Bolt2Points, 
      dexTrial2.bolt3Points as trial2Bolt3Points, 
      IFNULL(dexTrial2.bolt1Points, 0) + IFNULL(dexTrial2.bolt2Points, 0) + IFNULL(dexTrial2.bolt3Points, 0) AS trial2TotalPoints, 
      round(IFNULL(dexTrial2.trialTimeMS, 0) / 1000, 2) AS trial2TrialTime, 
      if(dexTrial2.trialTimeMS / 1000,
          round((IFNULL(dexTrial2.bolt1Points, 0) + IFNULL(dexTrial2.bolt2Points, 0) + IFNULL(dexTrial2.bolt3Points, 0)) /
                (dexTrial2.trialTimeMS / 1000), 2),
          0)
          AS trial2PointTimeRatio,
        greatest(
        if(dexTrial1.trialTimeMS / 1000,
            (IFNULL(dexTrial1.bolt1Points, 0) + IFNULL(dexTrial1.bolt2Points, 0) + IFNULL(dexTrial1.bolt3Points, 0)) /
                  (dexTrial1.trialTimeMS / 1000),
            0),
        if(dexTrial2.trialTimeMS / 1000,
            (IFNULL(dexTrial2.bolt1Points, 0) + IFNULL(dexTrial2.bolt2Points, 0) + IFNULL(dexTrial2.bolt3Points, 0)) /
                  (dexTrial2.trialTimeMS / 1000),
            0))
          AS bestPointTimeRatio
    FROM teams, `dexterity` AS dexTrial1, `dexterity` AS dexTrial2
    WHERE teams.teamID = dexTrial1.teamID
      AND dexTrial1.teamID = dexTrial2.teamID 
      AND dexTrial1.trialNum = '1' AND dexTrial2.trialNum = '2';
      
    CREATE OR REPLACE VIEW full_dist_acc
    AS
    SELECT 
      teams.teamID AS teamID,
      CONCAT(teams.teamSchool, " ", teams.teamName) AS fullTeamName,
      teams.teamLevel AS teamLevel,
      distAccTrial1.zone1Bags as trial1zone1Bags, 
      distAccTrial1.zone2Bags as trial1zone2Bags, 
      distAccTrial1.zone3Bags as trial1zone3Bags, 
      distAccTrial1.zone4Bags as trial1zone4Bags, 
      distAccTrial1.zone5Bags as trial1zone5Bags, 
      IFNULL(distAccTrial1.zone1Bags, 0) * 5 + IFNULL(distAccTrial1.zone2Bags, 0) * 10 + IFNULL(distAccTrial1.zone3Bags, 0) * 15 + 
        IFNULL(distAccTrial1.zone4Bags, 0) * 20 + IFNULL(distAccTrial1.zone5Bags, 0) * 25 AS trial1Subtotal, 
      round(IFNULL(distAccTrial1.trialTimeMS, 0) / 1000, 2) AS trial1TrialTime, 
      if(distAccTrial1.trialTimeMS / 1000,
          round((IFNULL(distAccTrial1.zone1Bags, 0) * 5 + IFNULL(distAccTrial1.zone2Bags, 0) * 10 + 
          IFNULL(distAccTrial1.zone3Bags, 0) * 15 + IFNULL(distAccTrial1.zone4Bags, 0) * 20 + 
          IFNULL(distAccTrial1.zone5Bags, 0) * 25) / (distAccTrial1.trialTimeMS / 1000), 2),
          0)
          AS trial1Score,
      distAccTrial2.zone1Bags as trial2zone1Bags, 
      distAccTrial2.zone2Bags as trial2zone2Bags, 
      distAccTrial2.zone3Bags as trial2zone3Bags, 
      distAccTrial2.zone4Bags as trial2zone4Bags, 
      distAccTrial2.zone5Bags as trial2zone5Bags, 
      IFNULL(distAccTrial2.zone1Bags, 0) * 5 + IFNULL(distAccTrial2.zone2Bags, 0) * 10 + IFNULL(distAccTrial2.zone3Bags, 0) * 15 + 
        IFNULL(distAccTrial2.zone4Bags, 0) * 20 + IFNULL(distAccTrial2.zone5Bags, 0) * 25 AS trial2Subtotal, 
      round(IFNULL(distAccTrial2.trialTimeMS, 0) / 1000, 2) AS trial2TrialTime, 
      if(distAccTrial2.trialTimeMS / 1000,
          round((IFNULL(distAccTrial2.zone1Bags, 0) * 5 + IFNULL(distAccTrial2.zone2Bags, 0) * 10 + 
          IFNULL(distAccTrial2.zone3Bags, 0) * 15 + IFNULL(distAccTrial2.zone4Bags, 0) * 20 + 
          IFNULL(distAccTrial2.zone5Bags, 0) * 25) / (distAccTrial2.trialTimeMS / 1000), 2),
          0)
        AS trial2Score,
      greatest(
        if(distAccTrial1.trialTimeMS / 1000,
          (IFNULL(distAccTrial1.zone1Bags, 0) * 5 + IFNULL(distAccTrial1.zone2Bags, 0) * 10 + 
          IFNULL(distAccTrial1.zone3Bags, 0) * 15 + IFNULL(distAccTrial1.zone4Bags, 0) * 20 + 
          IFNULL(distAccTrial1.zone5Bags, 0) * 25) / (distAccTrial1.trialTimeMS / 1000),
          0),
        if(distAccTrial2.trialTimeMS / 1000,
          (IFNULL(distAccTrial2.zone1Bags, 0) * 5 + IFNULL(distAccTrial2.zone2Bags, 0) * 10 + 
          IFNULL(distAccTrial2.zone3Bags, 0) * 15 + IFNULL(distAccTrial2.zone4Bags, 0) * 20 + 
          IFNULL(distAccTrial2.zone5Bags, 0) * 25) / (distAccTrial2.trialTimeMS / 1000),
          0))
        AS bestScore
    FROM teams, `dist_acc` AS distAccTrial1, `dist_acc` AS distAccTrial2
    WHERE teams.teamID = distAccTrial1.teamID
      AND distAccTrial1.teamID = distAccTrial2.teamID 
      AND distAccTrial1.trialNum = '1' AND distAccTrial2.trialNum = '2';

    CREATE OR REPLACE VIEW full_obj_reloc
    AS
    SELECT 
      teams.teamID AS teamID,
      CONCAT(teams.teamSchool, " ", teams.teamName) AS fullTeamName,
      teams.teamLevel AS teamLevel,
      round(IFNULL(objRelocTrial1.taskTimeMS, 0) / 1000, 2) AS trial1TaskTime, 
      IFNULL(objRelocTrial1.indexCardsStatus, 0) as trial1IndexCardsStatus,
      IFNULL(objRelocTrial1.maskingTapeStatus, 0) as trial1MaskingTapeStatus,
      IFNULL(objRelocTrial1.notebookStatus, 0) as trial1NotebookStatus,
      IFNULL(objRelocTrial1.halfLiterBottleStatus, 0) as trial1HalfLiterBottleStatus,
      IFNULL(objRelocTrial1.hackySackStatus, 0) as trial1HackySackStatus,
      IFNULL(objRelocTrial1.rulerStatus, 0) as trial1RulerStatus,
      IFNULL(objRelocTrial1.pencilStatus, 0) as trial1PencilStatus,
      IFNULL(objRelocTrial1.cdStatus, 0) as trial1CdStatus,
      IFNULL(objRelocTrial1.unident1Status, 0) as trial1Unident1Status,
      IFNULL(objRelocTrial1.unident2Status, 0) as trial1Unident2Status,
      if(objRelocTrial1.taskTimeMS / 1000,
          (IFNULL(objRelocTrial1.indexCardsStatus, 0) +  IFNULL(objRelocTrial1.maskingTapeStatus, 0) +
          IFNULL(objRelocTrial1.notebookStatus, 0)    + IFNULL(objRelocTrial1.halfLiterBottleStatus, 0) +
          IFNULL(objRelocTrial1.hackySackStatus, 0)   + IFNULL(objRelocTrial1.rulerStatus, 0) +
          IFNULL(objRelocTrial1.pencilStatus, 0)      + IFNULL(objRelocTrial1.cdStatus, 0) +
          IFNULL(objRelocTrial1.unident1Status, 0)    + IFNULL(objRelocTrial1.unident2Status, 0)),
          0)
          AS trial1Subtotal,
      if(objRelocTrial1.taskTimeMS / 1000,
        round(
          (IFNULL(objRelocTrial1.indexCardsStatus, 0) +  IFNULL(objRelocTrial1.maskingTapeStatus, 0) +
          IFNULL(objRelocTrial1.notebookStatus, 0)    + IFNULL(objRelocTrial1.halfLiterBottleStatus, 0) +
          IFNULL(objRelocTrial1.hackySackStatus, 0)   + IFNULL(objRelocTrial1.rulerStatus, 0) +
          IFNULL(objRelocTrial1.pencilStatus, 0)      + IFNULL(objRelocTrial1.cdStatus, 0) +
          IFNULL(objRelocTrial1.unident1Status, 0)    + IFNULL(objRelocTrial1.unident2Status, 0)) /
          (objRelocTrial1.taskTimeMS / 1000), 2),
          0)
          AS trial1Score,      
      round(IFNULL(objRelocTrial2.taskTimeMS, 0) / 1000, 2) AS trial2TaskTime, 
      IFNULL(objRelocTrial2.indexCardsStatus, 0) as trial2IndexCardsStatus,
      IFNULL(objRelocTrial2.maskingTapeStatus, 0) as trial2MaskingTapeStatus,
      IFNULL(objRelocTrial2.notebookStatus, 0) as trial2NotebookStatus,
      IFNULL(objRelocTrial2.halfLiterBottleStatus, 0) as trial2HalfLiterBottleStatus,
      IFNULL(objRelocTrial2.hackySackStatus, 0) as trial2HackySackStatus,
      IFNULL(objRelocTrial2.rulerStatus, 0) as trial2RulerStatus,
      IFNULL(objRelocTrial2.pencilStatus, 0) as trial2PencilStatus,
      IFNULL(objRelocTrial2.cdStatus, 0) as trial2CdStatus,
      IFNULL(objRelocTrial2.unident1Status, 0) as trial2Unident1Status,
      IFNULL(objRelocTrial2.unident2Status, 0) as trial2Unident2Status,
      if(objRelocTrial2.taskTimeMS / 1000,
          (IFNULL(objRelocTrial2.indexCardsStatus, 0) +  IFNULL(objRelocTrial2.maskingTapeStatus, 0) +
          IFNULL(objRelocTrial2.notebookStatus, 0)    + IFNULL(objRelocTrial2.halfLiterBottleStatus, 0) +
          IFNULL(objRelocTrial2.hackySackStatus, 0)   + IFNULL(objRelocTrial2.rulerStatus, 0) +
          IFNULL(objRelocTrial2.pencilStatus, 0)      + IFNULL(objRelocTrial2.cdStatus, 0) +
          IFNULL(objRelocTrial2.unident1Status, 0)    + IFNULL(objRelocTrial2.unident2Status, 0)),
          0)
          AS trial2Subtotal,
      if(objRelocTrial2.taskTimeMS / 1000,
        round(
          (IFNULL(objRelocTrial2.indexCardsStatus, 0) +  IFNULL(objRelocTrial2.maskingTapeStatus, 0) +
          IFNULL(objRelocTrial2.notebookStatus, 0)   + IFNULL(objRelocTrial2.halfLiterBottleStatus, 0) +
          IFNULL(objRelocTrial2.hackySackStatus, 0)  + IFNULL(objRelocTrial2.rulerStatus, 0) +
          IFNULL(objRelocTrial2.pencilStatus, 0)     + IFNULL(objRelocTrial2.cdStatus, 0) +
          IFNULL(objRelocTrial2.unident1Status, 0)   + IFNULL(objRelocTrial2.unident2Status, 0)) /
          (objRelocTrial2.taskTimeMS / 1000), 2),
          0)
          AS trial2Score,
      greatest(
        if(objRelocTrial1.taskTimeMS / 1000,
          (IFNULL(objRelocTrial1.indexCardsStatus, 0) +  IFNULL(objRelocTrial1.maskingTapeStatus, 0) +
          IFNULL(objRelocTrial1.notebookStatus, 0)    + IFNULL(objRelocTrial1.halfLiterBottleStatus, 0) +
          IFNULL(objRelocTrial1.hackySackStatus, 0)   + IFNULL(objRelocTrial1.rulerStatus, 0) +
          IFNULL(objRelocTrial1.pencilStatus, 0)      + IFNULL(objRelocTrial1.cdStatus, 0) +
          IFNULL(objRelocTrial1.unident1Status, 0)    + IFNULL(objRelocTrial1.unident2Status, 0)) /
          (objRelocTrial1.taskTimeMS / 1000),
          0),
        if(objRelocTrial2.taskTimeMS / 1000,
          (IFNULL(objRelocTrial2.indexCardsStatus, 0) +  IFNULL(objRelocTrial2.maskingTapeStatus, 0) +
          IFNULL(objRelocTrial2.notebookStatus, 0)    + IFNULL(objRelocTrial2.halfLiterBottleStatus, 0) +
          IFNULL(objRelocTrial2.hackySackStatus, 0)   + IFNULL(objRelocTrial2.rulerStatus, 0) +
          IFNULL(objRelocTrial2.pencilStatus, 0)      + IFNULL(objRelocTrial2.cdStatus, 0) +
          IFNULL(objRelocTrial2.unident1Status, 0)    + IFNULL(objRelocTrial2.unident2Status, 0)) /
          (objRelocTrial2.taskTimeMS / 1000),
          0))
        AS bestScore
    FROM teams, `obj_reloc` AS objRelocTrial1, `obj_reloc` AS objRelocTrial2
    WHERE teams.teamID = objRelocTrial1.teamID
      AND objRelocTrial1.teamID = objRelocTrial2.teamID 
      AND objRelocTrial1.trialNum = '1' AND objRelocTrial2.trialNum = '2';

    CREATE OR REPLACE VIEW full_poster
    AS
    SELECT 
      teams.teamID AS teamID,
      CONCAT(teams.teamSchool, " ", teams.teamName) AS fullTeamName,
      teams.teamLevel AS teamLevel,
      posterJudge1.abstract as judge1Abstract, 
      posterJudge1.designAndFeatures as judge1DesignAndFeatures, 
      posterJudge1.resultsDataAndAnalysis as judge1ResultsDataAndAnalysis, 
      posterJudge1.layout as judge1Layout, 
      if (posterJudge1.abstract,
          posterJudge1.abstract + posterJudge1.designAndFeatures + 
            posterJudge1.resultsDataAndAnalysis + posterJudge1.layout,
          NULL)            
        AS judge1, 
      posterJudge2.abstract as judge2Abstract, 
      posterJudge2.designAndFeatures as judge2DesignAndFeatures, 
      posterJudge2.resultsDataAndAnalysis as judge2ResultsDataAndAnalysis, 
      posterJudge2.layout as judge2Layout, 
      if (posterJudge2.abstract,
          posterJudge2.abstract + posterJudge2.designAndFeatures + 
            posterJudge2.resultsDataAndAnalysis + posterJudge2.layout,
          NULL)
        AS judge2, 
      posterJudge3.abstract as judge3Abstract, 
      posterJudge3.designAndFeatures as judge3DesignAndFeatures, 
      posterJudge3.resultsDataAndAnalysis as judge3ResultsDataAndAnalysis, 
      posterJudge3.layout as judge3Layout, 
      if (posterJudge3.abstract,
          posterJudge3.abstract + posterJudge3.designAndFeatures + 
            posterJudge3.resultsDataAndAnalysis + posterJudge3.layout,
          NULL)
        AS judge3,
      if(posterJudge1.abstract,1,0) + if(posterJudge2.abstract,1,0) +
        if(posterJudge3.abstract,1,0)
        AS numJudges,
      if(if(posterJudge1.abstract,1,0) + if(posterJudge2.abstract,1,0) + 
           if(posterJudge3.abstract,1,0),
        (IFNULL(posterJudge1.abstract + posterJudge1.designAndFeatures + 
          posterJudge1.resultsDataAndAnalysis + posterJudge1.layout, 0) +
        IFNULL(posterJudge2.abstract + posterJudge2.designAndFeatures + 
          posterJudge2.resultsDataAndAnalysis + posterJudge2.layout, 0) +
        IFNULL(posterJudge3.abstract + posterJudge3.designAndFeatures + 
          posterJudge3.resultsDataAndAnalysis + posterJudge3.layout, 0) )
        / 
        (if(posterJudge1.abstract,1,0) + if(posterJudge2.abstract,1,0) +
          if(posterJudge3.abstract,1,0)),
        0
        )
      AS posterPoints
    FROM  teams, `poster` AS posterJudge1, `poster` AS posterJudge2,
          `poster` AS posterJudge3
    WHERE teams.teamID = posterJudge1.teamID
      AND posterJudge1.teamID = posterJudge2.teamID 
      AND posterJudge1.teamID = posterJudge3.teamID 
      AND posterJudge1.judgeNum = '1' AND posterJudge2.judgeNum = '2'
      AND posterJudge3.judgeNum = '3';

    CREATE OR REPLACE VIEW full_presentation
    AS
    SELECT 
      teams.teamID AS teamID,
      CONCAT(teams.teamSchool, " ", teams.teamName) AS fullTeamName,
      teams.teamLevel AS teamLevel,
      presentationJudge1.introduction as judge1Introduction, 
      presentationJudge1.stemExplanations as judge1StemExplanations, 
      presentationJudge1.designProcess as judge1DesignProcess, 
      presentationJudge1.oralVisual as judge1OralVisual, 
      if (presentationJudge1.introduction,
          presentationJudge1.introduction + presentationJudge1.stemExplanations + 
            presentationJudge1.designProcess + presentationJudge1.oralVisual,
          NULL)            
        AS judge1, 
      presentationJudge2.introduction as judge2Introduction, 
      presentationJudge2.stemExplanations as judge2StemExplanations, 
      presentationJudge2.designProcess as judge2DesignProcess, 
      presentationJudge2.oralVisual as judge2OralVisual, 
      if (presentationJudge2.introduction,
          presentationJudge2.introduction + presentationJudge2.stemExplanations + 
            presentationJudge2.designProcess + presentationJudge2.oralVisual,
          NULL)
        AS judge2, 
      presentationJudge3.introduction as judge3Introduction, 
      presentationJudge3.stemExplanations as judge3StemExplanations, 
      presentationJudge3.designProcess as judge3DesignProcess, 
      presentationJudge3.oralVisual as judge3OralVisual, 
      if (presentationJudge3.introduction,
          presentationJudge3.introduction + presentationJudge3.stemExplanations + 
            presentationJudge3.designProcess + presentationJudge3.oralVisual,
          NULL)
        AS judge3,
      if(presentationJudge1.introduction,1,0) + if(presentationJudge2.introduction,1,0) +
        if(presentationJudge3.introduction,1,0)
        AS numJudges,
      if(if(presentationJudge1.introduction,1,0) + if(presentationJudge2.introduction,1,0) + 
        if(presentationJudge3.introduction,1,0),
        (IFNULL(presentationJudge1.introduction + presentationJudge1.stemExplanations + 
          presentationJudge1.designProcess + presentationJudge1.oralVisual, 0) +
        IFNULL(presentationJudge2.introduction + presentationJudge2.stemExplanations + 
          presentationJudge2.designProcess + presentationJudge2.oralVisual, 0) +
        IFNULL(presentationJudge3.introduction + presentationJudge3.stemExplanations + 
          presentationJudge3.designProcess + presentationJudge3.oralVisual, 0) )
        / 
        (if(presentationJudge1.introduction,1,0) + if(presentationJudge2.introduction,1,0) +
          if(presentationJudge3.introduction,1,0)),
        0)
      AS presentationPoints
    FROM  teams, `presentation` AS presentationJudge1, `presentation` AS presentationJudge2,
          `presentation` AS presentationJudge3
    WHERE teams.teamID = presentationJudge1.teamID
      AND presentationJudge1.teamID = presentationJudge2.teamID 
      AND presentationJudge1.teamID = presentationJudge3.teamID 
      AND presentationJudge1.judgeNum = '1' AND presentationJudge2.judgeNum = '2'
      AND presentationJudge3.judgeNum = '3';
      
    CREATE OR REPLACE VIEW full_tech_paper
    AS
    SELECT 
      teams.teamID AS teamID,
      CONCAT(teams.teamSchool, " ", teams.teamName) AS fullTeamName,
      teams.teamLevel AS teamLevel,
      techPaperJudge1.discussion as judge1Discussion, 
      techPaperJudge1.stemConceptsAnalysis as judge1StemConceptsAnalysis, 
      techPaperJudge1.qualityThoroughness as judge1QualityThoroughness, 
      techPaperJudge1.conventions as judge1Conventions, 
      if (techPaperJudge1.discussion,
          techPaperJudge1.discussion + techPaperJudge1.stemConceptsAnalysis + 
            techPaperJudge1.qualityThoroughness + techPaperJudge1.conventions,
          NULL)            
        AS judge1, 
      techPaperJudge2.discussion as judge2Discussion, 
      techPaperJudge2.stemConceptsAnalysis as judge2StemConceptsAnalysis, 
      techPaperJudge2.qualityThoroughness as judge2QualityThoroughness, 
      techPaperJudge2.conventions as judge2Conventions, 
      if (techPaperJudge2.discussion,
          techPaperJudge2.discussion + techPaperJudge2.stemConceptsAnalysis + 
            techPaperJudge2.qualityThoroughness + techPaperJudge2.conventions,
          NULL)
        AS judge2, 
      techPaperJudge3.discussion as judge3Discussion, 
      techPaperJudge3.stemConceptsAnalysis as judge3StemConceptsAnalysis, 
      techPaperJudge3.qualityThoroughness as judge3QualityThoroughness, 
      techPaperJudge3.conventions as judge3Conventions, 
      if (techPaperJudge3.discussion,
          techPaperJudge3.discussion + techPaperJudge3.stemConceptsAnalysis + 
            techPaperJudge3.qualityThoroughness + techPaperJudge3.conventions,
          NULL)
        AS judge3,
      if(techPaperJudge1.discussion,1,0) + if(techPaperJudge2.discussion,1,0) +
        if(techPaperJudge3.discussion,1,0)
        AS numJudges,
      if  (if(techPaperJudge1.discussion,1,0) + if(techPaperJudge2.discussion,1,0) + 
            if(techPaperJudge3.discussion,1,0),
          (IFNULL(techPaperJudge1.discussion + techPaperJudge1.stemConceptsAnalysis + 
            techPaperJudge1.qualityThoroughness + techPaperJudge1.conventions, 0) +
          IFNULL(techPaperJudge2.discussion + techPaperJudge2.stemConceptsAnalysis + 
            techPaperJudge2.qualityThoroughness + techPaperJudge2.conventions, 0) +
          IFNULL(techPaperJudge3.discussion + techPaperJudge3.stemConceptsAnalysis + 
            techPaperJudge3.qualityThoroughness + techPaperJudge3.conventions, 0) )
          / 
          (if(techPaperJudge1.discussion,1,0) + if(techPaperJudge2.discussion,1,0) +
            if(techPaperJudge3.discussion,1,0)),
          0)
      AS paperPoints
    FROM  teams, `tech_paper` AS techPaperJudge1, `tech_paper` AS techPaperJudge2,
          `tech_paper` AS techPaperJudge3
    WHERE teams.teamID = techPaperJudge1.teamID
      AND techPaperJudge1.teamID = techPaperJudge2.teamID 
      AND techPaperJudge1.teamID = techPaperJudge3.teamID 
      AND techPaperJudge1.judgeNum = '1' AND techPaperJudge2.judgeNum = '2'
      AND techPaperJudge3.judgeNum = '3';

    $new_task_point_tables
    
    CREATE OR REPLACE VIEW full_design_efficiency
    AS
    SELECT 
      teams.teamID AS teamID,
      CONCAT(teams.teamSchool, " ", teams.teamName) AS fullTeamName,
      teams.teamLevel AS teamLevel,
      round(IFNULL(designMassKG, 0), 2) AS designMass,
      distAccPoints.distAccTaskPoints AS distAccTaskPoints,
      objRelocPoints.objRelocTrialPoints AS objRelocTrialPoints,
      dexPoints.dexTrialPoints AS dexTrialPoints,
      distAccPoints.distAccTaskPoints + objRelocPoints.objRelocTrialPoints + 
        dexPoints.dexTrialPoints AS totalPerformanceScore,
      if (designMassKG,
        (distAccPoints.distAccTaskPoints + objRelocPoints.objRelocTrialPoints +
        dexPoints.dexTrialPoints) / designMassKG,
        0)
        AS designEfficiencyScore
    FROM teams, distAccPoints, objRelocPoints, dexPoints
    WHERE teams.teamID = distAccPoints.teamID AND
      teams.teamID = objRelocPoints.teamID AND
      teams.teamID = dexPoints.teamID;

    SELECT max(designEfficiencyScore) FROM full_design_efficiency 
      WHERE teamLevel = 'high school' INTO @maxDesignEfficiencyScoreHS;
    SELECT max(designEfficiencyScore) FROM full_design_efficiency 
      WHERE teamLevel = 'middle school' INTO @maxDesignEfficiencyScoreMS;
    DROP TABLE IF EXISTS designEfficiencyPoints;
    CREATE TABLE designEfficiencyPoints
    AS
    SELECT teamID, 
      if (designEfficiencyScore && @maxDesignEfficiencyScoreHS != 0,
        50*(designEfficiencyScore/@maxDesignEfficiencyScoreHS), 0)
        AS designEfficiencyTaskPoints 
      FROM full_design_efficiency WHERE teamLevel = 'high school'
    UNION
    SELECT teamID, 
      if (designEfficiencyScore && @maxDesignEfficiencyScoreHS != 0,
        50*(designEfficiencyScore/@maxDesignEfficiencyScoreMS), 0)
        AS designEfficiencyTaskPoints 
      FROM full_design_efficiency WHERE teamLevel = 'middle school';

    CREATE OR REPLACE VIEW full_overall
    AS
    SELECT 
      CONCAT(teams.teamSchool, " ", teams.teamName) AS fullTeamName,
      teams.teamLevel AS teamLevel,
      round(distAccPoints.distAccTaskPoints, 2) AS distAccTaskPoints,
      round(objRelocPoints.objRelocTrialPoints, 2) AS objRelocTrialPoints,
      round(dexPoints.dexTrialPoints, 2) AS dexTrialPoints,
      round(distAccPoints.distAccTaskPoints + objRelocPoints.objRelocTrialPoints + 
        dexPoints.dexTrialPoints, 2) AS performancePoints,
      round(designEfficiencyPoints.designEfficiencyTaskPoints, 2) AS designEfficiencyPoints,
      round(techPaperPoints.paperPoints, 2) AS techPaperPoints,
      round(presentationPoints.presentationPoints, 2) AS presentationPoints,
      round(posterPoints.posterPoints, 2) AS posterDisplayPoints
    FROM teams, distAccPoints, objRelocPoints, dexPoints, designEfficiencyPoints, techPaperPoints, presentationPoints, posterPoints
    WHERE teams.teamID = distAccPoints.teamID AND
      teams.teamID = objRelocPoints.teamID AND
      teams.teamID = dexPoints.teamID AND
      teams.teamID = designEfficiencyPoints.teamID AND
      teams.teamID = techPaperPoints.teamID AND
      teams.teamID = presentationPoints.teamID AND
      teams.teamID = posterPoints.teamID;
QUERY;
    if (mysqli_multi_query($link_id, $query)) 
    {
      do 
      {
          $result = mysqli_store_result($link_id); 
          if (mysqli_more_results($link_id))
            mysqli_free_result($result);
          mysqli_next_result($link_id);
      } 
      while (mysqli_more_results($link_id));
    }
  }
?>