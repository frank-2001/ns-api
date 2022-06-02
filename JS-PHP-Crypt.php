<?php
$word=rand(0,1000000000);
echo $word.' ===> '.openssl_encrypt($word, "AES-128-ECB" ,'nunua-store').' ===> '.openssl_decrypt(openssl_encrypt($word, "AES-128-ECB" ,'nunua-store'), "AES-128-ECB" ,'nunua-store');
$encoded = "18000o21375o21375o21375o21375o";   // <-- encoded string from the request
$decoded = "";
$encoded = explode('o', $encoded);
// print_r($encoded);
for( $i = 0; $i < count($encoded)-1; $i++ ) {
    $a = intval($encoded[$i])/375;
    // echo '-'.$a.'-'.chr($a);
    $encoded[$i]=chr($a);
    $decoded=$decoded.''.$encoded[$i];
    // $decoded = $decoded.''.chr($a);
}
$encoded=implode('-',$encoded);
// print_r($decoded);
?>
<html>
<head><title></title></head>
<body>
<script type="text/javascript">
function enc(str) {
    var encoded = "";
    for (i=0; i<str.length;i++) {
        var a = str.charCodeAt(i);
        var b = a*375+'o';    // bitwise XOR with any number, e.g. 123
        encoded = encoded+b;
    }
    // alert(encoded)
    return encoded;
}
var str =9999+'';
var encoded = enc(str);
// alert(encoded);           // shows encoded string
// alert(enc(encoded));      // shows the original string again
</script>
</body>
</html>
