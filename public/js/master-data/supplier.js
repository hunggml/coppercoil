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
/******/ 	return __webpack_require__(__webpack_require__.s = 2);
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
  "Number of records _MENU_": "S??? l?????ng b???n ghi _MENU_",
  "Showing _START_ to _END_ of _TOTAL_ entries": "B???n ghi t??? _START_ ?????n _END_ c???a _TOTAL_ b???n ghi",
  "Name": "T??n",
  "Symbols": "M??",
  "Type": "Lo???i",
  "Note": "Ghi ch??",
  "User Created": "Ng?????i t???o",
  "Time Created": "Th???i gian t???o",
  "User Updated": "Ng?????i c???p nh???t",
  "Time Updated": "Th???i gian c???p nh???t",
  "Action": "H??nh ?????ng",
  "Edit": "S???a",
  "Delete": "X??a",
  "Start Time": "Th???i Gian B???t ?????u",
  "End Time": "Th???i Gian K???t Th??c",
  "Cycle Time": "Chu K?? S???n Xu???t 1 S???n Ph???m ",
  "Detail": "Chi Ti???t",
  "Month": "Th??ng",
  "Year": "N??m",
  "Location Take Materials": "V??? Tr?? L???y H??ng",
  "Materials Return Location": "V??? Tr?? Tr??? H??ng",
  "Destroy": "H???y"
}, _defineProperty(_NumberOfRecords_M, "Detail", "Chi Ti???t"), _defineProperty(_NumberOfRecords_M, "Success", "Ho??n Th??nh"), _defineProperty(_NumberOfRecords_M, "Product", "S???n Ph???m"), _defineProperty(_NumberOfRecords_M, "Machine", "M??y S???n Xu???t"), _defineProperty(_NumberOfRecords_M, "Dont Production", "Ch??a S???n Xu???t"), _defineProperty(_NumberOfRecords_M, "Are Production", "??ang S???n Xu???t"), _defineProperty(_NumberOfRecords_M, "Success Production", "Ho??n Th??nh S???n Xu???t"), _defineProperty(_NumberOfRecords_M, "Status", "Tr???ng Th??i"), _defineProperty(_NumberOfRecords_M, "Export", "Xu???t Kho"), _defineProperty(_NumberOfRecords_M, "Materials", "Nguy??n V???t Li???u"), _defineProperty(_NumberOfRecords_M, "Dont Export", "Ch??a Xu???t"), _defineProperty(_NumberOfRecords_M, "Are Export", "??ang Xu???t"), _defineProperty(_NumberOfRecords_M, "Success Export", "Ho??n Th??nh Xu???t"), _defineProperty(_NumberOfRecords_M, "Stock Min", "T???n Gi???i H???n"), _defineProperty(_NumberOfRecords_M, "Cavity", "Cavity"), _defineProperty(_NumberOfRecords_M, "Mold", "Khu??n"), _defineProperty(_NumberOfRecords_M, "Quantity Mold", "S??? L?????ng Khu??n"), _defineProperty(_NumberOfRecords_M, "Quantity", "S??? L?????ng"), _defineProperty(_NumberOfRecords_M, "Date", "Ng??y"), _defineProperty(_NumberOfRecords_M, "Unit", "????n V??? T??nh"), _defineProperty(_NumberOfRecords_M, "Parking", "????n V??? ????ng G??i"), _defineProperty(_NumberOfRecords_M, "History", "L???ch S???"), _defineProperty(_NumberOfRecords_M, "Return", "Kh??i Ph???c"), _defineProperty(_NumberOfRecords_M, "Update", "C???p Nh???t"), _defineProperty(_NumberOfRecords_M, "Delete", "X??a"), _defineProperty(_NumberOfRecords_M, "Processing", "??ang Th???c Hi???n"), _defineProperty(_NumberOfRecords_M, "Enable", "K??ch Ho???t"), _defineProperty(_NumberOfRecords_M, "Disable", "V?? Hi???u H??a"), _defineProperty(_NumberOfRecords_M, "Part", "B??? Ph???n"), _defineProperty(_NumberOfRecords_M, "Time Start", "Th???i Gian B???t ?????u"), _defineProperty(_NumberOfRecords_M, "Time End", "Th???i Gian K???t Th??c"), _defineProperty(_NumberOfRecords_M, "Manager AGV", "Ng?????i Qu???n L?? AGV"), _defineProperty(_NumberOfRecords_M, "Maintenance Time", "Th???i Gian B???o Tr??"), _defineProperty(_NumberOfRecords_M, "Maintenance Date", "Ng??y B???o D?????ng"), _defineProperty(_NumberOfRecords_M, "Warehouse", "Kho"), _defineProperty(_NumberOfRecords_M, "Plan", "K??? Ho???ch"), _defineProperty(_NumberOfRecords_M, "Production", "S???n Xu???t"), _defineProperty(_NumberOfRecords_M, "Output", "S???n L?????ng"), _defineProperty(_NumberOfRecords_M, "End", "K???t Th??c"), _defineProperty(_NumberOfRecords_M, "Error", "L???i"), _defineProperty(_NumberOfRecords_M, "Time Real Start", "B???t ?????u Th???c T???"), _defineProperty(_NumberOfRecords_M, "Time Real End", "K???t Th??c Th???c T???"), _defineProperty(_NumberOfRecords_M, "Symbols Plan", "M?? Ch??? Th???"), _defineProperty(_NumberOfRecords_M, "Infor", "Th??ng Tin"), _defineProperty(_NumberOfRecords_M, "Normal", "Xu???t Th?????ng"), _defineProperty(_NumberOfRecords_M, "Cancel", "H???y"), _defineProperty(_NumberOfRecords_M, "Start", "B???t ?????u"), _defineProperty(_NumberOfRecords_M, "User Name", "T??n ????ng Nh???p"), _defineProperty(_NumberOfRecords_M, "ENABLE", "K??ch Ho???t"), _defineProperty(_NumberOfRecords_M, "DISABLE", "Kh??ng K??ch Ho???t"), _defineProperty(_NumberOfRecords_M, "Action Name", "Ki???u H??nh ?????ng"), _defineProperty(_NumberOfRecords_M, "INSERT", "Th??m M???i"), _defineProperty(_NumberOfRecords_M, "Insert", "Th??m M???i"), _defineProperty(_NumberOfRecords_M, "OEE", "Hi???u Su???t T???ng Th???"), _defineProperty(_NumberOfRecords_M, "Availability", "Kh??? D???ng"), _defineProperty(_NumberOfRecords_M, "Performance", "Hi???u Su???t"), _defineProperty(_NumberOfRecords_M, "Quality", "Ch???t L?????ng"), _defineProperty(_NumberOfRecords_M, "shift", "ca"), _defineProperty(_NumberOfRecords_M, "day", "ng??y"), _defineProperty(_NumberOfRecords_M, "Description", "Ch?? Th??ch"), _defineProperty(_NumberOfRecords_M, "Find AGV", "T??m AGV"), _defineProperty(_NumberOfRecords_M, "Role", "Vai Tr??"), _defineProperty(_NumberOfRecords_M, "Waiting for AGV", "Ch??? AGV"), _defineProperty(_NumberOfRecords_M, "AGV Shipping", "AGV ??ang Chuy???n H??ng"), _defineProperty(_NumberOfRecords_M, "IsDelete", "X??a"), _defineProperty(_NumberOfRecords_M, "To", "T???i"), _defineProperty(_NumberOfRecords_M, "Command", "L???nh"), _defineProperty(_NumberOfRecords_M, "INSERT_USER", "Th??m M???i T??i Kho???n"), _defineProperty(_NumberOfRecords_M, "INSERT_ROLE", "Th??m M???i Vai Tr??"), _defineProperty(_NumberOfRecords_M, "Update_User", "C???p Nh???t T??i Kho???n"), _defineProperty(_NumberOfRecords_M, "Delete_Role", "X??a Vai Tr??"), _defineProperty(_NumberOfRecords_M, "Command AGV Was Destroy", "L???nh AGV ???? b??? h???y"), _defineProperty(_NumberOfRecords_M, "Select machine", "Ch???n m??y s???n xu???t"), _defineProperty(_NumberOfRecords_M, "Loading data", "??ang t???i d??? li???u"), _defineProperty(_NumberOfRecords_M, "Mold code", "M?? khu??n"), _defineProperty(_NumberOfRecords_M, "Actual start time", "B???t ?????u th???c t???"), _defineProperty(_NumberOfRecords_M, "Cycle time", "Th???i gian ????ng m??? khu??n"), _defineProperty(_NumberOfRecords_M, "Quantity NG", "S??? l?????ng s???n ph???m l???i"), _defineProperty(_NumberOfRecords_M, "OEE parameter chart", "Bi???u ????? th??ng s??? hi???u su???t OEE"), _defineProperty(_NumberOfRecords_M, "Active timeline chart", "Bi???u ????? th???i gian ho???t ?????ng"), _defineProperty(_NumberOfRecords_M, "Address", "?????a Ch???"), _defineProperty(_NumberOfRecords_M, "Contact", "Li??n H???"), _defineProperty(_NumberOfRecords_M, "Phone", "??i???n Tho???i"), _NumberOfRecords_M);

/***/ }),

/***/ "./resources/js/master-data/supplier.js":
/*!**********************************************!*\
  !*** ./resources/js/master-data/supplier.js ***!
  \**********************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _lang__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../lang */ "./resources/js/lang/index.js");
var _$$DataTable;

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

$('.select2').select2();

var route = "".concat(window.location.origin, "/api/settings/supplier");
var route_show = "".concat(window.location.origin, "/setting/setting-supplier/show"); // var route_his = `${window.location.origin}/api/settings/unit/history`;
// var route_return = `${window.location.origin}/setting/setting-unit/return`;

var table = $('.table-supplier').DataTable((_$$DataTable = {
  scrollX: true,
  searching: false,
  ordering: false,
  language: {
    lengthMenu: Object(_lang__WEBPACK_IMPORTED_MODULE_0__["default"])('Number of records _MENU_'),
    info: Object(_lang__WEBPACK_IMPORTED_MODULE_0__["default"])('Showing _START_ to _END_ of _TOTAL_ entries'),
    paginate: {
      previous: '???',
      next: '???'
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
  data: 'Address',
  defaultContent: '',
  title: Object(_lang__WEBPACK_IMPORTED_MODULE_0__["default"])('Address')
}, {
  data: 'Contact',
  defaultContent: '',
  title: Object(_lang__WEBPACK_IMPORTED_MODULE_0__["default"])('Contact')
}, {
  data: 'Phone',
  defaultContent: '',
  title: Object(_lang__WEBPACK_IMPORTED_MODULE_0__["default"])('Phone')
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
//             previous: '???',
//             next: '???'
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

/***/ 2:
/*!****************************************************!*\
  !*** multi ./resources/js/master-data/supplier.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! F:\laravel\STI\coppercoil\Coppercoil\resources\js\master-data\supplier.js */"./resources/js/master-data/supplier.js");


/***/ })

/******/ });