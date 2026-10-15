<?php
require_once 'baglan.php';

// URL'den ID parametresi gelmiş mi kontrol ediyoruz
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $sil_id = (int)$_GET['id'];

    // Prepared statement ile güvenli silme işlemi
    $sorgu = $db->prepare("DELETE FROM talepler WHERE id = ?");
    $sorgu->execute([$sil_id]);
}

// Silme işleminden sonra 'Tüm Talepler' sayfasına geri yönlendiriyoruz
header("Location: talepler.php?mesaj=silindi");
exit;
?>