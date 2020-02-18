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

/***/ "./resources/js/bootstrap-strength-meter.js":
/*!**************************************************!*\
  !*** ./resources/js/bootstrap-strength-meter.js ***!
  \**************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _typeof(obj) { if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

/**
 * bootstrap-strength-meter.js
 * https://github.com/davidstutz/bootstrap-strength-meter
 *
 * Copyright 2013 - 2019 David Stutz
 */
!function ($) {
  "use strict"; // jshint ;_;

  var StrengthMeter = {
    progressBar: function progressBar(input, options) {
      var defaults = {
        container: input.parent(),
        base: 80,
        hierarchy: {
          '0': 'progress-bar-danger',
          '50': 'progress-bar-warning',
          '100': 'progress-bar-success'
        },
        passwordScore: {
          options: [],
          append: true
        }
      };
      var settings = $.extend(true, {}, defaults, options);

      if (_typeof(options) === 'object' && 'hierarchy' in options) {
        settings.hierarchy = options.hierarchy;
      }

      var template = '<div class="progress"><div class="progress-bar" role="progressbar"></div></div>';
      var progress;
      var progressBar;
      var passcheckTimeout;
      var core = {
        /**
         * Initialize the plugin.
         */
        init: function init() {
          progress = settings.container.append($(template));
          progressBar = $('.progress-bar', progress);
          progressBar.attr('aria-valuemin', 0).attr('aria-valuemay', 100);
          input.on('keyup', core.keyup).keyup();
        },
        queue: function queue(event) {
          var password = $(event.target).val();
          var value = 0;

          if (password.length > 0) {
            var score = new Score(password);
            value = score.calculateEntropyScore(settings.passwordScore.options, settings.passwordScore.append);
          }

          core.update(value);
        },

        /**
         * Update progress bar.
         *
         * @param {string} value
         */
        update: function update(value) {
          var width = Math.floor(value / settings.base * 100);

          if (width > 100) {
            width = 100;
          }

          progressBar.attr('area-valuenow', width).css('width', width + '%');

          for (var value in settings.hierarchy) {
            if (width > value) {
              progressBar.removeClass().addClass('progress-bar').addClass(settings.hierarchy[value]);
            }
          }
        },

        /**
         * Event binding on password input.
         *
         * @param {Object} event
         */
        keyup: function keyup(event) {
          if (passcheckTimeout) clearTimeout(passcheckTimeout);
          passcheckTimeout = setTimeout(function () {
            core.queue(event);
          }, 500);
        }
      };
      core.init();
    },
    text: function text(input, options) {
      var defaults = {
        container: input.parent(),
        hierarchy: {
          '0': ['text-danger', 'ridiculous'],
          '25': ['text-danger', 'very weak'],
          '50': ['text-warning', 'weak'],
          '75': ['text-warning', 'good'],
          '100': ['text-success', 'strong'],
          '125': ['text-success', 'very strong']
        },
        passwordScore: {
          options: [],
          append: true
        }
      };
      var settings = $.extend(true, {}, defaults, options);

      if (_typeof(options) === 'object' && 'hierarchy' in options) {
        settings.hierarchy = options.hierarchy;
      }

      var core = {
        /**
         * Initialize the plugin.
         */
        init: function init() {
          input.on('keyup', core.keyup).keyup();
        },

        /**
         * Update text element.
         *
         * @param {string} value
         */
        update: function update(value) {
          for (var border in settings.hierarchy) {
            if (value >= border) {
              var text = settings.hierarchy[border][1];
              var color = settings.hierarchy[border][0];
              settings.container.text(text).removeClass().addClass(color);
            }
          }
        },

        /**
         * Event binding on input element.
         *
         * @param {Object} event
         */
        keyup: function keyup(event) {
          var password = $(event.target).val();
          var value = 0;

          if (password.length > 0) {
            var score = new Score(password);
            value = score.calculateEntropyScore(settings.passwordScore.options, settings.passwordScore.append);
          }

          core.update(value);
        }
      };
      core.init();
    },
    tooltip: function tooltip(input, options) {
      var defaults = {
        hierarchy: {
          '0': 'ridiculous',
          '25': 'very weak',
          '50': 'weak',
          '75': 'good',
          '100': 'strong',
          '125': 'very strong'
        },
        tooltip: {
          placement: 'right'
        },
        passwordScore: {
          options: [],
          append: true
        }
      };
      var settings = $.extend(true, {}, defaults, options);

      if (_typeof(options) === 'object' && 'hierarchy' in options) {
        settings.hierarchy = options.hierarchy;
      }

      var core = {
        /**
         * Initialize the plugin.
         */
        init: function init() {
          input.tooltip(settings.tooltip);
          input.on('keyup', core.keyup).keyup();
        },

        /**
         * Update tooltip.
         *
         * @param {string} value
         */
        update: function update(value) {
          for (var border in settings.hierarchy) {
            if (value >= border) {
              var text = settings.hierarchy[border];
              input.attr('data-original-title', text).tooltip('show');
            }
          }
        },

        /**
         * Event binding on input element.
         *
         * @param {Object} event
         */
        keyup: function keyup(event) {
          var password = $(event.target).val();
          var value = 0;

          if (password.length > 0) {
            var score = new Score(password);
            value = score.calculateEntropyScore(settings.passwordScore.options, settings.passwordScore.append);
          }

          core.update(value);
        }
      };
      core.init();
    }
  };

  $.fn.strengthMeter = function (type, options) {
    type = type === undefined ? 'tooltip' : type;

    if (!type in StrengthMeter) {
      return;
    }

    var instance = this.data('strengthMeter');
    var elem = this;
    return elem.each(function () {
      var strengthMeter;

      if (instance) {
        return;
      }

      strengthMeter = StrengthMeter[type](elem, options);
      elem.data('strengthMeter', strengthMeter);
    });
  };
}(window.jQuery);

/***/ }),

/***/ 1:
/*!********************************************************!*\
  !*** multi ./resources/js/bootstrap-strength-meter.js ***!
  \********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Applications/XAMPP/xamppfiles/htdocs/gateway/resources/js/bootstrap-strength-meter.js */"./resources/js/bootstrap-strength-meter.js");


/***/ })

/******/ });