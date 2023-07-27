<?php

/**
 * Delete a user 
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
$user_id = isset($_GET['id']) ? $_GET['id']: die('ERROR: missing User Id');
$user->id = $user_id;
$user_data = $user->readOne();


/* 
@TODO we need to further develop this file.
It affects cohort and gamification.
*/

// if the form was submitted
if ($_POST) {

    $error = '';

    try {
        // data validation
        if (empty($_POST['id'])) {
            echo "<div class='alert alert-danger ml-5 mr-5'>User ID not found.</div>";
            $error = 1;
        }

        if ($_SESSION['user_id'] == $_POST['id']) {
            echo "<div class='alert alert-danger ml-5 mr-5'>You are not allowed to delete yourself.</div>";
            $error = 1;
        }

        if (!$error) {
            // set user property values
            $user->id = $_POST['id'];

            if (!$error) {
                // delete the user
                if ($user->delete()) {

                    // empty post array
                    $_POST = array();

                    //call ul.successSwalAlert first
                    echo '<script>ul.successSwalAlert("User deleted", "");</script>';
                    echo '<script>ul.ajax_content_area("user_management.php", "#content-area-div");</script>';

                } else {
                    echo "<div class='alert alert-danger ml-5 mr-5'>Unable to delete user.</div>";

                }
            }
        }
    } catch (\PDOException $exception) {
        die('ERROR: ' . $exception->getMessage());
    }

    die();
}

?>

<div class="card" id="delete-user-card">
    <div class="card-header"></div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <!-- Registration form -->
                <form method="post" action="users/delete_user_admin.php?id=<?php if(isset($user_data['id'])) echo $user_data['id']; ?>" class="flex-fill animated bounceInLeft" id="delete-user-form">
                    <div class="text-center mb-3">
                        <i class="icon-minus3 icon-2x text-danger border-danger border-3 rounded-round p-3 mb-3 mt-1"></i>
                        <h3 class="mb-0">Delete User</h3>
                        <span class="d-block text-warning">Are you sure you want to delete this user?</span>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group form-group-feedback form-group-feedback-right">
                                <input type="text" name="username" class="form-control" value="<?php if(isset($user_data['username'])) echo $user_data['username']; ?>" disabled>
                                <div class="form-control-feedback">
                                    <span class="text-muted">Username</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group form-group-feedback form-group-feedback-right">
                            <input type="email" name="email" class="form-control" value="<?php if(isset($user_data['email'])) echo $user_data['email']; ?>" disabled>
                                <div class="form-control-feedback">
                                    <span class="text-muted"> Email</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group form-group-feedback form-group-feedback-right">
                                <input type="text" name="user-id" class="form-control" value="<?php if(isset($user_data['id'])) echo $user_data['id']; ?>" disabled>
                                <div class="form-control-feedback">
                                    <span class="text-muted"> User ID</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-feedback form-group-feedback-right">
                                <input type="text" name="oid" class="form-control" value="<?php if(isset($user_data['oid'])) echo $user_data['oid']; ?>" disabled>
                                <div class="form-control-feedback">
                                    <span class="text-muted"> OID</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="id" value="<?php if(isset($user_data['id'])) echo $user_data['id']; ?>">

                    <button type="submit" class="btn bg-teal-400 btn-labeled btn-labeled-right"><b><i class="icon-plus3"></i></b> Delete</button>
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

$('#delete-user-form').on('submit', function(e) {

    //prevent form submission
    e.preventDefault();

    var error = '';

    if (!e.target.id.value.trim()) {
        error += 'No User Id. ';
    }

    if (error) {
        ul.errorSwalAlert("Info Warning!", error);
        return false;
    }

    var $content = $("#delete-user-card").find('.card-header');
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