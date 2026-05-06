<?php
include('config/config.php');
?>
<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>ICMR-NIHR </title>
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
    <link rel="stylesheet" href="assets/datatables/dataTables.bootstrap4.css">
    <style>
                    /* FORCE SAME HEIGHT FOR ALL TILE CARDS */
            .tile-card {
                display: flex;
                flex-direction: column;
                height: 100%;
            }

            /* HEADER FIXED HEIGHT */
            .tile-card .card-header {
                min-height: 160px;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
            }

            /* BODY STRETCH */
            .tile-card .card-body {
                flex-grow: 1;
                display: flex;
                align-items: flex-start;
                font-size: 0.95rem;
                line-height: 1.8;
            }

            /* OBJECTIVE LIST SPACING */
            .tile-card ul li {
                margin-bottom: 10px;
            }

            /* MOBILE SAFE */
            @media (max-width: 768px) {
                .tile-card .card-header {
                    min-height: auto;
                }
            }

                .tile-card {
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }

            .tile-card:hover {
                transform: translateY(-8px);
                box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            }

            .card-header h5 {
                font-weight: 600;
            }
            /* ICMR THEME */
            :root {
                --icmr-blue: #0b5ed7;
                --icmr-green: #198754;
                --icmr-teal: #0dcaf0;
                --icmr-warning: #ffc107;
                --icmr-danger: #dc3545;
            }

            /* CARD ANIMATION */
            .tile-card {
                border-radius: 16px;
                overflow: hidden;
                transition: all 0.35s ease;
                animation: fadeUp 0.8s ease forwards;
                opacity: 0;
            }

            .tile-card:hover {
                transform: translateY(-10px);
                box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            }

            /* FADE-UP EFFECT */
            @keyframes fadeUp {
                from {
                    opacity: 0;
                    transform: translateY(25px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* HEADER STYLING */
            .tile-card .card-header {
                text-align: center;
                padding: 25px 15px;
                border-bottom: none;
            }

            /* ICON BADGE */
            .icon-badge {
                width: 60px;
                height: 60px;
                border-radius: 50%;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-size: 26px;
                margin-bottom: 10px;
                background: rgba(255,255,255,0.2);
                transition: transform 0.3s ease;
            }

            /* ICON HOVER EFFECT */
            .tile-card:hover .icon-badge {
                transform: scale(1.15);
            }

            /* BODY TEXT */
            .tile-card .card-body {
                font-size: 15px;
                color: #333;
            }

            /* IMAGE ENHANCEMENT */
            .card-img-top {
                border-radius: 14px;
                transition: transform 0.4s ease;
            }

            .card-img-top:hover {
                transform: scale(1.03);
            }

            /* RESPONSIVE FIXES */
            @media (max-width: 768px) {
                .tile-card {
                    margin-bottom: 20px;
                }
            }
.tile-card {
  border-radius: 12px;
  transition: all 0.3s ease;
}

.tile-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 12px 25px rgba(0,0,0,0.12);
}

.icon-badge {
  font-size: 28px;
  margin-bottom: 5px;
}

.card-body p,
.card-body li {
  font-size: 14px;
  line-height: 1.6;
}

.objective-scroll {
  max-height: 260px;
  overflow-y: auto;
  padding-right: 8px;
}

/* subtle scrollbar */
.objective-scroll::-webkit-scrollbar {
  width: 6px;
}
.objective-scroll::-webkit-scrollbar-thumb {
  background: #ccc;
  border-radius: 10px;
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

    <?php include('config/hheader.php'); ?>


    <main>

        <!-- slider Area Start-->
        <div class="slider-area">
            <!-- Mobile Menu -->
            <div class="single-slider slider-height2 d-flex align-items-center" style="background-image:url(assets/img/hero/services_hero.jpg);">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="hero-cap text-center">
                                <h2>About ICMR-NIHR Jodhpur</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- slider Area End-->

       <section class="my-5">
    <div class="container">
        <div class="row">
            <div class="card col-lg-12 text-center shadow-lg" style="padding: 0">
                <div class="card-header bg-primary text-white" style="background-color: #134b8a !important;">
                    <h4 class="font-weight-bold">
                        राष्ट्रीय स्वास्थ्य अनुसंधान संस्थान, जोधपुर
                    </h4>
                    <h5>New Pali Road, Jodhpur</h5>
                </div>

                <div class="card-body">
                    <img class="card-img-top img-fluid rounded mb-4"
                         src="assets/img/about/building.jpg"
                         alt="Card image cap">

                    <p id="bg1" class="card-text text-justify " style="font-size: larger;">
                        राष्ट्रीय स्वास्थ्य अनुसंधान संस्थान (NIHR), जोधपुर में स्थित, मूल रूप से 27 जून 1984 को डेजर्ट मेडिसिन रिसर्च सेंटर (DMRC) के रूप में स्थापित किया गया था। बाद में 7 दिसंबर 2019 को इसका नाम बदलकर नेशनल इंस्टीट्यूट फॉर इम्प्लीमेंटेशन रिसर्च ऑन नॉन-कम्युनिकेबल डिज़ीज़ेज़ (NIIRNCD) कर दिया गया तथा 29 अप्रैल 2026 को इसे पुनः नामित कर राष्ट्रीय स्वास्थ्य अनुसंधान संस्थान (NIHR) किया गया।
                         </p>
                    <p id="bg1" class="card-text text-justify " style="font-size: larger;">
                        संस्थान में माइक्रोबायोलॉजी, बायोकेमिस्ट्री तथा वेक्टर बायोलॉजी में बुनियादी प्रयोगशाला-आधारित अनुसंधान के लिए अत्याधुनिक सुविधाएँ उपलब्ध हैं। साथ ही, यह गैर-संचारी रोगों (NCDs) के क्षेत्र में कार्यान्वयन अनुसंधान को आगे बढ़ाने हेतु अपनी क्षमता और मानव संसाधन को सक्रिय रूप से सुदृढ़ कर रहा है।
                    </p>
                    <p id="bg1" class="card-text text-justify " style="font-size: larger;">
                        वर्तमान में, NIHR में समर्पित वैज्ञानिकों और तकनीकी विशेषज्ञों की एक टीम कार्यरत है, जिन्हें प्रशासनिक एवं संचालन स्टाफ का सहयोग प्राप्त है। संस्थान के प्रमुख फोकस क्षेत्र में हृदय-वाहिका रोग, दीर्घकालिक श्वसन रोग, पर्यावरणीय स्वास्थ्य, पोषण संबंधी विकार, कैंसर, चोट एवं आघात, मानसिक स्वास्थ्य स्थितियाँ (जिसमें नशीले पदार्थों के उपयोग से संबंधित विकार शामिल हैं), आनुवंशिक विकार तथा भारत में जनस्वास्थ्य की दृष्टि से महत्वपूर्ण अन्य गैर-संचारी रोग शामिल हैं।
                    </p>
                    <p id="bg1" class="card-text text-justify " style="font-size: larger;">
                        NIHR का उद्देश्य इन क्षेत्रों में उच्च प्रभाव वाले कार्यान्वयन अनुसंधान को बढ़ावा देना, विशेष प्रशिक्षण कार्यक्रमों के माध्यम से क्षमता निर्माण करना, तथा NCD जोखिम कारकों को संबोधित करने के लिए प्रभावी व्यवहार परिवर्तन संचार रणनीतियों का विकास करना है। संस्थान शैक्षणिक एवं अनुसंधान संस्थानों के साथ-साथ उन व्यक्तिगत शोधकर्ताओं के साथ सहयोग का स्वागत करता है, जो राष्ट्रीय स्वास्थ्य प्राथमिकताओं के अनुरूप जनस्वास्थ्य को आगे बढ़ाने के लिए प्रतिबद्ध हैं।
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="my-5">
    <div class="container">
        <div class="row g-4 align-items-stretch">

            <!-- Vision -->
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card h-100 shadow-sm tile-card text-center">
                    <div class="card-header text-white" style="background:var(--icmr-teal);">
                        <div class="icon-badge"><i class="fa fa-camera"></i></div>
                        <h5 class="mb-0">दूरदृष्टि</h5>
                    </div>
                    <div class="card-body">
                        <i class="fa fa-hand-point-right text-muted"></i>
                        <p class="mt-2 mb-0">
                            असंचारी रोगों की रोकथाम और नियंत्रण के लिए कार्यान्वयन अनुसंधान करने में अग्रणी बनना
                        </p>
                    </div>
                </div>
            </div>

            <!-- Mission -->
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card h-100 shadow-sm tile-card text-center">
                    <div class="card-header text-white" style="background:var(--icmr-green);">
                        <div class="icon-badge"><i class="fa fa-telegram"></i></div>
                        <h5 class="mb-0">ध्येय</h5>
                    </div>
                    <div class="card-body">
                        <i class="fa fa-hand-point-right text-muted"></i>
                        <p class="mt-2 mb-0">
                            देश के सभी स्वास्थ्य देखभाल कर्मियों को आवश्यक कौशल और दक्षताओं से लैस करना, ताकि वे असंचारी रोगों की रोकथाम, नियंत्रण और उपचार में योगदान दे सकें।
                        </p>
                    </div>
                </div>
            </div>

            <!-- Goal -->
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card h-100 shadow-sm tile-card text-center">
                    <div class="card-header text-white" style="background:var(--icmr-warning);">
                        <div class="icon-badge"><i class="fa fa-bullseye"></i></div>
                        <h5 class="mb-0">लक्ष्य</h5>
                    </div>
                    <div class="card-body">
                        <i class="fa fa-hand-point-right text-muted"></i>
                        <p class="mt-2 mb-0">
                            असंचारी रोगों के बोझ को कम करना और असंचारी रोगों से पीड़ित लोगों के जीवन की गुणवत्ता में सुधार लाना
                        </p>
                    </div>
                </div>
            </div>

            <!-- Objective -->
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card h-100 shadow-sm tile-card">
                    <div class="card-header text-white text-center" style="background:var(--icmr-danger);">
                        <div class="icon-badge"><i class="fa fa-hand-pointer"></i></div>
                        <h5 class="mb-0">उद्देश्य</h5>
                    </div>

                    <!-- Scrollable body keeps symmetry -->
                    <div class="card-body objective-scroll">
                        <ul class="list-unstyled mb-0">
                            <li><i class="fa fa-hand-point-right text-danger"></i> जन स्वास्थ्य महत्व के असंचारी रोगों में कार्यान्वयन अनुसंधान करना</li>
                            <li><i class="fa fa-hand-point-right text-danger"></i> अन्य संस्थानों में कार्यान्वयन अनुसंधान क्षमताओं को सुदृढ बनाने के लिए मानव संसाधन विकसित करना और क्षमता निर्माण करना</li>
                            <li><i class="fa fa-hand-point-right text-danger"></i> असंचारी रोगों की रोकथाम, नियंत्रण और उपचार के लिए सूचना, शिक्षा और संचार (आई.ई.सी.) रणनीति और साधन विकसित करना</li>
                            <li><i class="fa fa-hand-point-right text-danger"></i> असंचारी रोगों की रोकथाम, नियंत्रण और उपचार के लिए नीतियां बनाने में नीति निर्माताओं और योजनाकारों को सिफारिशें प्रदान करना</li>
                            <li><i class="fa fa-hand-point-right text-danger"></i>असंचारी रोगों की रोकथाम, नियंत्रण और उपचार के लिए अभिनव समाधान विकसित करने हेतु अन्य संस्थानों, एजेंसियों और व्यक्तियों के साथ सहभागिता करना</li>
                            <li><i class="fa fa-hand-point-right text-danger"></i>संचारी रोगों और गैर-संचारी रोगों के बीच एक इंटरफेस बनाना</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

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

    <script src="./assets/js/datatables-demo.js"></script>
    <script src="./assets/datatables/jquery.dataTables.min.js"></script>
    <script src="./assets/datatables/dataTables.bootstrap4.min.js"></script>
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

</body>

</html>
