<?php

$result = $expr ? true : false;
$result = $expr ? false : true;
$result = ( $expr ? true : false );
$result = ( $expr ? false : true );

{
	// Missing semicolon on purpose.
	return $expr ? true : false
}
