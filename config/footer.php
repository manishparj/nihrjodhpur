<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>ICMR-NIIRNCD Jodhpur</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&family=Noto+Sans+Devanagari:wght@400;600;700&family=Source+Sans+3:wght@300;400;600&display=swap" rel="stylesheet"/>

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

  <!-- Header Stylesheet -->
  <link rel="stylesheet" href="config/footer.css"/>
</head>
<body><!-- FOOTER -->
<footer>
  <div class="footer-area">
    <div class="container-fluid">

      <div class="footer-grid">
        <!-- Column 1 -->
        <div class="footer-col">
          <h4 class="footer-title">Social Media</h4>
          <div class="footer-divider"></div>
          <div class="footer-social">
            <a href="https://www.facebook.com/nihrjodhpur"><i class="fab fa-facebook-f"></i></a>
            <a href="https://x.com/nihrjodhpur"><i class="fab fa-x-twitter"></i></a>
            <a href="https://www.instagram.com/nihrjodhpur/"><i class="fab fa-instagram"></i></a>
            <a href="https://www.linkedin.com/company/nihrjodhpur"><i class="fab fa-linkedin"></i></a>
          </div>
        </div>

        <div class="footer-col">
          <h4 class="footer-title">Contact Us</h4>
          <div class="footer-divider"></div>
           <!-- <div class="footer-logo">
                                    <a href="index.php"><img src="assets/img/logo/nihrlogo.png" alt="" width="200px" style="border: 1px solid #fff;"></a>
                                </div> -->
                                <ul class="footer-info horizontal-links"> 
                                     <li>
                                        <i class="fa fa-map-marker"></i>
                                            <strong>ICMR-NIHR New Pali Road,<br>
                                                                        Jodhpur (Raj.)- 342005</strong>
                                    </li>
                                     <li> <strong><i class="fa fa-phone"></i>
                                            0291-2722403, <br> 0291-2720618</strong>
                                    </li>

                                    <li> <strong><i class="fa fa-envelope"></i>
                                            director-niirncd[at]icmr[dot]gov[dot]in</strong> </li>
                                </ul>

          </div>

        <!-- Column 2 -->
        <div class="footer-col">
          <h4 class="footer-title">Useful Links</h4>
          <div class="footer-divider"></div>

          <ul class="footer-links horizontal-links">
            <li><a href="about-nihr.php">About Us</a></li>
            <li><a href="our-team.php">Our Team</a></li>
            <li><a href="recruitment.php">Career</a></li>
            <li><a href="calendar.php">Calendar 2026</a></li>
            <li><a href="rti-act.php">RTI</a></li>
            <li><a href="tenders.php">Tenders</a></li>
            <li><a href="committee.php">Our Committee</a></li>
            <li><a href="directory.php">Staff Directory</a></li>
          </ul>
        </div>

        <!-- Column 3 -->
        <div class="footer-col">
          <h4 class="footer-title">Information</h4>
          <div class="footer-divider"></div>

          <ul class="footer-info horizontal-links">
            <li>
              <i class="fas fa-sync-alt"></i>
              <div>
                <strong>Last Updated</strong>
                <span>
                  <?php
                  $sql = "SELECT * from web_last_update_date";
                  $query = $dbh->prepare($sql);
                  $query->execute();
                  $results = $query->fetchAll(PDO::FETCH_OBJ);
                  if ($query->rowCount() > 0) {
                      foreach ($results as $result) {
                          echo date_format(date_create_from_format('Y-m-d', $result->date), 'd/m/Y');
                      }
                  }
                  ?>
                </span>
              </div>
            </li>

            <li>
              <i class="fas fa-globe"></i>
              <div>
                <strong>Visitor Count:</strong>
                <?php include 'counter.php'; ?>
              </div>
            </li>
          </ul>
        </div>

      </div>

      <!-- Bottom Bar -->
      <div class="footer-bottom">
        <div>
          © <span id="copyrightYear"></span>
          <strong>ICMR-NIHR Jodhpur</strong>. All rights reserved.
        </div>
      </div>

    </div>
  </div>
</footer>

<script>
// Dynamic Year
document.getElementById('copyrightYear').textContent = new Date().getFullYear();

</script>
</body>
</html>