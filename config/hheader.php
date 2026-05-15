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
  <link rel="stylesheet" href="config/header.css"/>
</head>
<body>

<!-- ════════════════════════════════
     TOP UTILITY BAR
     ════════════════════════════════ -->
<div class="top-bar">
  <div class="top-bar-inner">

    <!-- Toggle Button -->
    <div class="toggle-btn">
      <i class="fa fa-universal-access"></i>
      <i class="fa fa-language"></i>
    </div>

    <!-- Content -->
    <div class="top-bar-right">
      <div class="font-btns">
        <button id="btn1" title="Increase font size">A+</button>
        <button id="btn2" title="Default font size">A</button>
        <button id="btn3" title="Decrease font size">A-</button>
      </div>

      <select class="lang-select" id="languageSwitcher">
        <option value="">Language</option>
        <option value="index.php">English</option>
        <option value="hindex.php">हिंदी</option>
      </select>
    </div>

  </div>
</div>


<!-- ════════════════════════════════
     BRAND / IDENTITY STRIP
     ════════════════════════════════ -->
<div class="brand-strip">
  <div class="brand-inner">

    <div class="brand-logo">
      <a href="index.php">
        <img src="assets/img/logo/nihrlogo.png" alt="ICMR-NIIRNCD Logo"
             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'"/>
        <div class="logo-fallback">ICMR</div>
      </a>
    </div>

    <div class="brand-text">
      <h1>राष्ट्रीय स्वास्थ्य अनुसंधान संस्थान, जोधपुर</h1>
      <h2>स्वास्थ्य अनुसंधान विभाग, स्वास्थ्य एवं परिवार कल्याण मंत्रालय, भारत सरकार<br></h2>
      <h1>National Institute of Health Research, Jodhpur</h1>
      <h2>
                        Department of Health Research, Ministry of Health and Family Welfare, Government of India</h2>
    </div>

  </div>
</div>


<!-- ════════════════════════════════
     STICKY NAVBAR
     ════════════════════════════════ -->
<div class="navbar-wrap">
  <div class="navbar-inner">

    <button class="hamburger" id="hamburger" aria-label="Toggle navigation" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>

    <ul class="nav-list" id="navList">

      <li><a href="hindex.php">मुखपृष्ठ</a></li>

      <li>
        <a href="#" role="button">हमारे बारे में<i class="fa fa-chevron-down caret"></i></a>
        <ul class="dropdown">
          <li><a href="habout-nihr.php">ICMR-NIHR के बारे में</a></li>
          <li><a href="hdgicmr.php">महानिदेशक ICMR के बारे में</a></li>
          <li><a href="habout-director.php">निदेशक के बारे में</a></li>
          <li><a href="habout-mrhrubk.php">MRHRU – क्षेत्र इकाई</a></li>
          <li><a href="hour-team.php">हमारी टीम</a></li>
          <li><a href="organogram.php">हमारा ऑर्गनोग्राम</a></li>
          <li><a href="committee.php">हमारी समिति</a></li>
          <li><a href="leadership.php">नेतृत्व से मिलें</a></li>
          <li><a href="former-directors.php">पूर्व निदेशक</a></li>
        </ul>
      </li>

      <li>
        <a href="#" role="button">अनुसंधान <i class="fa fa-chevron-down caret"></i></a>
        <ul class="dropdown">
          <li><a href="hresearch.php">वर्तमान अनुसंधान</a></li>
          <li><a href="hpublications.php">प्रकाशन</a></li>
          <li><a href="hannual-report.php">वार्षिक रिपोर्ट्स</a></li>
        </ul>
      </li>

      <li>
        <a href="#" role="button">अकादमिक<i class="fa fa-chevron-down caret"></i></a>
        <ul class="dropdown">
          <li><a href="phd.php">पीएचडी (AcSIR)</a></li>
          <li><a href="Internship.php">Internship/Dissertation</a></li>
        </ul>
      </li>

      <li>
        <a href="#" role="button">अधिसूचनायें <i class="fa fa-chevron-down caret"></i></a>
        <ul class="dropdown">
          <li><a href="hrecruitment.php">रिक्तियां</a></li>
          <li><a href="htenders.php">निविदाएं</a></li>
        </ul>
      </li>

      <li><a href="hevents.php">कार्यक्रम</a></li>

      <li>
        <a href="#" role="button">कर्मचारी कॉर्नर<i class="fa fa-chevron-down caret"></i></a>
        <ul class="dropdown">
          <li><a href="https://icmr.eoffice.gov.in/" target="_blank">ई-आफि़स</a></li>
          <li><a href="https://www.niirncd.org/salary_slip">ई-वेतन पर्ची जनरेशन</a></li>
          <li><a href="https://www.niirncd.org/esalary">ई-वेतन सॉफ्टवेयर</a></li>
          <li><a href="https://mail.gov.in">सरकारी ईमेल</a></li>
          <li><a href="./sci-admin/">वैज्ञानिकों का पैनल</a></li>
          <li><a href="hviewform.php">प्रपत्र</a></li>
          <li><a href="hviewcircular.php">परिपत्र</a></li>
          <li><a href="https://niirncd.org/vehicle_app/" target="_blank">वाहन आवेदन</a></li>
          <li><a href="https://dmrcjodhpur.attendance.gov.in/" target="_blank">Attendance Panel</a></li>
        </ul>
      </li>

      <li>
        <a href="#" role="button">मीडिया <i class="fa fa-chevron-down caret"></i></a>
        <ul class="dropdown">
          <li><a href="photogallery.php">फोटो गैलरी</a></li>
          <li><a href="#">वीडियो गैलरी</a></li>
          <li><a href="nihrnews.php">NIHR in News</a></li>
        </ul>
      </li>

      <li>
        <a href="#" role="button">लिंक<i class="fa fa-chevron-down caret"></i></a>
        <ul class="dropdown">
          <li><a href="http://www.icmr.gov.in/" target="_blank" rel="noopener">भारतीय आयुर्विज्ञान अनुसंधान परिषद &nbsp;<i class="fa fa-external-link-alt fa-xs"></i></a></li>
          <li><a href="http://www.dhr.gov.in/" target="_blank" rel="noopener">स्वास्थ्य अनुसंधान विभाग<i class="fa fa-external-link-alt fa-xs"></i></a></li>
          <li><a href="https://www.mohfw.gov.in/" target="_blank" rel="noopener">स्वास्थ्य और परिवार कल्याण विभाग<i class="fa fa-external-link-alt fa-xs"></i></a></li>
          <li><a href="calendar.php">कैलेंडर 2026</a></li>
          <li><a href="rti-act.php">RTI</a></li>
          <li><a href="screen-reader-help.php">स्क्रीन रीडर</a></li>
        </ul>
      </li>

      <li>
        <a href="#" role="button">संपर्क <i class="fa fa-chevron-down caret"></i></a>
        <ul class="dropdown">
          <li><a href="contact.php">संपर्क</a></li>
          <li><a href="directory.php">स्टाफ़ निदेशिका</a></li>
        </ul>
      </li>

    </ul>
  </div>
</div>

<div class="overlay" id="overlay"></div>


<!-- ════════════════════════════════
     SCRIPTS
     ════════════════════════════════ -->
<script>
  const ham     = document.getElementById('hamburger');
  const navL    = document.getElementById('navList');
  const overlay = document.getElementById('overlay');

  function closeDrawer() {
    ham.classList.remove('open');
    navL.classList.remove('open');
    overlay.classList.remove('show');
    ham.setAttribute('aria-expanded', 'false');
  }

  ham.addEventListener('click', () => {
    const isOpen = navL.classList.toggle('open');
    ham.classList.toggle('open');
    overlay.classList.toggle('show', isOpen);
    ham.setAttribute('aria-expanded', String(isOpen));
  });

  overlay.addEventListener('click', closeDrawer);

  navL.querySelectorAll('li').forEach(li => {
    const sub = li.querySelector('.dropdown');
    if (!sub) return;
    li.querySelector('a').addEventListener('click', e => {
      if (window.innerWidth <= 900) {
        e.preventDefault();
        navL.querySelectorAll('li.mob-open').forEach(other => {
          if (other !== li) other.classList.remove('mob-open');
        });
        li.classList.toggle('mob-open');
      }
    });
  });

  const root = document.documentElement;
  let basePx = 16;
  document.getElementById('btn1').addEventListener('click', () => {
    basePx = Math.min(basePx + 2, 22); root.style.fontSize = basePx + 'px';
  });
  document.getElementById('btn2').addEventListener('click', () => {
    basePx = 16; root.style.fontSize = '16px';
  });
  document.getElementById('btn3').addEventListener('click', () => {
    basePx = Math.max(basePx - 2, 12); root.style.fontSize = basePx + 'px';
  });
</script>

<script>
const toggleBtn = document.querySelector('.toggle-btn');
const panel = document.querySelector('.top-bar-inner');

toggleBtn.addEventListener('click', function (e) {
  e.stopPropagation();
  panel.classList.toggle('open');
});

/* Optional: close when clicking outside */
document.addEventListener('click', function(e){
  if(!panel.contains(e.target)){
    panel.classList.remove('open');
  }
});
</script>

<script>
document.getElementById("languageSwitcher").addEventListener("change", function () {
  if (this.value) {
    window.location.href = this.value;
  }
});
</script>



</body>
</html>