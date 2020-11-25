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
/******/ 	return __webpack_require__(__webpack_require__.s = 8);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/assets/backend/js/source.js":
/*!***********************************************!*\
  !*** ./resources/assets/backend/js/source.js ***!
  \***********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(document).ready(function () {
  function css() {
    $('#sources-table_length').addClass('main__table-text');
    $('#sources-table_paginate').addClass('paginator');
    $('#sources-table_length label select').select2();
  }

  function dataTable() {
    var source_key = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';
    var myTable = $('#sources-table').DataTable({
      processing: true,
      serverSide: true,
      searching: false,
      destroy: true,
      ajax: {
        "url": '/admin/video/source',
        "data": {
          "videoId": $('#videoId').val()
        }
      },
      columns: [{
        data: 'id',
        name: 'id',
        orderable: false,
        searchable: false
      }, {
        data: 'source_key',
        name: 'source_key',
        orderable: false,
        searchable: false,
        "class": 'td-with'
      }, {
        data: 'prioritize',
        name: 'prioritize',
        orderable: false,
        searchable: false
      }, {
        data: 'channel_id',
        name: 'channel_id',
        orderable: false,
        searchable: false,
        "class": 'td-with'
      }, {
        data: 'action',
        name: 'action',
        orderable: false,
        searchable: false
      }]
    });
    css();
  }

  dataTable();
  $('#sources-table').on('click', '.edit-source', function (e) {
    e.preventDefault();
    var sourceId = $(this).attr('data-source');
    $.ajax({
      type: 'GET',
      url: '/admin/source/' + sourceId + '/edit/',
      success: function success(data) {
        $("#frmEditSource input[name=source_key]").val(data.source.source_key);
        $("#frmEditSource input[name=source_id]").val(data.source.id);
        $("#frmEditSource input[name=prioritize]").val(data.source.prioritize);
        $("#frmEditSource .channels").val(data.source.channel_id);
        $("#frmEditSource .channels").select2();
        $("#edit-source").click();
      },
      error: function error(data) {
        alert('The system is maintenance');
      }
    });
  });
  $("#btn-create").click(function (e) {
    e.preventDefault();
    var formData = new FormData();
    formData.append('prioritize', $("#frmCreateSource input[name='prioritize']").val());
    formData.append('channel_id', $('#frmCreateSource .channels').val());
    formData.append('source_key', $("#frmCreateSource input[name='source_key']").val());
    formData.append('source_id', $("#frmCreateSource input[name='source_id']").val());
    formData.append('video_id', $("#frmCreateSource input[name='video_id']").val());
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('#frmCreateSource meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
      type: 'POST',
      url: '/admin/source/store',
      data: formData,
      contentType: false,
      processData: false,
      cache: false,
      success: function success(data) {
        $("#frmCreateSource input[name='prioritize']").val('');
        $("#frmCreateSource input[name='source_key']").val('');
        $("#frmCreateSource .prioritize").html('');
        $("#frmCreateSource .source_key").html('');
        dataTable();
        $("#frmCreateSource .modal__btn--dismiss").click();
        swal("Create source completed", " ", "success");
      },
      error: function error(data) {
        var errors = $.parseJSON(data.responseText);
        $("#frmCreateSource .prioritize").html('');
        $("#frmCreateSource .source_key").html('');
        $.each(errors.errors, function (key, value) {
          $("#frmCreateSource ." + key).html(value);
        });
      }
    });
  });
  $("#btn-edit").click(function (e) {
    e.preventDefault();
    var formData = new FormData();
    formData.append('prioritize', $("#frmEditSource input[name='prioritize']").val());
    formData.append('channel_id', $('#frmEditSource .channels').val());
    formData.append('source_key', $("#frmEditSource input[name='source_key']").val());
    formData.append('source_id', $("#frmEditSource input[name='source_id']").val());
    formData.append('video_id', $("#frmCreateSource input[name='video_id']").val());
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
      type: 'POST',
      url: '/admin/source/update/' + $("#frmEditSource input[name=source_id]").val(),
      data: formData,
      contentType: false,
      processData: false,
      cache: false,
      success: function success(data) {
        $("#frmEditSource .prioritize").html('');
        $("#frmEditSource .source_key").html('');
        dataTable();
        $("#frmEditSource .modal__btn--dismiss").click();
        swal("Update source completed", " ", "success");
      },
      error: function error(data) {
        var errors = $.parseJSON(data.responseText);
        $("#frmEditSource .prioritize").html('');
        $("#frmEditSource .source_key").html('');
        $.each(errors.errors, function (key, value) {
          $("#frmEditSource ." + key).html(value);
        });
      }
    });
  });

  window.deleteSource = function (sourceId) {
    swal({
      title: "Are you sure to delete this source?",
      text: "This action can not be undone",
      icon: "warning",
      buttons: true,
      dangerMode: true
    }).then(function (willDelete) {
      if (willDelete) {
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('#frmCreateSource meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
          type: "POST",
          url: '/admin/source/delete/' + sourceId,
          dataType: 'json',
          success: function success(response) {
            dataTable();
            swal({
              title: "Deleted",
              text: "Successfully",
              icon: "success"
            });
          },
          error: function error(data) {
            dataTable();
          }
        });
      }
    });
  };
});

/***/ }),

/***/ 8:
/*!*****************************************************!*\
  !*** multi ./resources/assets/backend/js/source.js ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /opt/lampp/htdocs/php_oe29_films_1/resources/assets/backend/js/source.js */"./resources/assets/backend/js/source.js");


/***/ })

/******/ });