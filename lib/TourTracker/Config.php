<?php
namespace TourTracker;
class Config{

    //MySQL or MariaDB Configuration
    protected $dbHost = "localhost";
    protected $dbUser = "root";
    protected $dbPass = "";
    protected $dbName = "tour_tracker";

    //Application settings
    const SYNC_LIMIT = 24; //Minimum number of hours before a tour can be synced again
    const ALERT_MAX_AGE = 28; //Days before change alerts disappear unless dismissed.
    const ALERT_AVAILABILITY_THRESHOLD = 7; // Show alerts when spaces fall below this number.


    // Optionally save data extracted from websites to a JSON file outside the database.
    const EXTRACTOR_JSON_BACKUP = true; // set true/false to enable backups
    const EXTRACTOR_JSON_BACKUP_DIR = PROJECT_DIR."../tourtracker-extra/.extract_backup/";

}
