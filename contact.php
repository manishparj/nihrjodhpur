<?php
include('config/config.php');

error_reporting(0);

?>
<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Contact | ICMR-NIIRNCD </title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="manifest" href="site.webmanifest">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico">

    <!-- CSS here -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">

    <link rel="stylesheet" href="assets/css/flaticon.css">

    <link rel="stylesheet" href="assets/css/slicknav.css">
    <link rel="stylesheet" href="assets/css/animate.min.css">
    <link rel="stylesheet" href="assets/css/magnific-popup.css">
    <link rel="stylesheet" href="assets/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/css/themify-icons.css">
    <link rel="stylesheet" href="assets/css/slick.css">
    <link rel="stylesheet" href="assets/css/nice-select.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="stylenav.css">
    <style>
        .errorWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #dd3d36;
            color: #fff;
            -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
            box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        }

        .succWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #5cb85c;
            color: #fff;
            -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
            box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // JavaScript function to reload the CAPTCHA image
        function refreshCaptcha() {
            var captchaImage = document.getElementById("captcha-image");
            captchaImage.src = "generate_captcha.php?r=" + Math.random(); // Append a random query parameter to the URL to force image reload
        }
    </script>
    <script>
        $(document).ready(function() {
            // Validation function
            function validateForm() {
                var name = $("#name").val();
                var email = $("#email").val();
                var mobile = $("#subject").val();
                var message = $("#message").val();
                // var captcha = $("#captcha").val();

                // Regular expression to validate email address
                var emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

                // Regular expression to validate mobile number (10 digits)
                var mobileRegex = /^[0-9]{10}$/;

                // Check if name is empty
                if (name == "") {
                    alert("Name must be filled out");
                    return false;
                } else if (!/^[a-zA-Z\s]+$/.test(name)) {
                    alert("Name should only contain letters and spaces");
                    return false;
                }

                // Check if email is empty and valid
                if (email == "") {
                    alert("Email must be filled out");
                    return false;
                } else if (!/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/.test(email)) {
                    alert("Invalid email address");
                    return false;
                }


                // Check if mobile number is empty and valid
                if (mobile == "") {
                    alert("Mobile number must be filled out");
                    return false;
                } else if (!mobileRegex.test(mobile)) {
                    alert("Invalid mobile number (10 numeric digits required)");
                    return false;
                }

                // Check if message is empty and within 250 characters limit
                if (message == "") {
                    alert("Message must be filled out");
                    return false;
                } else if (message.length > 250) {
                    alert("Message should be less than 250 characters");
                    return false;
                } 
                // else if (!/^[a-zA-Z\s]+$/.test(message)) {
                //     alert("Message should only contain letters and spaces");
                //     return false;
                // }

                // var stg1 = document.getElementById('capt').value;
                // var captcha = document.getElementById('captcha').value;

                // // Check if captcha is empty
                // if (captcha == "") {
                //     alert("Please enter the captcha");
                //     return false;
                // } else if (stg1 != captcha) {
                //     alert("Incorrect captcha");
                //     return false;
                // }

                // All validation passed
                return true;
            }

            // Event delegation for form submission
            $("#contactForm").on("submit", function(event) {
                event.preventDefault(); // Prevent the default form submission behavior

                // Call the validation function before submitting
                if (validateForm()) {
                    var form = $(this);
                    var formData = form.serialize();

                    $.ajax({
                        type: "POST",
                        url: "process_form.php", // Replace with the URL of your PHP processing script
                        data: formData,
                        success: function(response) {
                            $("#responseMessage").html(response);
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            alert("Error: " + errorThrown);
                        }
                    });
                } 
            });
        });

    </script>

</head>

<body>
    <!-- Preloader Start -->
    <div id="preloader-active">
        <div class="preloader d-flex align-items-center justify-content-center">
            <div class="preloader-inner position-relative">
                <div class="preloader-circle"></div>
                <div class="preloader-img pere-text">
                    <img src="assets/img/logo/loaderlogo.jpg" alt="">
                </div>
            </div>
        </div>
    </div>
    <!-- Preloader Start -->

    <?php include('config/header.php'); ?>
    <main>
        <!-- slider Area Start-->
        <div class="slider-area">
            <!-- Mobile Menu -->
            <div class="single-slider slider-height2 d-flex align-items-center" style="background-image:url(assets/img/hero/services_hero.jpg);">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="hero-cap text-center">
                                <h2>Get in Touch With Us</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- slider Area End-->
        <!-- ================ contact section start ================= -->
     <section class="contact-section py-5 bg-light">
    <div class="container">

        <!-- Heading -->
        <div class="row mb-4">
            <div class="col-lg-12 text-center">

                <p class="text-muted mx-auto"
                   style="max-width:750px; line-height:1.8; font-size:16px;">

                    We are committed to advancing public health research and innovation.
                    Reach out to us for institutional communication, collaborations, and inquiries.

                </p>

            </div>
        </div>

        <!-- Main Row -->
        <div class="row">

            <!-- LEFT SIDE MAP -->
            <div class="col-lg-7 mb-4">

                <div class="bg-white shadow-sm h-100"
                     style="border-radius:15px; overflow:hidden; border:1px solid #e9ecef;">

                    <div class="p-3 border-bottom text-white" style="background:#003679;">

                        <h5 class="mb-0 font-weight-bold">
                            <i class="ti-location-pin mr-2"></i>
                            Institute Location
                        </h5>

                    </div>

                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4255.999377738954!2d73.0276054281912!3d26.23391291055097!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39418b8f0bc41b59%3A0x452d769037ea5042!2sNational%20Institute%20for%20Implementation%20Research%20on%20Non-Communicable%20Diseases!5e0!3m2!1sen!2sin!4v1622014051415!5m2!1sen!2sin"
                        width="100%"
                        height="550"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy">
                    </iframe>

                </div>

            </div>

            <!-- RIGHT SIDE -->
            <div class="col-lg-5">

                <!-- Address -->
                <div class="bg-white shadow-sm mb-4"
                     style="border-radius:15px; border:1px solid #e9ecef;">

                    <div class="p-3 border-bottom"
                         style="background:#f8f9fa;">

                        <h5 class="mb-0 font-weight-bold text-dark">
                            <i class="ti-home text-primary mr-2"></i>
                            Address
                        </h5>

                    </div>

                    <div class="p-4">

                        <p class="text-muted mb-0"
                           style="line-height:2; font-size:15px;">

                            <strong class="text-dark">
                                ICMR-National Institute of Health Research
                            </strong><br>

                            Indian Council of Medical Research<br>

                            Department of Health Research<br>

                            Ministry of Health & Family Welfare<br>

                            New Pali Road, Jodhpur<br>

                            Rajasthan - 342005, INDIA

                        </p>

                    </div>

                </div>

                <!-- Contact Info -->
                <div class="bg-white shadow-sm"
                     style="border-radius:15px; border:1px solid #e9ecef;">

                    <div class="p-3 border-bottom"
                         style="background:#f8f9fa;">

                        <h5 class="mb-0 font-weight-bold text-dark">
                            <i class="ti-headphone-alt text-success mr-2"></i>
                            Contact Information
                        </h5>

                    </div>

                    <div class="p-4">

                        <!-- Phone -->
                        <div class="d-flex mb-4">

                            <div class="mr-3">

                                <div class="d-flex align-items-center justify-content-center"
                                     style="width:55px;
                                            height:55px;
                                            border-radius:50%;
                                            background:#28a745;
                                            color:#fff;
                                            font-size:22px;">

                                    <i class="ti-mobile"></i>

                                </div>

                            </div>

                            <div>

                                <h6 class="font-weight-bold mb-1">
                                    Phone Number
                                </h6>

                                <p class="mb-1 text-dark">
                                    +91-291-2722403
                                </p>

                                <small class="text-muted">
                                    Monday to Friday | 9:00 AM – 5:30 PM
                                </small>

                            </div>

                        </div>

                        <hr>

                        <!-- Email -->
                        <div class="d-flex mt-4">

                            <div class="mr-3">

                                <div class="d-flex align-items-center justify-content-center"
                                     style="width:55px;
                                            height:55px;
                                            border-radius:50%;
                                            background:#dc3545;
                                            color:#fff;
                                            font-size:22px;">

                                    <i class="ti-email"></i>

                                </div>

                            </div>

                            <div>

                                <h6 class="font-weight-bold mb-1">
                                    Email Address
                                </h6>

                                <p class="mb-0 text-muted"
                                   style="word-break:break-word;">

                                    prc[dot]niirncd[at]icmr[dot]gov[dot]in

                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
</section>
        <!-- ================ contact section end ================= -->
    </main>
    <footer>
        <!-- Footer Start-->

        <!-- footer-bottom aera -->
        <?php include('./config/footer.php'); ?>

        <!-- Footer End-->
    </footer>

    <!-- JS here -->

    <!-- All JS Custom Plugins Link Here here -->
    <script src="./assets/js/vendor/modernizr-3.5.0.min.js"></script>

    <!-- Jquery, Popper, Bootstrap -->
    <script src="./assets/js/vendor/jquery-1.12.4.min.js"></script>
    <script src="./assets/js/popper.min.js"></script>
    <script src="./assets/js/bootstrap.min.js"></script>
    <!-- Jquery Mobile Menu -->
    <script src="./assets/js/jquery.slicknav.min.js"></script>

    <!-- Jquery Slick , Owl-Carousel Plugins -->
    <script src="./assets/js/owl.carousel.min.js"></script>
    <script src="./assets/js/slick.min.js"></script>
    <!-- Date Picker -->
    <script src="./assets/js/gijgo.min.js"></script>
    <!-- One Page, Animated-HeadLin -->
    <script src="./assets/js/wow.min.js"></script>
    <script src="./assets/js/animated.headline.js"></script>
    <script src="./assets/js/jquery.magnific-popup.js"></script>

    <!-- Scrollup, nice-select, sticky -->
    <script src="./assets/js/jquery.scrollUp.min.js"></script>
    <script src="./assets/js/jquery.nice-select.min.js"></script>
    <script src="./assets/js/jquery.sticky.js"></script>

    <!-- contact js -->
    <script src="./assets/js/contact.js"></script>
    <script src="./assets/js/jquery.form.js"></script>
    <script src="./assets/js/jquery.validate.min.js"></script>
    <script src="./assets/js/mail-script.js"></script>
    <script src="./assets/js/jquery.ajaxchimp.min.js"></script>

    <!-- Jquery Plugins, main Jquery -->
    <script src="./assets/js/main.js"></script>
    <script src="./assets/js/jquery-2.2.4.min.js"></script>
    <script src="./assets/js/plugins.js"></script>
    <script src="./assets/js/active.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            setTimeout(function() {
                $('.succWrap').slideUp("slow");
            }, 3000);
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#btn1').click(function() {
                $("#bg").css("fontSize", "18px");
                $(".media-body").css("fontSize", "18px");
            });

            $('#btn2').click(function() {
                $("#bg").css("fontSize", "16px");
                $(".media-body").css("fontSize", "16px");
            });

            $('#btn3').click(function() {
                $("#bg").css("fontSize", "13px");
                $(".media-body").css("fontSize", "13px");
            });
        });
    </script>

</body>

</html>