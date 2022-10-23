<?php
// This loader file will set and prepare the classes for use and include required composer classes and any additional classes specified

require $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php'; // Composer autoload
require $_SERVER['DOCUMENT_ROOT'].'/includes/errors.php'; // More useful errors
require $_SERVER['DOCUMENT_ROOT'].'/includes/database.php'; // Main database class
require $_SERVER['DOCUMENT_ROOT'].'/includes/entity.php'; // Table Entities class

$db = new Database;

// Models functions for set tables
require $_SERVER['DOCUMENT_ROOT'].'/model/settings.php';
require $_SERVER['DOCUMENT_ROOT'].'/model/users.php';
require $_SERVER['DOCUMENT_ROOT'].'/model/courses.php';
require $_SERVER['DOCUMENT_ROOT'].'/model/enrolments.php';

// Init the tables
$_settings = new Settings;
$_settings->setTablename('settings');

$_users = new Users;
$_users->setTablename('users');

$_courses = new Courses;
$_courses->setTablename('courses');

$_enrolments = new Enrolments;
$_enrolments->setTablename('enrolments');