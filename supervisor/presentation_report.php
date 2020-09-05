<?php
  require "utilities.php";
  $team_level = $_GET["team_level"];
  $query = <<<QUERY
    SELECT       
      fullTeamName, judge1Introduction, judge1StemExplanations, 
      judge1DesignProcess, judge1OralVisual, judge1, 
      judge2Introduction, judge2StemExplanations, judge2DesignProcess, 
      judge2OralVisual, judge2, 
      judge3Introduction, judge3StemExplanations, judge3DesignProcess, 
      judge3OralVisual, judge3, numJudges, 
      round(presentationPoints, 2) AS presentationPoints
    FROM full_presentation WHERE teamLevel = '$team_level'
    ORDER BY presentationPoints DESC;
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
  print report_beginning("Presentation");
  print "\n" . '    <table border="1">' ."\n";
  print <<<HEADERS
      <caption><h1>$team_level_ucase Presentation Report</h1></caption>
      <tr valign="bottom">
        <th rowspan="3">Team</th>
        <th colspan="5">Judge 1:</th>
        <th colspan="5">Judge 2:</th>
        <th colspan="5">Judge 3:</th>
        <th rowspan="3">#<br />Judges</th>
        <th rowspan="2">Presentation<br />Points</th>
		<th rowspan="3">Place</th>
      </tr>
      <tr valign="bottom">
        <th><div class="vertical-text">Introduction</div></th>
        <th><div class="vertical-text">STEM Explanations</div></th>
        <th><div class="vertical-text">Design/Process</div></th>
        <th><div class="vertical-text">Oral & Visual Overall</div></th>
        <th><div class="vertical-text">Judge 1</div></th>
        <th><div class="vertical-text">Introduction</div></th>
        <th><div class="vertical-text">STEM Explanations</div></th>
        <th><div class="vertical-text">Design/ Process</div></th>
        <th><div class="vertical-text">Oral & Visual Overall</div></th>
        <th><div class="vertical-text">Judge 2</div></th>
        <th><div class="vertical-text">Introduction</div></th>
        <th><div class="vertical-text">STEM Explanations</div></th>
        <th><div class="vertical-text">Design/ Process</div></th>
        <th><div class="vertical-text">Oral & Visual Overall</div></th>
        <th><div class="vertical-text">Judge 3</div></th>
      </tr>
      <tr valign="bottom">
        <th>10</th><th>25</th><th>25</th><th>15</th><th>75</th>
        <th>10</th><th>25</th><th>25</th><th>15</th><th>75</th>
        <th>10</th><th>25</th><th>25</th><th>15</th><th>75</th>
        <th>75</th>
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