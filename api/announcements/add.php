<?php
// copy headers from classes/create.php
// look at classes/create.php to copy code

// require database class from config/Database.php
// require Announcement class from modes/Announcement.php

// connect db and initialzie class: $db = new Database()
// initialize Announcement class and pass $db: $announcement = new Announcement($db)

// try catch block
  // in try
    // require protect
    // get posted data
    // bind data: example $announcement->title = $data->title
    // call add function: $announcement->add()

    // echo json_encode message example announcement added

// catch block
  //echo message failed to add..

// test in postman http://localhost/classroom-api/api/announcements/add.php
// in body add data example {"title": "announcement title..", "body": "123123.."}
?>