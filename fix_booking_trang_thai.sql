-- Script để sửa cột trang_thai trong bảng booking
-- Chạy script này trong phpMyAdmin hoặc MySQL client

-- Kiểm tra và mở rộng cột trang_thai
ALTER TABLE booking MODIFY COLUMN trang_thai VARCHAR(50) DEFAULT 'cho_xac_nhan';

-- Nếu cột chưa tồn tại, chạy lệnh sau:
-- ALTER TABLE booking ADD COLUMN trang_thai VARCHAR(50) DEFAULT 'cho_xac_nhan';

-- Kiểm tra kết quả
SHOW COLUMNS FROM booking LIKE 'trang_thai';

