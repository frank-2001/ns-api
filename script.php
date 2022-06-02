<?php 
require 'bdd.php';
require 'functions.php';
print_r(getData('article',['1','1'],$bdd));
?>