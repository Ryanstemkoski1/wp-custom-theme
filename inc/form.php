<?php
   
   // Ajax started//
   if(is_user_logged_in()) {
    add_action( 'wp_ajax_get_ajaxLoginform', 'get_ajaxLoginform' );
   }else {
    add_action( 'wp_ajax_nopriv_get_ajaxLoginform', 'get_ajaxLoginform' );
   }

   function get_ajaxLoginform() {

    $email = $_POST['email'];
    $pass = $_POST['pass'];

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://app.cinchshare.com/frontEnd/login/userName',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => array('Username' => ''.$email.'','password' => ''.$pass.''),
      CURLOPT_HTTPHEADER => array(
        'Content-Type: multipart/form-data'
      ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    echo $response;
    die();
  }

  
add_action( 'gform_after_submission_5', 'pre_submission_handler' );

function pre_submission_handler($entry ) {
		$fname = $entry['1.3'];
		$lname = $entry['1.6'];
		$timezone = $entry['4'];
		$email = $entry['5'];
		$password = $entry['6'];
		$promo = $entry['8'];

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://app.cinchshare.com/frontEnd/create',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => array('firstName' => ''.$fname.'','lastName' => ''.$lname.'','timezoneId' => ''.$timezone.'','email'=>''.$email.'', 'password' => ''.$password.'','promoCode' => ''.$promo.''),
			CURLOPT_HTTPHEADER => array(
				'Content-Type: multipart/form-data'
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		$res = json_decode($response);
		var_dump($response);

		if($res->ErrorMessage == null && $res->Redirect){
			wp_redirect('/login');
			exit;
		}
}


add_filter( 'gform_confirmation_6', 'custom_confirmation', 10, 4 );

function custom_confirmation($confirmation,$form, $entry) {
  $body     = array(
    'first_name' => rgar( $entry, '1' ),
    'last_name'  => rgar( $entry, '3' ),
    'email'    => rgar( $entry, '4' ),
    'postId'    => rgar( $entry, '6' ),
  );

  $confirmation = array( 'redirect' => get_field('post_download_file', $body['postId']) );
  return $confirmation;
} 