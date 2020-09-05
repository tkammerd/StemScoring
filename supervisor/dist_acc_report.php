<?php
  require "utilities.php";
  $team_level = $_GET["team_level"];
  $query = <<<QUERY
    SELECT max(bestScore) FROM full_dist_acc 
      WHERE teamLevel = '$team_level' INTO @max;
    SELECT 
      fullTeamName, trial1zone1Bags, trial1zone2Bags, trial1zone3Bags, 
      trial1zone4Bags, trial1zone5Bags, trial1Subtotal, trial1TrialTime, 
      trial1Score, trial2zone1Bags, trial2zone2Bags, trial2zone3Bags, 
      trial2zone4Bags, trial2zone5Bags, trial2Subtotal, trial2TrialTime, 
      trial2Score, 
      round(bestScore, 2) AS bestScore,
      if (bestScore/@max, round(50*(bestScore/@max),2), 0) AS taskPoints 
    FROM full_dist_acc WHERE teamLevel = '$team_level'
    ORDER BY taskPoints DESC;
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
  print report_beginning("Distance Accuracy");
  print "\n" . '    <table border="1">' ."\n";
  print <<<HEADERS
      <caption><h1>$team_level_ucase Distance Accuracy Report</h1></caption>
      <tr valign="bottom">
        <th rowspan="3">Team</th>
        <th colspan="8">Trial 1</th>
        <th colspan="8">Trial 2</th>
        <th rowspan="3">Best<br />Score</th>
        <th rowspan="3">Task<br />Points<br />/50</th>
		<th rowspan="3">Place</th>
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