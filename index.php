<?php

define('PASS_PHRASE', 'PassphraseHere'); // Encryption passphrase
define('ENC_HEADER', 'encv1'); // Encryption token header

require __DIR__ . '/basic/Basic.php'; // BasicPHP library

/** Routing **/
Basic::route('GET', '/', function() { // Homepage
	include __DIR__ . '/includes/register.php';
});

Basic::route('ANY', '/register', function() {
	include __DIR__ . '/includes/register.php';
});

Basic::route('ANY', '/login', function() {
	$encrypted = base64_decode($_GET['token']);
	if ( substr($encrypted, 0, 5) !== ENC_HEADER && ! empty($_GET['token']) ) Basic::apiResponse(401, 'Invalid auth token.'); // Require valid token

	include __DIR__ . '/includes/login.php';
});

Basic::route('GET', '/private', function() {
	if (! isset($_COOKIE['token']) || empty($_COOKIE['token'])) Basic::apiResponse(401, 'No auth token.'); // Require token

	$plaintext = Basic::decrypt($_COOKIE['token'], PASS_PHRASE);
	if ( substr($_COOKIE['token'], 0, 5) !== ENC_HEADER || ! $plaintext) Basic::apiResponse(401, 'Invalid auth token.'); // Require valid token

	include __DIR__ . '/includes/private.php';
});

Basic::route('GET', '/logout', function() {
	setcookie('token', '');
	header('Location: ' . Basic::baseUrl() . 'register');
	exit;
});

Basic::apiResponse(404); // Error 404