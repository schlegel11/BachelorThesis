<?php
add_shortcode( 'shortcode_geton_mobilecoach_connection_login_form', function () { ?>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.1/css/all.css">
<!-- Bootstrap core CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
<!-- Material Design Bootstrap -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.8.0/css/mdb.min.css" rel="stylesheet">
<!-- SCRIPTS -->
<!-- JQuery -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<!-- Bootstrap tooltips -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.15.0/popper.min.js"></script>
<!-- Bootstrap core JavaScript -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
<!-- MDB core JavaScript -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.8.0/js/mdb.min.js"></script>

<style>
	.invalid-feedback {
		display: none;
		width: 100%;
		margin-top: 0.25rem;
		font-size: 110%;
		color: #dc3545;
	}

	.vertical-center {
		min-height: 100%;
		min-height: 100vh;
		display: flex;
		align-items: center;
	}

	.btn-geton {
		color: #ffffff;
		background-color: #E9730C;
		border-color: #D67520;
	}

	.btn-geton:hover,
	.btn-geton:focus,
	.btn-geton:active,
	.btn-geton.active,
	.open .dropdown-toggle.btn-geton {
		color: #ffffff;
		background-color: #FF9500;
		border-color: #D67520;
	}

	.btn-geton:active,
	.btn-geton.active,
	.open .dropdown-toggle.btn-geton {
		background-image: none;
	}

	.btn-geton.disabled,
	.btn-geton[disabled],
	fieldset[disabled] .btn-geton,
	.btn-geton.disabled:hover,
	.btn-geton[disabled]:hover,
	fieldset[disabled] .btn-geton:hover,
	.btn-geton.disabled:focus,
	.btn-geton[disabled]:focus,
	fieldset[disabled] .btn-geton:focus,
	.btn-geton.disabled:active,
	.btn-geton[disabled]:active,
	fieldset[disabled] .btn-geton:active,
	.btn-geton.disabled.active,
	.btn-geton[disabled].active,
	fieldset[disabled] .btn-geton.active {
		background-color: #E9730C;
		border-color: #D67520;
	}

	.btn-geton .badge {
		color: #E9730C;
		background-color: #ffffff;
	}
</style>

<!-- HTML login form. -->
<div class="container-fluid vertical-center" bgcolor="#E6E6FA">
	<div class="col">
		<div class="row justify-content-center align-items-center">
			<div class="col">
				<div class="card">
					<img class="card-img-top rounded mx-auto d-block" src="https://coach.geton-training.de/wp-content/uploads/2018/02/getonlogo.png" alt="Card image cap" style="width: 18rem; margin: 0 auto; position: relative; left: 16px;">
					<div class="card-body">
						<form action="" autocomplete="off" method="post" class="needs-validation" novalidate>
							<div class="form-group">
								<input type="text" class="form-control form-control-lg" name="loginUser" placeholder="Benutzername oder E-Mail-Adresse *" value="<?php echo isset($_POST['loginUser']) ? $_POST['loginUser'] : '' ?>" required>
								<div class="invalid-feedback">
									Bitte einen Benutzernamen oder E-Mail-Adresse angeben.
								</div>
							</div>
							<div class="form-group">
								<input type="password" class="form-control form-control-lg" name="loginPwd" placeholder="Passwort *" required>
								<div class="invalid-feedback">
									Bitte ein Passwort angeben.
								</div>
							</div>
							<button type="submit" name="loginSubmit" value="ls" id="loginSubmit" class="btn btn-lg btn-geton">Mit GET.ON verknüpfen</button>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="row mt-3 justify-content-left align-items-left">
			<div class="col">
				<p class="text-left" style="font-size: 180%; color: #dc3545;">
					<?php
					if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['loginSubmit']))
					{
						$participant_id = array_shift(explode('?', $_GET['pid']));
						$user = $_POST['loginUser'];
						$password = $_POST['loginPwd'];
						echo do_shortcode("[shortcode_connect_mobile_coach user=$user password=$password participant_id=$participant_id]");
					}
					?>
				</p>
			</div>
		</div>
	</div>
</div>

<!-- Validate html/php form elements. -->
<script>
	(function() {
		'use strict';
		window.addEventListener('load', function() {
			// Fetch all the forms we want to apply custom Bootstrap validation styles to
			var forms = document.getElementsByClassName('needs-validation');
			// Loop over them and prevent submission
			var validation = Array.prototype.filter.call(forms, function(form) {
				form.addEventListener('submit', function(event) {
					if (form.checkValidity() === false) {
						event.preventDefault();
						event.stopPropagation();
					}
					form.classList.add('was-validated');
				}, false);
			});
		}, false);
	})();
</script>
<?php
});

