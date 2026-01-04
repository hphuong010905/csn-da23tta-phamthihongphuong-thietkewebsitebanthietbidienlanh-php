# WEBSITE BÁN THIẾT BỊ ĐIỆN LẠNH

[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)
[![PHP](https://img.shields.io/badge/PHP-7.4+-purple.svg)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-5.7+-orange.svg)](https://www.mysql.com)

## 📋 Thông tin đồ án

**Đề tài**: Thiết kế website bán thiết bị điện lạnh

**Sinh viên thực hiện**:
- **Họ và tên**: Phạm Thị Hồng Phương
- **MSSV**: 110123040
- **Lớp**: DA23TTA
- **Khoa**: Công nghệ thông tin
- **Trường**: Kỹ thuật và Công nghệ

**Giảng viên hướng dẫn**: ThS Nguyễn Hoàng Duy Thiện

---

## 📞 Thông tin liên hệ

- **Email**: phuongdaudh2023@gmail.com
- **Phone**: 0393710219
- **GitHub**: https://github.com/hphuong010905
- **Repository**: https://github.com/hphuong010905/csn-da23tta-thietkewebditebanthietbidienlanh-php.git

---

## 📖 Giới thiệu

Website bán thiết bị điện lạnh là một hệ thống thương mại điện tử được phát triển bằng PHP thuần, cho phép người dùng:

### Chức năng khách hàng:
- 🔍 Tìm kiếm và xem chi tiết sản phẩm
- 🛒 Thêm sản phẩm vào giỏ hàng
- 💳 Đặt hàng và thanh toán
- 👤 Quản lý thông tin cá nhân
- 📦 Theo dõi đơn hàng

### Chức năng quản trị viên:
- 📊 Quản lý sản phẩm
- 📑 Quản lý danh mục
- 👥 Quản lý khách hàng
- 🛍️ Quản lý đơn hàng

---

## 🛠️ Công nghệ sử dụng

### Backend:
- **Ngôn ngữ**: PHP 7.4+
- **Database**: MySQL
- **Session**: PHP Session

### Frontend:
- **HTML5, CSS3**
- **JavaScript, jQuery**
- **Bootstrap 5.3.6**
- **Font Awesome 6.7.2**

### Server:
- **Apache** (Laragon)

---

## 📁 Cấu trúc thư mục

```
CSN/
├── setup/                  # Hướng dẫn cài đặt và database
│   ├── database.sql        # File SQL khởi tạo database
│   └── INSTALL.md          # Hướng dẫn cài đặt chi tiết
│
├── src/                    # Source code chính
│   ├── Backend/            # Backend logic
│   │   ├── config/         # Cấu hình (DB, Session, Paths)
│   │   ├── auth/           # Authentication & Authorization
│   │   ├── api/            # API endpoints (AJAX, Search)
│   │   └── controllers/    # Controllers (CRUD operations)
│   │
│   ├── Frontend/           # Frontend (Views & Assets)
│   │   ├── views/          # Các trang giao diện
│   │   └── assets/         # Static files (CSS, JS, Images, Libs)
│   │
│   └── Database/           # Database scripts & backups
│
├── progress-report/        # Báo cáo tiến độ [BẮT BUỘC]
│
├── thesis/                 # Tài liệu đồ án [BẮT BUỘC]
│   ├── doc/                # Tài liệu .DOC
│   ├── pdf/                # Tài liệu .PDF
│   ├── html/               # Tài liệu dạng web
│   ├── abs/                # Báo cáo (.PPT, .AVI)
│   └── refs/               # Tài liệu tham khảo
│
├── soft/                   # Phần mềm liên quan
│
├── .gitignore              # Git ignore file
└── README.md               # File này
```

---

## 🚀 Hướng dẫn cài đặt

### Yêu cầu hệ thống:
- PHP >= 7.4
- MySQL/MariaDB >= 5.7
- Apache Server
- Web Browser hiện đại

### Các bước cài đặt:

1. **Clone repository**:
```bash
git clone https://github.com/hphuong010905/csn-da23tta-thietkewebditebanthietbidienlanh-php.git
cd CSN
```

2. **Cài đặt database**:
   - Tạo database `databasecsn`
   - Import file `setup/database.sql`

3. **Cấu hình kết nối**:
   - Cập nhật `src/Backend/config/ConnectDB.php`

4. **Chạy ứng dụng**:
```
http://localhost/CSN/src/Frontend/views/index.php
```

📚 **Xem hướng dẫn chi tiết tại**: [setup/INSTALL.md](setup/INSTALL.md)

---

## 👤 Tài khoản demo

### Admin:
- Tài khoản: `PHUONG0109`
- Mật khẩu: `phuong0109`

### Khách hàng:
- Đăng ký mới tại trang chủ

---

## 📸 Screenshots

### Trang chủ
![Trang chủ](thesis/abs/screenshots/home.png)

### Quản trị
![Admin](thesis/abs/screenshots/admin.png)

*(Thêm ảnh screenshots vào thư mục thesis/abs/screenshots/)*

---

## 🔄 Sơ đồ triển khai

```
┌─────────────┐
│   Browser   │
└──────┬──────┘
       │
       ↓
┌─────────────┐
│   Apache    │
└──────┬──────┘
       │
       ↓
┌─────────────┐     ┌──────────┐
│  PHP App    │────→│  MySQL   │
└─────────────┘     └──────────┘
```

---

## 📝 Tính năng chính

### Module khách hàng:
- ✅ Đăng ký/Đăng nhập
- ✅ Xem danh sách sản phẩm
- ✅ Tìm kiếm sản phẩm (AJAX)
- ✅ Chi tiết sản phẩm
- ✅ Giỏ hàng (Session)
- ✅ Đặt hàng
- ✅ Quản lý thông tin cá nhân

### Module quản trị:
- ✅ Quản lý sản phẩm (CRUD)
- ✅ Quản lý danh mục (CRUD)
- ✅ Quản lý khách hàng
- ✅ Quản lý đơn hàng


---

## 🔐 Bảo mật

- Session management
- SQL injection prevention (Prepared statements)
- XSS protection
- Password hashing (bcrypt)
- Admin authentication & authorization

---

## 📚 Tài liệu tham khảo

Các tài liệu tham khảo được lưu trong thư mục `thesis/refs/`:
- PHP Documentation
- MySQL Documentation
- Bootstrap Documentation
- Web Security Best Practices

---

## 🐛 Báo lỗi

Nếu phát hiện lỗi, vui lòng tạo issue tại:
https://github.com/hphuong010905/csn-da23tta-thietkewebditebanthietbidienlanh-php/issues

---

## 📄 License

<<<<<<< HEAD
Dự án này được phát triển cho mục đích học tập tại Trường Kỹ thuật và Công nghệ - Trường Đại học Trà Vinh.
=======
Dự án này được phát triển cho mục đích học tập.
>>>>>>> 82231b6ba9aefb01f694c54632b0992bae58c003

---

## 🙏 Lời cảm ơn

- Giảng viên hướng dẫn: ThS Nguyễn Hoàng Duy Thiện
<<<<<<< HEAD
- Khoa Công nghệ Thông tin - Trường Kỹ thuật và Công nghệ - Trường Đại học Trà Vinh
=======
- Trường Kỹ thuật và Công nghệ
>>>>>>> 82231b6ba9aefb01f694c54632b0992bae58c003
- Các bạn trong lớp DA23TTA

---

<<<<<<< HEAD
**© 2025 - Đồ án cơ sở ngành - Trường Đại học Trà Vinh**
=======
**© 2025 - Đồ án cơ sở ngành**
>>>>>>> 82231b6ba9aefb01f694c54632b0992bae58c003
