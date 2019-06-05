<?php
add_shortcode('shortcode_mobilecoach_send_message_prototype', function () {
	// Added deepstream library to lib folder.	
	require_once(ABSPATH . 'lib/deepstream.io-client-php-1.0.1/src/DeepstreamClient.php');
	global $wpdb;

	function send_external_message($participants = [], $variables)
	{
		$client = new \Deepstreamhub\DeepstreamClient("https://ba.eatnbyte.com/ds-http/", array('token' => '1;external-system;jwdki890-mgj92yeay9c;PLEASE_REPASTE_COMPLETE_TOKEN_FROM_MOBILE_COACH'));
		$result = $client->makeRpc('external-message', array('systemId' => 'jwdki890-mgj92yeay9c', 'participants' => $participants, 'variables' => $variables));

		return $result;
	}
	?>

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
	</style>

	<!-- HTML form. -->
	<div class="container-fluid vertical-center" bgcolor="#E6E6FA">
		<div class="col">
			<div class="row justify-content-center align-items-center">
				<div class="col">
					<div class="card">
						<div class="card-body">
							<form action="" autocomplete="off" method="post">
								<h2>Verbundene MobileCoach-Nutzer</h2>
								<table class="table table-hover">
									<thead>
										<tr>
											<th>Auswahl</th>
											<th>Nutzername</th>
											<th>E-Mail-Adresse</th>
											<th>MobileCoach-TeilnehmerID</th>
										</tr>
									</thead>
									<tbody>
										<?php

										$result = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'mc_participants AS wpmcp INNER JOIN ' . $wpdb->users . ' AS wpu ON wpmcp.wp_users_id=wpu.id');
										foreach ($result as $row) {

											echo '<tr>';
											echo '<td><input type="checkbox" class="form-check-input" name="participants[]" value="' . $row->participant_id . '"></td>';
											echo '<td><input type="hidden" name="display_name" value="' . $row->display_name . '">' . $row->display_name . '</td>';
											echo '<td><input type="hidden" name="mail" value="' . $row->user_email . '">' . $row->user_email . '</td>';
											echo "<td>$row->participant_id</td>";
											echo '</tr>';
										}
										?>
									</tbody>
								</table>	
								<div class="row" >
								<button type="submit" name="sendMessage" class="btn btn-lg btn-geton">Test Nachricht senden</button>
								<button type="submit" name="sendDialogA" class="btn btn-lg btn-geton">Dialog A starten</button>
								<button type="submit" name="sendDialogEnd" class="btn btn-lg btn-geton">Dialog beenden</button>
								<button type="submit" name="removeParticipant" class="btn btn-lg btn-geton">Lösche Alle MobileCoach Verbindungen</button>
								<button class="btn btn-lg btn-geton" onClick="window.location.href=window.location.href">Aktualisieren</button>
								<div class="w-100"></div>
								<label>			
								Anzahl ausgehender Test Nachrichten:
								<input type="number" min="1" max="25" step="1" value="1" size="2" name="messageCount">
								</label>
								</div>			
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="row mt-3 justify-content-left align-items-left">
				<div class="col">
					<p class="text-left" style="font-size: 180%; color: #dc3545;">
						<?php
						if ($_SERVER['REQUEST_METHOD'] === 'POST'){

							$participantss = array();
																					$result = $wpdb->get_results('SELECT a.participant_id, b.login_date
FROM '. $wpdb->prefix .'mc_participants a
       INNER JOIN (SELECT user_id,
                          user_login,
                          login_date
                   FROM '. $wpdb->prefix .'aiowps_login_activity
                   WHERE  ( user_id, user_login, login_date ) IN (SELECT
                          user_id,
                          user_login,
                          Max(login_date)
                                                                  FROM
                          '. $wpdb->prefix .'aiowps_login_activity
                          GROUP  BY user_id)
                          AND Datediff(Now(), login_date) > -7) b
               ON a.wp_users_id = b.user_id');
							
										foreach ($result as $row) {
											echo $row->participant_id;
											echo is_string($row->login_date) ? 'a' : 'b';
										}
						}
									
						if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['participants'])) {
							$participants = $_POST['participants'];
							$display_name = $_POST['display_name'];
							$mail = $_POST['mail'];
							$sendMessageCount = $_POST['messageCount'];
							
							if (isset($_POST['sendMessage'])) {
								for ($i = 0; $i < $sendMessageCount; $i++) {
								$result = send_external_message($participants, array('messageType' => 'Test', 'testFeldA' => "GET.ON Nutzer: $display_name", 'testFeldB' => "GET.ON Mail: $mail"));
								echo $result ? "Nachricht wurde versendet" : "Es ist ein Fehler aufgetreten. Nachricht konnte nicht verarbeitet werden. Siehe MobileCoach-Server Logs.";
								}	
							} else if (isset($_POST['sendDialogA'])) {
								$result = send_external_message($participants, array('messageType' => 'DialogA', 'nutzername' => "$display_name", 'kraftquelle' => "Am Ende wird alles gut. Wenn es nicht gut wird, ist es noch nicht das Ende."));
								echo $result ? "Nachricht wurde versendet" : "Es ist ein Fehler aufgetreten. Nachricht konnte nicht verarbeitet werden. Siehe MobileCoach-Server Logs.";
							} else if (isset($_POST['sendDialogEnd'])) {
								$result = send_external_message($participants, array('messageType' => 'DialogEnd', 'nutzername' => "$display_name"));
								echo $result ? "Nachricht wurde versendet" : "Es ist ein Fehler aufgetreten. Nachricht konnte nicht verarbeitet werden. Siehe MobileCoach-Server Logs.";
							}
						}

						if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['removeParticipant'])) {
							$wpdb->query("DELETE FROM " . $wpdb->prefix . "mc_participants");
						}
						?>
					</p>
				</div>
			</div>
		</div>
	</div>
<?php
});

