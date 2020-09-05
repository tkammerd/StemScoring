<?php
  require "utilities.php";
  $team_level = $_GET["team_level"];
  $query = <<<QUERY
    SELECT 
      fullTeamName AS fullTeamName,
      round(distAccTaskPoints, 2) AS distAccTaskPoints,
      round(objRelocTrialPoints, 2) AS objRelocTrialPoints,
      round(dexTrialPoints, 2) AS dexTrialPoints,
      round(performancePoints, 2) AS performancePoints,
      designEfficiencyPoints,
      techPaperPoints,
      presentationPoints,
      posterDisplayPoints,
      round(performancePoints + designEfficiencyPoints + techPaperPoints + 
        presentationPoints + posterDisplayPoints, 2) AS overallPoints
    FROM full_overall WHERE teamLevel = '$team_level'
    ORDER BY overallPoints DESC;
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
  print report_beginning("Overall");
  print "\n" . '    <table border="1">' ."\n";
  if ($team_level == 'high school')
  {
    $opt_col_1 = "<th>Dexterity<br />Points</th>";
    $opt_col_2 = "<th>/50</th>";
    $opt_col_3 = "<th>/150</th>";
  }
  else
  {
    $opt_col_1 = $opt_col_2 = "";
    $opt_col_3 = "<th>/100</th>";
  }
  print <<<HEADERS
      <caption><h1>$team_level_ucase Overall Report</h1></caption>
      <tr valign="bottom">
        <th rowspan="2">Team</th>
        <th>Distance<br />Accuracy<br />Points</th>
        <th>Object<br />Relocation<br />Points</th>
        $opt_col_1
        <th>Performance<br />Points</th>
        <th>Design<br />Efficiency<br />Points</th>
        <th>Technical<br />Paper<br />Points</th>
        <th>Presentation<br />Points</th>
        <th>Poster /<br />Display<br />Points</th>
        <th>Overall<br />Points</th>
		<th rowspan="2">Place</th>
      </tr>
      <tr valign="bottom">
        <th>/50</th><th>/50</th>$opt_col_2$opt_col_3
        <th>/50</th><th>/100</th><th>/75</th><th>/75</th>
        <th>/450</th>
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
      if (!($field_num == 3 && $team_level == 'middle school'))
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