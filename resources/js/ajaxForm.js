/**
 * jQuery AJAX form
 *
 * @author http://guenanank.com
 */

(function($) {

    var __t = this;
    __t.baseUrl = $('base').attr('href');
    __t.token = $('meta[name="csrf-token"]').attr('content');

    $.fn.ajaxForm = function(obj) {
        var setting = $.fn.extend({
            url: '',
            data: {},
            beforeSend: function() {},
            afterSend: function() {},
            refresh: true
        }, obj);

        return this.each(function() {
            $.ajax({
                type: $(this).attr('method'),
                url: (setting.url) ? setting.url : $(this).attr('action'),
                data: typeof setting.data === 'undefined' ? setting.data : new FormData(this),
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: (setting.beforeSend) ? setting.beforeSend : function() {},
                statusCode: {
                    200: function(data) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Your work has been saved',
                            // showConfirmButton: false,
                            // timer: 1750
                        }).then(function() {
                            if (setting.refresh) {
                                location.reload(true);
                            }
                        });
                    },
                    422: function(response) {
                        $.each(response.responseJSON.errors, function(k, v) {
                            // $('#' + k).addClass('is-invalid');
                            $('#' + k + 'Help').text(v);
                        });
                    }
                }
            }).always((setting.afterSend) ? setting.afterSend : function() {});
        });
    };

    $.fn.ajaxDelete = function() {
        return this.each(function() {
            Swal.fire({
                icon: 'warning',
                title: 'Are you sure?',
                text: 'You won\'t be able to revert this!',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: 'DELETE',
                        url: $(this).attr('href'),
                        data: {
                            _method: 'DELETE'
                        },
                        success: function() {
                            Swal.fire({
                                icon: 'success',
                                title: 'Your work has been deleted',
                                // showConfirmButton: false,
                                // timer: 1750
                            }).then(function() {
                                location.reload(true);
                            });
                        }
                    });
                } else if (result.dismiss === swal.DismissReason.cancel) {
                    Swal.fire({
                        title: 'Your data is safe :)',
                        icon: 'error',
                        // showConfirmButton: false,
                        // timer: 1750
                    }).then(function() {
                        location.reload(true);
                    });
                }
            });
        });
    };
})(jQuery);
