<?php
// baglan.php - MySQL Veritabanı PDO Bağlantı Dosyası

// 1. Veritabanı sunucusunun adresi (Localhost: Kendi bilgisayarımız)
$host = "localhost";

// 2. Bağlanacağımız veritabanının adı
$db_adi = "bidb_destek";

// 3. Laragon varsayılan MySQL kullanıcı adı
$kullanici = "root";

// 4. Laragon varsayılan MySQL şifresi (Yerel ortamda boştur)
$sifre = "";

// TRY-CATCH Bloğu: Bağlantı denerken bir hata olursa sistemi çökertmeden hatayı yakalar
try {
    // PDO nesnesini oluşturuyoruz ve Türkçe karakter desteği (utf8mb4) tanımlıyoruz
    $db = new PDO("mysql:host={$host};dbname={$db_adi};charset=utf8mb4", $kullanici, $sifre);
    
    // Hataları daha net görebilmek için PDO hata modunu Exception (İstisna) olarak ayarlıyoruz
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Veritabanından veri çekerken dizileri ilişkisel (key-value) formatta getirmesini sağlıyoruz
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Eğer bağlantı başarısız olursa çalışmayı durdurur ve hatayı ekrana basar
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}
?>