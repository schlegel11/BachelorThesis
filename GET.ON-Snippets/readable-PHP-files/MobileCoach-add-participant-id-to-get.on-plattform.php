<?php
// Result class for success and error status/messages and a result object.
class Result
{
	public $success;
	public $failure;
	public $message;
	public $result_object;

	private function __construct($s)
	{
		$this->success = $s;
		$this->failure = !$s;
	}

	public static function success()
	{
		return new Result(true);
	}

	public static function failure()
	{
		return new Result(false);
	}

	public function message($em)
	{
		$this->message = $em;
		return $this;
	}

	public function result_object($ro)
	{
		$this->result_object = $ro;
		return $this;
	}
}
// Validate the logindata.
function check_user_auth($user, $password)
{
	return is_email($user) ? wp_authenticate_email_password(NULL, $user, $password) : wp_authenticate_username_password(NULL, $user, $password);
}
// Check logindata and return success or failure result.
function auth_user($user, $password)
{
	$result = check_user_auth($user, $password);

	if (is_wp_error($result)) {

		switch ($result->get_error_code()) {
			case "empty_username":
				return Result::failure()->message("Bitte einen Benutzernamen oder E-Mail-Adresse angeben.");
			case "empty_password":
				return Result::failure()->message("Bitte ein Passwort angeben.");
			case "invalid_username":
				return Result::failure()->message("Benutzername/E-Mail-Adresse oder Passwort ist ungültig. Probiere es nochmal.");
			case "incorrect_password":
				return Result::failure()->message("Benutzername/E-Mail-Adresse oder Passwort ist ungültig. Probiere es nochmal.");
			default:
				return Result::failure()->message("Ein Fehler ist aufgetreten. Bitte wende dich an den Administrator.");
		}
	}
	return Result::success()->result_object($result);
}
// Define the table name.
function get_participant_table_name()
{
	global $wpdb;
	return $wpdb->prefix . "mc_participants";
}
// If table wp_mc_participants doesn't exist create it.
function create_participant_table()
{
	global $wpdb;
	$table_exists = function ($table) use (&$wpdb) {
		return $wpdb->get_var("SHOW TABLES LIKE '{$table}'") == $table;
	};

	$table_name = get_participant_table_name();
	$charset_collate = $wpdb->get_charset_collate();

	if (!$table_exists($table_name)) {
		$sql = "CREATE TABLE $table_name (
            wp_users_id bigint(20) UNSIGNED NOT NULL,
            participant_id varchar(30) NOT NULL,
			FOREIGN KEY  (wp_users_id) REFERENCES wp_users(ID),
            PRIMARY KEY  (wp_users_id,participant_id)
        ) $charset_collate;";

		if (!function_exists('dbDelta')) {
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		}

		dbDelta($sql);

		if (!$table_exists($table_name)) {
			return Result::failure()->message("Konnte Tabelle nicht erstellen. Bitte wende dich an den Administrator.");
		}
	}

	return Result::success();
}
// Insert GET.ON user and participant ID in table wp_mc_participants.
function insert_wp_user_mc_participant($wp_user, $participant_id)
{
	global $wpdb;

	$table_name = get_participant_table_name();
	$row_exists = function ($user, $pid) use (&$wpdb, &$table_name) {
		return $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(*) FROM $table_name
                WHERE wp_users_id = %d
                AND participant_id = %s",
				$user,
				$pid
			)
		) > 0;
	};

	if (empty($participant_id)) {
		return Result::failure()->message("Participant id konnte nicht ermittelt werden. Bitte wende dich an den Administrator.");
	}
	if ($row_exists($wp_user->ID, $participant_id)) {
		return Result::failure()->message("Du bist bereits mit der GET.ON Plattform verbunden. Es sind keine weiteren Schritte erforderlich.");
	}

	$result = $wpdb->insert(
		$table_name,
		array(
			"wp_users_id"   => $wp_user->ID,
			"participant_id"   => $participant_id
		)
	);
	return is_bool($result) && !$result ? Result::failure()->message("Kein insert möglich. Bitte wende dich an den Administrator.") : Result::success();
}
// Compose functions for calling it via shortcode.
add_shortcode('shortcode_connect_mobile_coach', function ($attributes) {
	$validAtts = shortcode_atts(array(
		'user' => '',
		'password' => '',
		'participant_id' => ''
	), $attributes);
	// Check logindata.
	$result = auth_user($validAtts[user], $validAtts[password]);
	if ($result->failure) {
		return $result->message;
	}
	// Create table if it doesn't exist.
	$wp_user = $result->result_object;
	$result = create_participant_table();
	if ($result->failure) {
		return $result->message;
	}
	// Insert data record
	$result = insert_wp_user_mc_participant($wp_user, $validAtts[participant_id]);
	if ($result->failure) {
		return $result->message;
	}
	// If everything is successful send javascript back to the MobileCoach App for closing the webview.
	return <<<EOF
		<script type="text/javascript">
			function checkReady() {
				if (window.postMessage != undefined && window.postMessage != null && typeof window.postMessage === 'function' && window.postMessage.length === 1) {
					sendResults();
				} else {
					setTimeout(checkReady, 100);
				}
			}
			function sendResults() {

				window.postMessage('close');
			}
			$(document).ready(function() {
				setTimeout(checkReady, 100);
			});
		</script>
EOF;
});

