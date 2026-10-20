# Rentifia - Hệ Thống Quản Lý Vận Hành Chuỗi Nhà Trọ & Căn Hộ Dịch Vụ Thông Minh

> **Học phần:** Thực tập chuyên ngành (10 tuần)[cite: 2]  
> **Đề tài:** Xây dựng hệ thống quản lý vận hành chuỗi nhà trọ & căn hộ dịch vụ thông minh (Trọng tâm: Quy trình Quản lý Hợp đồng Lưu trú, Chốt chỉ số Điện/Nước và Tự động hóa Chu kỳ Hóa đơn - Thanh toán)[cite: 1]  
> **Sinh viên thực hiện:**
> - Ngô Lưu Gia Bảo | Lớp: D23_TH10 | MSSV: DH52300151[cite: 1]
> - Nguyễn Chương Bút | Lớp: D23_TH10 | MSSV: DH52300182[cite: 1]

---

## 1. Giới thiệu bài toán & Mục tiêu

Tại các đô thị lớn như TP.HCM, phần lớn các chủ trọ và đơn vị vận hành căn hộ dịch vụ (Co-living/Mini Apartment) vẫn quản lý thủ công qua sổ sách hoặc bảng tính rời rạc[cite: 1]. Điều này dẫn đến:
- Dễ sai sót số học khi ghi nhận chỉ số điện/nước hàng tháng[cite: 2].
- Tốn nhiều thời gian tổng hợp, tính toán hóa đơn từng phòng[cite: 2].
- Khó kiểm soát công nợ lũy kế và thiếu tính minh bạch với người thuê[cite: 2].

**Rentifia** giải quyết trọn vẹn quy trình nghiệp vụ cốt lõi (**End-to-End Workflow**) trong 10 tuần[cite: 2]:

> **Xếp phòng & Ký HĐ** ➔ **Chốt Điện/Nước có ảnh đối chứng** ➔ **Tự động tính & Phát hành Hóa đơn** ➔ **Thanh toán VietQR & Gạch nợ**[cite: 2]

---

## 2. Công nghệ sử dụng (Tech Stack)

- **Back-end:** PHP (Kiến trúc MVC OOP)[cite: 2]
- **Cơ sở dữ liệu:** MySQL (InnoDB, Khóa ngoại, Database Transaction)[cite: 2, 4, 5]
- **Front-end:** HTML5, CSS3, Tailwind CSS / Bootstrap 5, JavaScript Vanilla[cite: 2]
- **Tích hợp bên thứ ba:**
  - **VietQR API:** Tự động tạo mã QR động thanh toán theo từng hóa đơn[cite: 2].
  - **Cloudinary:** Lưu trữ và đồng bộ hình ảnh chụp mặt đồng hồ công tơ điện/nước[cite: 2].
  - **Dompdf / MPDF:** Kết xuất phiếu báo tiền và phiếu thu ra định dạng PDF chuẩn in ấn[cite: 2, 4].

---

## 3. Các phân hệ chức năng chính

### 3.1. Chủ trọ / Quản trị viên (Admin)
- Thiết lập danh mục Tòa nhà (Chi nhánh), Tầng, Phòng và đơn giá dịch vụ dùng chung (Điện, Nước, Wifi, Rác, Giữ xe)[cite: 2].
- Quản lý và phân quyền tài khoản: Quản lý tòa nhà (Manager), Người thuê (Tenant)[cite: 2].
- **Dashboard điều hành:** Thống kê tỷ lệ lấp đầy phòng, doanh thu thực thu và công nợ tồn đọng theo kỳ[cite: 2].

### 3.2. Quản lý tòa nhà / Thu ngân (Manager)
- **Sơ đồ phòng trực quan:** Theo dõi trạng thái phòng (Trống, Đã đặt cọc, Đang thuê, Đang bảo trì) phân theo từng tầng[cite: 2].
- **Quản lý Hợp đồng:** Lập hợp đồng mới, khai báo dịch vụ cố định, ghi nhận chỉ số ban đầu, gia hạn hoặc quyết toán thanh lý (tự động cấn trừ tiền cọc với nợ kỳ cuối)[cite: 2].
- **Chốt chỉ số Điện/Nước:** Bảng nhập nhanh hàng loạt theo tầng; tải ảnh chụp thực tế mặt đồng hồ làm minh chứng đối chiếu[cite: 2].
- **Phát hành Hóa đơn & Gạch nợ:** Kích hoạt tính tiền tự động toàn bộ phòng có hợp đồng hiệu lực; xác nhận thu tiền mặt/chuyển khoản (toàn bộ hoặc từng phần) và xuất phiếu thu PDF[cite: 2, 3].

### 3.3. Người thuê / Cư dân (Tenant - Có tài khoản)
- Xem chi tiết hợp đồng, danh sách thành viên phòng và các gói dịch vụ đăng ký[cite: 3].
- Tra cứu lịch sử hóa đơn kèm hình ảnh chụp công tơ do quản lý tải lên[cite: 3].
- Thanh toán nhanh bằng mã VietQR động tự điền STK, số tiền và nội dung hóa đơn[cite: 3].
- Gửi yêu cầu phản hồi / khiếu nại sai lệch số liệu hoặc báo hỏng hóc phòng ở[cite: 3].

### 3.4. Khách vãng lai (Guest - Chưa có tài khoản)
- Truy cập liên kết hóa đơn điện tử rút gọn (qua Zalo/SMS) để xem phiếu báo tiền và quét mã QR thanh toán nhanh không cần đăng nhập[cite: 3].

### 3.5. Cơ chế xử lý nghiệp vụ tự động (System Logic)
- **Đồng bộ trạng thái:** Hợp đồng kích hoạt ➔ Phòng chuyển `Đang thuê`; Hợp đồng thanh lý ➔ Phòng chuyển `Trống`[cite: 3].
- **Ràng buộc kiểm tra số liệu (Validation):** Ngăn chặn nhập `Chỉ số mới < Chỉ số cũ`, tự động kế thừa chỉ số cuối kỳ trước sang kỳ kế tiếp[cite: 3].
- **Billing Engine:** Tính toán tiền lẻ theo ngày thực ở (nếu nhận phòng giữa tháng), nhân sản lượng tiêu thụ theo đơn giá cấu hình, cộng dồn nợ cũ[cite: 3].
- **Snapshot đơn giá:** Đóng băng đơn giá tại thời điểm xuất hóa đơn vào bảng chi tiết để không bị sai lệch lịch sử nếu tương lai tăng giá dịch vụ.

---

## 4. Cấu trúc thư mục mã nguồn (MVC Architecture)

```text
Rentifia/
├── app/
│   ├── Controllers/      # Điều phối request (Admin, Manager, Tenant, Guest, Auth)
│   ├── Core/             # MVC Core: App, Router, Controller, Model, Request, Response
│   ├── Middlewares/      # Bộ lọc xác thực và phân quyền RBAC
│   ├── Models/           # Tương tác MySQL: Room, Contract, Invoice, MeterReading...
│   ├── Services/         # BillingCalculator, VietQR, CloudinaryUpload, PdfExport
│   └── Views/            # Layouts, Partials, View nghiệp vụ và mẫu in PDF
├── config/               # Cấu hình CSDL, Cloudinary, VietQR, Constants
├── database/             # schema.sql (ERD), seeders.sql (dữ liệu mẫu)
├── public/               # Web Root (.htaccess, index.php, assets CSS/JS/Images)
├── routes/               # Định tuyến web.php
├── storage/              # Lưu trữ tạm, logs hệ thống và cache PDF
├── composer.json         # Quản lý dependency (Dompdf, Cloudinary SDK...)
└── .env                  # Cấu hình biến môi trường cục bộ