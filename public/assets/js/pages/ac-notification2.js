'use strict';

$(document).ready(function() {
    if (window.location.pathname === '/produk') {
        function notify(from, align, icon, type, animIn, animOut, message) {
            $.notify({
                icon: icon,
                title: 'Low Stock Warning',
                message: message,
                url: ''
            }, {
                element: 'body',
                type: type,
                allow_dismiss: true,
                placement: {
                    from: from,
                    align: align
                },
                offset: {
                    x: 30,
                    y: 30
                },
                spacing: 10,
                z_index: 999999,
                delay: 2500,
                timer: 1000,
                url_target: '_blank',
                mouse_over: false,
                animate: {
                    enter: animIn || 'animated fadeInRight',
                    exit: animOut || 'animated fadeOutRight'
                },
                icon_type: 'class',
                template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                            '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                            '<span data-notify="icon"></span> ' +
                            '<span data-notify="title">{1}</span> ' +
                            '<span data-notify="message">{2}</span>' +
                            '<div class="progress" data-notify="progressbar">' +
                                '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
                            '</div>' +
                            '<a href="{3}" target="{4}" data-notify="url"></a>' +
                        '</div>'
            });
        };

        // Mengecek stok dengan class produk-row
        $('.produk-row').each(function() {
            var stok = parseInt($(this).attr('data-stok'), 10);
            var namaProduk = $(this).attr('data-nama');

            if (stok < 25) {
                notify(
                    'top',           // from
                    'right',         // align
                    'feather icon-alert-triangle', // icon
                    'danger',        // type
                    'animated fadeInRight',  // animIn
                    'animated fadeOutRight', // animOut
                    `Stok produk "${namaProduk}" rendah! Sisa stok: ${stok}` // message
                );
            }
        });
    }
});
