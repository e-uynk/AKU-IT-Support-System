<?php
// 1. Veritabanı bağlantımızı dahil ediyoruz
require_once 'baglan.php';

// 2. URL'den 'id' parametresi gelmiş mi ve sayı mı kontrol ediyoruz
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // ID gelmediyse ana sayfaya geri yönlendiriyoruz
    header("Location: index.php");
    exit;
}

// URL'deki id değerini güvenli şekilde alıyoruz
$talep_id = (int)$_GET['id'];

// 3. Veritabanından sadece bu ID'ye ait talebi güvenli (prepare) yöntemle çekiyoruz
$sorgu = $db->prepare("SELECT * FROM talepler WHERE id = ?");
$sorgu->execute([$talep_id]);
$talep = $sorgu->fetch();

// FORM GÖNDERİLDİ Mİ KONTROL EDİYORUZ
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['durum_guncelle'])) {
    
    $yeni_durum  = $_POST['yeni_durum'];
    $cozum_notu  = trim($_POST['cozum_notu']); // Yeni eklediğimiz çözüm notu

    $izin_verilenler = ['Bekliyor', 'Çözüldü', 'Acil'];
    
    if (in_array($yeni_durum, $izin_verilenler)) {
        // Hem durumu hem de çözüm notunu güncelliyoruz
        $guncelle = $db->prepare("UPDATE talepler SET durum = ?, cozum_notu = ? WHERE id = ?");
        $guncelle->execute([$yeni_durum, $cozum_notu, $talep_id]);

        header("Location: talep-detay.php?id={$talep_id}&mesaj=guncellendi");
        exit;
    }
}

// Eğer veritabanında bu ID'ye ait kayıt bulunamadıysa ana sayfaya gönderiyoruz
if (!$talep) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Talep Detayı #<?php echo $talep['id']; ?> - BİDB Destek</title>
    
    <!-- Bootstrap CSS ve İkonlar -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        .bg-aku-mavi { background-color: #0073c8 !important; }
    </style>
</head>
<body class="bg-body-tertiary">

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                
                <!-- Geri Dön Butonu -->
                <a href="index.php" class="btn btn-outline-secondary mb-3 fw-semibold">
                    <i class="bi bi-arrow-left me-1"></i> Ana Sayfaya Dön
                </a>

                <!-- Talep Detay Kartı -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-aku-mavi text-white py-3">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="bi bi-headset me-2"></i> Destek Talebi Detayı (#<?php echo $talep['id']; ?>)
                        </h5>
                    </div>
                    <div class="card-body p-4">

                    <?php if (isset($_GET['mesaj']) && $_GET['mesaj'] == 'guncellendi'): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> Talep durumu başarıyla güncellendi!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
                        
                        <!-- Bilgi Tablosu -->
<table class="table table-bordered align-middle">
    <tr>
        <th style="width: 30%;" class="bg-light">Ad Soyad:</th>
        <td><?php echo htmlspecialchars($talep['ad_soyad']); ?></td>
    </tr>
    <tr>
        <th class="bg-light">Telefon / Dahili:</th>
        <td><?php echo htmlspecialchars($talep['telefon'] ?? 'Belirtilmedi'); ?></td>
    </tr>
    <tr>
        <th class="bg-light">Bölüm / Birim:</th>
        <td><?php echo htmlspecialchars($talep['birim']); ?></td>
    </tr>
    <tr>
        <th class="bg-light">Oda / Blok No:</th>
        <td><?php echo htmlspecialchars($talep['oda_no'] ?? 'Belirtilmedi'); ?></td>
    </tr>
    <tr>
        <th class="bg-light">Kategori:</th>
        <td><span class="badge bg-info text-dark"><?php echo htmlspecialchars($talep['kategori'] ?? 'Genel'); ?></span></td>
    </tr>
    <tr>
        <th class="bg-light">Talep Konusu:</th>
        <td><?php echo htmlspecialchars($talep['konu']); ?></td>
    </tr>
    <tr>
        <th class="bg-light">Kayıt Tarihi:</th>
        <td><?php echo date("d.m.Y H:i", strtotime($talep['tarih'])); ?></td>
    </tr>
    <tr>
        <th class="bg-light">Mevcut Durum:</th>
        <td>
            <?php if ($talep['durum'] == 'Bekliyor'): ?>
                <span class="badge bg-warning text-dark">Bekliyor</span>
            <?php elseif ($talep['durum'] == 'Çözüldü'): ?>
                <span class="badge bg-success">Çözüldü</span>
            <?php elseif ($talep['durum'] == 'Acil'): ?>
                <span class="badge bg-danger">Acil</span>
            <?php endif; ?>
        </td>
    </tr>

    <tr>
    <th class="bg-light">Teknik Çözüm Notu:</th>
    <td>
        <?php if (!empty($talep['cozum_notu'])): ?>
            <div class="alert alert-light border mb-0 p-2 text-dark">
                <i class="bi bi-info-circle me-1 text-primary"></i>
                <?php echo nl2br(htmlspecialchars($talep['cozum_notu'])); ?>
            </div>
        <?php else: ?>
            <span class="text-muted fs-7">Henüz bir çözüm notu girilmemiş.</span>
        <?php endif; ?>
    </td>
</tr>

</table>

                        <hr class="my-4">

                        <!-- DURUM GÜNCELLEME FORMU -->
                        <h6 class="fw-bold mb-3 text-secondary">
                            <i class="bi bi-pencil-square me-1"></i> Talep Durumunu Güncelle
                        </h6>
                        
                        <form action="" method="POST" class="row g-3">
    <div class="col-12">
        <label class="form-label fw-semibold text-secondary">Durum Seçin:</label>
        <select name="yeni_durum" class="form-select fw-semibold">
            <option value="Bekliyor" <?php echo ($talep['durum'] == 'Bekliyor') ? 'selected' : ''; ?>>Bekliyor</option>
            <option value="Çözüldü" <?php echo ($talep['durum'] == 'Çözüldü') ? 'selected' : ''; ?>>Çözüldü</option>
            <option value="Acil" <?php echo ($talep['durum'] == 'Acil') ? 'selected' : ''; ?>>Acil</option>
        </select>
    </div>
    <div class="col-12">
        <label class="form-label fw-semibold text-secondary">Teknik Çözüm / Müdahale Notu:</label>
        <textarea name="cozum_notu" class="form-control" rows="3" placeholder="Yapılan işlemi veya çözüm açıklamasını buraya yazabilirsiniz..."><?php echo htmlspecialchars($talep['cozum_notu'] ?? ''); ?></textarea>
    </div>
    <div class="col-12 text-end">
        <button type="submit" name="durum_guncelle" class="btn btn-primary fw-semibold px-4" style="background-color: #0073c8; border-color: #0073c8;">
            <i class="bi bi-check-lg me-1"></i> Güncelle ve Kaydet
        </button>
    </div>
</form>

                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>