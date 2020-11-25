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
/******/ 	return __webpack_require__(__webpack_require__.s = 5);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/assets/backend/js/crudplaylist.js":
/*!*****************************************************!*\
  !*** ./resources/assets/backend/js/crudplaylist.js ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(document).ready(function () {
  $('#channels').select2({
    placeholder: "Choose roles"
  });

  function cssVideo() {
    $('#videos-table_length').addClass('main__table-text');
    $('#videos-table_paginate').addClass('paginator');
    $('#videos-table_length label select').select2();
  }

  function cssOptionVideo() {
    $('#no-playlist-videos-table_length').addClass('main__table-text');
    $('#no-playlist-videos-table_paginate').addClass('paginator');
    $('#no-playlist-videos-table_length label select').select2();
  }

  function dataTableVideo() {
    var myTable = $('#videos-table').DataTable({
      processing: true,
      serverSide: true,
      searching: false,
      destroy: true,
      ajax: {
        "url": '/admin/playlist/playlistVideos/' + $('#playlistId').val()
      },
      columns: [{
        data: 'id',
        name: 'id',
        orderable: false,
        searchable: false
      }, {
        data: 'title',
        name: 'title',
        orderable: false,
        searchable: false,
        "class": 'td-with'
      }, {
        data: 'chap',
        name: 'chap',
        orderable: false,
        searchable: false
      }, {
        data: 'action',
        name: 'action',
        orderable: false,
        searchable: false
      }]
    });
    cssVideo();
  }

  function dataTableOptionVideo() {
    var myTable = $('#no-playlist-videos-table').DataTable({
      processing: true,
      serverSide: true,
      searching: false,
      destroy: true,
      ajax: {
        "url": '/admin/playlist/videos/' + $('#playlistId').val()
      },
      columns: [{
        data: 'action',
        name: 'action',
        orderable: false,
        searchable: false
      }, {
        data: 'id',
        name: 'id',
        orderable: false,
        searchable: false
      }, {
        data: 'title',
        name: 'title',
        orderable: false,
        searchable: false,
        "class": 'td-with'
      }]
    });
    cssOptionVideo();
  }

  dataTableVideo();
  dataTableOptionVideo();
  $('#status').on('click', function () {
    var _this = this;

    $.ajax({
      type: "POST",
      url: '/admin/playlist/changeStatus/' + $('#playlistId').val(),
      dataType: 'json',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function success(response) {
        if (response.status == 1) {
          $(_this).html('<i class="fa fa-unlock-alt" aria-hidden="true" data-toggle="tooltip" title="Activate"></i>');
        } else {
          $(_this).html('<i class="icon ion-ios-lock" data-toggle="tooltip" title="Hide"></i>');
        }

        swal({
          title: "Changed",
          text: "Successfully",
          icon: "success"
        });
      }
    });
  });
  $("#btn-create-video").click(function (e) {
    e.preventDefault();
    var formData = new FormData();
    formData.append('title', $("#frmCreateVideo input[name='title']").val());
    formData.append('channel_id', $('#frmCreateVideo .channels').val());
    formData.append('source_key', $("#frmCreateVideo input[name='source_key']").val());
    formData.append('chap', $("#frmCreateVideo input[name='chap']").val());
    formData.append('description', $("#frmCreateVideo textarea[name='description']").val());
    formData.append('tags', $("#frmCreateVideo input[name='tags']").val());
    formData.append('playlist_id', $("#frmCreateVideo input[name='playlist_id']").val());
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('#frmCreateVideo meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
      type: 'POST',
      url: '/admin/video/store',
      data: formData,
      contentType: false,
      processData: false,
      cache: false,
      success: function success(data) {
        $("#frmCreateVideo input[name='title']").val('');
        $("#frmCreateVideo input[name='source_key']").val('');
        $("#frmCreateVideo input[name='chap']").val('');
        $("#frmCreateVideo textarea[name='description']").val('');
        $("#frmCreateVideo input[name='tags']").val('');
        $("#video1").html('');
        $("#frmCreateVideo .modal__btn--dismiss").click();
        dataTableVideo();
        swal("Create video completed", " ", "success");
      },
      error: function error(data) {
        var errors = $.parseJSON(data.responseText);
        $("#frmCreateVideo .title").html('');
        $("#frmCreateVideo .description").html('');
        $("#frmCreateVideo .chap").html('');
        $.each(errors.errors, function (key, value) {
          $("#frmCreateVideo ." + key).html(value);
        });
      }
    });
  });

  window.chooseVideo = function (videoId) {
    $.ajax({
      type: "POST",
      url: '/admin/playlist/chooseVideo/' + $('#playlistId').val(),
      "data": {
        "videoId": videoId
      },
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function success(response) {
        dataTableVideo();
        dataTableOptionVideo();
        swal("Choose video completed", " ", "success");
      }
    });
  };

  window.detachVideo = function (videoId) {
    swal({
      title: "Are you sure to remove this video?",
      icon: "warning",
      buttons: true,
      dangerMode: true
    }).then(function (remove) {
      if (remove) {
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
          type: "POST",
          url: '/admin/video/detach/' + videoId,
          success: function success() {
            dataTableVideo();
            dataTableOptionVideo();
            swal({
              title: "Detach",
              text: "Successfully",
              icon: "success"
            });
          }
        });
      }
    });
  };

  $('#delete_playlist').on('click', function (e) {
    e.preventDefault();
    var form = event.target.form;
    swal({
      title: "Are you sure to delete this playlist?",
      text: "This action can not be undone",
      icon: "warning",
      buttons: true,
      dangerMode: true
    }).then(function (willDelete) {
      if (willDelete) {
        form.submit();
      }
    });
  });
});

/***/ }),

/***/ 5:
/*!***********************************************************!*\
  !*** multi ./resources/assets/backend/js/crudplaylist.js ***!
  \***********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /opt/lampp/htdocs/php_oe29_films_1/resources/assets/backend/js/crudplaylist.js */"./resources/assets/backend/js/crudplaylist.js");


/***/ })

/******/ });