<html>
  <head>
    <title>Insert/Update a Distance/Accuracy Score</title>
  </head>
  <body>
    <?php
      require "utilities.php";
      $team_id = $_GET["team_id"];
      $trial_num = $_GET["trial_num"];
      $zone1_bags = $_GET["zone1_bags"];
      $zone2_bags = $_GET["zone2_bags"];
      $zone3_bags = $_GET["zone3_bags"];
      $zone4_bags = $_GET["zone4_bags"];
      $zone5_bags = $_GET["zone5_bags"];
      $trial_time = $_GET["trial_time"];
      open_database($link_id);
      if ($team_id && $team_id != "NEW")
      {
        $result = mysqli_query($link_id, "SELECT * FROM dist_acc WHERE teamID='$team_id' " . 
          "AND trialNum='$trial_num'");
        if (mysqli_num_rows($result) == 0)
        {
          $query = "INSERT INTO dist_acc " . 
            "(teamID, trialNum, zone1Bags, zone2Bags, zone3Bags, zone4Bags, " .
              "zone5Bags, trialTimeMS) " .
            "VALUES ('$team_id', '$trial_num', '$zone1_bags', '$zone2_bags', " .
              "'$zone3_bags', '$zone4_bags', '$zone5_bags', '$trial_time' * 1000)";
          if (!($query_status = mysqli_query( $link_id, $query)))
            die("Insert failed: $query\n");
        }
        else
        {
          $query = "UPDATE dist_acc " . 
            "SET zone1Bags='$zone1_bags', zone2Bags='$zone2_bags', " .
              "zone3Bags='$zone3_bags', zone4Bags='$zone4_bags', " .
              "zone5Bags='$zone5_bags', trialTimeMS='$trial_time' * 1000 " .
            "WHERE teamID='$team_id' AND trialNum='$trial_num'";
          $rows_changed = mysqli_query( $link_id, $query);
        }
      }
      $team_id = "";
    ?>
    <script type="text/javascript">
      window.open("simple_get_dist_acc.php?team_id=<?= $team_id ?>&trial_num=<?= $trial_num ?>", "_self");
    </script>
  </body>
</html>