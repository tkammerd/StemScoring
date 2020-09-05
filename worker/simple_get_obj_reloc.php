<html>
  <head>
    <title>Get Object Relocation Scores</title>
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
        $task_time = $index_cards_status = $masking_tape_status = $notebook_status = 
          $half_liter_bottle_status = $hacky_sack_status = $ruler_status = 
          $pencil_status = $cd_status = $unident1_status = $unident2_status = 0;
      }
      else
      {
        $trial_num = "1";
        $selectHTML = create_team_selection($link_id, "", "team_id", true, false);        
      }
      $result = mysqli_query($link_id, "SELECT * FROM obj_reloc WHERE teamID='$team_id' " . 
        "AND trialNum='$trial_num'");
      if ($cur_row = mysqli_fetch_array($result))
      {
        $task_time = $cur_row["taskTimeMS"] / 1000;
        $index_cards_status = $cur_row["indexCardsStatus"];
        $masking_tape_status = $cur_row["maskingTapeStatus"];
        $notebook_status = $cur_row["notebookStatus"];
        $half_liter_bottle_status = $cur_row["halfLiterBottleStatus"];
        $hacky_sack_status = $cur_row["hackySackStatus"];
        $ruler_status = $cur_row["rulerStatus"];
        $pencil_status = $cur_row["pencilStatus"];
        $cd_status = $cur_row["cdStatus"];
        $unident1_status = $cur_row["unident1Status"];
        $unident2_status = $cur_row["unident2Status"];
      }
      $chk_attrib = 'checked="checked"';
    ?>
    <h3>Update Object Relocation Scores</h3>
    <form action="simple_get_obj_reloc.php" method="get">
      Team: <?= $selectHTML ?><br />
      Trial Number: 
        <select name="trial_num" onchange="this.form.submit()">
          <option <?= ($trial_num == 1)?'selected="selected"':'' ?>>1</option>
          <option <?= ($trial_num == 2)?'selected="selected"':'' ?>>2</option>
        </select>
    </form>
    <form action="simple_update_obj_reloc.php" method="get">
      <input type="hidden" name="team_id" value="<?= $team_id ?>" />
      <input type="hidden" name="trial_num" value="<?= $trial_num ?>" />
      <table>
        <tr>
          <th>Item</th>
          <th>Not<br />Attempted</th>
          <th>Suc-<br />cessful<br />Attempt</th>
          <th>Unsuc-<br />cessful<br />Attempt</th>
        </tr>
        <tr>
          <td>Index Cards</td>
          <td style="text-align:center"><input type="radio" name="index_cards_status" value="0" <?= $index_cards_status==0?$chk_attrib:'' ?> /></td>
          <td style="text-align:center"><input type="radio" name="index_cards_status" value="5" <?= $index_cards_status==5?$chk_attrib:'' ?> /></td>
          <td style="text-align:center"><input type="radio" name="index_cards_status" value="-5" <?= $index_cards_status==-5?$chk_attrib:'' ?> /></td>
        </tr>
        <tr>
          <td>Masking Tape</td>
          <td style="text-align:center"><input type="radio" name="masking_tape_status" value="0" <?= $masking_tape_status==0?$chk_attrib:'' ?> /></td>
          <td style="text-align:center"><input type="radio" name="masking_tape_status" value="5" <?= $masking_tape_status==5?$chk_attrib:'' ?> /></td>
          <td style="text-align:center"><input type="radio" name="masking_tape_status" value="-5" <?= $masking_tape_status==-5?$chk_attrib:'' ?> /></td>
        </tr>
        <tr>
          <td>Notebook</td>
          <td style="text-align:center"><input type="radio" name="notebook_status" value="0" <?= $notebook_status==0?$chk_attrib:'' ?> /></td>
          <td style="text-align:center"><input type="radio" name="notebook_status" value="10" <?= $notebook_status==10?$chk_attrib:'' ?> /></td>
          <td style="text-align:center"><input type="radio" name="notebook_status" value="-10" <?= $notebook_status==-10?$chk_attrib:'' ?> /></td>
        </tr>
        <tr>
          <td>0.5 Liter Bottle</td>
          <td style="text-align:center"><input type="radio" name="half_liter_bottle_status" value="0" <?= $half_liter_bottle_status==0?$chk_attrib:'' ?> /></td>
          <td style="text-align:center"><input type="radio" name="half_liter_bottle_status" value="10" <?= $half_liter_bottle_status==10?$chk_attrib:'' ?> /></td>
          <td style="text-align:center"><input type="radio" name="half_liter_bottle_status" value="-10" <?= $half_liter_bottle_status==-10?$chk_attrib:'' ?> /></td>
        </tr>
        <tr>
          <td>Hacky Sack</td>
          <td style="text-align:center"><input type="radio" name="hacky_sack_status" value="0" <?= $hacky_sack_status==0?$chk_attrib:'' ?> /></td>
          <td style="text-align:center"><input type="radio" name="hacky_sack_status" value="15" <?= $hacky_sack_status==15?$chk_attrib:'' ?> /></td>
          <td style="text-align:center"><input type="radio" name="hacky_sack_status" value="-15" <?= $hacky_sack_status==-15?$chk_attrib:'' ?> /></td>
        </tr>
        <tr>
          <td>Ruler</td>
          <td style="text-align:center"><input type="radio" name="ruler_status" value="0" <?= $ruler_status==0?$chk_attrib:'' ?> /></td>
          <td style="text-align:center"><input type="radio" name="ruler_status" value="15" <?= $ruler_status==15?$chk_attrib:'' ?> /></td>
          <td style="text-align:center"><input type="radio" name="ruler_status" value="-15" <?= $ruler_status==-15?$chk_attrib:'' ?> /></td>
        </tr>
        <tr>
          <td>Pencil</td>
          <td style="text-align:center"><input type="radio" name="pencil_status" value="0" <?= $pencil_status==0?$chk_attrib:'' ?> /></td>
          <td style="text-align:center"><input type="radio" name="pencil_status" value="20" <?= $pencil_status==20?$chk_attrib:'' ?> /></td>
          <td style="text-align:center"><input type="radio" name="pencil_status" value="-20" <?= $pencil_status==-20?$chk_attrib:'' ?> /></td>
        </tr>
        <tr>
          <td>CD</td>
          <td style="text-align:center"><input type="radio" name="cd_status" value="0" <?= $cd_status==0?$chk_attrib:'' ?> /></td>
          <td style="text-align:center"><input type="radio" name="cd_status" value="20" <?= $cd_status==20?$chk_attrib:'' ?> /></td>
          <td style="text-align:center"><input type="radio" name="cd_status" value="-20" <?= $cd_status==-20?$chk_attrib:'' ?> /></td>
        </tr>
        <tr>
          <td>Unidentified #1</td>
          <td style="text-align:center"><input type="radio" name="unident1_status" value="0" <?= $unident1_status==0?$chk_attrib:'' ?>  /></td>
          <td style="text-align:center"><input type="radio" name="unident1_status" value="25" <?= $unident1_status==25?$chk_attrib:'' ?> /></td>
          <td style="text-align:center"><input type="radio" name="unident1_status" value="-25" <?= $unident1_status==-25?$chk_attrib:'' ?> /></td>
        </tr>
        <tr>
          <td>Unidentified #2</td>
          <td style="text-align:center"><input type="radio" name="unident2_status" value="0" <?= $unident2_status==0?$chk_attrib:'' ?> /></td>
          <td style="text-align:center"><input type="radio" name="unident2_status" value="25" <?= $unident2_status==25?$chk_attrib:'' ?> /></td>
          <td style="text-align:center"><input type="radio" name="unident2_status" value="-25" <?= $unident2_status==-25?$chk_attrib:'' ?> /></td>
        </tr>
        <tr>
          <td>Task Time:</td>
          <td colspan="3"><input class="shortblank" type="text" name="task_time" value="<?= $task_time ?>" /></td>
        </tr>
        <tr>
          <td colspan="2"><input type="submit" value="Accept Object Relocation Scores" /></td>
          <td colspan="2"><input type="reset" value="Reset Object Relocation Scores" />
        </tr>
      </table>
    </form>
  </body>
</html>