<html>
  <head>
    <title>Erase Entire Database</title>
  </head>
  <body>
    <?php
      require "utilities.php";
      $answer = $_GET["answer"];
      open_database($link_id);
      if ($answer == "YES" || $answer == "Y")
      {
        $query = <<<QUERY
        drop view if exists full_design_efficiency;
        drop view if exists full_dexterity;
        drop view if exists full_obj_reloc;
        drop view if exists full_overall;
        drop view if exists full_poster;
        drop view if exists full_presentation;
        drop view if exists full_tech_paper;
        truncate table dexterity;
        truncate table dist_acc;
        truncate table obj_reloc;
        truncate table poster;
        truncate table presentation;
        truncate table tech_paper;
        drop table if exists dexPoints;
        drop table if exists distAccPoints;
        drop table if exists objRelocPoints;
        drop table if exists posterPoints;
        drop table if exists presentationPoints;
        drop table if exists techPaperPoints;
        drop table if exists designEfficiencyPoints;
        delete from teams;
QUERY;
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
        print "<h1>Prosthetic Arm Competition database emptied!</h1>\n";
      }
      else
      {
        print "<h1>Emptying of Prosthetic Arm Competition database cancelled!</h1>\n";
      }
    ?>
    <a href="start.php">Return to Start</a>
  </body>
</html>