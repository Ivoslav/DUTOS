# DUTOS — Система за управление на наряди, отпуски и служебни задължения

### DUTOS — Duty, Leave and Task Organization System

---

## 📝 Описание | Description

**DUTOS** е вътрешна уеб система за военно поделение, която осигурява централизирано управление на наряди, отпуски, командировки, екипажи и войници.
**DUTOS** is an internal web-based system for military units, providing centralized management of duties, leaves, assignments, crews, and personnel.

---

## ⚙️ Основни функции | Key Features

* 🔐 Сигурен вход с ролево разпределение
  🔐 Secure login with role-based redirection
* 🧏 Управление на потребители и екипажи
  🧏 User and crew management
* 🗓️ Наряди, графици и замествания
  🗓️ Duty scheduling and replacements
* ✈️ Отпуски и командировки
  ✈️ Leave and travel assignment tracking
* 🧾 Системни логове и достъпност
  🧾 System logging and accountability
* 📊 Справки и експортиране (в процес на разработка)
  📊 Reporting and export tools (under development)

---

## 🧱 Структура на проекта | Project Structure

```
dutos/
├── dutos-api/              # PHP backend (API)
│   ├── auth/               # Вход / Login / Logout
│   ├── config/             # Конфигурация на база данни / DB config
│   ├── utils/              # Помощни функции / Utilities (logger, etc.)
│   ├── users/              # Потребителски модули / User management
│   └── ...
│
├── public/                 # Интерфейс (бъдещ SPA) / Frontend (future SPA)
├── logs/                   # Логове / Logs
└── README.md               # Информация за проекта / Project info
```

---

## 💾 Изисквания | Requirements

* PHP 8.0+
* MySQL 5.7+ или MariaDB
* Apache (препоръчително чрез XAMPP)
* Активиран `mod_rewrite` за пренаписване на URL (optional)

---

## 🚀 Стартиране | Getting Started

1. Клонирай проекта / Clone the repo:

   ```bash
   git clone https://github.com/Ivoslav/DUTOS.git
   ```

2. Импортирай `dutos.sql` в MySQL (ако е наличен)
   Import `dutos.sql` into MySQL (if available)

3. Настрой `config/db.php` с локални настройки
   Configure `config/db.php` with local DB credentials

4. Стартирай XAMPP и достъпи системата:
   Start XAMPP and access:

   ```
   http://localhost/projects/DUTOS/public/
   ```

   или / or:

   ```
   http://localhost/projects/DUTOS/dutos-api/
   ```

---

## 🚰 Планирани подобрения | Upcoming Features

* SPA интерфейс с Vue или React
  SPA frontend using Vue or React
* PDF/Excel експортиране на справки
  PDF/Excel report exports
* Известия и съобщения
  Notification and messaging system
* Панел за администратори и настройки
  Admin dashboard and settings

---

## 🛡️ Лиценз | License

Този проект е с вътрешна цел и не е предназначен за публична употреба без съгласие.
This project is for internal use and not intended for public distribution without permission.

---

**Автор / Author**: [Ivoslav Petkov](https://github.com/Ivoslav)
