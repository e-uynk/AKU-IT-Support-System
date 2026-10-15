# 🏛️ AKÜ BİDB Support System (v1.0)

[![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-8.4-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com/)
[![AdminLTE](https://img.shields.io/badge/AdminLTE-v4-3c8dbc?style=for-the-badge&logo=adminlte&logoColor=white)](https://adminlte.io/)
[![License](https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge)](LICENSE)

An internal web-based IT Service Management & Fault Tracking Portal designed for **Afyon Kocatepe University - Department of Information Technology (BİDB)**. The system streamlines the workflow of technical support requests between university staff/faculty and the IT support personnel.

---

## 📸 System Overview & Key Architecture

```
+-----------------------------------------------------------------------------------+
|                            AKÜ BİDB IT SUPPORT SYSTEM                             |
+------------------------------------+----------------------------------------------+
| 🌐 PUBLIC PORTAL                   | 🛠️ ADMIN CONTROL PANEL                       |
| - Staff Request Submission         | - Real-time Dashboard Analytics Cards        |
| - Contact & Office Location Input  | - Status-based Ticket Filtering              |
| - Categorized Issue Reporting      | - Technical Resolution Notes & Ticket Removal|
+------------------------------------+----------------------------------------------+
```

---

## ✨ Key Features

### 📋 User / Staff Request Module (`talep-olustur.php`)
- **Comprehensive Data Capture:** Collects requester full name, phone/extension number, faculty/department, office/block number, issue category, and detailed description.
- **Categorized Issue Selection:** Pre-defined channels for Hardware, Network/Internet, Software/EBYS/E-Signature, Email/Password, and General Support.
- **Server-side Validation:** Prevents incomplete submissions and gives real-time visual alert feedback.

### 📊 Admin Dashboard (`index.php`)
- **Live Analytical Cards:** Real-time counters displaying Total, Pending, Solved, and Urgent tickets.
- **Recent Overview List:** `LIMIT 5` query fetching the latest support requests for fast triage.

### 🔍 Comprehensive Ticket Management (`talepler.php`)
- **Status Filter Toolbar:** Instant filtering by **All**, **Pending**, **Solved**, and **Urgent** status.
- **Action Control:** Integrated View (`talep-detay.php`) and Delete (`talep-sil.php`) actions with confirmation dialogs.

### 📝 Resolution & Detail Management (`talep-detay.php`)
- **Technical Note Logging:** Allows technicians to log technical notes (`cozum_notu`) explaining hardware replacements, network fixes, or software patches.
- **Status Update Workflow:** Dynamic badge rendering (Yellow for Pending, Green for Solved, Red for Urgent).

---

## 🛠️ Tech Stack & Architecture

| Layer | Technology Used |
| :--- | :--- |
| **Backend Engine** | PHP 8.x (PDO Architecture, Prepared Statements) |
| **Database** | MySQL / MariaDB (UTF-8 MB4 Encoding) |
| **Frontend Framework**| Bootstrap 5.3 + AdminLTE v4 |
| **Iconography** | Bootstrap Icons |
| **Server Environment**| Apache (Laragon / XAMPP) |

---

## 🗄️ Database Schema (`talepler` Table)

```sql
CREATE TABLE `talepler` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `ad_soyad` VARCHAR(100) NOT NULL,
  `telefon` VARCHAR(20) DEFAULT NULL,
  `birim` VARCHAR(150) NOT NULL,
  `oda_no` VARCHAR(50) DEFAULT NULL,
  `kategori` VARCHAR(50) DEFAULT 'Genel',
  `konu` TEXT NOT NULL,
  `durum` ENUM('Bekliyor', 'Çözüldü', 'Acil') DEFAULT 'Bekliyor',
  `cozum_notu` TEXT DEFAULT NULL,
  `tarih` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

---

## 🚀 Quick Start / Installation Guide

1. **Clone the Repository:**
   ```bash
   git clone [https://github.com/YOUR_USERNAME/bidb-destek-sistemi.git](https://github.com/YOUR_USERNAME/bidb-destek-sistemi.git)
   cd bidb-destek-sistemi
   ```

2. **Import Database Schema:**
   - Open HeidiSQL / phpMyAdmin.
   - Create a new database named `bidb_destek`.
   - Import the included `bidb_destek.sql` file.

3. **Configure Database Connection:**
   - Update `baglan.php` with your local database credentials if necessary:
     ```php
     $db = new PDO("mysql:host=localhost;dbname=bidb_destek;charset=utf8", "root", "");
     ```

4. **Run the Application:**
   - Place the folder in your web server root (e.g., `C:/laragon/www/bidb_admin`).
   - Open `http://localhost/bidb_admin/index.php` in your browser.

---

## 📄 License

Distributed under the MIT License. See `LICENSE` for more information.

---
Developed for **Afyon Kocatepe University - Information Technology Department**.