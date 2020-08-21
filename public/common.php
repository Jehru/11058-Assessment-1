<?php
//This file holds commonly used functions


//Getting Rid of characters that wont fit nicely into a url
function escape($html) { return htmlspecialchars($html, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8"); }

?>