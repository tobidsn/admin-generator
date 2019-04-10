$(function () {
    // Replace the <textarea class="textarea-ckeditor"> with a CKEditor instance, using custom configuration.
    $(".wysiwyg-simple").each( function() {
        CKEDITOR.replace( $(this).attr('id'), {
            toolbar : 'Basic',
            height  : 200
        });
    });
    $(".wysiwyg-advanced").each( function() {
        CKEDITOR.replace( $(this).attr('id'), {
            toolbar : 'Standard',
            height  : 250,
            // filebrowserBrowseUrl : BASE_URL + 'filemanager/dialog.php?type=2&editor=ckeditor&fldr=',
            // filebrowserUploadUrl : BASE_URL + 'filemanager/dialog.php?type=2&editor=ckeditor&fldr=',
            filebrowserImageBrowseUrl : BASE_URL + '/filemanager/dialog.php?akey=868DA3BFA27E104C57E1CF4533271ED4&type=1&editor=ckeditor&fldr='
        });
    });

    $(".wysiwyg-simple-br").each( function() {
        CKEDITOR.replace( $(this).attr('id'), {
            customConfig : BASE_URL + '/dist/js/ckeditor_config.js',
            toolbar : 'Basic',
            height  : 200
        });
    });
    $(".wysiwyg-advanced-br").each( function() {
        CKEDITOR.replace( $(this).attr('id'), {
            customConfig : BASE_URL + '/dist/js/ckeditor_config.js',
            toolbar : 'Standard',
            height  : 250,
            // filebrowserBrowseUrl : BASE_URL + 'filemanager/dialog.php?type=2&editor=ckeditor&fldr=',
            // filebrowserUploadUrl : BASE_URL + 'filemanager/dialog.php?type=2&editor=ckeditor&fldr=',
            filebrowserImageBrowseUrl : BASE_URL + '/filemanager/dialog.php?akey=868DA3BFA27E104C57E1CF4533271ED4&type=1&editor=ckeditor&fldr='
        });
    });
    //-------------------------


    //Initialize Select2 Elements
    // $(".select2").select2();
    //-------------------------


    // File Manager Button
    $('.file-iframe-btn').fancybox({
        autoSize      : false
    });
    //-------------------------


    // Datepicker search form
    // $('#form-date .input-daterange').datepicker({
    //     format: "dd-mm-yyyy",
    //     autoclose: true,
    //     todayHighlight: true
    // });
    // //-------------------------


    // // Datepicker create form
    // $('.input-datepicker').datepicker({
    //     format: "dd-mm-yyyy",
    //     autoclose: true,
    //     todayHighlight: true
    // });
    //-------------------------


    // // Colorpicker create form
    // $('.input-colorpicker').colorpicker({
    //     customClass: 'colorpicker-2x',
    //     format: "hex",
    //     sliders: {
    //         saturation: {
    //             maxLeft: 400,
    //             maxTop: 400
    //         },
    //         hue: {
    //             maxTop: 400
    //         },
    //         alpha: {
    //             maxTop: 400
    //         }
    //     }
    // });
    //-------------------------
    //
});

var dataList = (function() {
    
    var that = {};

    that.init = function() { 
        var key = $('#key').val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content
            }
        });
        $.ajax({
            method: "GET",
            url: url+'page=1',
            data: {
                key: key,
                only: only,
            },
        }).done(function(data) {
            $('#spin').hide();
            document.getElementById("datalist").innerHTML = data;
        });   

        $(document).on('click', '.pagination a', function (e) {
            var key = $('#key').val();
            $('#spin').show();
            getPosts($(this).attr('href').split('page=')[1], key, only);
            e.preventDefault();
        });

        function getPosts(page, key, only) {
            $.ajax({
                method: "GET",
                url : url + 'page=' + page,
                data: {
                    key: key,
                    only: only,
                },
            }).done(function (data) {
                $('#spin').hide();
                document.getElementById("datalist").innerHTML = data;
                location.hash = page;
            });
        }

        $(window).on('hashchange', function() {
            if (window.location.hash) {
                var page = window.location.hash.replace('#', '');
                var key = $('#key').val();
                if (page == Number.NaN || page <= 0) {
                    return false;
                } else {
                    getPosts(page, key, only);
                }
            }
        });
    }

    return that;
})();

function user_action(id, type) {

    if (type == 'destroy') {
        var result = confirm("Want to delete?");
        if (result) {
            $.ajax({
                url: urlDelete+id,
                type: 'DELETE',
            })
            .done(function(response) {
                $('#row_'+response.id).hide();
                $('#alert-msg').html('<div class="px-3 pt-3">' +
                        '<div class="alert alert-success alert-dismissible">' +
                            '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                              '<h5><i class="icon fa fa-check"></i>' + response.message + '</h5>' +
                        '</div>' +
                    '</div>');
                $("#alert-msg").fadeTo(2000, 500).slideUp(500, function(){
                    $("#alert-msg").slideUp(500);
                });
            });
        }

    } else {
        alert("Opps!")
    }
}