<?php 
namespace Leonardo\LibrarySystem\helpers;
function sendJson($data, $status = 200){

// Limpeza
if(ob_get_length()) ob_clean();

header('Content-Type: application/json');
http_response_code($status);
echo json_encode($data);
exit;
}
?>