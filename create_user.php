<?php

/**
 * Create User
 *
 * Process create user form and insert user into table.
 * Don't show page unless user is at least admin level 2.
 * Validate required fields. Process properly completed form.
 * Insert/create user.
 *
 * PHP version 7.2.5
 *
 * @category Main_App
 * @package  UnlockED
 * @author   UnlockedLabs <developers@unlockedlabs.org>
 * @license  https://www.gnu.org/licenses/gpl.html GPLv3
 * @link     http://unlockedlabs.org
 */

namespace unlockedlabs\unlocked;

// get database connection
require_once dirname(__FILE__).'/config/core.php';
require_once dirname(__FILE__).'/config/database.php';
require_once dirname(__FILE__).'/objects/users.php';


if ($_SESSION['current_site_settings']['gamification_enabled'] == 'true') {
    include_once dirname(__FILE__).'/objects/gamification.php';
}

// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate user object
$user = new User($db);


if ($_SESSION['current_site_settings']['gamification_enabled'] == 'true') {
    $game = new Game($db);
}


require_once 'layout_header.php';

// if the form was submitted
if ($_POST) {
    //var_dump($_POST);

    $error = '';

    try {
        // data validation
        if (empty($_POST['username'])) {
            echo "<div class='alert alert-danger ml-5 mr-5'>Username cannot be empty.</div>";
            $error = 1;
        }

        if (empty($_POST['password'])) {
            echo "<div class='alert alert-danger ml-5 mr-5'>Password cannot be empty.</div>";
            $error = 1;
        }

        if (empty($_POST['repeat_password'])) {
            echo "<div class='alert alert-danger ml-5 mr-5'>Repeat password cannot be empty.</div>";
            $error = 1;
        }

        if (!empty($_POST['email'])) {
            //ensure email is formed properly
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                echo "<div class='alert alert-danger ml-5 mr-5'>Email is not formed properly.</div>";
                $error = 1;
            }
        }

        if (!$error) {
            // set user property values
            $user->username = $_POST['username'];
            $user->password = $_POST['password'];
            $user->repeat_password = $_POST['repeat_password'];
            $user->email = $_POST['email'];
            $user->oid = $_POST['oid'];
            $user->access_id = 1;
            $user->admin_id = 1;

            //ensure username does not already exist
            if (!$user->checkUniqueSignUp()) {
                echo "<div class='alert alert-danger ml-5 mr-5'>User already exists, please choose another one.</div>";
                $error = 1;
            }

            //ensure $user->password and $user->repeat_password match
            if ($user->password != $user->repeat_password) {
                echo "<div class='alert alert-danger ml-5 mr-5'>Password and repeat password do not match.</div>";
                $error = 1;
            }

            if (!$error) {
                // create the user
                if ($user->create()) {
                    //setting $_GET['newusername'] will show a welcome alert to the new user in login.php
                    
                    if ($_SESSION['current_site_settings']['gamification_enabled'] == 'true') {
                        //adds user to gamification table
                        $game->id = $user->id;
                        $game->username = $user->username;
                        $game->coins = "0";
                        $game->coin_balance = "0";
                        $game->user_level = "1";
                        $game->user_status = "NEW USER";
                        $game->logins = "0";
                        $game->addUserGame();
                    }

                    $_GET['newusername'] = $user->username;
                    include 'login.php';
                    // empty post array
                    $_POST = array();
                } else {
                    //setting  $_GET['newusername'] to null allows user_html.php to be included below.
                    $_GET['newusername'] = '';
                    echo "<div class=\"alert alert-danger ml-5 mr-5 alert-dismissable\">";
                        echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                        echo "Unable to create user.";
                    echo "</div>";
                }
            }
        }
    } catch (\PDOException $exception) {
        die('ERROR: ' . $exception->getMessage());
    }
}

if (empty($_GET['newusername'])) {
    include_once 'user_html.php';
}
require_once 'layout_footer.php';
?>
