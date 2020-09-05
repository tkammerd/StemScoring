<html>
  <head>
    <title>Display Team Information</title>
    <style type="text/css">
      th    { 
              text-align: center; 
              font-weight: bold;
              border: 1px solid black;
              font-size: 50%;
            }
      td    { 
              text-align: center; 
              border: 1px solid black; 
              font-size: 50%;
            }
      table { border: 1px solid black;
              border-collapse: collapse;
            }
      tr:first-child {
                       background-color: gray;
                       color: white;
                     }
      .vertical-text {}
      .vertical-text2
            {
              float: left; 
              position: relative;
              -moz-transform: rotate(270deg);  /* FF3.5+ */        
              -o-transform: rotate(270deg);  /* Opera 10.5 */   
              -webkit-transform: rotate(270deg);  /* Saf3.1+, Chrome */              
              filter:  progid:DXImageTransform.Microsoft.BasicImage(rotation=3);  /* IE6,IE7 */          
              -ms-filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3); /* IE8 */
    
            }
    </style>
  </head>
  <body>
    <?php
      require "utilities.php";
      $team_id = $_GET["team_id"];
      open_database($link_id);
      if (trim($team_id) == '')
      {
        $result = mysqli_query($link_id, "SELECT * FROM teams ORDER BY teamSchool, teamLevel, teamName LIMIT 1");
        $cur_row = mysqli_fetch_array($result);
        $team_id = $cur_row["teamID"];
      }
      $selectHTML = create_team_selection($link_id, $team_id, "team_id", false);
      $result = mysqli_query($link_id, "SELECT * FROM teams WHERE teamID='$team_id'");
      if ($cur_row = mysqli_fetch_array($result))
      {
        $team_school = $cur_row["teamSchool"];
        $team_name = $cur_row["teamName"];
        $team_level = $cur_row["teamLevel"];
        $design_mass_KG = $cur_row["designMassKG"];
      }
      for ($trial_num = 1; $trial_num <= 2; $trial_num++)
      {
        $result = mysqli_query($link_id, "SELECT * FROM dist_acc WHERE teamID='$team_id' " . 
          "AND trialNum='$trial_num'");
        if ($cur_row = mysqli_fetch_array($result))
        {
          $zone1_bags[$trial_num] = $cur_row["zone1Bags"];
          $zone2_bags[$trial_num] = $cur_row["zone2Bags"];
          $zone3_bags[$trial_num] = $cur_row["zone3Bags"];
          $zone4_bags[$trial_num] = $cur_row["zone4Bags"];
          $zone5_bags[$trial_num] = $cur_row["zone5Bags"];
          $dist_acc_time_MS[$trial_num] = $cur_row["trialTimeMS"];
        }        
      }
      for ($trial_num = 1; $trial_num <= 2; $trial_num++)
      {
        $result = mysqli_query($link_id, "SELECT * FROM obj_reloc WHERE teamID='$team_id' " . 
          "AND trialNum='$trial_num'");
        if ($cur_row = mysqli_fetch_array($result))
        {
          $index_cards_status[$trial_num] = $cur_row["indexCardsStatus"];
          $masking_tape_status[$trial_num] = $cur_row["maskingTapeStatus"];
          $notebook_status[$trial_num] = $cur_row["notebookStatus"];
          $half_liter_bottle_status[$trial_num] = $cur_row["halfLiterBottleStatus"];
          $hacky_sack_status[$trial_num] = $cur_row["hackySackStatus"];
          $ruler_status[$trial_num] = $cur_row["rulerStatus"];
          $pencil_status[$trial_num] = $cur_row["pencilStatus"];
          $cd_status[$trial_num] = $cur_row["cdStatus"];
          $unident1_status[$trial_num] = $cur_row["unident1Status"];
          $unident2_status[$trial_num] = $cur_row["unident2Status"];
          $obj_reloc_time_MS[$trial_num] = $cur_row["taskTimeMS"];
        }
      }
      for ($trial_num = 1; $trial_num <= 2; $trial_num++)
      {
        $result = mysqli_query($link_id, "SELECT * FROM dexterity WHERE teamID='$team_id' " . 
          "AND trialNum='$trial_num'");
        if ($cur_row = mysqli_fetch_array($result))
        {
          $bolt1_points[$trial_num] = $cur_row["bolt1Points"];
          $bolt2_points[$trial_num] = $cur_row["bolt2Points"];
          $bolt3_points[$trial_num] = $cur_row["bolt3Points"];
          $dexterity_time_MS[$trial_num] = $cur_row["trialTimeMS"];
        }
      }
      for ($judge_num = 1; $judge_num <= 3; $judge_num++)
      {
        $result = mysqli_query($link_id, "SELECT * FROM tech_paper WHERE teamID='$team_id' " . 
          "AND judgeNum='$judge_num'");
        if ($cur_row = mysqli_fetch_array($result))
        {
          $discussion[$judge_num] = $cur_row["discussion"];
          $stem_concepts_analysis[$judge_num] = $cur_row["stemConceptsAnalysis"];
          $quality_thoroughness[$judge_num] = $cur_row["qualityThoroughness"];
          $conventions[$judge_num] = $cur_row["conventions"];
        }
      }
      for ($judge_num = 1; $judge_num <= 3; $judge_num++)
      {
        $result = mysqli_query($link_id, "SELECT * FROM poster WHERE teamID='$team_id' " . 
          "AND judgeNum='$judge_num'");
        if ($cur_row = mysqli_fetch_array($result))
        {
          $abstract[$judge_num] = $cur_row["abstract"];
          $design_and_features[$judge_num] = $cur_row["designAndFeatures"];
          $results_data_and_analysis[$judge_num] = $cur_row["resultsDataAndAnalysis"];
          $layout[$judge_num] = $cur_row["layout"];
        }
      }
      for ($judge_num = 1; $judge_num <= 3; $judge_num++)
      {
        $result = mysqli_query($link_id, "SELECT * FROM presentation WHERE teamID='$team_id' " . 
          "AND judgeNum='$judge_num'");
        if ($cur_row = mysqli_fetch_array($result))
        {
          $introduction[$judge_num] = $cur_row["introduction"];
          $stem_explanations[$judge_num] = $cur_row["stemExplanations"];
          $design_process[$judge_num] = $cur_row["designProcess"];
          $oral_visual[$judge_num] = $cur_row["oralVisual"];
        }
      }
    ?>
    <!--
    <form action="display_team_info.php" method="get">
      Team: <?= $selectHTML ?><br />
    </form>
    -->
    <h1><?= $team_school ?> <?= ucwords($team_level) ?> <?= $team_name ?></h1>
    <?= dist_acc_display($zone1_bags, $zone2_bags, $zone3_bags, $zone4_bags, $zone5_bags, $dist_acc_time_MS, $best_score) ?><br />
    <?= dexterity_display($bolt1_points, $bolt2_points, $bolt3_points, $dexterity_time_MS, $best_point_time_ratio) ?><br />
    <?= obj_reloc_display($index_cards_status, $masking_tape_status, $notebook_status, $half_liter_bottle_status, $hacky_sack_status,
          $ruler_status, $pencil_status, $cd_status, $unident1_status, $unident2_status, $obj_reloc_time_MS, $best_trial) ?><br />
    <div style="float: left">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</div>
    <?= design_efficiency_display($design_mass_KG, $best_score + $best_trial + $best_point_time_ratio, $design_efficiency_score) ?><br />
    <br /><?= tech_paper_display($discussion, $stem_concepts_analysis, $quality_thoroughness, $conventions, $paper_points) ?><br />
    <?= poster_display($abstract, $design_and_features, $results_data_and_analysis, $layout, $poster_points) ?><br />
    <?= presentation_display($introduction, $stem_explanations, $design_process, $oral_visual, $presentation_points) ?>
  </body>
</html>