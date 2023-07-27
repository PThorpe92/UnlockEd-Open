<?php

/**
 * Create a new user and set admin level
 *
 * PHP version 7.2.5
 *
 * @category  Main_App
 * @package   UnlockED
 * @author    UnlockedLabs <developers@unlockedlabs.org>
 * @copyright 2021 UnlockedLabs.org <http://unlockedlabs.org/>
 * @license   https://www.gnu.org/licenses/gpl.html GPLv3
 * @link      http://unlockedlabs.org
 */

namespace unlockedlabs\unlocked;

require_once dirname(__FILE__) . '/../admin-session-validation.php';
require_once dirname(__FILE__) . '/../../config/core.php';
require_once dirname(__FILE__) . '/../../config/database.php';
require_once dirname(__FILE__) . '/../../objects/users.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

require_once dirname(__FILE__) . '/../../objects/gamification.php';
$game = new Game($db);

require_once dirname(__FILE__) . '/../../objects/user_preferences.php';
$userPreference = new userPreference($db);

require_once dirname(__FILE__) . '/../../objects/user_tasks.php';
$userTask = new userTasks($db);

// if the form was submitted
if ($_POST) {

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

        if (empty($_POST['admin_id'])) {
            echo "<div class='alert alert-danger ml-5 mr-5'>Admin level cannot be empty.</div>";
            $error = 1;
        }

        if (!empty($_POST['admin_id']) && ($_POST['admin_id'] > $_SESSION['admin_num'])) {
            echo "<div class='alert alert-danger ml-5 mr-5'>Invalid Admin level.</div>";
            $error = 1;
        }

        if (!$error) {
            // set user property values
            $user->username = $_POST['username'];
            $user->password = $_POST['password'];
            $user->repeat_password = $_POST['repeat_password'];
            $user->email = $_POST['email'];
            $user->oid = $_POST['oid'];
            $user->admin_id = $_POST['admin_id'];
            // @TODO figure out what we are going to set access_id to in this context
            $user->access_id = 1;

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
                    
                        //adds user to gamification table
                        $game->id = $user->id;
                        $game->username = $user->username;
                        $game->coins = "0";
                        $game->coin_balance = "0";
                        $game->user_level = "1";
                        $game->user_status = "NEW USER";
                        $game->addUserGame();

                        //adds user to user_preferences table
                        $userPreference->id = $user->id;
                        $userPreference->username = $user->username;
                        $userPreference->banner = "1";
                        $userPreference->night_mode = "light";
                        $userPreference->user_color = "#8BC34A";
                        $userPreference->dashboard_color = "#37474F";
                        $userPreference->sidebarToggle = "1";
                        $userPreference->addUserPreferences();

                        //adds sample task list to dashboard
                        $userTask->id = $user->id;
                        $userTask->task_id = "1";
                        $userTask->task = "My first task";
                        $userTask->checked = 0;
                        $userTask->addUserTask();

                    // empty post array
                    $_POST = array();

                    echo '<script>ul.successSwalAlert("User created", "");</script>';
                    echo '<script>ul.ajax_content_area("user_management.php", "#content-area-div");</script>';

                } else {
                    echo "<div class='alert alert-danger ml-5 mr-5 alert-dismissable'>";
                        echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
                        echo "Unable to create user.";
                    echo "</div>";
                }
            }
        }
    } catch (\PDOException $exception) {
        die('ERROR: ' . $exception->getMessage());
    }

    die();
}

?>

<div class="card" id="create-user-card">
    <div class="card-header"></div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <!-- Registration form -->
                <form method="post" action="users/create_user_admin.php" class="flex-fill animated bounceInLeft" id="create-user-form">
                    <div class="text-center mb-3">
                        <i class="icon-plus3 icon-2x text-success border-success border-3 rounded-round p-3 mb-3 mt-1"></i>
                        <h3 class="mb-0">Create account</h3>
                        <span class="d-block text-warning">User uniqueness is based on: <br>Organizational User Identifier if provided, email if it is not and username if neither of those are provided.</span>
                        <span class="d-block text-muted">Starred fields (*) are required.</span>
                    </div>

                    <div class="form-group form-group-feedback form-group-feedback-right">
                        <input type="text" name="username" class="form-control" value="<?php if (isset($user->username)) echo $user->username; ?>" placeholder="User Name" required>
                        <div class="form-control-feedback">
                            <i class="icon-user-plus text-muted"></i>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group form-group-feedback form-group-feedback-right">
                                <input type="password" name="password" class="form-control" value='' placeholder="Create password" required>
                                <div class="form-control-feedback">
                                    <i class="icon-user-lock text-muted"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group form-group-feedback form-group-feedback-right">
                                <input type="password" name="repeat_password" class="form-control" value='' placeholder="Repeat password" required>
                                <div class="form-control-feedback">
                                    <i class="icon-user-lock text-muted"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group form-group-feedback form-group-feedback-right">
                                <input type="email" name="email" class="form-control" value="<?php if (isset($user->email)) echo $user->email; ?>" placeholder="Your email (optional)">
                                <div class="form-control-feedback">
                                    <i class="icon-mention text-muted"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-feedback form-group-feedback-right">
                                <input type="text" name="oid" class="form-control" value="<?php if (isset($user->OID)) echo $user->OID; ?>" placeholder="Organizational User Identifier (Optional)">
                                <div class="form-control-feedback">
                                    <i class="icon-vcard text-muted"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <select class="form-control" name='admin_id' id="adminFormControlSelect1" required>
                            <?php
                            $admin_levels = $user->readAdminLevels();
                            while ($row = $admin_levels->fetch(\PDO::FETCH_ASSOC)) {
                                if ($_SESSION['admin_num'] < $row['admin_num']) {
                                    break;
                                }
                                echo '<option value=' . $row['admin_num'] . '>' . $row['admin_name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <button type="submit" class="btn bg-teal-400 btn-labeled btn-labeled-right"><b><i class="icon-plus3"></i></b> Create</button>
                    <a href="#" class="btn bg-danger-400 btn-labeled btn-labeled-right" onclick="$('#user-management-div').html('');"><b><i class="icon-minus3"></i></b> Cancel</a>
                </form> <!-- /registration form -->
            </div> <!--/ col-12 -->
        </div> <!--/ row -->
    </div> <!-- /card body -->
</div> <!-- /card -->

<script>
/* 
Immediately invoked function (IIFE).
Executes as soon as js sees it.
Runs in it own scope.
*/

(function(){

$('#create-user-form').on('submit', function(e) {

    //prevent form submission
    e.preventDefault();

    var error = '';

    if (!e.target.username.value.trim()) {
        error += 'Must Supply User Name. ';
    }

    if (!e.target.password.value.trim()) {
        error += 'Must Supply Password. ';
    }

    if (!e.target.repeat_password.value.trim()) {
        error += 'Must Supply Repeat Password. ';
    }

    if (!e.target.admin_id.value.trim()) {
        error += 'Must Select Admin Level Password. ';
    }

    if (e.target.password.value.trim() != e.target.repeat_password.value.trim()) {
        error += 'Password and repeat password do not match. ';
    }

    if (error) {
        ul.errorSwalAlert("Info Warning!", error);
        return false;
    }

    var $content = $("#create-user-card").find('.card-header');
    var url = e.target.action;
    var serializedForm = $(this).serialize();

    $.ajax({
        type: 'POST',
        url: url,
        data: serializedForm,
        timeout: 30000,
        success: function(data) {
            $content.html(data);
        },
        error: function(data) {
            $content.html(data.responseText);
        },
        fail : function(data) {
            $content.html(data.responseText);
        }
    });

});
}) ();
</script>