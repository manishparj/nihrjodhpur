<?php include('./config/config.php');
?>
<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>ICMR-NIHR Jodhpur</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="manifest" href="site.webmanifest">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico">

    <!-- CSS here -->
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./assets/css/flaticon.css">
    <link rel="stylesheet" href="./assets/css/slicknav.css">
    <link rel="stylesheet" href="./assets/css/animate.min.css">
    <link rel="stylesheet" href="./assets/css/magnific-popup.css">
    <link rel="stylesheet" href="./assets/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="./assets/css/themify-icons.css">
    <link rel="stylesheet" type="text/css" href="./assets/slick/slick.css" />
    <link rel="stylesheet" type="text/css" href="./assets/slick/slick-theme.css" />
    <!-- <link rel="stylesheet" href="./assets/css/slick.css"> -->
    <link rel="stylesheet" href="./assets/css/nice-select.css">
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./stylenav.css">
    <style>

.custom-modal-overlay{
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.75);
    z-index: 999999;
    display: flex;
    align-items: center;
    justify-content: center;

    opacity: 0;
    visibility: hidden;

    transition: all 0.3s ease;
}

.custom-modal-overlay.active{
    opacity: 1;
    visibility: visible;
}

.custom-modal-box{
    background: #fff;
    width: 90%;
    max-width: 700px;

    border-radius: 10px;
    padding: 25px;

    position: relative;

    max-height: 85vh;
    overflow-y: auto;

    animation: modalAnimation 0.4s ease;
}

@keyframes modalAnimation{
    from{
        transform: translateY(-30px);
        opacity: 0;
    }
    to{
        transform: translateY(0);
        opacity: 1;
    }
}

.custom-modal-close{
    position: absolute;
    top: 10px;
    right: 15px;

    background: transparent;
    border: none;

    font-size: 30px;
    cursor: pointer;

    line-height: 1;
}

.custom-modal-header h2{
    margin-bottom: 15px;
    color: #003679;
    font-size: 28px;
}

.custom-modal-body{
    font-size: 16px;
    line-height: 1.7;
}

.custom-modal-body img{
    max-width: 100%;
    height: auto;
}

.custom-modal-footer{
    margin-top: 20px;
    text-align: right;
}

.custom-modal-footer button{
    background: #003679;
    color: #fff;
    border: none;
    padding: 10px 25px;
    border-radius: 5px;
    cursor: pointer;
}

body.modal-open{
    overflow: hidden;
}

@media(max-width:768px){

    .custom-modal-box{
        width: 95%;
        padding: 20px;
    }

    .custom-modal-header h2{
        font-size: 22px;
    }
}

</style>


</head>

<body id="bg">

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
        <!-- ##### Hero Area Start ##### -->
        <div class="hero-area">
            <div class="hero-slideshow owl-carousel">
            <?php                  	
                        $sql = "SELECT * from slider where status='1' ORDER BY id asc";
                        $query = $dbh->prepare($sql);
                        $query->execute();
                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                        $cnt = 1;
                        if ($query->rowCount() > 0) {
                            foreach ($results as $result) {
                           
                                ?>
                                <div class="single-slide bg-img">
                              
                                <div class="slide-bg-img bg-img bg-overlay"><img src="assets/img/hero/<?php echo htmlentities($result->doc_upload); ?>">
                                </div>
                              
                                <div class="container">
                                    <div class="row h-100 align-items-center justify-content-center">
                                    <div class="col-12 xs-12 sm-12 col-lg-9">
                                            <div class="welcome-text text-center mt-1">
                                                <h4 data-animation="fadeInUp" data-delay="100ms" style="color:#003679;"><?php echo htmlentities($result->messages); ?></h4>
                                                <p data-animation="fadeInUp" data-delay="100ms" style="color:#003679;"><?php echo htmlentities($result->messages2); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="slide-du-indicator"></div>
                            </div>
                                <?php
                            }
                        }
                    ?>
             
            </div>
        </div>
        <!-- ##### Hero Area End ##### -->
        <!-- slider Area End -->

<!-- announcement new code         -->
        <div class="announcement-area" style="background-color:#d2e6ff ;">
            <div class="container h-100">
                <div class="row h-100 align-items-center">
                    <div class="col-12 d-flex justify-content-between" style="line-height: 30px;">
                        <div class="ann2 d-flex align-items-center">
                            <span class="font-weight-bold">Announcement</span>
                        </div>

                        <marquee direction="left" onmouseover="this.stop();" onmouseout="this.start();" loop="INFINITE">
                            <div class="ann3 d-flex align-items-center"><span>


                                    <?php

                                    $sql = "SELECT * from announcement order by id DESC";
                                    $query = $dbh->prepare($sql);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    $cnt = 1;
                                    if ($query->rowCount() > 0) {
                                        foreach ($results as $result) {
                                            if ($result->doc_upload) { ?>
                                                 <a style="font-weight:bold;color:red" href="./admin/en_doc/<?php echo htmlentities($result->doc_upload); ?>" target="_blank"><u style="color: red;"><?php echo htmlentities($result->messages); ?></u></a>&nbsp;|
                                            <?php
                                            } else { ?>
                                                <a style="font-weight:bold;color:red" ><?php echo htmlentities($result->messages); ?></a>&nbsp;|
                                    <?php
                                            }
                                        }
                                    }
                                    ?>
                                </span>
                            </div>

                        </marquee>

                    </div>
                </div>
            </div>
        </div>





        <!-- <div class="announcement-area" style="background-color:#d2e6ff ;">
            <div class="container h-100">
                <div class="row h-100 align-items-center">
                    <div class="col-12 d-flex justify-content-between" style="line-height: 30px;">
                        <div class="ann2 d-flex align-items-center">
                            <span class="font-weight-bold">Announcement</span>
                        </div>

                        <marquee direction="left" onmouseover="this.stop();" onmouseout="this.start();" loop="INFINITE">
                            <div class="ann3 d-flex align-items-center">

                                 <span><a style="font-weight:bold;color:red" href="./doc/internship_notification.pdf" target="_blank">Regarding Invitation to Participate in One-Month Internship Program || 15 June – 16 July 2023</a></span>
                                <span><a style="font-weight:bold;color:red" href="#">Welcome to ICMR-NIIRNCD Jodhpur</span>

                            </div>

                        </marquee>

                    </div>
                </div>
            </div>
        </div> -->




        <!-- notication-tab Start -->
        <div class="team-padding">
            <div class="container">
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <div class="shadow p-3 mb-5 bg-white rounded">
                            <h3 class="card-title text-center pb-4 font-weight-bold" style="color: #012f5f;">From the Director's desk</h3>
                            <p class="card-text text-justify" id="bg1">The institute is located in Jodhpur and it replaces the erstwhile Desert Medicine Research Centre. As the name suggests, our focus is on conducting research to identify and innovate methods to tackle the rising threats..</p>
                            <a class="genric-btn success" href="about-director.php" style="width: 100%;background-color: #003679;">View profile »</a>
                        </div>
                    </div>
                    <div class="col-xl-8 col-lg-8 col-md-8">
                        <div class="shadow p-3 mb-5 bg-white rounded">
                            <h3 class="card-title text-center font-weight-bold" style="color: #012f5f;">Welcome to ICMR-National Institute of Health Research, Jodhpur</h3>
                            <img src="assets/img/icon/line.png" />
                            <p class="card-text text-justify" id="bg2">The National Institute for Health Research (NIHR), located in Jodhpur, was originally established on 27 June 1984 as the Desert Medicine Research Centre (DMRC). It was later renamed the National Institute for Implementation Research on Non-Communicable Diseases (NIIRNCD) on 7 December 2019, and redesignated as NIHR on 29 April 2026.The Institute houses state-of-the-art facilities for conducting basic laboratory-based....</p>
                            <a class="genric-btn success" href="about-nihr.php" style="width: 100%;background-color: #003679;">See More » </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- notification End-->


        <!-- collobrater Back Start -->

        <!-- Request Back End -->


        <!-- Request Back Start -->
        <section class="request-back-area bg-img-request jarallax" style="background-image:url(assets/img/gallery/12.jpg);padding: 20px 0px;">
            <div class="container ">
                <div class="row d-flex justify-content-between">
                    <div class="col-xl-4 col-lg-5 col-md-5 request-content">
                        <div class="nav flex-column nav-pills form-box" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <a class="nav-link active px-lg-5 py-5 m-1 text-left" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true"><i class="fa fa-bullhorn"></i>&nbsp;What's New</a>
                            <a class="nav-link px-lg-5 py-5 m-1 text-left" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false"><i class="fa fa-calendar-check"></i>&nbsp;Events</a>
                            <a class="nav-link px-lg-5 py-5 m-1 text-left" href="employee-corner.php"><i class="fa fa-file"></i>&nbsp;Circulars</a>
                        </div>
                    </div>

                    <div class="col-xl-8 col-lg-7 col-md-7 form-box2" style="height:400px;">
                        <div class="tab-content p-3" id="v-pills-tabContent" style="font-size:14px ;">
                            <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                                <marquee style="height:300px;" direction="up" behavior="scroll" scrollamount="4" onmouseover="this.stop();" onmouseout="this.start();">
                                    <?php

                                    $sql = "SELECT * from info_en where type = 'whatsnew' or type = 'recruitment' or type = 'circular' ORDER BY id DESC LIMIT 5";
                                    $query = $dbh->prepare($sql);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    $cnt = 1;
                                    if ($query->rowCount() > 0) {
                                        foreach ($results as $result) {                ?>


                                            <?php
                                            $sql9 = "SELECT * from doc_en where doc_id = $result->id";
                                            $query9 = $dbh->prepare($sql9);
                                            $query9->execute();
                                            $results9 = $query9->fetchAll(PDO::FETCH_OBJ);
                                            if ($query9->rowCount() > 0) {
                                                foreach ($results9 as $result19) {                ?>
                                                    <ul class=" unordered-list">
                                                        <li class="text-justify"><a href="admin/en_doc/<?php echo htmlentities($result19->doc_main); ?>" target="_blank" style="color:white;"><?php echo htmlentities($result->title); ?></a>
                                                            <hr style="margin-top:8px;opacity:0.3;border:1px solid #fff;">
                                                        </li>
                                                    </ul>
                                            <?php }
                                            } ?>

                                            </tr>
                                    <?php }
                                    } ?>
                                </marquee>
                                <a href="viewnews.php" class="genric-btn success circle arrow medium f-right mb-2 border" style="background-color: #003679;">See More »</a>

                            </div>
                            <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                                <marquee style="height:300px;" direction="up" behavior="scroll" scrollamount="4" onmouseover="this.stop();" onmouseout="this.start();">
                                    <?php

                                    $sql1 = "SELECT * from info_en where type = 'event' ORDER BY id DESC";
                                    $query1 = $dbh->prepare($sql1);
                                    $query1->execute();
                                    $results1 = $query1->fetchAll(PDO::FETCH_OBJ);
                                    if ($query1->rowCount() > 0) {
                                        foreach ($results1 as $result11) {                ?>


                                            <?php
                                            $sql91 = "SELECT * from doc_en where doc_id = $result11->id";
                                            $query91 = $dbh->prepare($sql91);
                                            $query91->execute();
                                            $results91 = $query91->fetchAll(PDO::FETCH_OBJ);
                                            if ($query91->rowCount() > 0) {
                                                foreach ($results91 as $result191) {                ?>
                                                    <ul class="unordered-list">
                                                        <li class="text-justify"><a href="admin/en_doc/<?php echo htmlentities($result191->doc_main); ?>" target="_blank" style="color:white;"><?php echo htmlentities($result11->title); ?></a>
                                                            <hr style="margin-top:8px;opacity:0.3;border:1px solid #fff;">
                                                        </li>
                                                    </ul>
                                            <?php }
                                            } ?>

                                            </tr>
                                    <?php }
                                    } ?>
                                </marquee>
                                <a href="view.php" class="genric-btn success circle arrow medium f-right mb-2 border" style="background-color: #003679;">See More »</a>

                            </div>
                            <!-- <div class="tab-pane fade" id="v-pills-messages" role="tabpanel"
                                aria-labelledby="v-pills-messages-tab"></div> -->
                        </div>
                    </div>


                </div>
            </div>
        </section>
        <!-- Request Back End -->




        <section class="border">
            <div class="container">
                <!-- <div class="col-12 text-center">
                <h2 class="font-weight-bold">Our Collaborators</h2>
                </div> -->

                <div class="variable-width py-1">
                    <div class="card px-4 border-0">
                        <img class="img-thumbnail border-0" src="./assets/img/footerlogo/who.jpg" alt="" style="width: 150px;height: 100px;" alt="Card image cap">
                    </div>
                    <div class="card  px-5 border-0">
                        <img class="img-thumbnail border-0" src="./assets/img/footerlogo/aiimslogo.png" alt="" style="width: 100px;height: 100px;" alt="Card image cap">
                    </div>
                    <div class="card  px-5 border-0">
                        <img class="img-thumbnail border-0" src="./assets/img/footerlogo/icmr_logo.png" alt="" style="width: 200px;height: 100px;" alt="Card image cap">
                    </div>
                    <div class="card  px-5 border-0">
                        <img class="img-thumbnail border-0" src="./assets/img/footerlogo/MOHFW-Recruitment-2017.jpg" alt="" style="width: 150px;height: 100px;" alt="Card image cap">
                    </div>
                    <div class="card  px-5 border-0">
                        <img class="img-thumbnail border-0" src="./assets/img/footerlogo/dhr.jpg" alt="" style="width: 150px;height: 100px;" alt="Card image cap">
                    </div>

                </div>

            </div>
        </section>

    </main>
    <?php include "./config/footer.php"; ?>
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
    <!-- <script src="./assets/js/slick.min.js"></script> -->

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
    <script src="./assets/js/jquery-2.2.4.min.js"></script>
    <script src="./assets/js/plugins.js"></script>
    <script src="./assets/js/main.js"></script>
    <script src="./assets/js/active.js"></script>
    <script type="text/javascript" src="./assets/slick/slick.min.js"></script>


    <script>
        $(document).ready(function() {
            $('#btn1').click(function() {
                $("#bg").css("fontSize", "18px");
                $(".card-text").css("fontSize", "18px");
            });

            $('#btn2').click(function() {
                $("#bg").css("fontSize", "16px");
                $(".card-text").css("fontSize", "16px");
            });

            $('#btn3').click(function() {
                $("#bg").css("fontSize", "13px");
                $(".card-text").css("fontSize", "13px");
            });


        });
    </script>

    <script>
        $('.variable-width').slick({
            dots: false,
            infinite: true,
            slidesToShow: 2,
            slidesToScroll: 1,
            variableWidth: true,
            autoplay: true,
            autoplaySpeed: 3000,
        });
    </script>



<script>

window.addEventListener("load", function () {

    // wait 5 seconds AFTER full page load
    setTimeout(function () {

        const modal = document.getElementById("customModalOverlay");

        if(modal){

            // show modal
            modal.classList.add("active");

            // disable body scroll
            document.body.classList.add("modal-open");

            const closeBtn = document.getElementById("modalCloseBtn");
            const footerBtn = document.getElementById("modalFooterBtn");

            // close function
            function closeModal(){

                modal.classList.remove("active");
                document.body.classList.remove("modal-open");

            }

            // close button
            if(closeBtn){
                closeBtn.onclick = closeModal;
            }

            // footer button
            if(footerBtn){
                footerBtn.onclick = closeModal;
            }

            // outside click
            modal.onclick = function(e){

                if(e.target === modal){
                    closeModal();
                }

            };

            // ESC key
            document.addEventListener("keydown", function(e){

                if(e.key === "Escape"){
                    closeModal();
                }

            });

        }

    }, 2000);

});

</script>

    <?php
        $query = "SELECT * FROM modals WHERE is_enabled = 1 ORDER BY id DESC LIMIT 1";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {

            $modal = mysqli_fetch_assoc($result);
        ?>

        <div id="customModalOverlay" class="custom-modal-overlay">

            <div class="custom-modal-box">

                <button class="custom-modal-close" id="modalCloseBtn">
                    &times;
                </button>

                <div class="custom-modal-header">
                    <h2><?php echo htmlspecialchars($modal['modal_title']); ?></h2>
                </div>

                <div class="custom-modal-body">
                    <?php echo html_entity_decode($modal['modal_content']); ?>
                    
                </div>

                <div class="custom-modal-footer">
                    <button id="modalFooterBtn">
                        <?php echo htmlspecialchars($modal['footer_text']); ?>
                    </button>
                </div>

            </div>

        </div>

<?php } ?>

</body>

</html>
