(function($) {
    "use strict";

    var table = $('#dataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            type: 'post',
            url: baseUrl + '/order/dataTable'
        },
        columns: [{
                data: null,
                searchable: false,
                orderable: false,
                className: 'text-center details',
                defaultContent: '<button class="btn btn-secondary"><i class="fa fa-search"></i></button>'
            },
            {
                name: 'number',
                data: 'number'
            },
            {
                name: 'date',
                data: 'date'
            },
            {
                name: 'customer.name',
                data: 'customer.name',
                defaultContent: ''
            },
            {
                name: 'updated_at',
                data: 'updated_at',
                className: 'text-center'
            },
            {
                name: 'control',
                className: 'text-center',
                orderable: false,
                searchable: false
            }
        ],
        order: [
            [4, 'desc']
        ],
        columnDefs: [{
            targets: -1,
            className: 'text-center',
            data: 'control',
            render: function(data, type, row) {

                var settle = '';
                if (row.payment_type == null) {
                    settle += '<a href="' + baseUrl + '/payment/' + row.id + '" class="btn btn-secondary btn-icon-split" id="payment">' +
                        '<span class="icon text-white-50"><i class="fas fa-check"></i></span>' +
                        '<span class="text">Lunas</span>' +
                        '</a>';
                }

                var destroy = '<a href="' + baseUrl + '/order/' + row.id + '/" class="btn btn-danger btn-icon-split delete">' +
                    '<span class="icon text-white-50"><i class="fas fa-trash"></i></span>' +
                    '<span class="text">Hapus</span>' +
                    '</a>';

                return settle + '&nbsp;' + destroy;
            }
        }]
    });

    $('body').on('click', 'a#payment', function(e) {
        e.preventDefault();
        $.get($(this).attr('href'), function(modal) {
            $(modal).modal().on('shown.bs.modal', function() {
                $('.selectpicker').selectpicker();
                $('.datepicker').datetimepicker({
                    format: 'YYYY-MM-DD'
                });
                autosize($('.autosize'));
                $('form.ajaxForm').submit(function(e) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    var form = e.target;
                    var data = new FormData(form);
                    $.each(form.files, function(k, v) {
                        data.append('photos', form.files[k]);
                    });

                    $(this).ajaxForm({
                        data: data,
                        beforeSend: function() {
                            $('.loader').addClass('is-active');
                        },
                        afterSend: function() {
                            $('.loader').removeClass('is-active');
                        }
                    });
                });
            }).on('hidden.bs.modal', function() {
                $(this).remove();
            });
        });
    });

    var childFormat = function(d, id) {
        var header = '<form method="POST" action="' + baseUrl + '/print/invoice">' +
            '<input type="hidden" name="_method" value="PATCH" />' +
            '<input type="hidden" name="_token" value="' + token + '" />' +
            '<input type="hidden" name="id" value="' + id + '" />' +
            '<input type="hidden" name="printed" value="1" />' +
            '<div class="row">' +
            '<div class="col">' +
            '<p>Tgl Dikirim : <strong>' + moment(d.sent).format('LLLL') + '</strong></p>' +
            '</div>';

        var table = '<div class="table-responsive">' +
            '<table class="table table-hover">' +
            '<tr class="text-center">' +
            '<th scope="col">#</th>' +
            '<th scope="col">Barang</th>' +
            '<th scope="col">Jasa</th>' +
            '<th scope="col">Sub Tunai</th>' +
            '<th scope="col">Sub Cicil</th>' +
            '</tr>';

        $.each(d.details, function(k, v) {
            var checkbox = '<div class="custom-control custom-checkbox">' +
                '<input class="custom-control-input" type="checkbox" name="details[]" value="' + v.item.id + ',' + v.service.id + '" id="detail-' + v.item.id + '-' + v.service.id + '">' +
                '<label class="custom-control-label" for="detail-' + v.item.id + '-' + v.service.id + '"></label>' +
                '</div>';

            table += '<tr>' +
                '<td class="text-center">' + checkbox + '</td>' +
                '<td>' + v.item.name + '</td>' +
                '<td>' + v.service.name + '</td>' +
                '<td class="text-right"><strong>' + numberFormat(v.quantity * v.cash) + '</strong><br /><small>' + v.quantity + ' x ' + numberFormat(v.cash) + '</small></td>' +
                '<td class="text-right"><strong>' + numberFormat(v.quantity * v.installment) + '</strong><br /><small>' + v.quantity + ' x ' + numberFormat(v.installment) + '</small></td>' +
                '</tr>';
        });

        table += '<tr class="text-primary">' +
            '<td colspan="2" class="text-center">&nbsp;</td>' +
            '<td class="text-center"><strong>Total</strong></td>' +
            '<td class="text-right"><strong>' + numberFormat(d.total_cash) + '</strong></td>' +
            '<td class="text-right"><strong>' + numberFormat(d.total_installment) + '</strong></td>' +
            '</tr>';

        table += '</table></div>';

        var print = '<button type="submit" class="btn btn-secondary btn-icon-split">' +
            '<span class="icon text-white-50"><i class="fas fa-print"></i></span>' +
            '<span class="text">Cetak Surat Jalan</span>' +
            '</button>&nbsp;';

        var edit = '';
        if (d.printed == false && d.payment_type == null) {
            edit += '<a href="' + baseUrl + '/order/' + d.id + '/edit/" class="btn btn-info btn-icon-split">' +
                '<span class="icon text-white-50"><i class="fas fa-edit"></i></span>' +
                '<span class="text">Ubah</span>' +
                '</a>';
        }

        var footer = print + edit;
        return '<br /><div class="container">' + header + table + footer + '</form></div><br />';

    };

    $('#dataTable tbody').on('click', 'td.details', function() {
        var tr = $(this).closest('tr');
        var row = table.row(tr);

        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass('shown')
        } else {
            var id = row.data().id;
            $.ajax({
                type: 'get',
                url: baseUrl + '/order/' + id,
                beforeSend: function() {
                    $('.loader').addClass('is-active');
                },
                success: function(order) {
                    $('input:checkbox').prop('indeterminate', true);
                    row.child(childFormat(order, id)).show();
                    tr.addClass('shown');
                }
            }).always(function() {
                $('.loader').removeClass('is-active');
            });
        }
    });

    var data, i = $('table#list tbody').find('tr').length;
    var cash = [],
        installment = [],
        obj = {};


    if ($('input[name="customer_id"]')[0]) {
        $.getJSON(baseUrl + '/price/' + $('input[name="customer_id"]').val() + '/check', function(p) {
            $('input[name^="details"]').each(function(k, v) {
                cash.push($('input[name="details[' + (k + 1) + '][quantity]"]').val() * $('input[name="details[' + (k + 1) + '][cash]"]').val());
                installment.push($('input[name="details[' + (k + 1) + '][quantity]"]').val() * $('input[name="details[' + (k + 1) + '][installment]"]').val());
            });
            prices(p);
        });
    } else {
        $('#customer_id').on('change', function() {
            $('.loader').addClass('is-active');
            $.getJSON(baseUrl + '/price/' + $(this).val() + '/check', function(p) {
                prices(p);
            }).done(function() {
                $('.loader').removeClass('is-active');
            });
        });
    }

    $('div#details').on('click', 'button#add', function() {
        $(this).parents('div#details').find(':input').each(function() {
            var name = $(this).attr('name');
            if (typeof name === 'undefined')
                return;

            if ($(this).val().length === 0) {
                $('#' + name + '-help').text('The ' + name + ' field is required');
            } else {
                obj[name] = $(this).val();
                obj['_item_name'] = $('select[name="_items"]').find(':selected').text();
                obj['_service_name'] = $('select[name="_services"]').find(':selected').text();
                if (name == '_services') {
                    obj['_cash'] = $(this).find(':selected').data('cash');
                    obj['_installment'] = $(this).find(':selected').data('installment');
                }
            }
        });

        if ($.isEmptyObject(obj) == false)
            data = $.makeArray(obj);

        if (data.length !== 0)
            $('small.form-text').text(null);


        tBody(data);
        cash.push($('input[name="_quantity"]').val() * $('select[name="_services"]').find(':selected').data('cash'));
        installment.push($('input[name="_quantity"]').val() * $('select[name="_services"]').find(':selected').data('installment'));
        $('span#totalCash').text(numberFormat(total(cash)));
        $('span#totalInstallment').text(numberFormat(total(installment)));
        $('input[name="total_cash"]').val(total(cash));
        $('input[name="total_installment"]').val(total(installment));
        $('select[name="_items"], select[name="_services"]').selectpicker('val', null);
        $('input[name="_quantity"]').val(null).blur();
    });

    $('table#list').on('click', '.del', function() {
        $(this).parents('tr').hide(function() {
            $(this).remove();
        });

        cash.splice($.inArray($(this).data('cash'), cash), 1);
        installment.splice($.inArray($(this).data('installment'), installment), 1);

        $('span#totalCash').text(numberFormat(total(cash)));
        $('span#totalInstallment').text(numberFormat(total(installment)));
        $('input[name="total_cash"]').val(total(cash));
        $('input[name="total_installment"]').val(total(installment));
    });

    var prices = function(p) {
        if (p.length === 0)
            return;

        $.each(p.items, function(k, v) {
            $('select[name="_items"]').append('<option value="' + k + '">' + v + '</option>');
        });

        $('select[name="_items"]').on('change', function() {
            $('select[name="_services"]').empty().selectpicker('refresh');
            var item = $(this).find(':selected').val();
            $.each(p.services[item], function(index, row) {
                $('select[name="_services"]').append('<option data-cash="' + row.cash + '" data-installment="' + row.installment + '" value="' + index + '">' + row.name + '</option>');
                $('select[name="_services"]').selectpicker('refresh');
            });
        });

        $('.selectpicker').selectpicker('refresh');
    };

    var clear = function() {
        $('select[name="_items"], select[name="_services"]').empty().selectpicker('refresh');
        $('input[name="_quantity"]').val(null).blur();
        $('span.#totalCash').text(null);
        $('span#totalInstallment').text(null);
        $('input[name="total_cash"]').val(0);
        $('input[name="total_installment"]').val(0);

        $('table#list tbody > tr').hide(function() {
            $(this).remove();
        });
    };

    var tBody = function(t) {
        var tbody;
        $.each(t, function(k, v) {
            var totalCash = v._quantity * v._cash;
            var totalInstallment = v._quantity * v._installment;

            i += 1;
            tbody += '<tr>';

            tbody += '<td>' + v._item_name;
            tbody += '<input type="hidden" name="details[' + i + '][item_id]" value="' + v._items + '" />';
            tbody += '</td>';

            tbody += '<td>' + v._service_name;
            tbody += '<input type="hidden" name="details[' + i + '][service_id]" value="' + v._services + '" />';
            tbody += '</td>';

            tbody += '<td class="text-center">' + v._quantity;
            tbody += '<input type="hidden" name="details[' + i + '][quantity]" value="' + v._quantity + '" />';
            tbody += '</td>';

            tbody += '<td class="text-right">' + numberFormat(totalCash);
            tbody += '<input type="hidden" name="details[' + i + '][cash]" value="' + v._cash + '" />';
            tbody += '</td>';

            tbody += '<td class="text-right">' + numberFormat(totalInstallment);
            tbody += '<input type="hidden" name="details[' + i + '][installment]" value="' + v._installment + '" />';
            tbody += '</td>';

            tbody += '<button class="btn btn-danger btn-circle">';
            tbody += '<i class="fas fa-edit"></i>';
            tbody += '</a>';

            tbody += '<td>';
            tbody += '<button type="button" class="btn btn-danger del" data-cash="' + totalCash + '" data-installment="' + totalInstallment + '">';
            tbody += '<i class="fas fa-times"></i></button>';
            tbody += '</td>';
            tbody += '</tr>';
            $('table#list tbody').append(tbody);
        });
    };

    var total = function(arr) {
        var t = 0;
        for (var i = 0; i < arr.length; i++) t += arr[i] << 0;
        return parseInt(t, 10);
    };
})(jQuery);
