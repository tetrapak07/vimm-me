<?php

return [
	
	/**
	 * Application Name.
	 */
	'application_name' => 'Your Application',

	/**
	 * Client ID.
	 */
	'client_id' => '884352965184-kp2h9jha7k2u2tl3r2nrt039jmsdbu4l.apps.googleusercontent.com',//getenv('GOOGLE_CLIENT_ID'),

	/**
	 * Client Secret.
	 */
	'client_secret' => 'eTqR_WWTRsnDDfHvswKeVuK_',//getenv('GOOGLE_CLIENT_SECRET'),

	/**
	 * Route Base URI. You can use this to prefix all route URI's.
	 * Example: 'admin', would prefix the below routes with 'http://domain.com/admin/'
	 */
	'route_base_uri' => '',

	/**
	 * Redirect URI, this does not include your TLD.
	 * Example: 'callback' would be http://domain.com/callback
	 */
	'redirect_uri' => 'https://vimm.me',

	/**
	 * The autentication URI in with you will require to first authorize with Google.
	 */
	'authentication_uri' => 'youtube-auth',

	/**
	 * Access Type
	 */
	'access_type' => 'offline',

	/**
	 * Approval Prompt
	 */
	'approval_prompt' => 'auto',

	/**
	 * Scopes.
	 */
	'scopes' => [
		'https://www.googleapis.com/auth/youtube',
		'https://www.googleapis.com/auth/youtube.upload',
		'https://www.googleapis.com/auth/youtube.readonly'
	],

	/**
	 * Developer key.
	 */
	'developer_key' =>'AIzaSyCmuaN97vA6x7E-V1n_fzd_zy6b9lEsc_Q'// getenv('GOOGLE_DEVELOPER_KEY')

];
