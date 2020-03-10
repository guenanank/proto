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

/***/ "./resources/js/ajaxForm.js":
/*!**********************************!*\
  !*** ./resources/js/ajaxForm.js ***!
  \**********************************/
/*! no static exports found */
/***/ (function(module, exports) {

/**
 * jQuery AJAX form
 *
 * @author http://guenanank.com
 */
(function ($) {
  var __t = this;

  __t.baseUrl = $('base').attr('href');
  __t.token = $('meta[name="csrf-token"]').attr('content');

  $.fn.ajaxForm = function (obj) {
    var setting = $.fn.extend({
      url: '',
      data: {},
      beforeSend: function beforeSend() {},
      afterSend: function afterSend() {},
      refresh: true
    }, obj);
    return this.each(function () {
      $.ajax({
        type: $(this).attr('method'),
        url: setting.url ? setting.url : $(this).attr('action'),
        data: typeof setting.data === 'undefined' ? setting.data : new FormData(this),
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: setting.beforeSend ? setting.beforeSend : function () {},
        statusCode: {
          200: function _(data) {
            Swal.fire({
              icon: 'success',
              title: 'Your work has been saved' // showConfirmButton: false,
              // timer: 1750

            }).then(function () {
              if (setting.refresh) {
                location.reload(true);
              }
            });
          },
          422: function _(response) {
            $.each(response.responseJSON.errors, function (k, v) {
              // $('#' + k).addClass('is-invalid');
              $('#' + k + 'Help').text(v);
            });
          }
        }
      }).always(setting.afterSend ? setting.afterSend : function () {});
    });
  };

  $.fn.ajaxDelete = function () {
    return this.each(function () {
      var _this = this;

      Swal.fire({
        icon: 'warning',
        title: 'Are you sure?',
        text: 'You won\'t be able to revert this!',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!'
      }).then(function (result) {
        if (result.value) {
          $.ajax({
            type: 'DELETE',
            url: $(_this).attr('href'),
            data: {
              _method: 'DELETE'
            },
            success: function success() {
              Swal.fire({
                icon: 'success',
                title: 'Your work has been deleted' // showConfirmButton: false,
                // timer: 1750

              }).then(function () {
                location.reload(true);
              });
            }
          });
        } else if (result.dismiss === swal.DismissReason.cancel) {
          Swal.fire({
            title: 'Your data is safe :)',
            icon: 'error' // showConfirmButton: false,
            // timer: 1750

          }).then(function () {
            location.reload(true);
          });
        }
      });
    });
  };
})(jQuery);

/***/ }),

/***/ 2:
/*!****************************************!*\
  !*** multi ./resources/js/ajaxForm.js ***!
  \****************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Applications/XAMPP/xamppfiles/htdocs/gateway/resources/js/ajaxForm.js */"./resources/js/ajaxForm.js");


/***/ })

/******/ });