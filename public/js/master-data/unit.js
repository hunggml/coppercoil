<<<<<<< HEAD
/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/lang/index.js":
/*!************************************!*\
  !*** ./resources/js/lang/index.js ***!
  \************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
var lang = document.querySelector('html').getAttribute('lang');
var translations = {};

var requireModules = __webpack_require__("./resources/js/lang/translations sync \\.js$");

requireModules.keys().forEach(function (modulePath) {
  var key = modulePath.replace(/(^.\/)|(.js$)/g, '');
  translations[key] = requireModules(modulePath)["default"];
});

var t = function t(text) {
  return translations[lang][text] || text;
};

/* harmony default export */ __webpack_exports__["default"] = (t);

/***/ }),

/***/ "./resources/js/lang/translations sync \\.js$":
/*!****************************************************************!*\
  !*** ./resources/js/lang/translations sync nonrecursive \.js$ ***!
  \****************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var map = {
	"./en.js": "./resources/js/lang/translations/en.js",
	"./ko.js": "./resources/js/lang/translations/ko.js",
	"./vi.js": "./resources/js/lang/translations/vi.js"
};


function webpackContext(req) {
	var id = webpackContextResolve(req);
	return __webpack_require__(id);
}
function webpackContextResolve(req) {
	if(!__webpack_require__.o(map, req)) {
		var e = new Error("Cannot find module '" + req + "'");
		e.code = 'MODULE_NOT_FOUND';
		throw e;
	}
	return map[req];
}
webpackContext.keys = function webpackContextKeys() {
	return Object.keys(map);
};
webpackContext.resolve = webpackContextResolve;
module.exports = webpackContext;
webpackContext.id = "./resources/js/lang/translations sync \\.js$";

/***/ }),

/***/ "./resources/js/lang/translations/en.js":
/*!**********************************************!*\
  !*** ./resources/js/lang/translations/en.js ***!
  \**********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _vi__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./vi */ "./resources/js/lang/translations/vi.js");

var en = {};
Object.keys(_vi__WEBPACK_IMPORTED_MODULE_0__["default"]).forEach(function (key) {
  en[key] = key;
});
/* harmony default export */ __webpack_exports__["default"] = (en);

/***/ }),

/***/ "./resources/js/lang/translations/ko.js":
/*!**********************************************!*\
  !*** ./resources/js/lang/translations/ko.js ***!
  \**********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony default export */ __webpack_exports__["default"] = ({});

/***/ }),

/***/ "./resources/js/lang/translations/vi.js":
/*!**********************************************!*\
  !*** ./resources/js/lang/translations/vi.js ***!
  \**********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
var _NumberOfRecords_M;

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

/* harmony default export */ __webpack_exports__["default"] = (_NumberOfRecords_M = {
  "Number of records _MENU_": "Số lượng bản ghi _MENU_",
  "Showing _START_ to _END_ of _TOTAL_ entries": "Bản ghi từ _START_ đến _END_ của _TOTAL_ bản ghi",
  "Name": "Tên",
  "Symbols": "Mã",
  "Type": "Loại",
  "Note": "Ghi chú",
  "User Created": "Người tạo",
  "Time Created": "Thời gian tạo",
  "User Updated": "Người cập nhật",
  "Time Updated": "Thời gian cập nhật",
  "Action": "Hành Động",
  "Edit": "Sửa",
  "Delete": "Xóa",
  "Start Time": "Thời Gian Bắt Đầu",
  "End Time": "Thời Gian Kết Thúc",
  "Cycle Time": "Chu Kì Sản Xuất 1 Sản Phẩm ",
  "Detail": "Chi Tiết",
  "Month": "Tháng",
  "Year": "Năm",
  "Location Take Materials": "Vị Trí Lấy Hàng",
  "Materials Return Location": "Vị Trí Trả Hàng",
  "Destroy": "Hủy"
}, _defineProperty(_NumberOfRecords_M, "Detail", "Chi Tiết"), _defineProperty(_NumberOfRecords_M, "Success", "Hoàn Thành"), _defineProperty(_NumberOfRecords_M, "Product", "Sản Phẩm"), _defineProperty(_NumberOfRecords_M, "Machine", "Máy Sản Xuất"), _defineProperty(_NumberOfRecords_M, "Dont Production", "Chưa Sản Xuất"), _defineProperty(_NumberOfRecords_M, "Are Production", "Đang Sản Xuất"), _defineProperty(_NumberOfRecords_M, "Success Production", "Hoàn Thành Sản Xuất"), _defineProperty(_NumberOfRecords_M, "Status", "Trạng Thái"), _defineProperty(_NumberOfRecords_M, "Export", "Xuất Kho"), _defineProperty(_NumberOfRecords_M, "Materials", "Nguyên Vật Liệu"), _defineProperty(_NumberOfRecords_M, "Dont Export", "Chưa Xuất"), _defineProperty(_NumberOfRecords_M, "Are Export", "Đang Xuất"), _defineProperty(_NumberOfRecords_M, "Success Export", "Hoàn Thành Xuất"), _defineProperty(_NumberOfRecords_M, "Stock Min", "Tồn Giới Hạn"), _defineProperty(_NumberOfRecords_M, "Cavity", "Cavity"), _defineProperty(_NumberOfRecords_M, "Mold", "Khuôn"), _defineProperty(_NumberOfRecords_M, "Quantity Mold", "Số Lượng Khuôn"), _defineProperty(_NumberOfRecords_M, "Quantity", "Số Lượng"), _defineProperty(_NumberOfRecords_M, "Date", "Ngày"), _defineProperty(_NumberOfRecords_M, "Unit", "Đơn Vị Tính"), _defineProperty(_NumberOfRecords_M, "Parking", "Đơn Vị Đóng Gói"), _defineProperty(_NumberOfRecords_M, "History", "Lịch Sử"), _defineProperty(_NumberOfRecords_M, "Return", "Khôi Phục"), _defineProperty(_NumberOfRecords_M, "Update", "Cập Nhật"), _defineProperty(_NumberOfRecords_M, "Delete", "Xóa"), _defineProperty(_NumberOfRecords_M, "Processing", "Đang Thực Hiện"), _defineProperty(_NumberOfRecords_M, "Enable", "Kích Hoạt"), _defineProperty(_NumberOfRecords_M, "Disable", "Vô Hiệu Hóa"), _defineProperty(_NumberOfRecords_M, "Part", "Bộ Phận"), _defineProperty(_NumberOfRecords_M, "Time Start", "Thời Gian Bắt Đầu"), _defineProperty(_NumberOfRecords_M, "Time End", "Thời Gian Kết Thúc"), _defineProperty(_NumberOfRecords_M, "Manager AGV", "Người Quản Lý AGV"), _defineProperty(_NumberOfRecords_M, "Maintenance Time", "Thời Gian Bảo Trì"), _defineProperty(_NumberOfRecords_M, "Maintenance Date", "Ngày Bảo Dưỡng"), _defineProperty(_NumberOfRecords_M, "Warehouse", "Kho"), _defineProperty(_NumberOfRecords_M, "Plan", "Kế Hoạch"), _defineProperty(_NumberOfRecords_M, "Production", "Sản Xuất"), _defineProperty(_NumberOfRecords_M, "Output", "Sản Lượng"), _defineProperty(_NumberOfRecords_M, "End", "Kết Thúc"), _defineProperty(_NumberOfRecords_M, "Error", "Lỗi"), _defineProperty(_NumberOfRecords_M, "Time Real Start", "Bắt Đầu Thực Tế"), _defineProperty(_NumberOfRecords_M, "Time Real End", "Kết Thúc Thực Tế"), _defineProperty(_NumberOfRecords_M, "Symbols Plan", "Mã Chỉ Thị"), _defineProperty(_NumberOfRecords_M, "Infor", "Thông Tin"), _defineProperty(_NumberOfRecords_M, "Normal", "Xuất Thường"), _defineProperty(_NumberOfRecords_M, "Cancel", "Hủy"), _defineProperty(_NumberOfRecords_M, "Start", "Bắt Đầu"), _defineProperty(_NumberOfRecords_M, "User Name", "Tên Đăng Nhập"), _defineProperty(_NumberOfRecords_M, "ENABLE", "Kích Hoạt"), _defineProperty(_NumberOfRecords_M, "DISABLE", "Không Kích Hoạt"), _defineProperty(_NumberOfRecords_M, "Action Name", "Kiểu Hành Động"), _defineProperty(_NumberOfRecords_M, "INSERT", "Thêm Mới"), _defineProperty(_NumberOfRecords_M, "Insert", "Thêm Mới"), _defineProperty(_NumberOfRecords_M, "OEE", "Hiệu Suất Tổng Thể"), _defineProperty(_NumberOfRecords_M, "Availability", "Khả Dụng"), _defineProperty(_NumberOfRecords_M, "Performance", "Hiệu Suất"), _defineProperty(_NumberOfRecords_M, "Quality", "Chất Lượng"), _defineProperty(_NumberOfRecords_M, "shift", "ca"), _defineProperty(_NumberOfRecords_M, "day", "ngày"), _defineProperty(_NumberOfRecords_M, "Description", "Chú Thích"), _defineProperty(_NumberOfRecords_M, "Find AGV", "Tìm AGV"), _defineProperty(_NumberOfRecords_M, "Role", "Vai Trò"), _defineProperty(_NumberOfRecords_M, "Waiting for AGV", "Chờ AGV"), _defineProperty(_NumberOfRecords_M, "AGV Shipping", "AGV Đang Chuyển Hàng"), _defineProperty(_NumberOfRecords_M, "IsDelete", "Xóa"), _defineProperty(_NumberOfRecords_M, "To", "Tới"), _defineProperty(_NumberOfRecords_M, "Command", "Lệnh"), _defineProperty(_NumberOfRecords_M, "INSERT_USER", "Thêm Mới Tài Khoản"), _defineProperty(_NumberOfRecords_M, "INSERT_ROLE", "Thêm Mới Vai Trò"), _defineProperty(_NumberOfRecords_M, "Update_User", "Cập Nhật Tài Khoản"), _defineProperty(_NumberOfRecords_M, "Delete_Role", "Xóa Vai Trò"), _defineProperty(_NumberOfRecords_M, "Command AGV Was Destroy", "Lệnh AGV đã bị hủy"), _defineProperty(_NumberOfRecords_M, "Select machine", "Chọn máy sản xuất"), _defineProperty(_NumberOfRecords_M, "Loading data", "Đang tải dữ liệu"), _defineProperty(_NumberOfRecords_M, "Mold code", "Mã khuôn"), _defineProperty(_NumberOfRecords_M, "Actual start time", "Bắt đầu thực tế"), _defineProperty(_NumberOfRecords_M, "Cycle time", "Thời gian đóng mở khuôn"), _defineProperty(_NumberOfRecords_M, "Quantity NG", "Số lượng sản phẩm lỗi"), _defineProperty(_NumberOfRecords_M, "OEE parameter chart", "Biểu đồ thông số hiệu suất OEE"), _defineProperty(_NumberOfRecords_M, "Active timeline chart", "Biểu đồ thời gian hoạt động"), _defineProperty(_NumberOfRecords_M, "Address", "Địa Chỉ"), _defineProperty(_NumberOfRecords_M, "Contact", "Liên Hệ"), _defineProperty(_NumberOfRecords_M, "Phone", "Điện Thoại"), _NumberOfRecords_M);

/***/ }),

/***/ "./resources/js/master-data/unit.js":
/*!******************************************!*\
  !*** ./resources/js/master-data/unit.js ***!
  \******************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _lang__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../lang */ "./resources/js/lang/index.js");
var _$$DataTable;

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

$('.select2').select2();

var route = "".concat(window.location.origin, "/api/settings/unit");
var route_show = "".concat(window.location.origin, "/setting/setting-unit/show"); // var route_his = `${window.location.origin}/api/settings/unit/history`;
// var route_return = `${window.location.origin}/setting/setting-unit/return`;

var table = $('.table-unit').DataTable((_$$DataTable = {
  scrollX: true,
  searching: false,
  ordering: false,
  language: {
    lengthMenu: Object(_lang__WEBPACK_IMPORTED_MODULE_0__["default"])('Number of records _MENU_'),
    info: Object(_lang__WEBPACK_IMPORTED_MODULE_0__["default"])('Showing _START_ to _END_ of _TOTAL_ entries'),
    paginate: {
      previous: '‹',
      next: '›'
    }
  },
  processing: true,
  serverSide: true
}, _defineProperty(_$$DataTable, "searching", false), _defineProperty(_$$DataTable, "dom", 'rt<"bottom"flp><"clear">'), _defineProperty(_$$DataTable, "lengthMenu", [10, 20, 30, 40, 50]), _defineProperty(_$$DataTable, "ajax", {
  url: route,
  dataSrc: 'data',
  data: function data(d) {
    delete d.columns;
    delete d.order;
    delete d.search;
    d.page = d.start / d.length + 1;
    d.symbols = $('.symbols').val();
    d.name = $('.name').val();
  }
}), _defineProperty(_$$DataTable, "columns", [{
  data: 'ID',
  defaultContent: '',
  title: 'ID'
}, {
  data: 'Name',
  defaultContent: '',
  title: Object(_lang__WEBPACK_IMPORTED_MODULE_0__["default"])('Name')
}, {
  data: 'Symbols',
  defaultContent: '',
  title: Object(_lang__WEBPACK_IMPORTED_MODULE_0__["default"])('Symbols')
}, {
  data: 'Type',
  title: Object(_lang__WEBPACK_IMPORTED_MODULE_0__["default"])('Type'),
  render: function render(data) {
    if (data == 1) {
      return Object(_lang__WEBPACK_IMPORTED_MODULE_0__["default"])('Unit');
    } else {
      return Object(_lang__WEBPACK_IMPORTED_MODULE_0__["default"])('Parking');
    }
  }
}, {
  data: 'Note',
  defaultContent: '',
  title: Object(_lang__WEBPACK_IMPORTED_MODULE_0__["default"])('Note')
}, {
  data: 'user_created.username',
  defaultContent: '',
  title: Object(_lang__WEBPACK_IMPORTED_MODULE_0__["default"])('User Created')
}, {
  data: 'Time_Created',
  defaultContent: '',
  title: Object(_lang__WEBPACK_IMPORTED_MODULE_0__["default"])('Time Created')
}, {
  data: 'user_updated.username',
  defaultContent: '',
  title: Object(_lang__WEBPACK_IMPORTED_MODULE_0__["default"])('User Updated')
}, {
  data: 'Time_Updated',
  defaultContent: '',
  title: Object(_lang__WEBPACK_IMPORTED_MODULE_0__["default"])('Time Updated')
}, {
  data: 'ID',
  title: Object(_lang__WEBPACK_IMPORTED_MODULE_0__["default"])('Action'),
  render: function render(data) {
    console.log(route);
    return "<a href=\"" + route_show + "?ID=" + data + "\" class=\"btn btn-success\" style=\"width: 80px\">\n           " + Object(_lang__WEBPACK_IMPORTED_MODULE_0__["default"])('Edit') + "\n            </a>\n            \n            <button id=\"del-" + data + "\" class=\"btn btn-danger btn-delete\" style=\"width: 80px\">\n            " + Object(_lang__WEBPACK_IMPORTED_MODULE_0__["default"])('Delete') + "\n\t\t\t</button>\n            ";
  }
}]), _$$DataTable));
$('table').on('page.dt', function () {
  console.log(table.page.info());
});
$('.filter').on('click', function () {
  table.ajax.reload();
});
$('.btn-import').on('click', function () {
  $('#modalImport').modal();
  $('#importFile').val('');
  $('.input-text').text(__input.file);
  $('.error-file').hide();
  $('.btn-save-file').prop('disabled', false);
  $('#product_id').val('');
});
var check_file = false;
$('#importFile').on('change', function () {
  var val = $(this).val();
  var name = val.split('\\').pop();
  var typeFile = name.split('.').pop().toLowerCase();
  $('.input-text').text(name);
  $('.error-file').hide();

  if (typeFile != 'xlsx' && typeFile != 'xls' && typeFile != 'txt') {
    $('.error-file').show();
    $('.btn-save-file').prop('disabled', true);
  } else {
    $('.btn-save-file').prop('disabled', false);
    check_file = true;
  }
});
$('.btn-save-file').on('click', function () {
  $('.error-file').hide();

  if (check_file) {
    $('.btn-submit-file').click();
  } else {
    $('.error-file').show();
  }
});
$(document).on('click', '.btn-delete', function () {
  var id = $(this).attr('id');
  var name = $(this).parent().parent().children('td').first().text();
  $('#modalRequestDel').modal();
  $('#nameDel').text(name);
  $('#idDel').val(id.split('-')[1]);
}); // $(document).on('click', '.btn-history', function() {
//     let id = $(this).attr('id');
//     let name = $(this).parent().parent().children('td').first().text();
//     $('#modalTableHistory').modal();
//     $('#idUnit').val(id.split('-')[1]);
//     tablehis.ajax.reload()
//     $('#nameDel').text(name);
// });
// const tablehis = $('.table-his').DataTable({
//     scrollX: true,
//     searching: false,
//     ordering: false,
//     language: {
//         lengthMenu: t('Number of records _MENU_'),
//         info: t('Showing _START_ to _END_ of _TOTAL_ entries'),
//         paginate: {
//             previous: '‹',
//             next: '›'
//         },
//     },
//     processing: true,
//     serverSide: true,
//     searching: false,
//     dom: 'rt<"bottom"flp><"clear">',
//     lengthMenu: [10, 20, 30, 40, 50],
//     ajax: {
//         url: route_his,
//         dataSrc: 'data',
//         data: d => {
//             delete d.columns
//             delete d.order
//             delete d.search
//             d.page = (d.start / d.length) + 1
//             d.unitid = $('#idUnit').val()
//         }
//     },
//     columns: [
//         { data: 'ID', defaultContent: '', title: 'ID' },
//         { data: 'Name', defaultContent: '', title: t('Name') },
//         { data: 'Symbols', defaultContent: '', title: t('Symbols') },
//         {
//             data: 'Type',
//             title: t('Type'),
//             render: function(data) {
//                 if (data == 1) {
//                     return t('Unit');
//                 } else {
//                     return t('Parking');
//                 }
//             }
//         },
//         {
//             data: 'Status',
//             title: t('Status'),
//             render: function(data) {
//                 if (data == 1) {
//                     return t('Update');
//                 } else if (data == 2) {
//                     return t('Delete');
//                 } else {
//                     return t('Return');
//                 }
//             }
//         },
//         { data: 'Note', defaultContent: '', title: t('Note') },
//         { data: 'user_created.username', defaultContent: '', title: t('User Created') },
//         { data: 'Time_Created', defaultContent: '', title: t('Time Created') },
//     ]
// })

/***/ }),

/***/ 1:
/*!************************************************!*\
  !*** multi ./resources/js/master-data/unit.js ***!
  \************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! F:\laravel\STI\coppercoil\Coppercoil\resources\js\master-data\unit.js */"./resources/js/master-data/unit.js");


/***/ })

/******/ });
=======
$('.select2').select2()

$('#tableUnit').DataTable({
    language: __languages.table,
    scrollX: '100%',
    scrollY: '100%'
});

$(document).on('click', '.btn-delete', function() {
    let id = $(this).attr('id');
    let name = $(this).parent().parent().children('td').first().text();

    $('#modalRequestDel').modal();
    $('#nameDel').text(name);
    $('#idDel').val(id.split('-')[1]);
});

$('.btn-import').on('click', function() {
    $('#modalImport').modal();
    $('#importFile').val('');
    $('.input-text').text(__input.file);
    $('.error-file').hide();
    $('.btn-save-file').prop('disabled', false);
    $('#product_id').val('');

});

$('#importFile').on('change', function() {
    check_file = false;
    let val = $(this).val();
    let name = val.split('\\').pop();
    let typeFile = name.split('.').pop().toLowerCase();
    $('.input-text').text(name);
    $('.error-file').hide();

    if (typeFile != 'xlsx' && typeFile != 'xls' && typeFile != 'txt') {
        $('.error-file').show();
        $('.btn-save-file').prop('disabled', true);
    } else {
        $('.btn-save-file').prop('disabled', false);
        check_file = true;
    }
});

$('.btn-save-file').on('click', function() {
    $('.error-file').hide();

    if (check_file) {
        $('.btn-submit-file').click();
    } else {
        $('.error-file').show();
    }
});
>>>>>>> ac6326cc4ac17c09168a3da964348ab4740293d0
