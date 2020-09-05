<?php
  require "utilities.php";
  $team_level = $_GET["team_level"];
  $query = <<<QUERY
    SELECT max(bestPointTimeRatio) FROM full_dexterity 
      WHERE teamLevel = '$team_level' INTO @max;
    SELECT fullTeamName, trial1Bolt1Points, trial1Bolt2Points, 
      trial1Bolt3Points, trial1TotalPoints, trial1TrialTime, 
      trial1PointTimeRatio, trial2Bolt1Points, trial2Bolt2Points, 
      trial2Bolt3Points, trial2TotalPoints, trial2TrialTime, 
      trial2PointTimeRatio, 
      round(bestPointTimeRatio, 2) AS bestPointTimeRatio, 
      if(bestPointTimeRatio/@max, round(50*(bestPointTimeRatio/@max),2), 0) AS trialPoints 
    FROM full_dexterity WHERE teamLevel = '$team_level' 
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
  print report_beginning("Dexterity");
  print "\n" . '    <table border="1">' ."\n";
  print <<<HEADERS
      <caption><h1>$team_level_ucase Dexterity Report</h1></caption>
      <tr valign="bottom">
        <th rowspan="2">Team</th>
        <th colspan="6">Trial 1</th>
        <th colspan="6">Trial 2</th>
        <th rowspan="2">Best<br />Point-Time<br />Ratio</th>
        <th rowspan="2">Task<br />Points<br />/50</th>
		<th rowspan="2">Place</th>
      </tr>
      <tr valign="bottom">
        <th>Bolt 1<br />points</th><th>Bolt 2<br />points</th><th>Bolt 3<br />points</th>
        <th>Total<br />points</th>
        <th>Trial Time<br />seconds</th>
        <th>Point-Time<br />Ratio</th>
        <th>Bolt 1<br />points</th><th>Bolt 2<br />points</th><th>Bolt 3<br />points</th>
        <th>Total<br />points</th>
        <th>Trial Time<br />seconds</th>
        <th>Point-Time<br />Ratio</th>
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