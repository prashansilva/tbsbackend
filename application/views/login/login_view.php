<!DOCTYPE html>
<html lang="en">
    <head>
        <!--        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                 Meta, title, CSS, favicons, etc. 
                <meta charset="utf-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1">-->

        <title>Employee Manager</title>

        <!-- Bootstrap -->
        <link href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet">
        <!-- Font Awesome -->
        <link href="<?php echo base_url('assets/css/font-awesome.css'); ?>" rel="stylesheet">

        <!-- NProgress -->
        <link href="<?php echo base_url('assets/css/nprogress.css'); ?>" rel="stylesheet">


        <!-- Custom Theme Style -->
        <link href="<?php echo base_url('assets/css/custom.min.css'); ?>" rel="stylesheet">

        <link href="<?php echo base_url('assets/css/login_style.css'); ?>" rel="stylesheet">
    </head>

    <body class="login">
        <div class="login_wrapper">
            <div class="animate form login_form">
                <section class="login_content">
                    <?php if ($id != null) { ?>
                        <?php echo form_open(base_url('index.php/welcome/validate_user?id=' . $id), array('id' => 'loginform')); ?>
                    <?php } else { ?>
                        <?php echo form_open(base_url('index.php/welcome/validate_user'), array('id' => 'loginform')); ?>
                    <?php } ?>
                    <h1>Login Form</h1>
                    <div class="row">
                        <div class="col-xs-12">
                            <?php if (isset($error_msg_login) && $error_msg_login) { ?>
                                <div class = "alert alert-error" style="padding: 10px;">
                                    <strong>Warning!</strong> <span><?php echo $error_msg_login ?></span>
                                </div>
                            <?php } else if (isset($success_msg_login) && $success_msg_login) { ?>
                                <div class = "alert alert-success" style="padding: 10px;">
                                    <strong>Success!</strong> <span><?php echo $success_msg_login ?></span>
                                </div>
                            <?php } else if (isset($error_login) && $error_login = 'INVALIDLOGIN') { ?>
                                <?php if (isset($error_msg_email) && $error_msg_email) { ?>
                                    <div class = "alert alert-error" style="padding: 10px;">
                                        <strong>Warning!</strong> <span><?php echo $error_msg_email ?></span>
                                    </div>
                                <?php } ?>
                                <?php if (isset($error_msg_password) && $error_msg_password) { ?>
                                    <div class = "alert alert-error" style="padding: 10px;">
                                        <strong>Warning!</strong> <span><?php echo $error_msg_password ?></span>
                                    </div>
                                <?php } ?>
                            <?php if (isset($error_msg_deactivate) && $error_msg_deactivate) { ?>
                                    <div class = "alert alert-error" style="padding: 10px;">
                                        <strong>Warning!</strong> <span><?php echo $error_msg_deactivate ?></span>
                                    </div>
                                <?php } ?>
                            <?php } ?>

                            <?php if (isset($error_msg_securitycode) && $error_msg_securitycode) { ?>
                                <div class = "alert alert-error" style="padding: 10px;">
                                    <strong>Warning!</strong> <span><?php echo $error_msg_securitycode ?></span>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div>
                        <?php
                        $data_form = array(
                            'id' => 'email',
                            'name' => 'email',
                            'autocomplete' => "off",
                            'class' => "form-control",
                            'placeholder' => 'Enter Email'
                        );
                        echo form_input($data_form);
                        ?>
                    </div>
                    <div>
                        <?php
                        $data_form = array(
                            'id' => 'password',
                            'name' => 'password',
                            'autocomplete' => "off",
                            'type' => 'password',
                            'class' => "form-control",
                            'placeholder' => 'Enter Password'
                        );
                        echo form_input($data_form);
                        ?> 
                    </div>
                    <div>
                        <button type="submit" class="btn btn-default submit"/>Log In</button>
                    </div>

                    <div class="clearfix"></div>

                    <div class="separator">


                        <!-- <div>
                            <a href="<?php echo base_url();?>"><img src="<?php echo base_url('assets/images/emp.png'); ?>" height="50%" width="50%"></a>
                            <p>©2017 All Rights Reserved. Powered by <a href="http://iterminal.net" target="_blank">iTerminal Technologies</a></p>
                        </div> -->
                    </div>
                    <?php echo form_close(); ?>
                </section>
            </div>
        </div>
        <script type="text/javascript" src="<?php echo base_url('assets/js/jquery-2.0.2.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('assets/js/jquery.validate.min.js'); ?>"></script>
        <script>
            $("#loginform").validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true
                    }

                }, errorElement: "div", // default is 'label'
                errorPlacement: function (error, element) {
                    error.insertAfter(element);
                }
            });
        </script>
    </body>
</html>
