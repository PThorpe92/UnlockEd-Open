<?php

/**
 * Login
 *
 * Handle Login
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


?>


<!-- Page content -->
<div class="page-content overflow-hidden navbar-bottom">

	<!-- Main content -->
	<div class="content-wrapper">
    
        <!-- background image -->
        <img id="welcome_img" src="./images/cafe.jpg"> 

		<!-- Content area -->
		<div class="content d-flex justify-content-center align-items-center" style="z-index: 2;">


            <div id="welcome_elems" class="justify-content-center align-items-center invisible">
                <div class="card-body">
                    <div class="text-center mb-2">
                        <img src="./images/UELogo_white.png" width="90px">
                    </div>
                    <div class="text-center mb-2">
                        <span class="mb-0 text-light font-weight-light" style="font-size:30px;">unlock a world of possibilities</span>
                    </div>
                    <div class="text-center">
                        <button class="btn btn-lg border-light text-light" onclick="fadeForms('login_card');">Get Started</button>
                    </div>
                </div>
            </div>


            <!-- Login card -->
            <form class="login-form invisible" id="login_card" method="post" action="index.php">
                <div class="card mb-0">
                    <div class="card-body">
                        <div class="text-center mb-3"> 
                            <h5 class="mb-0">Login to your account</h5>
                            <span class="d-block text-muted">Your credentials</span>
                            <span id="new_user_greet"></span>
                            <?php
                            if(isset($_GET['newusername'])) {
                            echo '<div class="alert alert-primary alert-styled-left alert-dismissible mt-2">';
                            echo '<button type="button" class="close" data-dismiss="alert"><span>×</span></button>';
                            echo '<span class="font-weight-semibold">Hello '.$_GET['newusername'].'!</span> Thank you for creating an account. You can now login using your username and password.';
                            echo '</div>';
                            } 
                            ?>
                        </div>
                        <div class="form-group form-group-feedback form-group-feedback-left">
                            <input type="text" id="username" class="form-control" name="username" placeholder="Username">
                            <div class="form-control-feedback">
                                <i class="icon-user text-muted"></i>
                            </div>
                        </div>

                        <div class="form-group form-group-feedback form-group-feedback-left">
                            <input type="password" class="form-control" name="password" placeholder="Password">
                            <div class="form-control-feedback">
                                <i class="icon-lock2 text-muted"></i>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">Sign in <i class="icon-circle-right2 ml-2"></i></button>

                            <!--  SIGN UP OPTION NOT INCLUDED IN VERSION AT TIME OF COMMENT 1/21/2022 -->
                            <!--a href="#" id="sign_up" onclick="fadeForms('registration_card');" class="btn btn-light btn-block">Sign up</a-->
                        </div>

                        <span class="form-text text-center text-muted">By continuing, you're confirming that you've read our <a style="font-family:roboto;" href="#eulaModal" data-toggle="modal">Terms &amp; Conditions</a>.</span>
                    </div>
                </div>
            </form>
            <!-- /login card -->



            <!-- EULA Modal -->
            <div class="modal fade" id="eulaModal" tabindex="-1" role="dialog" data-backdrop="false" aria-labelledby="eulaModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eulaModalLabel">Terms and Conditions</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <div class="accordion" id="eulaAccordion">
            <div class="card">
                <div class="card-header" id="eulaHeadingOne">
                <h2 class="mb-0">
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#eulaCollapseOne" aria-expanded="true" aria-controls="eulaCollapseOne">
                    Student Terms
                    </button>
                </h2>
                </div>

                <div id="eulaCollapseOne" class="collapse show" aria-labelledby="eulaHeadingOne" data-parent="#eulaAccordion">
                <div class="card-body">
                    <?php require_once dirname(__FILE__).'/EULA/EULAStudent.php';?>
                </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="eulaHeadingTwo">
                <h2 class="mb-0">
                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#eulaCollapseTwo" aria-expanded="false" aria-controls="eulaCollapseTwo">
                            License
                    </button>
                </h2>
                </div>
                <div id="eulaCollapseTwo" class="collapse" aria-labelledby="eulaHeadingTwo" data-parent="#eulaAccordion">
                <div class="card-body">
                <?php require_once dirname(__FILE__).'/EULA/EULA.php';?>
            
                </div>
                </div>
            </div>
    
            </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
            </div>
            <!-- /EULA Modal -->


            <!-- Registration form -->
            <!--form method="post" action="#" id="registration_card" class="flex-fill invisible">
                <div class="row">
                    <div class="col-lg-6 offset-lg-3">
                        <div class="card mb-0">
                            <div class="card-body">
                                <div class="text-center mb-3">
                                    <i class="icon-plus3 icon-2x text-success border-success border-3 rounded-round p-3 mb-3 mt-1"></i>
                                    <h5 class="mb-0">Create account</h5>
                                    <span class="d-block text-muted">All fields are required</span>
                                </div>

                                <div class="form-group form-group-feedback form-group-feedback-right">
                                    <input type="text" name="username" class="form-control" value="<?php if(isset($user->username)) echo $user->username; ?>" placeholder="Username" placeholder="Choose username" required>
                                    <div class="form-control-feedback">
                                        <i class="icon-user-plus text-muted"></i>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group form-group-feedback form-group-feedback-right">
                                            <input type="text" name="first_name" class="form-control" value="<?php if(isset($user->first_name)) echo $user->first_name; ?>" placeholder="First name" required>
                                            <div class="form-control-feedback">
                                                <i class="icon-user-check text-muted"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group form-group-feedback form-group-feedback-right">
                                            <input type="text" name="second_name" class="form-control" value="<?php if(isset($user->second_name)) echo $user->second_name; ?>" placeholder="Second name" required>
                                            <div class="form-control-feedback">
                                                <i class="icon-user-check text-muted"></i>
                                            </div>
                                        </div>
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
                                            <input type="email" name="email" class="form-control" value="<?php if(isset($user->email)) echo $user->email; ?>" placeholder="Your email" required>
                                            <div class="form-control-feedback">
                                                <i class="icon-mention text-muted"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group form-group-feedback form-group-feedback-right">
                                            <input type="email" name="repeat_email" class="form-control" value="<?php if(isset($user->repeat_email)) echo $user->repeat_email; ?>" placeholder="Repeat email" required>
                                            <div class="form-control-feedback">
                                                <i class="icon-mention text-muted"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <a href="#" class="btn bg-teal-400 btn-labeled btn-labeled-right" onclick="userRegistration();"><b><i class="icon-plus3"></i></b> Create account</a>
                                <a href="#" id="back_to_login" onclick="fadeForms('login_card');" class="btn bg-primary-400 btn-labeled btn-labeled-right"><b><i class="icon-plus3"></i></b> Return to Login</a>

                            </div>
                        </div>
                    </div>
                </div>
            </form-->
            <!-- /registration form -->


            <!-- Footer -->
			<div class="navbar navbar-expand-lg navbar-light fixed-bottom">

				<div class="navbar-collapse collapse" id="navbar-footer">
					<span class="navbar-text">
						&copy; 2021 - 2022. <a href="#">Unlocked Education</a> by <a href="#" target="_blank">Unlocked Labs</a>
					</span>

					<ul class="navbar-nav ml-lg-auto">
						<li class="nav-item"><a href="#" class="navbar-nav-link" target="_blank"><i class="icon-lifebuoy mr-2"></i> Help</a></li>
						<li class="nav-item"><a href="#eulaModal" data-toggle="modal" class="navbar-nav-link"><i class="icon-file-text2 mr-2"></i> Terms</a></li>
						<li class="nav-item"><a href="#" class="navbar-nav-link font-weight-semibold"><span class="text-pink-400"><i class="icon-comments mr-2"></i> Contact Us</span></a></li>
					</ul>
				</div>
			</div>
			<!-- /footer -->

		</div>
		<!-- /content area -->

	</div>
	<!-- /main content -->

</div>
<!-- /page content -->

<script>

//.navbar-top adds a top margin that we do not want on this page
document.querySelector("body").classList.remove('navbar-top');
</script>