<?php
  require "utilities.php";
  $team_level = $_GET["team_level"];
  $query = <<<QUERY
    SELECT max(bestScore) FROM full_obj_reloc 
      WHERE teamLevel = '$team_level' INTO @max;
    SELECT
      fullTeamName, trial1IndexCardsStatus, trial1MaskingTapeStatus,
      trial1NotebookStatus, trial1HalfLiterBottleStatus, trial1HackySackStatus,
      trial1RulerStatus, trial1PencilStatus, trial1CdStatus, trial1Unident1Status,
      trial1Unident2Status, trial1Subtotal, trial1TaskTime, trial1Score,
      trial2IndexCardsStatus, trial2MaskingTapeStatus,
      trial2NotebookStatus, trial2HalfLiterBottleStatus, trial2HackySackStatus,
      trial2RulerStatus, trial2PencilStatus, trial2CdStatus, trial2Unident1Status,
      trial2Unident2Status, trial2Subtotal, trial2TaskTime, trial2Score, 
      round(bestScore, 2) AS bestScore, 
      if (bestScore/@max, round(50*(bestScore/@max),2), 0) AS trialPoints 
    FROM full_obj_reloc WHERE teamLevel = '$team_level'
    ORDER BY trialPoints DESC;
QUERY;
  open_database($link_id);
  if(!mysqli_query($link_id, "DESCRIBE `full_overall`")) 
  {
    set_up_views($link_id);
  }
  if (mysqli_multi_query($link_id, $query)) 
  {
    do 
    {
        if ($result = mysqli_store_result($link_id)) 
        {
          if (mysqli_more_results($link_id))
            mysqli_free_result($result);
        }
    } 
    while (mysqli_next_result($link_id));
  }
  $team_level_ucase = ucwords($team_level);
  print report_beginning("Object Relocation");
  print "\n" . '    <table border="1">' ."\n";
  print <<<HEADERS
      <caption><h1>$team_level_ucase Object Relocation Report</h1></caption>
      <tr valign="bottom">
        <th rowspan="2">Team</th>
        <th colspan="13">Trial 1</th>
        <th colspan="13">Trial 2</th>
        <th rowspan="2">Best<br />Trial</th>
        <th rowspan="2">Task<br />Points<br />/50</th>
		<th rowspan="2">Place</th>
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
HEADERS;
  $natPlace = 0;
  $lastEventScore = 0;
  $place = 1;
  while ($cur_row = mysqli_fetch_array($result))
  {
    print "\n      <tr>\n";
    print '        <td class="vert-headers">' . ucwords($cur_row[0]) . "</td>\n";
    for ($field_num = 1; $field_num < count($cur_row) / 2; $field_num++)
    {
      print "        <td>$cur_row[$field_num]</td>\n";
    }
	$natPlace = $natPlace + 1;
	if ($cur_row[$field_num - 1] != $lastEventScore)
		$place = $natPlace;
	$lastEventScore = $cur_row[$field_num - 1];
	print "        <td>$place</td>\n";
    print "      </tr>\n";    
  }
  print "    </table>\n";
  print "  </body>\n";
  print "</html>"
?>