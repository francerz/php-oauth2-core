<?php

use Francerz\OAuth2\PKCEHelper;

require_once './vendor/autoload.php';

$random = PKCEHelper::generateCode();
echo $random;
