<?php
  require "utilities.php";
  $team_level = $_GET["team_level"];
  $query = <<<QUERY
    SELECT       
      fullTeamName, judge1Discussion, judge1StemConceptsAnalysis, 
      judge1QualityThoroughness, judge1Conventions, judge1, 
      judge2Discussion, judge2StemConceptsAnalysis, judge2QualityThoroughness,
      judge2Conventions, judge2, 
      judge3Discussion, judge3StemConceptsAnalysis, judge3QualityThoroughness,
      judge3Conventions, judge3, numJudges, 
      round(paperPoints, 2) AS paperPoints
    FROM full_tech_paper  WHERE teamLevel = '$team_level'
    ORDER BY paperPoints DESC;
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
  print report_beginning("Technical Paper");
  print "\n" . '    <table border="1">' ."\n";
  print <<<HEADERS
      <caption><h1>$team_level_ucase Technical Paper Report</h1></caption>
      <tr valign="bottom">
        <th rowspan="3">Team</th>
        <th colspan="5">Judge 1:</th>
        <th colspan="5">Judge 2:</th>
        <th colspan="5">Judge 3:</th>
        <th rowspan="3">#<br />Judges</th>
        <th rowspan="2">Paper<br />Points</th>
		<th rowspan="3">Place</th>
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