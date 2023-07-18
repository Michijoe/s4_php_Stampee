<?php
define("ENV", "DEV");
// define("ENV", "PROD");
if (ENV === "DEV") define("MOCK_NOW", "2023-07-01 20:00:00");
