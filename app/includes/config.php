<?php
define("ENV", "WEBDEV");
// define("ENV", "DEV");
// define("ENV", "PROD");
if (ENV === "DEV" || ENV === "WEBDEV") define("MOCK_NOW", "2023-07-04 20:00:00");
