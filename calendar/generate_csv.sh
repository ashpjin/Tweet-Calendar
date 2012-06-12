#!/bin/bash
cat /dev/null > temp.csv

php csv_add_col.php
cat /dev/null > norm_data.csv

cp temp.csv norm_data.csv
cat /dev/null/ temp.csv
