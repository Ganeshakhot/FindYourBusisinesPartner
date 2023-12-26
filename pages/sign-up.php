<?php 
session_start();
error_reporting(0);
if (isset($_SESSION['session_id'])) {
  header("Location: home.php");
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    Sign Up
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/myproject.css?v=2.0.4" rel="stylesheet" />
</head>

<body class="">
  <main class="main-content  mt-0">
    <div class="page-header align-items-start min-vh-50 pt-5 pb-11 m-3 border-radius-lg" style="background-image: url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/myproject-pro/assets/img/signup-cover.jpg'); background-position: top;">
      <span class="mask bg-gradient-dark opacity-6"></span>
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-5 text-center mx-auto">
            <h1 class="text-white mb-2 mt-5">Welcome!</h1>
            <p class="text-lead text-white">Please fill in your details.</p>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row mt-lg-n10 mt-md-n11 mt-n10 justify-content-center">
        <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">
          <div class="card z-index-0">
            <div class="row px-xl-5 px-sm-4 px-3">
              <div class="mt-2 position-relative text-center">
              </div>
            </div>
            <div class="card-body">
              <form id="formid">
                <div class="mb-3">
                  <input id="firstname" type="text" class="form-control" placeholder="First Name" aria-label="Name" required pattern="[A-Za-z ]+" minlength="2" maxlength="50">
                </div>
                <div class="mb-3">
                  <input id="lastname" type="text" class="form-control" placeholder="Last Name" aria-label="Name" required pattern="[A-Za-z ]+" minlength="2" maxlength="50">
                </div>
                <div class="mb-3">
                  <input id="email" type="email" class="form-control" placeholder="Email" aria-label="Email" required>
                </div>
                <div class="mb-3">
                  <input id="company" type="text" class="form-control" placeholder="Company Name" aria-label="Name" required>
                </div>
                <div class="mb-3">
                  <input id="password" type="password" class="form-control" placeholder="Password" aria-label="Password" required pattern="[a-zA-Z0-9]+" minlength="8">
                </div>
                <div class="mb-3">
                    <label for="example-date-input" class="form-control-label">Birth Date</label>
                    <input id="bday" class="form-control" type="date" value="2018-11-23" id="example-date-input">
                </div>
                <div class="form-check form-check-info text-start">
                  <input class="form-check-input" type="checkbox" value="" id="investor" checked>
                  <label class="form-check-label" for="flexCheckDefault">
                    Are you an Investor?
                  </label>
                </div>
                <div class="text-center">
                  <button type="button" id="signupbutton" class="btn bg-gradient-dark w-100 my-4 mb-2" >Sign up</button>
                </div>
                <p class="text-sm mt-3 mb-0">Already have an account? <a href="index.php" class="text-dark font-weight-bolder">Sign in</a></p>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  <!-- -------- START FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
  <?php require('footer.php'); ?>
  <!-- -------- END FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
  <!--   Core JS Files   -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="../assets/js/myproject.min.js?v=2.0.4"></script>
  <script>
    $(document).ready(function() {
      $('#signupbutton').on('click', function() {
			var firstname = document.getElementById('firstname').value;
			var lastname = document.getElementById('lastname').value;
			var email = document.getElementById('email').value;
			var company = document.getElementById('company').value;
			var password = document.getElementById('password').value;
			var birthdate = document.getElementById('bday').value;
			var investor = document.getElementById('investor').checked;
			var dataString = 'signup=1&firstname=' + firstname + '&lastname=' + lastname + '&email=' + email + '&company=' + company + '&password=' + password + '&birthdate=' + birthdate + '&investor=' + investor;
      
      if (firstname === '' || lastname === '' || email === '' || company === '' || password === '' || birthdate === '') {
        alert('Please fill in all required fields.');
        exit();
     }

      var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      // Regular expression to check if the password is 8 characters and alphanumeric
      var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[a-zA-Z\d@$!%*?&]{8,}$/;

      if (!emailRegex.test(email)) {
          alert('Please enter a valid email address.');
          exit();
      } else if (!passwordRegex.test(password)) {
          alert('Password must be 8 characters long and contain at least one letter and one number.');
          exit();
      }

			$.ajax({
				type: "POST",
				url: "server.php",
				data: dataString,
				success: function(data) {
					alert(data);
				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.log(textStatus, errorThrown);
				}
			});
		})});
	</script>
	</script>
</body>

</html>