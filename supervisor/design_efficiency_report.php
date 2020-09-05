<?php
  require "utilities.php";
  $team_level = $_GET["team_level"];
  $query = <<<QUERY
    
    SELECT max(designEfficiencyScore) FROM full_design_efficiency INTO @max;
    SELECT 
      fullTeamName, designMass, round(totalPerformanceScore, 2) AS totalPerformanceScore, 
      round(designEfficiencyScore, 2) AS designEfficiencyScore,
      round(50*(designEfficiencyScore/@max),2) AS designEfficiencyPoints 
    FROM full_design_efficiency WHERE teamLevel = '$team_level'
    ORDER BY designEfficiencyPoints DESC;
QUERY;
  open_database($link_id);
  set_up_views($link_id);
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
  print report_beginning("Design Efficiency");
  print "\n" . '    <table border="1">' ."\n";
  print <<<HEADERS
      <caption><h1>$team_level_ucase Design Efficiency Report</h1></caption>
      <tr valign="bottom">
        <th rowspan="2">Team</th>
        <th>Design<br />Mass</th>
        <th>Total<br />Performance<br />Score</th>
        <th>Design<br />Efficiency<br />Score</th>
        <th rowspan="2">Design<br />Efficiency<br />Points<br />/50</th>
		<th rowspan="2">Place</th>
      </tr>
      <tr valign="bottom">
        <th>KG</th>
        <th>Pts</th>
        <th>Pts/KG</th>
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