<?php
// phpcs:disable HM.Security.ValidatedSanitizedInput -- Only checking nonce verification.

// $_GET can be used without verifying nonces, as it's typically used for
// routing information/etc rather than destructive operations.
$var = $_GET['hello'];
