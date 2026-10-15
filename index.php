<?php

require_once 'baglan.php';
// Veritabanındaki tüm talepleri sayıyoruz
$sorgu_toplam = $db->query("SELECT COUNT(*) as toplam FROM talepler");
$toplam_talep = $sorgu_toplam->fetch()['toplam'];

// Bekleyen, Çözülen ve Acil talep sayılarını çekiyoruz
$bekleyen_talep = $db->query("SELECT COUNT(*) FROM talepler WHERE durum = 'Bekliyor'")->fetchColumn();
$cozulen_talep  = $db->query("SELECT COUNT(*) FROM talepler WHERE durum = 'Çözüldü'")->fetchColumn();
$acil_talep     = $db->query("SELECT COUNT(*) FROM talepler WHERE durum = 'Acil'")->fetchColumn();

// Son eklenen 5 talebi veritabanından çekiyoruz (id DESC: En yeni kayıt en üstte)
$sorgu_talepler = $db->query("SELECT * FROM talepler ORDER BY id DESC LIMIT 5");
$talepler_listesi = $sorgu_talepler->fetchAll();

// PHP Kod Bloğu Başlangıcı
// $sayfa_basligi isminde bir metin (string) değişkeni tanımlıyoruz
$sayfa_basligi = "Kontrol Paneli"; // İleride bu başlığı dinamik hale getireceğiz
// PHP Kod Bloğu Bitişi
?>

<!DOCTYPE html> <!-- Tarayıcıya bu belgenin modern bir HTML5 belgesi olduğunu bildirir -->
<html lang="tr"> <!-- Sayfanın varsayılan dilini Türkçe olarak ayarlar -->
<head> <!-- Sayfanın ayarlarının, başlığının ve kütüphane linklerinin bulunduğu görünmeyen kafa bölümü -->
    
    <meta charset="UTF-8"> <!-- Türkçe karakterlerin (ğ, ş, ı, ö, ü, Ç) düzgün görüntülenmesini sağlar -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Sayfanın mobil cihazlarda ekran boyutuna tam uyum sağlamasını (Responsive) ayarlar -->
    
    <title>BİDB - Destek Yönetim Paneli</title> <!-- Tarayıcı sekmesinde görünen sayfa başlığı -->

    <!-- Bootstrap CSS: Sayfanın temel ızgara (grid) ve kutu hizalama kurallarını internetten çeker -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons: Butonlarda ve menülerde kullandığımız ikon simgelerini getirir -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- AdminLTE CSS: Proje klasörüne attığımız 'dist' içindeki ana tema tasarım dosyamız -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">

    <style> /* Özel CSS stil kodlarımızı yazdığımız bölüm */
        /* AKÜ Kurumsal Açık Mavi Rengi Sınıfı */
        .bg-aku-mavi {
            background-color: #0073c8 !important; /* AKÜ logosundaki canlı açık mavi renk kodu */
        }
        /* Menüde aktif (seçili) olan halkanın arka plan rengini AKÜ mavisi yapar */
        .sidebar-menu .nav-link.active {
            background-color: #0073c8 !important; /* Seçili menüyü mavi yap */
            color: #ffffff !important; /* İçindeki yazıyı bembeyaz yap */
        }
    </style> <!-- Özel CSS bölümü bitişi -->

</head> <!-- Kafa bölümünün bitişi -->

<!-- data-bs-theme="light": Tüm sayfayı aydınlık (ferah kurumsal) modda çalıştırır -->
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary" data-bs-theme="light">

    <!-- app-wrapper: Tüm paneli (Üst bar, Sol menü, İçerik) kapsayan en dış ana kutu -->
    <div class="app-wrapper">

        <!-- =============================================== -->
        <!-- 1. ÜST MENÜ (NAVBAR) - AKÜ KURUMSAL AÇIK MAVİ     -->
        <!-- =============================================== -->
        <!-- nav: Üst menü çubuğunu temsil eden HTML5 etiketi -->
        <nav class="app-header navbar navbar-expand bg-aku-mavi navbar-dark shadow-sm">
            <!-- container-fluid: Üst menünün içeriğini ekranın sağından ve solundan esnek biçimde yayar -->
            <div class="container-fluid">
                
                <!-- Sol Taraftaki Butonlar ve Kurum Unvanı -->
                <ul class="navbar-nav"> <!-- Liste yapısı başlatıyoruz -->
                    <li class="nav-item"> <!-- Sol menüyü daraltıp açan daraltma butonu kaplaması -->
                        <!-- data-lte-toggle="sidebar": AdminLTE'nin sol menüyü gizleme işlevini tetikler -->
                        <a class="nav-link text-white" data-lte-toggle="sidebar" href="#" role="button">
                            <i class="bi bi-list fs-5"></i> <!-- Hamburger menü (üç çizgi) ikonu -->
                        </a>
                    </li>
                    <li class="nav-item d-none d-md-block"> <!-- d-none d-md-block: Sadece orta ve büyük ekranlarda gösterir -->
                        <span class="nav-link text-white fw-semibold">Afyon Kocatepe Üniversitesi - Bilgi İşlem Daire Başkanlığı</span> <!-- Kurum Adı -->
                    </li>
                </ul>

                <!-- Sağ Taraftaki Kullanıcı Bilgisi -->
                <ul class="navbar-nav ms-auto"> <!-- ms-auto: Bu listeyi en sağa yaslar -->
                    <li class="nav-item">
                        <span class="nav-link text-white fw-bold">
                            <i class="bi bi-person-circle me-1"></i> Teknik Personel <!-- Kullanıcı ikonu ve unvanı -->
                        </span>
                    </li>
                </ul>

            </div> <!-- container-fluid bitişi -->
        </nav> <!-- Üst menü bitişi -->


        <!-- =============================================== -->
        <!-- 2. SOL MENÜ (SIDEBAR) KISMI                      -->
        <!-- =============================================== -->
        <!-- aside: Sayfanın yan menüsünü temsil eden HTML5 etiketi -->
        <aside class="app-sidebar bg-body shadow-sm" data-bs-theme="light">
            
            <!-- BÜYÜK AKÜ LOGOSU VE BAŞLIK ALANI -->
            <!-- d-flex flex-column align-items-center: Resim ve yazıyı dikeyde merkeze hizalar -->
            <div class="sidebar-brand d-flex flex-column align-items-center justify-content-center" style="height: auto; padding: 20px 10px 15px 10px;">
                <a href="index.php" class="text-decoration-none text-center">
                    <!-- AKÜ Logo Resmi (İstediğin gibi tam 200px x 200px boyutunda) -->
                    <!-- rounded-circle: JPG resmin siyah köşelerini yuvarlayarak keser -->
                    <!-- object-fit: cover: Resmin oranlarını bozmadan kutuya tam oturtur -->
                    <img src="aku_logo.png" alt="AKÜ Logo" class="rounded-circle shadow-sm mb-3" style="width: 200px; height: 200px; object-fit: cover;">
                    <!-- Logonun altındaki başlık metni -->
                    <span class="brand-text fw-bold d-block" style="color: #0073c8; font-size: 20px; letter-spacing: 0.5px;">BİDB DESTEK</span>
                </a>
            </div>
            
            <!-- hr: Logo alanı ile menü linkleri arasına ince ayırıcı bir çizgi çeker -->
            <hr class="mt-0 mb-3 border-secondary border-opacity-25">

            <!-- SOL MENÜ LİNKLERİ -->
            <div class="sidebar-wrapper">
                <nav class="mt-2">
                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu">
                        
                        <!-- Ana Sayfa (Kontrol Paneli) Linki -->
                        <li class="nav-item">
                            <!-- active sınıfı bu sayfanın şu an aktif/açık olduğunu belirtir -->
                            <a href="index.php" class="nav-link active">
                                <i class="nav-icon bi bi-speedometer2"></i> <!-- Hız göstergesi ikonu -->
                                <p><?php echo $sayfa_basligi; ?></p> <!-- PHP değişkenimizdeki başlığı yazdırır -->
                            </a>
                        </li>
                        
                        <!-- Gelen Talepler Linki -->
                        <li class="nav-item mt-1">
                            <a href="talepler.php" class="nav-link text-dark">
                                <i class="nav-icon bi bi-headset"></i> <!-- Kulaklık/Destek ikonu -->
                                <p>Tüm Talepler</p>
                            </a>
                        </li>

                    </ul>
                </nav>
            </div>

        </aside> <!-- Sol menü bitişi -->


        <!-- =============================================== -->
        <!-- 3. ANA İÇERİK ALANI (MAIN CONTENT)               -->
        <!-- =============================================== -->
        <!-- main: Sayfanın asıl içerik bölgesini temsil eder -->
        <main class="app-main">
            
            <!-- Sayfa Başlığı Bilgi Alanı -->
            <div class="app-content-header pt-3 pb-2">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0 fw-bold text-secondary">
                                <i class="bi bi-grid-1x2-fill me-2" style="color: #0073c8;"></i> BİDB Destek Talepleri
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sayfa İçeriği Bölümü -->
            <div class="app-content">
                <div class="container-fluid">
                    
                    <!-- İSTATİSTİK KUTULARI SATIRI BAŞLANGICI -->
                    <!-- g-3: Kutular arasına eşit miktarda boşluk (gap) bırakır -->
                    <div class="row g-3">

                        <!-- 1. KUTU: MAVİ (Toplam Talep) -->
                        <div class="col-12 col-sm-6 col-md-3"> <!-- Mobilde 12 birim, tablette 6, bilgisayarda 3 birim genişlik -->
                            <div class="small-box text-bg-primary shadow-sm"> <!-- text-bg-primary: Mavi arka plan -->
                                <div class="inner">
                                    <h3><?php echo $toplam_talep; ?></h3> <!-- İstatiksel Veri Sayısı -->
                                    <p class="fw-semibold">Toplam Talep</p>
                                </div>
                                <i class="small-box-icon bi bi-headset"></i> <!-- Kutudaki büyük şeffaf ikon -->
                                <a href="#" class="small-box-footer link-light link-underline-opacity-0">
                                    Detaylar <i class="bi bi-arrow-right-circle-fill ms-1"></i>
                                </a>
                            </div>
                        </div>

                        <!-- 2. KUTU: SARI (Bekleyen Talepler) -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="small-box text-bg-warning shadow-sm"> <!-- text-bg-warning: Sarı arka plan -->
                                <div class="inner text-white">
                                    <h3><?php echo $bekleyen_talep; ?></h3>
                                    <p class="fw-semibold text-white">Bekleyen Talepler</p>
                                </div>
                                <i class="small-box-icon bi bi-clock-history text-white"></i>
                                <a href="#" class="small-box-footer link-light link-underline-opacity-0">
                                    Detaylar <i class="bi bi-arrow-right-circle-fill ms-1"></i>
                                </a>
                            </div>
                        </div>

                        <!-- 3. KUTU: YEŞİL (Çözülen Talepler) -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="small-box text-bg-success shadow-sm"> <!-- text-bg-success: Yeşil arka plan -->
                                <div class="inner">
                                    <h3><?php echo $cozulen_talep; ?></h3>
                                    <p class="fw-semibold">Çözülen Talepler</p>
                                </div>
                                <i class="small-box-icon bi bi-check-circle"></i>
                                <a href="#" class="small-box-footer link-light link-underline-opacity-0">
                                    Detaylar <i class="bi bi-arrow-right-circle-fill ms-1"></i>
                                </a>
                            </div>
                        </div>

                        <!-- 4. KUTU: KIRMIZI (Acil Talepler) -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="small-box text-bg-danger shadow-sm"> <!-- text-bg-danger: Kırmızı arka plan -->
                                <div class="inner">
                                    <h3><?php echo $acil_talep; ?></h3>
                                    <p class="fw-semibold">Acil Talepler</p>
                                </div>
                                <i class="small-box-icon bi bi-exclamation-triangle"></i>
                                <a href="#" class="small-box-footer link-light link-underline-opacity-0">
                                    Detaylar <i class="bi bi-arrow-right-circle-fill ms-1"></i>
                                </a>
                            </div>
                        </div>

                    </div>
                    <!-- İSTATİSTİK KUTULARI BİTİŞİ -->


                    <!-- TABLO SATIRI BAŞLANGICI -->
                    <div class="row mt-4"> <!-- mt-4: Üstteki kutularla arasında 4 birim üst marjin bırakır -->
                        <div class="col-12">
                            <!-- card: İçeriği çerçeveleyen Bootstrap kart kutusu -->
                            <div class="card card-outline shadow-sm" style="border-top: 3px solid #0073c8;">
                                
                                <!-- Kart Başlık Alanı -->
                                <div class="card-header bg-white py-3">
                                    <h5 class="card-title fw-bold mb-0" style="color: #0073c8;">
                                        <i class="bi bi-list-task me-2"></i>Son Gelen Destek Talepleri (Son 5 Talep)
                                    </h5>
                                </div>
                                
                                <!-- Kart Gövdesi ve Tablo Alanı -->
                                <div class="card-body table-responsive p-0"> <!-- table-responsive: Dar ekranlarda tablonun kaymasını sağlar -->
                                    <table class="table table-hover align-middle mb-0"> <!-- table-hover: Fare üstüne gelince satırı belirginleştirir -->
                                        <thead class="table-light"> <!-- Tablonun başlık satırı rengi -->
                                            <tr>
                                                <th># ID</th> <!-- Sütun 1: Talep Numarası -->
                                                <th>Ad Soyad</th> <!-- Sütun 2: Talep Sahibi -->
                                                <th>Bölüm / Birim</th> <!-- Sütun 3: Birim -->
                                                <th>Konu</th> <!-- Sütun 4: Destek Konusu -->
                                                <th>Durum</th> <!-- Sütun 5: Talebin Durumu -->
                                                <th>Tarih</th> <!-- Sütun 6: Kayıt Tarihi -->
                                                <th class="text-center">İşlem</th> <!-- Sütun 7: İnceleme Butonu -->
                                            </tr>
                                        </thead>
                                        <tbody> <!-- Tablonun veri satırları -->
                                        <?php foreach ($talepler_listesi as $talep): ?>
    <tr>
        <!-- Talep ID -->
        <td><b>#<?php echo $talep['id']; ?></b></td>
        
        <!-- Ad Soyad (XSS güvenlik önlemi için htmlspecialchars ekliyoruz) -->
        <td><?php echo htmlspecialchars($talep['ad_soyad']); ?></td>
        
        <!-- Birim -->
        <td><?php echo htmlspecialchars($talep['birim']); ?></td>
        
        <!-- Konu -->
        <td><?php echo htmlspecialchars($talep['konu']); ?></td>
        
        <!-- Duruma Göre Renk Alan Badge (Rozet) -->
        <td>
            <?php if ($talep['durum'] == 'Bekliyor'): ?>
                <span class="badge bg-warning text-dark">Bekliyor</span>
            <?php elseif ($talep['durum'] == 'Çözüldü'): ?>
                <span class="badge bg-success">Çözüldü</span>
            <?php elseif ($talep['durum'] == 'Acil'): ?>
                <span class="badge bg-danger">Acil</span>
            <?php endif; ?>
        </td>
        
        <!-- Tarih (MySQL formatını Türkçe Gün.Ay.Yıl Saat:Dakika formatına çeviriyoruz) -->
        <td><?php echo date("d.m.Y H:i", strtotime($talep['tarih'])); ?></td>
        
        <!-- İncele Butonu -->
        <td class="text-center">
            <a href="talep-detay.php?id=<?php echo $talep['id']; ?>" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-eye"></i> İncele
            </a>
        </td>
    </tr>
<?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div> <!-- table-responsive bitişi -->

                            </div> <!-- card bitişi -->
                        </div> <!-- col-12 bitişi -->
                    </div>
                    <!-- TABLO SATIRI BİTİŞİ -->

                </div> <!-- container-fluid bitişi -->
            </div> <!-- app-content bitişi -->
        </main> <!-- Main bitişi -->

        
        <!-- =============================================== -->
        <!-- 4. ALT BİLGİ (FOOTER) KISMI                      -->
        <!-- =============================================== -->
        <footer class="app-footer bg-white border-top">
            <div class="float-end d-none d-sm-inline">Versiyon 1.0</div> <!-- Versiyon bilgisi -->
            <strong>Afyon Kocatepe Üniversitesi &copy; BİDB</strong> Tüm hakları saklıdır. <!-- Telif yazısı -->
        </footer>

    </div> <!-- app-wrapper bitişi -->

    <!-- JAVASCRIPT KÜTÜPHANELERİ (Sayfa hızlansın diye en alta eklenir) -->
    <!-- Bootstrap JS: Menü açılışları ve dinamik hareketler için gerekli script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE JS: Yerel dist klasöründeki menü daraltma/genişletme yazılımı -->
    <script src="dist/js/adminlte.min.js"></script>

</body>
</html>