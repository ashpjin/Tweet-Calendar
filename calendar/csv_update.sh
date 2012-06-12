#!/bin/bash
line=$(tail -1 ~/public_html/viz/calendar/norm_data.csv)
date=${line:0:10}


php -f ~/public_html/viz/calendar/csv_update.php last_date=$date

