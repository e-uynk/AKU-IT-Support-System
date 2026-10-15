<?php
require_once 'baglan.php';

$sayfa_basligi = "Tüm Talepler";

// URL'den filtre parametresi gelmiş mi kontrol ediyoruz
$durum_filtre = $_GET['durum'] ?? '';

if (in_array($durum_filtre, ['Bekliyor', 'Çözüldü', 'Acil'])) {
    // Seçilen duruma göre filtreliyoruz
    $sorgu_talepler = $db->prepare("SELECT * FROM talepler WHERE durum = ? ORDER BY id DESC");
    $sorgu_talepler->execute([$durum_filtre]);
    $talepler_listesi = $sorgu_talepler->fetchAll();
} else {
    // Filtre yoksa tümünü getiriyoruz
    $sorgu_talepler = $db->query("SELECT * FROM talepler ORDER BY id DESC");
    $talepler_listesi = $sorgu_talepler->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tüm Talepler - BİDB Destek</title>

    <!-- Bootstrap CSS ve İkonlar -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">

    <style>
        .bg-aku-mavi { background-color: #0073c8 !important; }
        .sidebar-menu .nav-link.active { background-color: #0073c8 !important; color: #ffffff !important; }
    </style>
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary" data-bs-theme="light">

    <div class="app-wrapper">

        <!-- 1. ÜST MENÜ (NAVBAR) -->
        <nav class="app-header navbar navbar-expand bg-aku-mavi navbar-dark shadow-sm">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link text-white" data-lte-toggle="sidebar" href="#" role="button">
                            <i class="bi bi-list fs-5"></i>
                        </a>
                    </li>
                    <li class="nav-item d-none d-md-block">
                        <span class="nav-link text-white fw-semibold">Afyon Kocatepe Üniversitesi - Bilgi İşlem Daire Başkanlığı</span>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <span class="nav-link text-white fw-bold">
                            <i class="bi bi-person-circle me-1"></i> Teknik Personel
                        </span>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- 2. SOL MENÜ (SIDEBAR) -->
        <aside class="app-sidebar bg-body shadow-sm" data-bs-theme="light">
            <div class="sidebar-brand d-flex flex-column align-items-center justify-content-center" style="height: auto; padding: 20px 10px 15px 10px;">
                <a href="index.php" class="text-decoration-none text-center">
                    <img src="aku_logo.png" alt="AKÜ Logo" class="rounded-circle shadow-sm mb-3" style="width: 200px; height: 200px; object-fit: cover;">
                    <span class="brand-text fw-bold d-block" style="color: #0073c8; font-size: 20px; letter-spacing: 0.5px;">BİDB DESTEK</span>
                </a>
            </div>
            
            <hr class="mt-0 mb-3 border-secondary border-opacity-25">

            <div class="sidebar-wrapper">
                <nav class="mt-2">
                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu">
                        <li class="nav-item">
                            <a href="index.php" class="nav-link text-dark">
                                <i class="nav-icon bi bi-speedometer2"></i>
                                <p>Kontrol Paneli</p>
                            </a>
                        </li>
                        <li class="nav-item mt-1">
                            <!-- Bu sayfada Tüm Talepler seçili (active) -->
                            <a href="talepler.php" class="nav-link active">
                                <i class="nav-icon bi bi-headset"></i>
                                <p>Tüm Talepler</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- 3. ANA İÇERİK ALANI -->
        <main class="app-main">
            <div class="app-content-header pt-3 pb-2">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0 fw-bold text-secondary">
                                <i class="bi bi-headset me-2" style="color: #0073c8;"></i> Tüm Destek Talepleri
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="app-content">
                <div class="container-fluid">
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-outline shadow-sm" style="border-top: 3px solid #0073c8;">
                                
                                
                               <!-- SILINME BILDIRIM MESAJI -->
        <?php if (isset($_GET['mesaj']) && $_GET['mesaj'] == 'silindi'): ?>
            <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                <i class="bi bi-trash-fill me-2"></i> Destek talebi başarıyla silindi.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- KART BASLIGI VE DURUM FILTRE BUTONLARI -->
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="card-title fw-bold mb-0" style="color: #0073c8;">
                <i class="bi bi-list-stars me-2"></i> Talepler Listesi
            </h5>
            
            <!-- DURUM FILTRE BUTONLARI -->
            <div class="btn-group btn-group-sm" role="group">
                <a href="talepler.php" class="btn btn-outline-secondary <?php echo empty($durum_filtre) ? 'active' : ''; ?>">Tümü</a>
                <a href="talepler.php?durum=Bekliyor" class="btn btn-outline-warning text-dark <?php echo ($durum_filtre == 'Bekliyor') ? 'active' : ''; ?>">Bekleyenler</a>
                <a href="talepler.php?durum=Çözüldü" class="btn btn-outline-success <?php echo ($durum_filtre == 'Çözüldü') ? 'active' : ''; ?>">Çözülenler</a>
                <a href="talepler.php?durum=Acil" class="btn btn-outline-danger <?php echo ($durum_filtre == 'Acil') ? 'active' : ''; ?>">Aciller</a>
            </div>
        </div>
                                
                                <div class="card-body table-responsive p-0">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th># ID</th>
                                                <th>Ad Soyad</th>
                                                <th>Bölüm / Birim</th>
                                                <th>Konu</th>
                                                <th>Durum</th>
                                                <th>Tarih</th>
                                                <th class="text-center">İşlem</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($talepler_listesi as $talep): ?>
                                                <tr>
                                                    <td><b>#<?php echo $talep['id']; ?></b></td>
                                                    <td><?php echo htmlspecialchars($talep['ad_soyad']); ?></td>
                                                    <td><?php echo htmlspecialchars($talep['birim']); ?></td>
                                                    <td><?php echo htmlspecialchars($talep['konu']); ?></td>
                                                    <td>
                                                        <?php if ($talep['durum'] == 'Bekliyor'): ?>
                                                            <span class="badge bg-warning text-dark">Bekliyor</span>
                                                        <?php elseif ($talep['durum'] == 'Çözüldü'): ?>
                                                            <span class="badge bg-success">Çözüldü</span>
                                                        <?php elseif ($talep['durum'] == 'Acil'): ?>
                                                            <span class="badge bg-danger">Acil</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo date("d.m.Y H:i", strtotime($talep['tarih'])); ?></td>
                                                    <!-- YENİ HALİ (İncele + Sil) -->
                                                    <td class="text-center text-nowrap">
    <div class="d-inline-flex gap-1">
        <!-- İNCELE BUTONU -->
        <a href="talep-detay.php?id=<?php echo $talep['id']; ?>" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-eye"></i> İncele
        </a>
        <!-- SİL BUTONU -->
        <a href="talep-sil.php?id=<?php echo $talep['id']; ?>" 
           class="btn btn-sm btn-outline-danger" 
           onclick="return confirm('#<?php echo $talep['id']; ?> numaralı talebi silmek istediğinize emin misiniz?');">
            <i class="bi bi-trash"></i> Sil
        </a>
    </div>
</td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </main>
        
        <!-- 4. ALT BİLGİ (FOOTER) -->
        <footer class="app-footer bg-white border-top">
            <div class="float-end d-none d-sm-inline">Versiyon 1.0</div>
            <strong>Afyon Kocatepe Üniversitesi &copy; BİDB</strong> Tüm hakları saklıdır.
        </footer>

    </div>

    <!-- JAVASCRIPT DOSYALARI -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="dist/js/adminlte.min.js"></script>

</body>
</html>