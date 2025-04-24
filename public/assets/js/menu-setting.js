"use strict";
function saveSettings(key, value) {
    try {
        localStorage.setItem('menu_' + key, value); // Simpan ke local storage
        console.log('Saved:', 'menu_' + key, '=', value); // Debugging
    } catch (e) {
        console.error('Error saving to localStorage:', e);
    }
}


function getSettings(key) {
    const value = localStorage.getItem('menu_' + key);
    console.log('Retrieved:', 'menu_' + key, '=', value); // Debugging
    return value;
}

function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}

function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function loadSettings() {
    const layoutType = getSettings('layoutType') || 'menu-dark';
    const backgroundColor = getSettings('backgroundColor') || 'background-default';
    const rtl = getSettings('rtl') === 'true';
    const menuFixed = getSettings('menuFixed') === 'true';
    const headerFixed = getSettings('headerFixed') === 'true';
    const boxLayouts = getSettings('boxLayouts') === 'true';
    const breadcumbLayouts = getSettings('breadcumbLayouts') === 'true';

    // Terapkan pengaturan
    $('.layout-type > a[data-value="' + layoutType + '"]').click();
    $('.background-color.flat > a[data-value="' + backgroundColor + '"]').click();
    $('#theme-rtl').prop('checked', rtl).trigger('change');
    $('#menu-fixed').prop('checked', menuFixed).trigger('change');
    $('#header-fixed').prop('checked', headerFixed).trigger('change');
    $('#box-layouts').prop('checked', boxLayouts).trigger('change');
    $('#breadcumb-layouts').prop('checked', breadcumbLayouts).trigger('change');

    console.log('Settings loaded'); // Debugging
}

function applySettings() {
    // Apply layout type
    const layoutType = getCookie('menu_layoutType') || 'menu-dark';
    $('.layout-type > a[data-value="' + layoutType + '"]').click();

    // Apply background color
    const bgColor = getCookie('menu_backgroundColor') || 'background-default';
    $('.background-color.flat > a[data-value="' + bgColor + '"]').click();

    // Apply RTL setting
    const isRTL = getCookie('menu_rtl') === 'true';
    $('#theme-rtl').prop('checked', isRTL).trigger('change');

    // Apply menu fixed setting
    const isMenuFixed = getCookie('menu_menuFixed') === 'true';
    $('#menu-fixed').prop('checked', isMenuFixed).trigger('change');

    // Apply header fixed setting
    const isHeaderFixed = getCookie('menu_headerFixed') === 'true';
    $('#header-fixed').prop('checked', isHeaderFixed).trigger('change');

    // Apply box layouts setting
    const isBoxLayout = getCookie('menu_boxLayouts') === 'true';
    $('#box-layouts').prop('checked', isBoxLayout).trigger('change');

    // Apply breadcrumb sticky setting
    const isBreadcumbSticky = getCookie('menu_breadcumbLayouts') === 'true';
    $('#breadcumb-layouts').prop('checked', isBreadcumbSticky).trigger('change');
}
$(document).ready(function () {
    loadSettings(); // Load settings saat halaman dimuat

    $('.layout-type > a').click(function () {
        const layoutValue = $(this).data('value');
        saveSettings('layoutType', layoutValue); // Simpan layout
        console.log('Layout saved:', layoutValue); // Debugging
    });

    $('.background-color.flat > a').click(function () {
        const bgColorValue = $(this).data('value');
        saveSettings('backgroundColor', bgColorValue); // Simpan background color
        console.log('Background color saved:', bgColorValue); // Debugging
    });

    $('#theme-rtl, #menu-fixed, #header-fixed, #box-layouts, #breadcumb-layouts').change(function () {
        const settingKey = $(this).attr('id');
        const settingValue = $(this).prop('checked');
        saveSettings(settingKey, settingValue); // Simpan pengaturan lainnya
        console.log('Setting saved:', settingKey, '=', settingValue); // Debugging
    });
    // =========================================================
    // =========    Menu Customizer [ HTML ] code   ============
    // =========================================================
    $('body').append('' +
        '<div id="styleSelector" class="menu-styler">' +
            '<div class="style-toggler">' +
                '<a href="#!"></a>' +
            '</div>' +
            '<div class="style-block">' +
                '<h4 class="mb-2">SIA Cloud <small class="font-weight-normal">Customizer</small></h4>' +
                '<hr class="">' +
                '<div class="m-style-scroller">' +
                    '<h6 class="mt-2">Layouts</h6>' +
                    '<div class="theme-color layout-type">' +
                        '<a href="#!" class="" data-value="menu-dark" title="Default Layout"><span></span><span></span></a>' +
                        '<a href="#!" class="active" data-value="menu-light" title="Light"><span></span><span></span></a>' +
                        '<a href="#!" class="" data-value="dark" title="Dark"><span></span><span></span></a>' +
                        '<a href="#!" class="" data-value="reset" title="Reset">Reset</a>' +
                    '</div>' +
                    '<h6>background color</h6>' +
                    '<div class="theme-color background-color flat">' +
                        '<a href="#!" class="active" data-value="background-blue"><span></span><span></span></a>' +
                        '<a href="#!" class="" data-value="background-red"><span></span><span></span></a>' +
                        '<a href="#!" class="" data-value="background-purple"><span></span><span></span></a>' +
                        '<a href="#!" class="" data-value="background-info"><span></span><span></span></a>' +
                        '<a href="#!" class="" data-value="background-green"><span></span><span></span></a>' +
                        '<a href="#!" class="" data-value="background-dark"><span></span><span></span></a>' +
                    '</div>' +
                    '<h6>background Gradient</h6>' +
                    '<div class="theme-color background-color gradient">' +
                        '<a href="#!" class="" data-value="background-grd-blue"><span></span><span></span></a>' +
                        '<a href="#!" class="" data-value="background-grd-red"><span></span><span></span></a>' +
                        '<a href="#!" class="" data-value="background-grd-purple"><span></span><span></span></a>' +
                        '<a href="#!" class="" data-value="background-grd-info"><span></span><span></span></a>' +
                        '<a href="#!" class="" data-value="background-grd-green"><span></span><span></span></a>' +
                        '<a href="#!" class="" data-value="background-grd-dark"><span></span><span></span></a>' +
                    '</div>' +
                    '<h6>background Image</h6>' +
                    '<div class="theme-color background-color image">' +
                        '<a href="#!" class="" data-value="background-img-1"><span></span><span></span></a>' +
                        '<a href="#!" class="" data-value="background-img-2"><span></span><span></span></a>' +
                        '<a href="#!" class="" data-value="background-img-3"><span></span><span></span></a>' +
                        '<a href="#!" class="" data-value="background-img-4"><span></span><span></span></a>' +
                        '<a href="#!" class="" data-value="background-img-5"><span></span><span></span></a>' +
                        '<a href="#!" class="" data-value="background-img-6"><span></span><span></span></a>' +
                    '</div>' +
                    '<div class="form-group mb-2">' +
                        '<div class="switch switch-primary d-inline m-r-10">' +
                            '<input type="checkbox" id="theme-rtl">' +
                            '<label for="theme-rtl" class="cr"></label>' +
                        '</div>' +
                        '<label>RTL</label>' +
                    '</div>' +
                    '<div class="form-group mb-2">' +
                        '<div class="switch switch-primary d-inline m-r-10">' +
                            '<input type="checkbox" id="menu-fixed" checked>' +
                            '<label for="menu-fixed" class="cr"></label>' +
                        '</div>' +
                        '<label>Sidebar Fixed</label>' +
                    '</div>' +
                    '<div class="form-group mb-2">' +
                        '<div class="switch switch-primary d-inline m-r-10">' +
                            '<input type="checkbox" id="header-fixed" checked>' +
                            '<label for="header-fixed" class="cr"></label>' +
                        '</div>' +
                        '<label>Header Fixed</label>' +
                    '</div>' +
                    '<div class="form-group mb-2">' +
                        '<div class="switch switch-primary d-inline m-r-10">' +
                            '<input type="checkbox" id="box-layouts">' +
                            '<label for="box-layouts" class="cr"></label>' +
                        '</div>' +
                        '<label>Box Layouts</label>' +
                    '</div>' +
                    '<div class="form-group mb-2">' +
                        '<div class="switch switch-primary d-inline m-r-10">' +
                            '<input type="checkbox" id="breadcumb-layouts">' +
                            '<label for="breadcumb-layouts" class="cr"></label>' +
                        '</div>' +
                        '<label>Breadcumb sticky</label>' +
                    '</div>' +
                '</div>' +
                '<a href="https://themeforest.net/user/phoenixcoded" class="btn btn-success btn-block m-r-15 m-t-10 m-b-10">Profile</a>' +
                '<a href="http://ableproadmin.com/doc-7.0/" target="_blank" class="btn btn-primary btn-block m-r-15 m-t-5 m-b-10 ">Online Documentation</a>' +
                '<div class="text-center">' +
                    '<span class="text-center f-18 m-t-15 m-b-15 d-block">Thank you for sharing !</span>' +
                    '<a href="https://www.facebook.com/Phoenixcoded/" target="_blank" class="btn text-white bg-facebook btn-icon m-b-20">' +
                        '<i class="feather icon-facebook"></i>' +
                    '</a>' +
                    '<a href="https://twitter.com/phoenixcoded" target="_blank" class="btn text-white bg-twitter btn-icon m-l-20 m-b-20">' +
                        '<i class="feather icon-twitter"></i>' +
                    '</a>' +
                '</div>' +
            '</div>' +
        '</div>');
    setTimeout(function() {
        $('.m-style-scroller').css({'height':'calc(100vh - 335px)','position':'relative'});
        var px = new PerfectScrollbar('.m-style-scroller', {
            wheelSpeed: .5,
            swipeEasing: 0,
            suppressScrollX: !0,
            wheelPropagation: 1,
            minScrollbarLength: 40,
        });
    }, 400);
    // =========================================================
    // ==================    Menu Customizer Start   ===========
    // =========================================================
    // open Menu Styler
    $('#styleSelector > .style-toggler').on('click', function() {
        $('#styleSelector').toggleClass('open');
        $('#styleSelector').removeClass('prebuild-open');
    });
    // layout types
    $('.layout-type > a').on('click', function() {
        var temp = $(this).attr('data-value');
        $('.layout-type > a').removeClass('active');
        $('.pcoded-navbar').removeClassPrefix('navbar-image-');
        $(this).addClass('active');
        $('head').append('<link rel="stylesheet" class="layout-css" href="">');
        if (temp == "menu-dark") {
            $('.pcoded-navbar').removeClassPrefix('menu-');
            $('.pcoded-navbar').removeClass('navbar-dark');
        }
        if (temp == "menu-light") {
            $('.pcoded-navbar').removeClassPrefix('menu-');
            $('.pcoded-navbar').removeClass('navbar-dark');
            $('.pcoded-navbar').addClass(temp);
        }
        if (temp == "reset") {
            location.reload();
        }
        if (temp == "dark") {
            $('.pcoded-navbar').removeClassPrefix('menu-');
            $('.pcoded-navbar').addClass('navbar-dark');
            $('.layout-css').attr("href", "assets/css/layout-dark.css");
        } else {
            $('.layout-css').attr("href", "");
        }
        saveSettings('layoutType', temp);
    });
    // background Color
    $('.background-color.flat > a').on('click', function() {
        var temp = $(this).attr('data-value');
        $('.background-color > a').removeClass('active');
        $('.pcoded-header').removeClassPrefix('brand-');
        $(this).addClass('active');
        if (temp == "background-default") {
            $('.pcoded-header').removeClassPrefix('header-');
        } else {
            $('.pcoded-header').removeClassPrefix('header-');
            $('.pcoded-header').addClass('header-'+ temp.slice(11, temp.length));
            $('body').removeClassPrefix('background-');
            $('body').addClass('background-'+ temp.slice(11, temp.length));
        }
        saveSettings('backgroundColor', temp);
    });

    
    $('#theme-rtl').on('change', function() {
        saveSettings('rtl', this.checked);
    });
    
    $('#menu-fixed').on('change', function() {
        saveSettings('menuFixed', this.checked);
    });
    
    $('#header-fixed').on('change', function() {
        saveSettings('headerFixed', this.checked);
    });
    
    $('#box-layouts').on('change', function() {
        saveSettings('boxLayouts', this.checked);
    });
    
    $('#breadcumb-layouts').on('change', function() {
        saveSettings('breadcumbLayouts', this.checked);
    });
    // background Color outher
    $('.background-color.gradient > a').on('click', function() {
        var temp = $(this).attr('data-value');
        $('.background-color > a').removeClass('active');
        $('.pcoded-header').removeClassPrefix('brand-');
        $(this).addClass('active');
        if (temp == "background-default") {
        } else {
            $('body').removeClassPrefix('background-');
            $('body').addClass('background-'+ temp.slice(11, temp.length));
        }
    });
    // background Color outher
    $('.background-color.image > a').on('click', function() {
        var temp = $(this).attr('data-value');
        $('.background-color > a').removeClass('active');
        $('.pcoded-header').removeClassPrefix('brand-');
        $(this).addClass('active');
        if (temp == "background-default") {
        } else {
            $('body').removeClassPrefix('background-');
            $('body').addClass('background-'+ temp.slice(11, temp.length));
        }
    });
    // rtl layouts
    $('#theme-rtl').change(function() {
        $('head').append('<link rel="stylesheet" class="rtl-css" href="">');
        if ($(this).is(":checked")) {
            $('.rtl-css').attr("href", "assets/css/layout-rtl.css");
        } else {
            $('.rtl-css').attr("href", "");
        }
        saveSettings('isRTL', $(this).is(":checked"));
    });
    // Menu Fixed
    $('#menu-fixed').change(function() {
        if ($(this).is(":checked")) {
            $('.pcoded-navbar').addClass('menupos-fixed');
            setTimeout(function() {
                // $(".navbar-content").css({'overflow':'visible','height':'calc(100% - 70px)'});
            }, 400);
        } else {
            $('.pcoded-navbar').removeClass('menupos-fixed');
        }
        saveSettings('isMenuFixed', $(this).is(":checked"));
    });
    // Header Fixed
    $('#header-fixed').change(function() {
        if ($(this).is(":checked")) {
            $('.pcoded-header').addClass('headerpos-fixed');
        } else {
            $('.pcoded-header').removeClass('headerpos-fixed');
        }
        saveSettings('isHeaderFixed', $(this).is(":checked"));
    });
    // breadcumb sicky
    $('#breadcumb-layouts').change(function() {
        if ($(this).is(":checked")) {
            $('.page-header').addClass('breadcumb-sticky');
        } else {
            $('.page-header').removeClass('breadcumb-sticky');
        }
        saveSettings('isBreadcumbSticky', $(this).is(":checked"));
    });
    // Box layouts
    $('#box-layouts').change(function() {
        if ($(this).is(":checked")) {
            $('body').addClass('container');
            $('body').addClass('box-layout');
        } else {
            $('body').removeClass('container');
            $('body').removeClass('box-layout');
        }
        saveSettings('isBoxLayout', $(this).is(":checked"));
    });
    $.fn.removeClassPrefix = function(prefix) {
        this.each(function(i, it) {
            var classes = it.className.split(" ").map(function(item) {
                return item.indexOf(prefix) === 0 ? "" : item;
            });
            it.className = classes.join(" ");
        });
        return this;
    };

        // Handle Submenu Toggle
        $('.pcoded-hasmenu > a').on('click', function(e) {
            e.preventDefault(); // Prevent default anchor click
            var $parent = $(this).closest('.pcoded-hasmenu');
            var $submenu = $parent.find('.pcoded-submenu');
    
            if ($submenu.length > 0) {
                $submenu.slideToggle(); // Show or hide submenu
                $parent.toggleClass('submenu-open'); // Add class for styling
            }
        });
    
        // Highlight active menu and expand submenus
        var currentUrl = window.location.href;
        $('ul.nav a').each(function() {
            if (this.href === currentUrl) {
                $(this).addClass('active'); // Add active class to current menu
                $(this).parents('.pcoded-hasmenu').addClass('submenu-open'); // Expand parent menu
                $(this).parents('.pcoded-submenu').slideDown(); // Show parent submenu
            }
        });
    
        // Ensure Tentang Perusahaan does not break submenu behavior
        $('.nav-item a').on('click', function() {
            if (!$(this).closest('.pcoded-hasmenu').length) {
                $('.pcoded-submenu').slideUp(); // Collapse all submenus
                $('.pcoded-hasmenu').removeClass('submenu-open');
            }
        });
    
        // Menu Fixed (Optional)
        $('#menu-fixed').change(function() {
            if ($(this).is(":checked")) {
                $('.pcoded-navbar').addClass('menupos-fixed');
            } else {
                $('.pcoded-navbar').removeClass('menupos-fixed');
            }
        });
        
    // ==================    Menu Customizer End   =============
    // =========================================================
});
