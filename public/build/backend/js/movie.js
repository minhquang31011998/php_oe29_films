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

/***/ "./resources/assets/backend/js/movie.js":
/*!**********************************************!*\
  !*** ./resources/assets/backend/js/movie.js ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(document).ready(function () {
  function css() {
    $('#movies-table_length').addClass('main__table-text');
    $('#movies-table_paginate').addClass('paginator');
    $('#movies-table_length label select').select2();
  }

  ;

  function dataTable() {
    var name = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';
    var slug = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : '';
    var sort = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : '';
    $('#movies-table').DataTable({
      processing: true,
      serverSide: true,
      searching: false,
      destroy: true,
      ajax: {
        "url": '/admin/movie/data',
        "data": {
          "name": name,
          "slug": slug,
          "sort": sort
        }
      },
      columns: [{
        data: 'id',
        name: 'id',
        orderable: false,
        searchable: false
      }, {
        data: 'name',
        name: 'name',
        orderable: false,
        searchable: false,
        "class": 'td-with'
      }, {
        data: 'slug',
        name: 'slug',
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

  ;
  dataTable();
  $('.filter__item-menu li').on('click', function (e) {
    e.preventDefault();
    var sort = $('.filter__item-btn input').val();
    var name = $('#filter').val();
    ;
    var slug = '';
    $.ajax({
      "url": '/admin/movie/data',
      data: {
        sort: sort,
        name: name,
        slug: slug
      },
      success: function success(response) {
        dataTable(name, slug, sort);
      },
      error: function error(_error) {
        alert('The system is maintenance');
      }
    });
  });

  function search(e) {
    var sort = $('#sort').val();
    var name = $('#filter').val();
    var slug = '';
    $.ajax({
      url: '/admin/movie/data',
      data: {
        sort: sort,
        name: name,
        slug: slug
      },
      success: function success(response) {
        dataTable(name, slug, sort);
      },
      error: function error(_error2) {
        alert('The system is maintenance');
      }
    });
    $('#movies-table').dataTable;
  }

  ;
  $('#filter').on('change', function (e) {
    e.preventDefault();
    window.setTimeout(search, 200);
  });
  $('#movies-table').on('click', '.btn-nominations', function (e) {
    var _this = this;

    e.preventDefault();
    var status = $(this).attr('data_status');
    var id = $(this).attr('movie_id');
    $.ajax({
      type: 'get',
      url: '/admin/movie/' + id + '/nominations',
      success: function success(result) {
        if (result.nominations == 1) {
          $(_this).attr('title', 'Turn off nomination');
          $(_this).removeClass('main__table-btn--delete');
          $(_this).addClass('main__table-btn--edit');
          $(_this).remove('icon');
          $(_this).html('<icon class="icon ion-ios-radio-button-on">');
        } else {
          $(_this).attr('title', 'Turn on nomination');
          $(_this).addClass('main__table-btn--delete');
          $(_this).removeClass('main__table-btn--edit');
          $(_this).remove('icon');
          $(_this).html('<icon class="icon ion-ios-radio-button-off">');
        }

        swal({
          title: "Changed",
          text: "Successfully",
          icon: "success"
        });
      }
    });
  });
});

/***/ }),

/***/ 2:
/*!****************************************************!*\
  !*** multi ./resources/assets/backend/js/movie.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /opt/lampp/htdocs/php_oe29_films_1/resources/assets/backend/js/movie.js */"./resources/assets/backend/js/movie.js");


/***/ })

/******/ });