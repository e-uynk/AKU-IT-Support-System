<?php
// Veritabanı bağlantımızı dahil ediyoruz
require_once 'baglan.php';

$mesaj = ""; // Kullanıcıya gösterilecek bildirim mesajı değişkeni

// Form POST edildiyse (Gönder butonuna basıldıysa)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['talep_gonder'])) {
    
    // Formdan gelen tüm verileri alıyoruz
    $ad_soyad = trim($_POST['ad_soyad']);
    $telefon  = trim($_POST['telefon']);
    $birim    = trim($_POST['birim']);
    $oda_no   = trim($_POST['oda_no']);
    $kategori = trim($_POST['kategori']);
    $konu     = trim($_POST['konu']);

    // Zorunlu alanların kontrolü
    if (!empty($ad_soyad) && !empty($birim) && !empty($konu)) {
        
        // Veritabanına tüm sütunlarla birlikte yeni kaydı ekliyoruz
        $ekle = $db->prepare("INSERT INTO talepler (ad_soyad, telefon, birim, oda_no, kategori, konu, durum) VALUES (?, ?, ?, ?, ?, ?, 'Bekliyor')");
        $sonuc = $ekle->execute([$ad_soyad, $telefon, $birim, $oda_no, $kategori, $konu]);

        if ($sonuc) {
            $mesaj = '<div class="alert alert-success"><i class="bi bi-check-circle-fill me-2"></i> Destek talebiniz başarıyla alındı. En kısa sürede ilgileneceğiz.</div>';
        } else {
            $mesaj = '<div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i> Bir hata oluştu, lütfen tekrar deneyin.</div>';
        }
    } else {
        $mesaj = '<div class="alert alert-warning"><i class="bi bi-exclamation-circle-fill me-2"></i> Lütfen gerekli alanları doldurun.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BİDB - Arıza / Destek Talebi Oluştur</title>
    
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
            <div class="col-md-7">

                <!-- Kart Başlığı ve Logo -->
                <div class="text-center mb-4">
                    <img src="aku_logo.png" alt="AKÜ Logo" style="width: 120px;" class="mb-2">
                    <h4 class="fw-bold text-secondary">Afyon Kocatepe Üniversitesi</h4>
                    <h5 style="color: #0073c8;" class="fw-semibold">Bilgi İşlem Daire Başkanlığı Destek Sistemi</h5>
                </div>

                <!-- Bildirim Mesajı Alanı -->
                <?php echo $mesaj; ?>

                <!-- Form Kartı -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-aku-mavi text-white py-3">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="bi bi-pencil-square me-2"></i> Yeni Destek Talebi Oluştur
                        </h5>
                    </div>
                    <div class="card-body p-4">
                    <form action="" method="POST">
    
    <!-- Ad Soyad & Telefon -->
    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <label class="form-label fw-semibold">Adınız Soyadınız *</label>
            <input type="text" name="ad_soyad" class="form-control" placeholder="Örn: Dr. Ahmet Yılmaz" required>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Telefon / Dahili No</label>
            <input type="text" name="telefon" class="form-control" placeholder="Örn: 0272 218 XX XX veya Dahili 1234">
        </div>
    </div>

    <!-- Birim & Oda No -->
    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <label class="form-label fw-semibold">Fakülte / Birim / Bölüm *</label>
            <input type="text" name="birim" class="form-control" placeholder="Örn: Mühendislik Fakültesi" required>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Oda / Blok No</label>
            <input type="text" name="oda_no" class="form-control" placeholder="Örn: A-Blok Kat:2 Oda:204">
        </div>
    </div>

    <!-- Kategori Seçimi -->
    <div class="mb-3">
        <label class="form-label fw-semibold">Arıza / Talep Kategorisi *</label>
        <select name="kategori" class="form-select" required>
            <option value="Donanım (Kasa, Monitör, Yazıcı)">Donanım (Kasa, Monitör, Yazıcı vb.)</option>
            <option value="Ağ ve İnternet Bağlantısı">Ağ ve İnternet Bağlantısı</option>
            <option value="Yazılım ve EBYS / E-İmza">Yazılım ve EBYS / E-İmza</option>
            <option value="E-Posta ve Şifre İşlemleri">E-Posta ve Şifre İşlemleri</option>
            <option value="Diğer / Genel">Diğer / Genel</option>
        </select>
    </div>

    <!-- Talep Konusu -->
    <div class="mb-3">
        <label class="form-label fw-semibold">Arıza / Talep Konusu *</label>
        <textarea name="konu" class="form-control" rows="4" placeholder="Yaşadığınız sorunu veya talebinizi detaylıca açıklayınız..." required></textarea>
    </div>

    <button type="submit" name="talep_gonder" class="btn btn-primary w-100 py-2 fw-semibold" style="background-color: #0073c8; border-color: #0073c8;">
        <i class="bi bi-send-fill me-1"></i> Talebi Gönder
    </button>

</form>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>