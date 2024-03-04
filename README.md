# Phân tích trang môi giới việc làm
## _Dự án vì cộng đồng_

[![N|Solid](https://cldup.com/dTxpPi9lDf.thumb.png)](https://nodesource.com/products/nsolid)

[![Build Status](https://travis-ci.org/joemccann/dillinger.svg?branch=master)](https://travis-ci.org/joemccann/dillinger)

Dillinger is a cloud-enabled, mobile-ready, offline-storage compatible,
AngularJS-powered HTML5 Markdown editor.

### Đối tượng sử dụng
- Quản trị viên
- Nhà tuyển dụng
- Ứng viên

### Chức năng từng đối tượng
A. Quản trị viên
- Quản lí trang thông tin: banner, giới thiệu,...
- Quản lí người dùng
- Quản lí file: JD, CV
- Quản lí bài đăng công việc
- Quản lí báo cáo

B. Nhà tuyển dụng
- Quản lí bài tuyển dụng
- Tìm kiếm CV
- Chỉnh sửa thông tin cá nhân ( thuộc công ty nào, thông tin liên hệ )

C. Ứng viên
- Tìm kiếm công việc ( công ty, vị trí, mức lương, địa điểm, ngôn ngữ, trình độ, yêu cầu, bằng cấp - chứng chỉ, số lượng)
- Đăng CV
- Xem danh sách công việc ( có thể ghim và sắp xếp ngẫu nhiên )
- Báo cáo vi phạm: công ty, cá nhân ( lừa đảo, spam, không liên hệ được, thông tin bài tuyển dụng sai )

### Tài khoản mặc định admin
hiepxuannguyen2001@gmail.com
123123

### Phân tích chức năng

| Các tác nhân | Nhà tuyển dụng |
| ------ | ------ |
| Mô tả | Đăng bài tuyển dụng |
| Kích hoạt | Người dùng ấn vào nút "Đăng bài tuyển dụng" trên thanh menu |
| Đầu vào | Tên công ty<br>Tên công việc<br>Địa điểm: thành phố -quận (select2 -load về local)<br>Remote |
| Trình tự xử lí |  |
| Đầu ra | Đúng: Hiển thị trang người dùng và thông báo thành công<br>Sai: Hiển thị trang đăng nhập và thông báo thất bại |
| Lưu ý | kiểm tra ô nhập không được để trống bằng javascript |

## License

MIT

**Free Software, Hell Yeah!**
