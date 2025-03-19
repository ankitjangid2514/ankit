var base_url = $("#base_url").val();
var admin = $("#admin").val();
var myTable;
var oTable = $('#myTable');
var valid = {


    ajaxError: function(jqXHR, exception) {
        var msg = '';
        if (jqXHR.status === 0) {
            msg = 'Not connect.\n Verify Network.';
        } else if (jqXHR.status == 404) {
            msg = 'Requested page not found. [404]';
        } else if (jqXHR.status == 500) {
            msg = 'Internal Server Error [500].';
        } else if (exception === 'parsererror') {
            msg = 'Requested JSON parse failed.';
        } else if (exception === 'timeout') {
            msg = 'Time out error.';
        } else if (exception === 'abort') {
            msg = 'Ajax request aborted.';
        } else {
            msg = 'Uncaught Error.\n' + jqXHR.responseText;
        }
        return msg;
    },

    phonenumber: function(inputtxt) {
        var phoneno = /^\d{10}$/;
        return phoneno.test(inputtxt);
    },
    validPhone: function(inputtxt) {
        var phoneno = /^[0-9]\d{2,4}-\d{6,8}$/;
        return phoneno.test(inputtxt);
    },
    validURL: function(inputtxt) {
        var re = /(http(s)?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/g;
        return re.test(inputtxt);
    },
    validateEmail: function(email) {
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    },
    validFBurl: function(enteredURL) {
        var FBurl = /^(http|https)\:\/\/www.facebook.com\/.*/i;
        return FBurl.test(enteredURL);
    },
    validTwitterurl: function(enteredURL) {
        var twitterURL = /^(http|https)\:\/\/twitter.com\/.*/i;
        return twitterURL.test(enteredURL);
    },
    validYoutubeURL: function(enteredURL) {
        var youtubeURL = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=|\?v=)([^#\&\?]*).*/;
        return youtubeURL.test(enteredURL);
    },
    validGPlusURL: function(enteredURL) {
        var gPlusURL = /\+[^/]+|\d{21}/;
        return gPlusURL.test(enteredURL);
    },
    validInstagramURL: function(enteredURL) {
        var instagramURL = /(?:(?:http|https):\/\/)?(?:www.)?(?:instagram.com|instagr.am)\/([A-Za-z0-9-_\.]+)/im;
        return instagramURL.test(enteredURL);
    },
    validateExtension: function(val, type) {
        if (type == 1)
            var re = /(\.jpeg|\.jpg|\.png)$/i;
        else if (type == 2)
            var re = /(\.jpeg|\.jpg|\.png\.pdf|\.doc|\.xml|\.docx|\.PDF|\.DOC|\.XML|\.DOCX|\.xls|\.xlsx)$/i;
        else if (type == 3)
            var re = /(\.pdf|\.docx|\.PDF|\.DOC|\.DOCX)$/i;
        return re.test(val)
    },
    snackbar: function(msg) {
        $("#snackbar").html(msg).fadeIn('slow').delay(3000).fadeOut('slow');
    },
    snackbar2: function(msg) {
        $("#snackbar").html(msg).fadeIn('slow');
    },
    snackbar_info: function(msg) {
        $("#snackbar-info").html(msg).fadeIn('slow').delay(3000).fadeOut('slow');
    },
    snackbar_error: function(msg) {
        $("#snackbar-error").html(msg).fadeIn('slow').delay(3000).fadeOut('slow');
    },
    snackbar_success: function(msg) {
        $("#snackbar-success").html(msg).fadeIn('slow').delay(3000).fadeOut('slow');
    },
    error: function(msg) {
        return "<p class='alert alert-danger'><strong>Error : </strong> " + msg + "</p>";
    },
    success: function(msg) {
        return "<p class='alert alert-success'>" + msg + "</p>";
    },
    info: function(msg) {
        return "<p class='alert alert-info'>" + msg + "</p>";
    }
};

var inputQuantity = [];

jQuery(function($) {
    "use strict";


    $(".digit_number").on("input", function(e) {

        var $field = $(this),
            val = this.value,
            $thisIndex = parseInt($field.data("idx"), 10);
        if (this.validity && this.validity.badInput || isNaN(val) || $field.is(":invalid")) {
            this.value = inputQuantity[$thisIndex];
            return;
        }
        if (val.length > Number($field.attr("maxlength"))) {
            val = val.slice(0, 5);
            $field.val(val);
        }
        inputQuantity[$thisIndex] = val;
    });

    $('#total_data').hide();
    $('#withdraw_data_details').hide();

    $('#myTable').DataTable();
    $('#withdrawTable').DataTable();
    $('.clear_btn').hide();
    $(document).on('click', '.openViewWithdrawRequest', function(e) {
        var url = $(this).data('href');
        $('.viewWithdrawRequestBody').load(url, function() {
            $('#viewWithdrawRequest').modal('show');
        });
    });

    $(document).on('click', '.dataCopy', function(e) {

        var copyText = $(this).attr('data-text');
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val(copyText).select();
        $temp.focus();
        document.execCommand("copy");
        $temp.remove();
        valid.snackbar('Copied');
    });


    $(document).on('click', '.blockUnblock', function(e) {
        var id = $(this).attr('id');
        var myArray = id.split('-');
        var table_id = myArray[3];

        $.ajax({
            type: "POST",
            url: base_url + 'block-data-function',
            data: 'id=' + myArray[1] + "&table=" + myArray[2] + "&table_id=" + table_id + "&status_name=" + myArray[4],
            success: function(data) {


                if (myArray[0] == 'danger') {
                    $("#" + id).html('<button class="btn btn-outline-success btn-xs m-l-5" type="button" title="Inactivate">Inactivate</button>');
                    $("#status_show" + myArray[1]).html('<badge class="badge badge-success">Active</badge>');
                    $("#" + id).attr('id', 'success-' + myArray[1] + '-' + myArray[2] + '-' + table_id + '-' + myArray[4]);
                } else {
                    $("#" + id).html('<button class="btn btn-outline-danger btn-xs m-l-5" type="button" title="Activate">Activate</button>');
                    $("#status_show" + myArray[1]).html('<badge class="badge badge-danger">Inactive</badge>');
                    $("#" + id).attr('id', 'danger-' + myArray[1] + '-' + myArray[2] + '-' + table_id + '-' + myArray[4]);
                }
                $("#msg").html(data);
                $("#msg").fadeIn('slow').delay(5000).fadeOut('slow');
            },
            error: function(jqXHR, exception) {
                var msg = valid.ajaxError(jqXHR, exception);
                $("#msg").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');

            }
        });

        e.preventDefault();
    });

    $(document).on('click', '.activeDeactive', function(e) {
        var id = $(this).attr('id');
        var myArray = id.split('-');
        var table_id = myArray[3];

        $.ajax({
            type: "POST",
            url: base_url + 'block-data-function',
            data: 'id=' + myArray[1] + "&table=" + myArray[2] + "&table_id=" + table_id + "&status_name=" + myArray[4],
            success: function(data) {


                if (myArray[0] == 'danger') {
                    $("#" + id).html('<span class="badge badge-pill badge-soft-success font-size-12">Yes</span>');

                    $("#" + id).attr('id', 'success-' + myArray[1] + '-' + myArray[2] + '-' + table_id + '-' + myArray[4]);
                } else {
                    $("#" + id).html('<span class="badge badge-pill badge-soft-danger font-size-12">No</span>');
                    $("#" + id).attr('id', 'danger-' + myArray[1] + '-' + myArray[2] + '-' + table_id + '-' + myArray[4]);
                }
                $("#msg").html(data);
                $("#msg").fadeIn('slow').delay(5000).fadeOut('slow');
            },
            error: function(jqXHR, exception) {
                var msg = valid.ajaxError(jqXHR, exception);
                $("#msg").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');

            }
        });

        e.preventDefault();
    });

    $(document).on('click', '.activeDeactiveStatus', function(e) {
        var id = $(this).attr('id');
        var myArray = id.split('-');
        var table_id = myArray[3];

        $.ajax({
            type: "POST",
            url: base_url + 'block-data-function',
            data: 'id=' + myArray[1] + "&table=" + myArray[2] + "&table_id=" + table_id + "&status_name=" + myArray[4],
            success: function(data) {


                if (myArray[0] == 'danger') {
                    $("#" + id).html('<span class="badge badge-pill badge-success font-size-12">Yes</span>');

                    $("#" + id).attr('id', 'success-' + myArray[1] + '-' + myArray[2] + '-' + table_id + '-' + myArray[4]);
                } else {
                    $("#" + id).html('<span class="badge badge-pill badge-danger font-size-12">No</span>');
                    $("#" + id).attr('id', 'danger-' + myArray[1] + '-' + myArray[2] + '-' + table_id + '-' + myArray[4]);
                }
                $("#msg").html(data);
                $("#msg").fadeIn('slow').delay(5000).fadeOut('slow');
            },
            error: function(jqXHR, exception) {
                var msg = valid.ajaxError(jqXHR, exception);
                $("#msg").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');

            }
        });

        e.preventDefault();
    });



    $(document).on('change', '#game_type', function(e) {
        var game_type = $("#game_type").val();

        $("#digit").prop("disabled", true);
        if (game_type != "") {
            $.ajax({
                url: base_url + 'show-digit',
                type: 'POST',
                data: {
                    'game_type': game_type
                },
                dataType: "json",
                success: function(data) {
                    $("#digit").prop("disabled", false);
                    $("#digit").html(data.result);


                }
            });
        } else {
            $("#digit").html('<option value="">Select Digit</option>');

        }
        e.preventDefault();

    });

    $(document).on('submit', '#searchBidFrm', function(e) {
        var game_name = $("#bid_game_name").val();
        var market_status = $("#market_status").val();
        if (game_name == '') {
            valid.snackbar_error('Please select game name');
        } else if (market_status == '') {
            valid.snackbar_error('Please select market time');
        } else {
            $("#searchBtn").attr("disabled", true);
            var changeBtn = $("#searchBtn").html();
            $("#searchBtn").html("...");
            $.ajax({
                type: "POST",
                url: base_url + "get-search-market-bid-details",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.status == 'success') {
                        $("#search").show();
                        $("#total0").html(data.total_zero);
                        $("#total1").html(data.total_one);
                        $("#total2").html(data.total_two);
                        $("#total3").html(data.total_three);
                        $("#total4").html(data.total_four);
                        $("#total5").html(data.total_five);
                        $("#total6").html(data.total_six);
                        $("#total7").html(data.total_seven);
                        $("#total8").html(data.total_eight);
                        $("#total9").html(data.total_nine);
                        $("#bid0").html(data.bid_zero);
                        $("#bid1").html(data.bid_one);
                        $("#bid2").html(data.bid_two);
                        $("#bid3").html(data.bid_three);
                        $("#bid4").html(data.bid_four);
                        $("#bid5").html(data.bid_five);
                        $("#bid6").html(data.bid_six);
                        $("#bid7").html(data.bid_seven);
                        $("#bid8").html(data.bid_eight);
                        $("#bid9").html(data.bid_nine);
                    }
                    $("#searchBtn").attr("disabled", false);
                    $("#searchBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    valid.snackbar_error(msg);
                    $("#searchBtn").attr("disabled", false);
                    $("#searchBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });

    $(document).on('submit', '#editbidFrm', function(e) {


        var digit = $("#digit").val();

        if (digit == '') {

            $("#error").html(valid.error('Please select digit')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#updategameBtn").attr("disabled", true);
            var changeBtn = $("#updategameBtn").html();
            $("#updategameBtn").html("Upadatting..");
            $.ajax({
                type: "POST",
                url: base_url + "update-bid",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.status == 'update') {
                        $("#error").html(data.msg);
                        $("#error").fadeIn('slow').delay(5000).fadeOut('slow');

                        $("#digit").val(data.digit);
                        $("#updatebidModal").modal("hide");

                        $("#mainMarketFrm").submit();
                        //dataTable.ajax.reload();
                        //location.reload(true); 


                    } else {
                        $("#error").html(data.msg);
                        $("#error").fadeIn('slow').delay(5000).fadeOut('slow');
                    }
                    $("#updategameBtn").attr("disabled", false);
                    $("#updategameBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#error").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#updategameBtn").attr("disabled", false);
                    $("#updategameBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });
    $(document).on('submit', '#editbidhistoryFrm', function(e) {


        var digit = $("#digit").val();

        if (digit == '') {

            $("#error").html(valid.error('Please select digit')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#updategameBtn").attr("disabled", true);
            var changeBtn = $("#updategameBtn").html();
            $("#updategameBtn").html("Upadatting..");
            $.ajax({
                type: "POST",
                url: base_url + "update-bid",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.status == 'update') {
                        $("#error").html(data.msg);
                        $("#error").fadeIn('slow').delay(5000).fadeOut('slow');
                        /* $("#digit").val(data.digit);*/
                        $("#updatebidhistoryModal").modal("hide");
                        $("#getBidHistoryFrm").submit();
                        $("#geWinningpredictFrm").submit();
                        //dataTable.ajax.reload();
                        //location.reload(true); 


                    } else {
                        $("#error").html(data.msg);
                        $("#error").fadeIn('slow').delay(5000).fadeOut('slow');
                    }
                    $("#updategameBtn").attr("disabled", false);
                    $("#updategameBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#error").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#updategameBtn").attr("disabled", false);
                    $("#updategameBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });


    $(document).on('submit', '#editbidgalidissawarhistoryFrm', function(e) {


        var digit = $("#digits").val();

        if (digit == '') {

            $("#error").html(valid.error('Please select digit')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#updategameBtn").attr("disabled", true);
            var changeBtn = $("#updategameBtn").html();
            $("#updategameBtn").html("Upadatting..");
            $.ajax({
                type: "POST",
                url: base_url + "update-galidissawar-bid",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.status == 'update') {
                        $("#error").html(data.msg);
                        $("#error").fadeIn('slow').delay(5000).fadeOut('slow');
                        /* $("#digit").val(data.digit);*/
                        $("#updatebidhistoryModal").modal("hide");
                        $("#getgalidisswarBidHistoryFrm").submit();
                        //dataTable.ajax.reload();
                        //location.reload(true); 


                    } else {
                        $("#error").html(data.msg);
                        $("#error").fadeIn('slow').delay(5000).fadeOut('slow');
                    }
                    $("#updategameBtn").attr("disabled", false);
                    $("#updategameBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#error").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#updategameBtn").attr("disabled", false);
                    $("#updategameBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });

    $(document).on('submit', '#mainMarketFrm', function(e) {


        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();
        var game_name = $("#game_name").val();
        var market_status = $("#market_status").val();
        var game_type = $("#game_type").val();
        var digit = $("#digit").val();
        if (start_date == '') {

            $("#msg").html(valid.error('Please select start date')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (end_date == '') {
            $("#msg").html(valid.error('Please select end date')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (game_name == '') {
            $("#msg").html(valid.error('Please select game name ')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (market_status == '' && game_type != "Jodi Digit") {
            $("#msg").html(valid.error('Please select market time')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (game_type == '') {
            $("#msg").html(valid.error('Please select game type')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (digit == '') {
            $("#msg").html(valid.error('Please select digit')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#submitBtn").attr("disabled", true);
            var changeBtn = $("#submitBtn").html();
            $("#submitBtn").html("Submiting..");
            $.ajax({
                type: "POST",
                url: base_url + "main-market-report",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    $("#myTable").dataTable().fnClearTable();
                    $('#myTable').dataTable().fnDestroy();
                    $("#tabledata").html(data.listdata);
                    var table = $('#myTable').DataTable({
                        rowReorder: {
                            selector: 'td:nth-child(2)'
                        },
                        responsive: true,
                        dom: 'Bfrtip',
                        buttons: [{
                                extend: 'excelHtml5',
                                title: 'Main Market Report (' + exceldate + ')',
                                footer: true,
                                exportOptions: {
                                    columns: ':not(:last-child)',
                                    stripNewlines: false
                                },
                                orientation: 'landscape',
                                pageSize: 'LEGAL',
                            },
                            {
                                extend: 'pageLength',
                                footer: false,
                                exportOptions: {
                                    stripNewlines: false
                                }
                            },

                        ],
                        lengthMenu: [
                            [10, 25, 50, -1],
                            [10, 25, 50, 'All']
                        ],
                        iDisplayLength: 10,
                    });

                    $('#myTable').DataTable();
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#msg").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });


    $("#addgameFrm").submit(function(e) {
        var game_name = $("#game_name").val();
        var game_name_hindi = $("#game_name_hindi").val();
        var open_time = $("#open_time").val();
        var close_time = $("#close_time").val();

        if (game_name == '') {
            $("#msg").html(valid.error('Please enter game name')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (game_name_hindi == '') {
            $("#msg").html(valid.error('Please enter game name in hindi')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (open_time == '') {
            $("#msg").html(valid.error('Please enter open time ')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (close_time == '') {
            $("#msg").html(valid.error('Please enter close time')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#submitBtn").attr("disabled", true);
            var changeBtn = $("#submitBtn").html();
            $("#submitBtn").html("Submitting..");
            $.ajax({
                type: "POST",
                url: base_url + "add-game",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.status == 'success') {
                        $("#msg").html(data.msg);
                        $("#msg").fadeIn('slow').delay(5000).fadeOut('slow');
                        $('#addgameFrm')[0].reset();
                        dataTable.ajax.reload();
                    } else {
                        $("#msg").html(data.msg);
                        $("#msg").fadeIn('slow').delay(5000).fadeOut('slow');
                    }
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#msg").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });

    $("#referralBonusSettingFrm").submit(function(e) {
        var referral_first_bonus = $("#referral_first_bonus").val();
        var referral_first_bonus_max = $("#referral_first_bonus_max").val();
        var referral_second_bonus = $("#referral_second_bonus").val();
        var referral_second_bonus_max = $("#referral_second_bonus_max").val();

        if (referral_first_bonus == '') {
            $("#error_ref").html(valid.error('Please enter referral first bonus')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (referral_first_bonus_max == '') {
            $("#error_ref").html(valid.error('Please enter referral first bonus max')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (referral_second_bonus == '') {
            $("#error_ref").html(valid.error('Please enter referral second bonus')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (referral_second_bonus_max == '') {
            $("#error_ref").html(valid.error('Please enter referral second bonus max')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#submitBtn").attr("disabled", true);
            var changeBtn = $("#submitBtn").html();
            $("#submitBtn").html("Submitting..");
            $.ajax({
                type: "POST",
                url: base_url + "add-referral-bonus-settings-update",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.status == 'success') {
                        $("#error_ref").html(data.msg);
                        $("#error_ref").fadeIn('slow').delay(5000).fadeOut('slow');
                    } else {
                        $("#error_ref").html(data.msg);
                        $("#error_ref").fadeIn('slow').delay(5000).fadeOut('slow');
                    }
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#error_ref").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });



    $(document).on('submit', '#updateeditgameFrm', function(e) {


        var game_name = $("#up_game_name").val();
        var game_name_hindi = $("#up_game_name_hindi").val();
        var open_time = $("#up_open_time").val();
        var close_time = $("#up_close_time").val();
        if (game_name == '') {

            $("#u_msg").html(valid.error('Please enter game name')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (game_name_hindi == '') {
            $("#u_msg").html(valid.error('Please enter game name in hindi')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (open_time == '') {
            $("#u_msg").html(valid.error('Please enter open time ')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (close_time == '') {
            $("#u_msg").html(valid.error('Please enter close time')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#updategameBtn").attr("disabled", true);
            var changeBtn = $("#updategameBtn").html();
            $("#updategameBtn").html("Upadatting..");
            $.ajax({
                type: "POST",
                url: base_url + "add-game",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.status == 'update') {
                        $("#u_msg").html(data.msg);
                        $("#u_msg").fadeIn('slow').delay(5000).fadeOut('slow');
                        dataTable.ajax.reload();

                    } else {
                        $("#u_msg").html(data.msg);
                        $("#u_msg").fadeIn('slow').delay(5000).fadeOut('slow');
                    }
                    $("#updategameBtn").attr("disabled", false);
                    $("#updategameBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#u_msg").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#updategameBtn").attr("disabled", false);
                    $("#updategameBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });

    $("#geWinningpredictFrm").submit(function(e) {
        var game_id = $("#win_game_name").val();
        var result_date = $("#result_date").val();
        var win_market_status = $("#win_market_status").val();
        var winning_ank = $("#winning_ank").val();
        var close_number = $("#close_number").val();

        if (game_id == '') {
            $("#error").html(valid.error('Please select game name')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (result_date == '') {
            $("#error").html(valid.error('Please select  date')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (win_market_status == '') {
            $("#error").html(valid.error('Please select market session ')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (winning_ank == '') {
            $("#error").html(valid.error('Please Enter Open Number')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (win_market_status == 2 && close_number == "") {
            $("#error").html(valid.error('Please Enter Close Number')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#submitBtn").attr("disabled", true);
            var changeBtn = $("#submitBtn").html();
            $("#submitBtn").html("Submiting..");
            $("#winner_result_data").html('');
            $("#t_bid").text('');
            $("#t_winneing_amt").text('');

            $.ajax({
                type: "POST",
                url: base_url + "get-predict-winner-list",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.winner_list != '') {

                        $("#t_bid").text(data.points_amt_sum);
                        $("#t_winneing_amt").text(data.win_amt_sum);
                        var i = 1;

                        $.each(data.winner_list, function(key, val) {

                            var action = '<a title="Edit" href="javascript:void(0);" data-href="' + base_url + admin + '/edit-bid-history/' + val.bid_id + '" class="openpopupeditbidhistory"><button  class="btn btn-outline-primary btn-xs m-l-5" type="button"  title="edit">Edit</button></a>';

                            $("#winner_result_data").append('<tr><td>' + i + '</td><td><a href="' + base_url + admin + '/view-user/' + val.user_id + '" target="blank">' + val.user_name + '</a></td><td>' + val.points + '</td><td>' + val.win_amt + '</td><td>' + val.pana + '</td><td>' + val.bid_tx_id + '</td><td>' + action + '</td></tr>');
                            i++;
                        });

                    } else {
                        $("#winner_result_data").html('<tr><td colspan="7" class="table_text_align">No Data Available</td></tr>');
                    }

                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#error").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });



    $(document).on('submit', '#offdayFrm', function(e) {

        var day = $("input[name='day[]']:checked").length;

        if (day == 0) {

            $("#u_msg").html(valid.error('Please select market off day')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#submitBtn").attr("disabled", true);
            var changeBtn = $("#submitBtn").html();
            $("#submitBtn").html("submitting..");
            $.ajax({
                type: "POST",
                url: base_url + "add-off-day",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.status == 'success') {
                        $("#u_msg").html(data.msg);
                        $("#u_msg").fadeIn('slow').delay(5000).fadeOut('slow');
                        window.setTimeout(function() {
                            $('#offdayModal').modal('hide')
                        }, 1000);
                        dataTable.ajax.reload();

                    } else {
                        $("#u_msg").html(data.msg);
                        $("#u_msg").fadeIn('slow').delay(5000).fadeOut('slow');
                    }
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#u_msg").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });



    $("#addstarlinegameFrm").submit(function(e) {
        var game_name = $("#game_name").val();
        var game_name_hindi = $("#game_name_hindi").val();
        var open_time = $("#open_time").val();
        var close_time = $("#close_time").val();

        if (game_name == '') {
            $("#msg").html(valid.error('Please enter game name')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (game_name_hindi == '') {
            $("#msg").html(valid.error('Please enter game name in hindi')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (open_time == '') {
            $("#msg").html(valid.error('Please enter open time ')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (close_time == '') {
            $("#msg").html(valid.error('Please enter close time')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#submitBtn").attr("disabled", true);
            var changeBtn = $("#submitBtn").html();
            $("#submitBtn").html("Submitting..");
            $.ajax({
                type: "POST",
                url: base_url + "add-starline-game",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.status == 'success') {
                        $("#msg").html(data.msg);
                        $("#msg").fadeIn('slow').delay(5000).fadeOut('slow');
                        $('#addstarlinegameFrm')[0].reset();
                        dataTable.ajax.reload();
                    } else {
                        $("#msg").html(data.msg);
                        $("#msg").fadeIn('slow').delay(5000).fadeOut('slow');
                    }
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#msg").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });



    $(document).on('submit', '#updateeditstarlinegameFrm', function(e) {


        var game_name = $("#up_game_name").val();
        var game_name_hindi = $("#up_game_name_hindi").val();
        var open_time = $("#up_open_time").val();
        var close_time = $("#up_close_time").val();
        if (game_name == '') {

            $("#u_msg").html(valid.error('Please enter game name')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (game_name_hindi == '') {
            $("#u_msg").html(valid.error('Please enter game name in hindi')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (open_time == '') {
            $("#u_msg").html(valid.error('Please enter open time ')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (close_time == '') {
            $("#u_msg").html(valid.error('Please enter close time')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#updategameBtn").attr("disabled", true);
            var changeBtn = $("#updategameBtn").html();
            $("#updategameBtn").html("Upadatting..");
            $.ajax({
                type: "POST",
                url: base_url + "add-starline-game",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.status == 'update') {
                        $("#u_msg").html(data.msg);
                        $("#u_msg").fadeIn('slow').delay(5000).fadeOut('slow');
                        dataTable.ajax.reload();

                    } else {
                        $("#u_msg").html(data.msg);
                        $("#u_msg").fadeIn('slow').delay(5000).fadeOut('slow');
                    }
                    $("#updategameBtn").attr("disabled", false);
                    $("#updategameBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#u_msg").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#updategameBtn").attr("disabled", false);
                    $("#updategameBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });


    $("#addgalidisswargamegameFrm").submit(function(e) {
        var game_name = $("#game_name").val();
        var game_name_hindi = $("#game_name_hindi").val();
        var open_time = $("#open_time").val();
        var close_time = $("#close_time").val();

        if (game_name == '') {
            $("#msg").html(valid.error('Please enter game name')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (game_name_hindi == '') {
            $("#msg").html(valid.error('Please enter game name in hindi')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (open_time == '') {
            $("#msg").html(valid.error('Please enter open time ')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (close_time == '') {
            $("#msg").html(valid.error('Please enter close time')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#submitBtn").attr("disabled", true);
            var changeBtn = $("#submitBtn").html();
            $("#submitBtn").html("Submitting..");
            $.ajax({
                type: "POST",
                url: base_url + "add-galidisswar-game",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.status == 'success') {
                        $("#msg").html(data.msg);
                        $("#msg").fadeIn('slow').delay(5000).fadeOut('slow');
                        $('#addgalidisswargamegameFrm')[0].reset();
                        dataTable.ajax.reload();
                    } else {
                        $("#msg").html(data.msg);
                        $("#msg").fadeIn('slow').delay(5000).fadeOut('slow');
                    }
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#msg").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });


    $(document).on('submit', '#updateeditgalidisswargameFrm', function(e) {


        var game_name = $("#up_game_name").val();
        var game_name_hindi = $("#up_game_name_hindi").val();
        var open_time = $("#up_open_time").val();
        var close_time = $("#up_close_time").val();
        if (game_name == '') {

            $("#u_msg").html(valid.error('Please enter game name')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (game_name_hindi == '') {
            $("#u_msg").html(valid.error('Please enter game name in hindi')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (open_time == '') {
            $("#u_msg").html(valid.error('Please enter open time ')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (close_time == '') {
            $("#u_msg").html(valid.error('Please enter close time')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#updategameBtn").attr("disabled", true);
            var changeBtn = $("#updategameBtn").html();
            $("#updategameBtn").html("Upadatting..");
            $.ajax({
                type: "POST",
                url: base_url + "add-galidisswar-game",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.status == 'update') {
                        $("#u_msg").html(data.msg);
                        $("#u_msg").fadeIn('slow').delay(5000).fadeOut('slow');
                        dataTable.ajax.reload();

                    } else {
                        $("#u_msg").html(data.msg);
                        $("#u_msg").fadeIn('slow').delay(5000).fadeOut('slow');
                    }
                    $("#updategameBtn").attr("disabled", false);
                    $("#updategameBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#u_msg").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#updategameBtn").attr("disabled", false);
                    $("#updategameBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });


    $(document).on('click', '.openClose', function(e) {
        var id = $(this).attr('id');
        var myArray = id.split('-');
        var table_id = myArray[3];

        $.ajax({
            type: "POST",
            url: base_url + 'market-open-close-function',
            data: 'id=' + myArray[1] + "&table=" + myArray[2] + "&table_id=" + table_id + "&status_name=" + myArray[4],
            success: function(data) {
                if (myArray[0] == 'danger') {
                    $("#" + id).html('<button class="btn btn-outline-success btn-sm m-l-5" type="button" title="close">Market Close</button>');
                    $("#" + id).attr('id', 'success-' + myArray[1] + '-' + myArray[2] + '-' + table_id + '-' + myArray[4]);
                } else {
                    $("#" + id).html('<button class="btn btn-outline-danger btn-sm m-l-5" type="button" title="Open">Market Open</button>');
                    $("#" + id).attr('id', 'danger-' + myArray[1] + '-' + myArray[2] + '-' + table_id + '-' + myArray[4]);
                }
                $("#msg").html(data);
                $("#msg").fadeIn('slow').delay(5000).fadeOut('slow');
            },
            error: function(jqXHR, exception) {
                var msg = valid.ajaxError(jqXHR, exception);
                $("#msg").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
            }
        });
        e.preventDefault();
    });


    $("#loginform").submit(function(e) {

        var username = $("#name").val();
        var password = $("#password").val();
        if (username == '') {
            valid.snackbar_error('Please enter username');
        } else if (password == '') {
            valid.snackbar_error('Please enter password');
        } else {
            valid.snackbar_info("Authenticating..");
            $("#submitBtn").attr("disabled", true);
            var changeBtn = $("#submitBtn").html();
            $("#submitBtn").html("Authenticating..");
            $.ajax({
                url: base_url + 'loginCheck',
                type: 'POST',
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.status == "success") {
                        valid.snackbar_success(data.msg);
                        var url = base_url + admin + "/dashboard";
                        $(location).attr('href', url);
                    } else {
                        valid.snackbar_error(data.msg);
                    }
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    valid.snackbar_error(msg);
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });

    $("#recoverform").submit(function(e) {

        var email = $("#email").val();
        if (email == '') {
            $("#recovermsg").html(valid.error('Please enter email')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#recoverSubmitBtn").attr("disabled", true);
            var changeBtn = $("#recoverSubmitBtn").html();
            $("#recoverSubmitBtn").html("Sending..");
            $.ajax({
                url: base_url + 'forgot-password',
                type: 'POST',
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {

                    if (data.status == "success") {
                        $("#recovermsg").html(data.msg).fadeIn('slow').delay(2500).fadeOut('slow');
                        $("#recoverform")[0].reset();
                    } else {
                        $("#recovermsg").html(data.msg);
                        $("#recovermsg").fadeIn('slow').delay(2500).fadeOut('slow');

                    }

                    $("#recoverSubmitBtn").attr("disabled", false);
                    $("#recoverSubmitBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#recovermsg").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#recoverSubmitBtn").attr("disabled", false);
                    $("#recoverSubmitBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });



    $("#updatepass").submit(function(e) {
        var oldpass = $("#oldpass").val();
        var newpass = $("#newpass").val();
        var retypepass = $("#retypepass").val();

        if (oldpass == '') {
            $("#updatepassmsg").html(valid.error('Please enter old password')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (newpass == '') {
            $("#updatepassmsg").html(valid.error('Please enter new password')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (retypepass == '') {
            $("#updatepassmsg").html(valid.error('Please enter confirm password')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#submitBtn").attr("disabled", true);
            var changeBtn = $("#submitBtn").html();
            $("#submitBtn").html("Submitting..");
            $.ajax({
                type: "POST",
                url: base_url + "update-password",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.status == 'success') {
                        $("#updatepassmsg").html(valid.success("Password updated successfully!"));
                        $("#updatepassmsg").fadeIn('slow').delay(5000).fadeOut('slow');
                        $('#updatepass')[0].reset();
                    } else {
                        $("#updatepassmsg").html(data.msg);
                        $("#updatepassmsg").fadeIn('slow').delay(5000).fadeOut('slow');
                    }
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#updatepassmsg").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });

    $("#subAdminFrm").submit(function(e) {
        var sub_admin_name = $("#sub_admin_name").val();
        var email = $("#email").val();
        var user_name = $("#user_name").val();
        var password = $("#password").val();

        if (sub_admin_name == '') {
            $("#msg").html(valid.error('Please enter sub admin name')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (email == '') {
            $("#msg").html(valid.error('Please enter email')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (user_name == '') {
            $("#msg").html(valid.error('Please enter user name')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (password == '') {
            $("#msg").html(valid.error('Please enter password')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#submitBtn").attr("disabled", true);
            var changeBtn = $("#submitBtn").html();
            $("#submitBtn").html("Submitting..");
            $.ajax({
                type: "POST",
                url: base_url + "add-sub-admin",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.status == 'success') {
                        $("#msg").html(data.msg);
                        $("#msg").fadeIn('slow').delay(5000).fadeOut('slow');
                        $('#subAdminFrm')[0].reset();
                        dataTable.ajax.reload();
                    } else {
                        $("#msg").html(data.msg);
                        $("#msg").fadeIn('slow').delay(5000).fadeOut('slow');
                    }
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#msg").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });
    $(document).on('submit', '#customerSellFrm', function(e) {


        var date = $("#start_date").val();
        var game_name = $("#game_name").val();
        var market_status = $("#market_status").val();
        var game_type = $("#game_type").val();
        if (date == '') {

            $("#msg").html(valid.error('Please select date')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (game_name == '') {
            $("#msg").html(valid.error('Please select game name ')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (market_status == '' && game_type != "Jodi Digit" && game_type != "Full Sangam") {
            $("#msg").html(valid.error('Please select market time')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (game_type == '') {
            $("#msg").html(valid.error('Please select game type')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#submitBtn").attr("disabled", true);
            var changeBtn = $("#submitBtn").html();
            $("#submitBtn").html("Submiting..");
            $.ajax({
                type: "POST",
                url: base_url + "get-customer-sell-report",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {

                    $(".mytable").html(data.listData);


                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#msg").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });



    $(document).on('submit', '#starlineSellFrm', function(e) {


        var date = $("#start_date").val();
        var game_name = $("#game_name").val();
        var market_status = $("#market_status").val();
        var game_type = $("#game_type").val();
        if (date == '') {

            $("#msg").html(valid.error('Please select date')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (game_name == '') {
            $("#msg").html(valid.error('Please select game name ')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (market_status == '' && game_type != "Jodi Digit" && game_type != "Full Sangam") {
            $("#msg").html(valid.error('Please select market time')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (game_type == '') {
            $("#msg").html(valid.error('Please select game type')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#submitBtn").attr("disabled", true);
            var changeBtn = $("#submitBtn").html();
            $("#submitBtn").html("Submiting..");
            $.ajax({
                type: "POST",
                url: base_url + "get-starline-sell-report",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {

                    $(".mytable").html(data.listData);


                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#msg").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });


    $(document).on('submit', '#galidisswarSellFrm', function(e) {


        var date = $("#start_date").val();
        var game_name = $("#game_name").val();
        var market_status = $("#market_status").val();
        var game_type = $("#game_type").val();
        if (date == '') {

            $("#msg").html(valid.error('Please select date')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (game_name == '') {
            $("#msg").html(valid.error('Please select game name ')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (game_type == '') {
            $("#msg").html(valid.error('Please select game type')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#submitBtn").attr("disabled", true);
            var changeBtn = $("#submitBtn").html();
            $("#submitBtn").html("Submiting..");
            $.ajax({
                type: "POST",
                url: base_url + "get-galidisswar-sell-report",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {

                    $(".mytable").html(data.listData);


                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#msg").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });

    $(document).on('keypress', '.mobile-valid', function(e) {
        var $this = $(this);
        var regex = new RegExp("^[0-9\b]+$");
        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
        // for 10 digit number only
        if ($this.val().length > 9) {
            e.preventDefault();
            return false;
        }
        if (e.charCode < 54 && e.charCode > 47) {
            if ($this.val().length == 0) {
                e.preventDefault();
                return false;
            } else {
                return true;
            }

        }
        if (regex.test(str)) {
            return true;
        }
        e.preventDefault();
        return false;
    });


    $(document).on('keypress', '.name-valid', function(e) {
        if (event.charCode != 0) {
            var regex = new RegExp("^[a-zA-Z ]*$");
            var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
            if (!regex.test(key)) {
                event.preventDefault();
                return false;
            }
        }
    });


    if (localStorage.alert_msg) {
        $("#alert_msg").html(localStorage.getItem("alert_msg")).fadeIn('slow').delay(2500).fadeOut('slow');
        localStorage.removeItem('alert_msg');
    }

    $(".member_id_check").keydown(function(e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
            // Allow: Ctrl+A,Ctrl+C,Ctrl+V, Command+A
            ((e.keyCode == 65 || e.keyCode == 86 || e.keyCode == 67) && (e.ctrlKey === true || e.metaKey === true)) ||
            // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
            // let it happen, don't do anything
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

    $("#adminsettingFrm").submit(function(e) {
        var ac_name = $("#ac_name").val();
        var ac_number = $("#ac_number").val();
        var ifsc_code = $("#ifsc_code").val();

        if (ac_name == '') {
            $("#error").html(valid.error('Please enter account holder name')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (ac_number == '') {
            $("#error").html(valid.error('Please enter account number')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (ifsc_code == '') {
            $("#error").html(valid.error('Please enter IFSC Code')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#submitBtn").attr("disabled", true);
            var changeBtn = $("#submitBtn").html();
            $("#submitBtn").html("Submitting..");
            $.ajax({
                type: "POST",
                url: base_url + "add-admin-bank-detail",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.status == 'success') {
                        $("#error").html(data.msg);
                        $("#error").fadeIn('slow').delay(5000).fadeOut('slow');
                    } else {
                        $("#error").html(data.msg);
                        $("#error").fadeIn('slow').delay(5000).fadeOut('slow');
                    }
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#error").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });

    $("#adminUPIFrm").submit(function(e) {
        var upi_payment_id = $("#upi_payment_id").val();
        var google_upi_payment_id = $("#google_upi_payment_id").val();
        var phonepay_upi_payment_id = $("#phonepay_upi_payment_id").val();

        if (upi_payment_id == '') {
            $("#error_upi").html(valid.error('Please enter UPI Id')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#upiSubmitBtn").attr("disabled", true);
            var changeBtn = $("#upiSubmitBtn").html();
            $("#upiSubmitBtn").html("Submitting..");
            $.ajax({
                type: "POST",
                url: base_url + "add-admin-upi-detail",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.status == 'success') {
                        $("#error_upi_otp").html(data.msg);
                        $("#otp_number").html('OTP Sent To :- ' + data.mobile);
                        $("#error_upi_otp").fadeIn('slow').delay(5000).fadeOut('slow');
                        $("#upi_id").val(upi_payment_id);
                        $("#up_google_upi_payment_id").val(google_upi_payment_id);
                        $("#up_phonepay_upi_payment_id").val(phonepay_upi_payment_id);
                        $("#upiUpdateModal").modal('show');
                        /* window.setTimeout(function(){$('#upiUpdateModal').modal('show')}, 1500); */
                    } else {
                        $("#error_upi").html(data.msg);
                        $("#error_upi").fadeIn('slow').delay(5000).fadeOut('slow');
                    }
                    $("#upiSubmitBtn").attr("disabled", false);
                    $("#upiSubmitBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#error_upi").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#upiSubmitBtn").attr("disabled", false);
                    $("#upiSubmitBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });

    $("#UPIOTPConfirmFrm").submit(function(e) {
        var otp_code = $("#otp_code").val();

        if (otp_code == '') {
            $("#error_upi_otp").html(valid.error('Please enter OTP')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#otpSubmitBtn").attr("disabled", true);
            var changeBtn = $("#otpSubmitBtn").html();
            $("#otpSubmitBtn").html("Submitting..");
            $.ajax({
                type: "POST",
                url: base_url + "add-admin-upi-update-otp-check",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.status == 'success') {
                        $("#error_upi_otp").html(data.msg);
                        $("#error_upi_otp").fadeIn('slow').delay(5000).fadeOut('slow');
                        $("#error_upi").html(data.msg);
                        window.setTimeout(function() {
                            $('#upiUpdateModal').modal('hide')
                        }, 1500);
                    } else {
                        $("#error_upi_otp").html(data.msg);
                        $("#error_upi_otp").fadeIn('slow').delay(5000).fadeOut('slow');
                    }
                    $("#otpSubmitBtn").attr("disabled", false);
                    $("#otpSubmitBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#error_upi_otp").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#otpSubmitBtn").attr("disabled", false);
                    $("#otpSubmitBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });

    $("#appLinkFrm").submit(function(e) {
        var app_link = $("#app_link").val();

        if (app_link == '') {
            $("#error_msg").html(valid.error('Please enter app link')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#submitMobileBtn").attr("disabled", true);
            var changeBtn = $("#submitMobileBtn").html();
            $("#submitMobileBtn").html("Submitting..");
            $.ajax({
                type: "POST",
                url: base_url + "add-app-link",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.status == 'success') {
                        $("#error_msg").html(data.msg);
                        $("#error_msg").fadeIn('slow').delay(5000).fadeOut('slow');
                    } else {
                        $("#error_msg").html(data.msg);
                        $("#error_msg").fadeIn('slow').delay(5000).fadeOut('slow');
                    }
                    $("#submitMobileBtn").attr("disabled", false);
                    $("#submitMobileBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#error_msg").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#submitMobileBtn").attr("disabled", false);
                    $("#submitMobileBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });

    $("#allowedBetting").click(function() {
        $('#bettingAllowedModal').modal('show');
    });

    $(document).on('submit', '#bettingAllowedFrm', function(e) {
        $("#submitBtn").attr("disabled", true);
        var changeBtn = $("#submitBtn").html();
        $("#submitBtn").html("Processing..");
        $.ajax({
            url: base_url + 'allowed-betting',
            type: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            dataType: "json",
            success: function(data) {
                if (data.status == "success") {
                    $('#userPremiumModal').modal('hide');
                    window.setTimeout(function() {
                        window.location.reload()
                    }, 500);
                } else {
                    $("#alert").html(data.msg);
                    $("#alert").fadeIn('slow').delay(2500).fadeOut('slow');
                }
                $("#submitBtn").attr("disabled", false);
                $("#submitBtn").html(changeBtn);
            },
            error: function(jqXHR, exception) {
                var msg = valid.ajaxError(jqXHR, exception);
                $("#alert").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                $("#submitBtn").attr("disabled", false);
                $("#submitBtn").html(changeBtn);
            }
        });
        e.preventDefault();
    });

    $("#adminvaluesettingFrm").submit(function(e) {
        var min_deposite = $("#min_deposite").val();
        var max_deposite = $("#max_deposite").val();
        var min_withdrawal = $("#min_withdrawal").val();
        var max_withdrawal = $("#max_withdrawal").val();
        var min_transfer = $("#min_transfer").val();
        var max_transfer = $("#max_transfer").val();
        var min_bid_amt = $("#min_bid_amt").val();
        var max_bid_amt = $("#max_bid_amt").val();
        var welcome_bonus = $("#welcome_bonus").val();

        if (min_deposite == '') {
            $("#alert").html(valid.error('Please enter minimum deposite amount')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (max_deposite == '') {
            $("#alert").html(valid.error('Please enter maximum deposite amount')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (min_withdrawal == '') {
            $("#alert").html(valid.error('Please enter minimum withdrawal amount')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (max_withdrawal == '') {
            $("#alert").html(valid.error('Please enter maximum withdrawal amount')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (min_transfer == '') {
            $("#alert").html(valid.error('Please enter minimum transfer amount')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (max_transfer == '') {
            $("#alert").html(valid.error('Please enter maximum transfer amount')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (min_bid_amt == '') {
            $("#alert").html(valid.error('Please enter minimum bid amount')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (max_bid_amt == '') {
            $("#alert").html(valid.error('Please enter maximum bid amount')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (welcome_bonus == '') {
            $("#alert").html(valid.error('Please enter welcome bonus amount')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#submitValueBtn").attr("disabled", true);
            var changeBtn = $("#submitValueBtn").html();
            $("#submitValueBtn").html("Submitting..");
            $.ajax({
                type: "POST",
                url: base_url + "add-admin-fix-values",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.status == 'success') {
                        $("#alert").html(data.msg);
                        $("#alert").fadeIn('slow').delay(5000).fadeOut('slow');
                    } else {
                        $("#alert").html(data.msg);
                        $("#alert").fadeIn('slow').delay(5000).fadeOut('slow');
                    }
                    $("#submitValueBtn").attr("disabled", false);
                    $("#submitValueBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#alert").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#submitValueBtn").attr("disabled", false);
                    $("#submitValueBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });

    $(document).on('click', '.openPopupAcceptRequest', function(e) {
        var link_id = $(this).attr('data-id');
        $("#accept_request_id").val(link_id);
        $('#fundRequestAcceptModal').modal({
            show: true
        });
    });

    $(document).on('click', '.openPopupRejectRequest', function(e) {
        var link_id = $(this).attr('data-id');
        $("#reject_request_id").val(link_id);
        $('#fundRequestRejectModal').modal({
            show: true
        });
    });


    $(document).on('click', '.openPopupAcceptAutoRequest', function(e) {
        var link_id = $(this).attr('data-id');
        $("#accept_auto_request_id").val(link_id);
        $('#fundRequestAutoAcceptModal').modal({
            show: true
        });
    });

    $(document).on('click', '.openPopupRejectAutoRequest', function(e) {
        var link_id = $(this).attr('data-id');
        $("#reject_auto_request_id").val(link_id);
        $('#fundRequestAutoRejectModal').modal({
            show: true
        });
    });



    $("#gameRatesFrm").submit(function(e) {
        var single_digit_1 = $("#single_digit_1").val();
        var single_digit_2 = $("#single_digit_2").val();
        var jodi_digit_1 = $("#jodi_digit_1").val();
        var jodi_digit_2 = $("#jodi_digit_2").val();
        var single_pana_1 = $("#single_pana_1").val();
        var single_pana_2 = $("#single_pana_2").val();
        var double_pana_1 = $("#double_pana_1").val();
        var double_pana_2 = $("#double_pana_2").val();
        var tripple_pana_1 = $("#tripple_pana_1").val();
        var tripple_pana_2 = $("#tripple_pana_2").val();
        var half_sangam_1 = $("#half_sangam_1").val();
        var half_sangam_2 = $("#half_sangam_2").val();
        var full_sangam_1 = $("#full_sangam_1").val();
        var full_sangam_2 = $("#full_sangam_2").val();

        if (single_digit_1 == '') {
            $("#error").html(valid.error('Please enter single digit first value')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (single_digit_2 == '') {
            $("#error").html(valid.error('Please enter single digit second value')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (jodi_digit_1 == '') {
            $("#error").html(valid.error('Please enter jodi digit first value')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (jodi_digit_2 == '') {
            $("#error").html(valid.error('Please enter jodi digit second value')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (single_pana_1 == '') {
            $("#error").html(valid.error('Please enter single pana first value')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (single_pana_2 == '') {
            $("#error").html(valid.error('Please enter single pana second value')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (double_pana_1 == '') {
            $("#error").html(valid.error('Please enter double pana first value')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (double_pana_2 == '') {
            $("#error").html(valid.error('Please enter double pana second value')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (tripple_pana_1 == '') {
            $("#error").html(valid.error('Please enter tripple pana first value')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (tripple_pana_2 == '') {
            $("#error").html(valid.error('Please enter tripple pana second value')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (half_sangam_1 == '') {
            $("#error").html(valid.error('Please enter half sangam first value')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (half_sangam_2 == '') {
            $("#error").html(valid.error('Please enter half sangam second value')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (full_sangam_1 == '') {
            $("#error").html(valid.error('Please enter full sangam first value')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (full_sangam_2 == '') {
            $("#error").html(valid.error('Please enter full sangam second value')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#submitBtn").attr("disabled", true);
            var changeBtn = $("#submitBtn").html();
            $("#submitBtn").html("Submitting..");
            $.ajax({
                type: "POST",
                url: base_url + "add-game-rates",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.status == 'success') {
                        $("#error").html(data.msg);
                        $("#error").fadeIn('slow').delay(5000).fadeOut('slow');
                    } else {
                        $("#error").html(data.msg);
                        $("#error").fadeIn('slow').delay(5000).fadeOut('slow');
                    }
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#error").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });


    $("#starlineGameRatesFrm").submit(function(e) {
        var single_digit_1 = $("#single_digit_1").val();
        var single_digit_2 = $("#single_digit_2").val();
        var single_pana_1 = $("#single_pana_1").val();
        var single_pana_2 = $("#single_pana_2").val();
        var double_pana_1 = $("#double_pana_1").val();
        var double_pana_2 = $("#double_pana_2").val();
        var tripple_pana_1 = $("#tripple_pana_1").val();
        var tripple_pana_2 = $("#tripple_pana_2").val();


        if (single_digit_1 == '') {
            $("#error").html(valid.error('Please enter single digit first value')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (single_digit_2 == '') {
            $("#error").html(valid.error('Please enter single digit second value')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (single_pana_1 == '') {
            $("#error").html(valid.error('Please enter single pana first value')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (single_pana_2 == '') {
            $("#error").html(valid.error('Please enter single pana second value')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (double_pana_1 == '') {
            $("#error").html(valid.error('Please enter double pana first value')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (double_pana_2 == '') {
            $("#error").html(valid.error('Please enter double pana second value')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (tripple_pana_1 == '') {
            $("#error").html(valid.error('Please enter tripple pana first value')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (tripple_pana_2 == '') {
            $("#error").html(valid.error('Please enter tripple pana second value')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#submitBtn").attr("disabled", true);
            var changeBtn = $("#submitBtn").html();
            $("#submitBtn").html("Submitting..");
            $.ajax({
                type: "POST",
                url: base_url + "add-starline-game-rates",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.status == 'success') {
                        $("#error").html(data.msg);
                        $("#error").fadeIn('slow').delay(5000).fadeOut('slow');
                    } else {
                        $("#error").html(data.msg);
                        $("#error").fadeIn('slow').delay(5000).fadeOut('slow');
                    }
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#error").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });



    $("#galidisswarGameRatesFrm").submit(function(e) {
        var single_digit_1 = $("#single_digit_1").val();
        var single_digit_2 = $("#single_digit_2").val();
        var single_pana_1 = $("#single_pana_1").val();
        var single_pana_2 = $("#single_pana_2").val();
        var double_pana_1 = $("#double_pana_1").val();
        var double_pana_2 = $("#double_pana_2").val();


        if (single_digit_1 == '') {
            $("#error").html(valid.error('Please enter left digit first value')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (single_digit_2 == '') {
            $("#error").html(valid.error('Please enter left digit second value')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (single_pana_1 == '') {
            $("#error").html(valid.error('Please enter right pana first value')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (single_pana_2 == '') {
            $("#error").html(valid.error('Please enter right pana second value')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (double_pana_1 == '') {
            $("#error").html(valid.error('Please enter jodi first value')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (double_pana_2 == '') {
            $("#error").html(valid.error('Please enter jodi second value')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#submitBtn").attr("disabled", true);
            var changeBtn = $("#submitBtn").html();
            $("#submitBtn").html("Submitting..");
            $.ajax({
                type: "POST",
                url: base_url + "add-galidisswar-game-rates",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.status == 'success') {
                        $("#error").html(data.msg);
                        $("#error").fadeIn('slow').delay(5000).fadeOut('slow');
                    } else {
                        $("#error").html(data.msg);
                        $("#error").fadeIn('slow').delay(5000).fadeOut('slow');
                    }
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#error").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });


    $(document).on('click', '#accept', function(e) {
        var withdraw_request_id = $(this).attr('data-withdraw_request_id');
        var user_name = $(this).attr('data-user_name');
        var mobile = $(this).attr('data-mobile');
        var request_amount = $(this).attr('data-request_amount');
        var request_number = $(this).attr('data-request_number');
        $(".user_info").html('<table  class="table table-striped table-bordered "><tr><td>User Name</td><td>' + user_name + '</td></tr><tr><td>Mobile</td><td>' + mobile + '</td></tr><tr><td>Request Number</td><td>' + request_number + '</td></tr><td>Request Amount</td><td>' + request_amount + '</td></tr></table>');

        $("#withdraw_req_id").val(withdraw_request_id);
        $('#requestApproveModel').modal('show');
    });

    $(document).on('click', '#reject', function(e) {
        var withdraw_request_id = $(this).attr('data-withdraw_request_id');
        $("#r_withdraw_req_id").val(withdraw_request_id);
        $('#requestRejectModel').modal('show');
    });

    $("#adFund").click(function() {
        $('#addFundModel').modal('show');
    });

    $("#withdrawFund").click(function() {
        $('#withdrawFundModel').modal('show');
    });

    $(document).on('submit', '#withdrawapproveFrm', function(e) {
        $("#submitBtn").attr("disabled", true);
        var changeBtn = $("#submitBtn").html();
        $("#submitBtn").html("Submitting..");
        $.ajax({
            url: base_url + 'approve-withdraw-request',
            type: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            dataType: "json",
            success: function(data) {
                if (data.status == "success") {
                    $('#alert_msg_manager').html(data.msg).fadeIn('slow').delay(2000).fadeOut('slow');
                    $("#status").html('<badge class="badge badge-success">' + data.request_status + '</badge>');
                    $("#accept").attr("disabled", true);
                    $("#reject").attr("disabled", true);
                    location.reload();
                    window.setTimeout(function() {
                        $('#requestApproveModel').modal('hide')
                    }, 1000);
                } else {
                    $("#alert_msg_manager").html(data.msg);
                    $("#alert_msg_manager").fadeIn('slow').delay(2500).fadeOut('slow');
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                }

            },
            error: function(jqXHR, exception) {
                var msg = valid.ajaxError(jqXHR, exception);
                $("#alert_msg_manager").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                $("#submitBtn").attr("disabled", false);
                $("#submitBtn").html(changeBtn);
            }
        });
        e.preventDefault();
    });

    $(document).on('submit', '#withdrawRejectFrm', function(e) {
        $("#submitBtnReject").attr("disabled", true);
        var changeBtn = $("#submitBtnReject").html();
        $("#submitBtnReject").html("Submitting..");
        $.ajax({
            url: base_url + 'reject-withdraw-request',
            type: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            dataType: "json",
            success: function(data) {
                if (data.status == "success") {
                    $('#alert_msg').html(data.msg).fadeIn('slow').delay(2000).fadeOut('slow');
                    $("#status").html('<badge class="badge badge-success">' + data.request_status + '</badge>');
                    $("#accept").attr("disabled", true);
                    $("#reject").attr("disabled", true);
                    location.reload();
                    window.setTimeout(function() {
                        $('#requestRejectModel').modal('hide')
                    }, 1000);
                } else {
                    $("#alert_msg").html(data.msg);
                    $("#alert_msg").fadeIn('slow').delay(2500).fadeOut('slow');
                    $("#submitBtnReject").attr("disabled", false);
                    $("#submitBtnReject").html(changeBtn);
                }

            },
            error: function(jqXHR, exception) {
                var msg = valid.ajaxError(jqXHR, exception);
                $("#alert_msg").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                $("#submitBtnReject").attr("disabled", false);
                $("#submitBtnReject").html(changeBtn);
            }
        });
        e.preventDefault();
    });

    $(document).on('submit', '#addFundFrm', function(e) {
        var amount = $('#user_amount').val();
        if (amount == '') {
            $("#alert_msg").html(valid.error('Please enter amount')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#submitAddBtn").attr("disabled", true);
            var changeBtn = $("#submitAddBtn").html();
            $("#submitAddBtn").html("Submitting..");
            $.ajax({
                url: base_url + 'add-balance-user-wallet',
                type: 'POST',
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.status == "success") {
                        $('#alert_msg').html(data.msg).fadeIn('slow').delay(2000).fadeOut('slow');
                        location.reload();
                        window.setTimeout(function() {
                            $('#addFundModel').modal('hide')
                        }, 1000);
                    } else {
                        $("#alert_msg").html(data.msg);
                        $("#alert_msg").fadeIn('slow').delay(2500).fadeOut('slow');
                        $("#submitAddBtn").attr("disabled", false);
                        $("#submitAddBtn").html(changeBtn);
                    }

                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#alert_msg").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#submitAddBtn").attr("disabled", false);
                    $("#submitAddBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });


    $(document).on('submit', '#changePinFrm', function(e) {
        var security_pin = $('#security_pin').val();
        if (security_pin == '') {
            valid.snackbar_error('Please enter security pin');
        } else {
            $("#submitchangepinBtn").attr("disabled", true);
            var changeBtn = $("#submitchangepinBtn").html();
            $("#submitchangepinBtn").html("Submitting..");
            $.ajax({
                url: base_url + 'change-security-pin',
                type: 'POST',
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.status == "success") {
                        valid.snackbar_success(data.msg);
                        $('#security_pin_text').text(data.security_pin);
                        valid.snackbar_error('Please enter security pin');
                        $('#changePinModal').modal('hide');
                    } else {
                        valid.snackbar_error(data.msg);
                        $("#alert_msg").fadeIn('slow').delay(2500).fadeOut('slow');
                        $("#submitchangepinBtn").attr("disabled", false);
                        $("#submitchangepinBtn").html(changeBtn);
                    }

                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#alert_msg").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#submitchangepinBtn").attr("disabled", false);
                    $("#submitchangepinBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });

    $(document).on('submit', '#withdrawFundFrm', function(e) {
        var amount = $('#amount').val();
        if (amount == '') {
            $("#error_msg").html(valid.error('Please enter amount')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#submitWithdrawBtn").attr("disabled", true);
            var changeBtn = $("#submitWithdrawBtn").html();
            $("#submitWithdrawBtn").html("Submitting..");
            $.ajax({
                url: base_url + 'withdraw-balance-user-wallet',
                type: 'POST',
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.status == "success") {
                        $('#error_msg').html(data.msg).fadeIn('slow').delay(2000).fadeOut('slow');
                        location.reload();
                        window.setTimeout(function() {
                            $('#withdrawFundModel').modal('hide')
                        }, 1000);
                    } else {
                        $('#with_error_msg').html(data.msg).fadeIn('slow').delay(2000).fadeOut('slow');
                        /* $("#with_error_msg").html(data.msg);
                        $("#error_msg").fadeIn('slow').delay(2500).fadeOut('slow'); */
                        $("#submitWithdrawBtn").attr("disabled", false);
                        $("#submitWithdrawBtn").html(changeBtn);
                    }
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#error_msg").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#submitWithdrawBtn").attr("disabled", false);
                    $("#submitWithdrawBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });

    $("#howToPlayFrm").submit(function(e) {
        var description = $("#description").val();
        var video_link = $("#video_link").val();

        if (description == '') {
            $("#error").html(valid.error('Please enter content')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (video_link == '') {
            $("#error").html(valid.error('Please enter how to play video link')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#submitBtn").attr("disabled", true);
            var changeBtn = $("#submitBtn").html();
            $("#submitBtn").html("Submitting..");
            $.ajax({
                type: "POST",
                url: base_url + "add-how-to-play-data",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.status == 'success') {
                        $("#error").html(data.msg);
                        $("#error").fadeIn('slow').delay(5000).fadeOut('slow');
                    } else {
                        $("#error").html(data.msg);
                        $("#error").fadeIn('slow').delay(5000).fadeOut('slow');
                    }
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#error").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });

    $("#noticeFrm").submit(function(e) {
        var notice_title = $("#notice_title").val();
        var description = $("#description").val();
        var notice_date = $("#notice_date").val();

        if (notice_title == '') {
            $("#error").html(valid.error('Please enter notice title')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (description == '') {
            $("#error").html(valid.error('Please enter notice description')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (notice_date == '') {
            $("#error").html(valid.error('Please select notice date')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#submitBtn").attr("disabled", true);
            var changeBtn = $("#submitBtn").html();
            $("#submitBtn").html("Submitting..");
            $.ajax({
                type: "POST",
                url: base_url + "add-notice",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.status == 'success') {
                        $("#error").html(data.msg);
                        $("#error").fadeIn('slow').delay(5000).fadeOut('slow');
                        dataTable.ajax.reload();
                    } else {
                        $("#error").html(data.msg);
                        $("#error").fadeIn('slow').delay(5000).fadeOut('slow');
                    }
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#error").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });

    $(document).on('click', '.openPopupNotice', function(e) {
        var dataURL = $(this).attr('data-href');
        $('.batch_body').load(dataURL, function() {
            $('#editNoticeModal').modal({
                show: true
            });
        });
    });

    $(document).on('submit', '#updatenoticeFrm', function(e) {
        var u_notice_title = $('#u_notice_title').val();
        var u_description = $("#u_description").val();

        if (u_notice_title == '') {
            $("#alertmsg").html(valid.error('Please enter notice title')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (u_description == '') {
            $("#alertmsg").html(valid.error('Please enter notice content')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {

            $("#updateBtn").attr("disabled", true);
            var changeBtn = $("#updateBtn").html();
            $("#updateBtn").html("Updating..");
            $.ajax({
                type: "POST",
                url: base_url + "add-notice",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.status == 'update') {
                        $("#alertmsg").html(data.msg);
                        $("#alertmsg").fadeIn('slow').delay(5000).fadeOut('slow');
                        /* window.setTimeout(function(){$('#brandModal').modal('hide')}, 1000); */
                        dataTable.ajax.reload();
                    } else {
                        $("#alertmsg").html(data.msg);
                        $("#alertmsg").fadeIn('slow').delay(5000).fadeOut('slow');
                    }
                    $("#updateBtn").attr("disabled", false);
                    $("#updateBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#alertmsg").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#updateBtn").attr("disabled", false);
                    $("#updateBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });

    $("#gamesTimeFrm").submit(function(e) {
        $("#submitBtn").attr("disabled", true);
        var changeBtn = $("#submitBtn").html();
        $("#submitBtn").html("Submitting..");
        $.ajax({
            type: "POST",
            url: base_url + "add-games-time",
            data: new FormData(this),
            processData: false,
            contentType: false,
            dataType: "json",
            success: function(data) {
                if (data.status == 'success') {
                    $("#error").html(data.msg);
                    $("#error").fadeIn('slow').delay(5000).fadeOut('slow');
                } else {
                    $("#error").html(data.msg);
                    $("#error").fadeIn('slow').delay(5000).fadeOut('slow');
                }
                $("#submitBtn").attr("disabled", false);
                $("#submitBtn").html(changeBtn);
            },
            error: function(jqXHR, exception) {
                var msg = valid.ajaxError(jqXHR, exception);
                $("#error").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                $("#submitBtn").attr("disabled", false);
                $("#submitBtn").html(changeBtn);
            }
        });
        e.preventDefault();
    });

    $(document).on('submit', '#sliderImageFrm', function(e) {
        var file = $("#file").val();
        var display_order = $("#display_order").val();

        if (file == '') {
            $("#error").html(valid.error('Please select slider image')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (display_order == '') {
            $("#error").html(valid.error('Please enter image display order')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#submitBtn").attr("disabled", true);
            var changeBtn = $("#submitBtn").html();
            $("#submitBtn").html("Submitting..");
            $.ajax({
                type: "POST",
                url: base_url + "add-slider-image",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.status == 'success') {
                        $("#error").html(data.msg);
                        $("#error").fadeIn('slow').delay(5000).fadeOut('slow');
                        $("#sliderImageFrm")[0].reset();
                        dataTable.ajax.reload();
                    } else {
                        $("#error").html(data.msg);
                        $("#error").fadeIn('slow').delay(5000).fadeOut('slow');
                    }
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#error").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });

    $(document).on('submit', '#tipsFrm', function(e) {
        var tips_title = $("#tips_title").val();
        var description = $("#description").val();
        var file = $("#file").val();

        if (tips_title == '') {
            $("#error").html(valid.error('Please enter tips title')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (description == '') {
            $("#error").html(valid.error('Please enter tips description')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (file == '') {
            $("#error").html(valid.error('Please select file')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#submitBtn").attr("disabled", true);
            var changeBtn = $("#submitBtn").html();
            $("#submitBtn").html("Submitting..");
            $.ajax({
                type: "POST",
                url: base_url + "add-tips",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.status == 'success') {
                        $("#error").html(data.msg);
                        $("#error").fadeIn('slow').delay(5000).fadeOut('slow');
                        $("#tipsFrm")[0].reset();
                        dataTable.ajax.reload();
                    } else {
                        $("#error").html(data.msg);
                        $("#error").fadeIn('slow').delay(5000).fadeOut('slow');
                    }
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#error").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });

    $(document).on('click', '.openPopupEditTips', function(e) {
        var dataURL = $(this).attr('data-href');
        $('.batch_body').load(dataURL, function() {
            $('#editTipsModal').modal({
                show: true
            });
        });
    });

    $(document).on('click', '.openPopupEditStarlineGame', function(e) {
        var dataURL = $(this).attr('data-href');
        $('.modal_update_game_body').load(dataURL, function() {
            $('#updategameModal').modal({
                show: true
            });
        });
    });

    $(document).on('click', '.openPopupEditGame', function(e) {
        var dataURL = $(this).attr('data-href');
        $('.modal_update_game_body').load(dataURL, function() {
            $('#updategameModal').modal({
                show: true
            });
        });
    });

    $(document).on('click', '.openPopupEditgalidisswarGame', function(e) {
        var dataURL = $(this).attr('data-href');
        $('.modal_update_game_body').load(dataURL, function() {
            $('#updategameModal').modal({
                show: true
            });
        });
    });

    $(document).on('click', '.openPopupoffDayGame', function(e) {
        var dataURL = $(this).attr('data-href');
        $('.modal_off_day').load(dataURL, function() {
            $('#offdayModal').modal({
                show: true
            });
        });
    });



    $(document).on('click', '.openPopupStarlineoffDayGame', function(e) {
        var dataURL = $(this).attr('data-href');
        $('.starline_modal_off_day').load(dataURL, function() {
            $('#starlineoffdayModal').modal({
                show: true
            });
        });
    });


    $(document).on('click', '.openPopupGalidisawaroffDayGame', function(e) {
        var dataURL = $(this).attr('data-href');
        $('.galidisawar_modal_off_day').load(dataURL, function() {
            $('#galidisawaroffdayModal').modal({
                show: true
            });
        });
    });

    $(document).on('submit', '#updateTipsFrm', function(e) {
        var tips_title = $("#u_tips_title").val();
        var description = $("#u_description").val();

        if (tips_title == '') {
            $("#alertmsg").html(valid.error('Please enter tips title')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (description == '') {
            $("#alertmsg").html(valid.error('Please enter tips description')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {

            $("#updateBtn").attr("disabled", true);
            var changeBtn = $("#updateBtn").html();
            $("#updateBtn").html("Updating..");
            $.ajax({
                type: "POST",
                url: base_url + "add-tips",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.status == 'update') {
                        $("#alertmsg").html(data.msg);
                        $("#alertmsg").fadeIn('slow').delay(5000).fadeOut('slow');
                        dataTable.ajax.reload();
                    } else {
                        $("#alertmsg").html(data.msg);
                        $("#alertmsg").fadeIn('slow').delay(5000).fadeOut('slow');
                    }
                    $("#updateBtn").attr("disabled", false);
                    $("#updateBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#alertmsg").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#updateBtn").attr("disabled", false);
                    $("#updateBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });

    $(document).on('click', '.openCloseMarket', function(e) {
        var id = $(this).attr('id');
        var myArray = id.split('-');
        var table_id = myArray[3];

        $.ajax({
            type: "POST",
            url: base_url + 'block-data-function',
            data: 'id=' + myArray[1] + "&table=" + myArray[2] + "&table_id=" + table_id + "&status_name=" + myArray[4],
            success: function(data) {


                if (myArray[0] == 'danger') {
                    $("#" + id).html('<button class="btn btn-success btn-xs mr-1" type="button" title="Market Close">Market Close</button>');
                    $("#status_market" + myArray[1]).html('<span class="badge badge-pill badge-soft-success font-size-12">Market Open</span>');
                    $("#" + id).attr('id', 'success-' + myArray[1] + '-' + myArray[2] + '-' + table_id + '-' + myArray[4]);
                } else {
                    $("#" + id).html('<button class="btn btn-danger btn-xs mr-1" type="button" title="Market Open">Market Open</button>');
                    $("#status_market" + myArray[1]).html('<span class="badge badge-pill badge-soft-danger font-size-12">Market Close</span>');
                    $("#" + id).attr('id', 'danger-' + myArray[1] + '-' + myArray[2] + '-' + table_id + '-' + myArray[4]);
                }
                $("#msg").html(data);
                $("#msg").fadeIn('slow').delay(5000).fadeOut('slow');
            },
            error: function(jqXHR, exception) {
                var msg = valid.ajaxError(jqXHR, exception);
                $("#msg").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');

            }
        });

        e.preventDefault();
    });


    $(document).on('click', '.transferPointStatus', function(e) {
        var id = $(this).attr('id');

        var myArray = id.split('-');
        var table_id = myArray[3];

        $("#" + id).html('processing..');
        $.ajax({
            type: "POST",
            url: base_url + 'block-data-function',
            data: 'id=' + myArray[1] + "&table=" + myArray[2] + "&table_id=" + table_id + "&status_name=" + myArray[4],
            success: function(data) {


                if (myArray[0] == '0') {
                    $("#" + id).html('<button class="btn btn-outline-success btn-xs m-l-5" type="button" title="Transfer Point Enabled">Change</button>');
                    $("#" + id).attr('id', '1-' + myArray[1] + '-' + myArray[2] + '-' + table_id + '-' + myArray[4]);
                    $("#tp_stats").html('<badge class="badge badge-danger">Deactivated</badge>');
                } else {
                    $("#" + id).html('<button class="btn btn-outline-success btn-xs m-l-5" type="button" title="Transfer Point Enabled">Change</button>');
                    $("#" + id).attr('id', '0-' + myArray[1] + '-' + myArray[2] + '-' + table_id + '-' + myArray[4]);
                    $("#tp_stats").html('<badge class="badge badge-success">Active</badge>');
                }
                $("#msg").html(data);
                $("#msg").fadeIn('slow').delay(5000).fadeOut('slow');
            },
            error: function(jqXHR, exception) {
                var msg = valid.ajaxError(jqXHR, exception);
                $("#msg").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');

            }
        });

        e.preventDefault();
    });



    $(document).on('click', '.openPopupDeleteImage', function(e) {
        var image_id = $(this).attr('data-id');
        $("#delete_id").val(image_id);
        $('#imageDeleteModal').modal({
            show: true
        });
    });

    $(document).on('submit', '#balanceAddFrm', function(e) {
        var user = $('#user').val();
        var amount = $("#amount").val();
        var user_name = $(this).find(':selected').attr('data-user_name');
        var mobile = $(this).find(':selected').attr('data-mobile');
        var wallet_balance = $(this).find(':selected').attr('data-wallet_balance');
        if (user == '') {
            $("#error").html(valid.error('Please select user')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (amount == '') {
            $("#error").html(valid.error('Please enter amount')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (user != '' && amount != '') {
            $(".userInfo").html('<table class="table table-striped table-bordered "><tr><td>User Name</td><td>' + user_name + '</td></tr><tr><td>Mobile</td><td>' + mobile + '</td></tr><tr><td>Transfer Amount</td><td>' + amount + '</td></tr></table>');

            $("#user_id").val(user);
            $("#user_amount").val(amount);
            $('#paymentApprovalModel').modal('show');
        }
        e.preventDefault();
    });

    $(document).on('submit', '#paymentApprovalFrm', function(e) {

        $('#paymentApprovalModel').modal('hide');
        $("#submitBtn").attr("disabled", true);
        var changeBtn = $("#submitBtn").html();
        $("#submitBtn").html("Submitting..");
        $.ajax({
            url: base_url + 'add-balance-user-wallet',
            type: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            dataType: "json",
            success: function(data) {
                if (data.status == "success") {
                    $('#error').html(data.msg).fadeIn('slow').delay(2000).fadeOut('slow');
                    $("#user_id").val('');
                    $("#user_amount").val('');
                    $('#balanceAddFrm')[0].reset();
                    $('#paymentApprovalFrm')[0].reset();



                } else {
                    $("#error").html(data.msg);
                    $("#error").fadeIn('slow').delay(2500).fadeOut('slow');
                }
                $("#submitBtn").attr("disabled", false);
                $("#submitBtn").html(changeBtn);
            },
            error: function(jqXHR, exception) {
                var msg = valid.ajaxError(jqXHR, exception);
                $("#error").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                $("#submitBtn").attr("disabled", false);
                $("#submitBtn").html(changeBtn);
            }
        });
        e.preventDefault();
    });



    $("#getBidHistoryFrm").submit(function(e) {
        var bid_date = $("#bid_date").val();
        var game_name = $("#game_name").val();
        var game_type = $("#game_type").val();

        if (bid_date == '') {
            $("#error_msg").html(valid.error('Please select date')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (game_name == '') {
            $("#error_msg").html(valid.error('Please select game name')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (game_type == '') {
            $("#error_msg").html(valid.error('Please select game type')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#submitBtn").attr("disabled", true);
            var changeBtn = $("#submitBtn").html();
            $("#submitBtn").html("Submitting..");
            $("#bidHistory_date").val(bid_date);
            $("#bid_game_name").val(game_name);
            $("#bid_game_type").val(game_type);
            $('#bidHistory tbody').empty();
            $.ajax({
                type: "POST",
                url: base_url + "get-bid-history-data",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.getBidHistory != '') {
                        $('#export_btn').show();

                        if ($.fn.DataTable.isDataTable('#bidHistory')) {
                            $('#bidHistory').DataTable().destroy();
                        }
                        $('#bidHistory tbody').empty();


                        $.each(data.getBidHistory, function(key, val) {

                            if (val.pana == 'Single Digit') {
                                if (val.session == 'Open') {
                                    var open_digit = val.digits;
                                    var close_digit = 'N/A';
                                    var open_pana = 'N/A';
                                    var close_pana = 'N/A';
                                } else {
                                    var open_digit = 'N/A';
                                    var close_digit = val.digits;
                                    var open_pana = 'N/A';
                                    var close_pana = 'N/A';
                                }
                            }

                            if (val.pana == 'Jodi Digit') {
                                var open_digit = val.digits[0];
                                var close_digit = val.digits % 10;
                                var open_pana = 'N/A';
                                var close_pana = 'N/A';
                            }

                            if (val.pana == 'Single Pana' || val.pana == 'Double Pana' || val.pana == 'Triple Pana') {
                                if (val.session == 'Open') {
                                    var a = val.digits[0];
                                    var b = val.digits[1];
                                    var c = val.digits[2];

                                    var total_digits = parseInt(a) + parseInt(b) + parseInt(c);
                                    if (total_digits < 10) {
                                        var digits = total_digits;
                                    } else if (total_digits > 9) {
                                        var digits = total_digits % 10;
                                    }
                                    var open_digit = digits;
                                    var close_digit = 'N/A';
                                    var open_pana = val.digits;
                                    var close_pana = 'N/A';
                                } else {
                                    var a = val.digits[0];
                                    var b = val.digits[1];
                                    var c = val.digits[2];
                                    var total_digits = parseInt(a) + parseInt(b) + parseInt(c);
                                    if (total_digits < 10) {
                                        var digits = total_digits;
                                    } else if (total_digits > 9) {
                                        var digits = total_digits % 10;
                                    }
                                    var open_digit = 'N/A';
                                    var close_digit = digits;
                                    var open_pana = 'N/A';
                                    var close_pana = val.digits;
                                }
                            }

                            if (val.pana == 'Half Sangam') {
                                if (val.session == 'Open') {
                                    var open_digit = val.digits;
                                    var close_digit = 'N/A';
                                    var open_pana = 'N/A';
                                    var close_pana = val.closedigits;
                                } else {
                                    var open_digit = 'N/A';
                                    var close_digit = val.digits;
                                    var open_pana = val.closedigits;
                                    var close_pana = 'N/A';
                                }
                            }

                            if (val.pana == 'Full Sangam') {
                                var open_digit = "N/A";
                                var close_digit = "N/A";
                                var open_pana = val.digits;
                                var close_pana = val.closedigits;
                            }
                            if (val.pay_status == 1) {
                                var action = "<b style='text-align:center;'>--</b>";
                            } else {
                                var action = '<a title="Edit" href="javascript:void(0);" data-href="' + base_url + admin + '/edit-bid-history/' + val.bid_id + '" class="openpopupeditbidhistory"><button  class="btn btn-outline-primary btn-xs m-l-5" type="button"  title="edit">Edit</button></a>';
                            }
                            $("#bid_data").append('<tr><td>' + val.user_name + ' <a href="' + base_url + admin + '/view-user/' + val.user_id + '"><i class="bx bx-link-external"></i></a></td><td>' + val.bid_tx_id + '</td><td>' + val.game_name + '</td><td>' + val.pana + '</td><td>' + val.session + '</td><td>' + open_pana + '</td><td>' + open_digit + '</td><td>' + close_pana + '</td><td>' + close_digit + '</td><td>' + val.points + '</td><td>' + action + '</td></tr>');
                        });

                        $("#bidHistory").dataTable();

                    } else {
                        $('#export_btn').hide();
                        $("#bid_data").append('<tr><td colspan=9 class="table_text_align">No Data Available</td></tr>');
                    }

                    /* $("#bidHistory").dataTable().fnClearTable();
                    $('#bidHistory').dataTable().fnDestroy();
                    $("#bidHistory").DataTable({lengthChange:!1,buttons:["copy","excel","pdf"]}).buttons().container().appendTo("#bidHistory_wrapper .col-md-6:eq(0)"); */



                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#error_msg").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });





    $("#getStarlineBidHistoryFrm").submit(function(e) {
        var bid_date = $("#bid_date").val();
        var game_name = $("#game_name").val();
        var game_type = $("#game_type").val();

        if (bid_date == '') {
            $("#error_msg").html(valid.error('Please select date')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (game_name == '') {
            $("#error_msg").html(valid.error('Please select game name')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (game_type == '') {
            $("#error_msg").html(valid.error('Please select game type')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#submitBtn").attr("disabled", true);
            var changeBtn = $("#submitBtn").html();
            $("#submitBtn").html("Submitting..");
            $("#bidHistory_date").val(bid_date);
            $("#bid_game_name").val(game_name);
            $("#bid_game_type").val(game_type);
            $('#bidHistory tbody').empty();
            $.ajax({
                type: "POST",
                url: base_url + "get-starline-bid-history-data",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.getBidHistory != '') {
                        $('#export_btn').show();
                        $.each(data.getBidHistory, function(key, val) {

                            if (val.pana == 'Single Digit') {
                                if (val.session == 'Open') {
                                    var open_digit = val.digits;
                                    var close_digit = 'N/A';
                                    var open_pana = 'N/A';
                                    var close_pana = 'N/A';
                                }

                            }



                            if (val.pana == 'Single Pana' || val.pana == 'Double Pana' || val.pana == 'Triple Pana') {
                                if (val.session == 'Open') {
                                    var a = val.digits[0];
                                    var b = val.digits[1];
                                    var c = val.digits[2];

                                    var total_digits = parseInt(a) + parseInt(b) + parseInt(c);
                                    if (total_digits < 10) {
                                        var digits = total_digits;
                                    } else if (total_digits > 9) {
                                        var digits = total_digits % 10;
                                    }
                                    var open_digit = digits;
                                    var close_digit = 'N/A';
                                    var open_pana = val.digits;
                                    var close_pana = 'N/A';
                                }

                            }

                            $("#bid_data").append('<tr><td><a href="' + base_url + admin + '/view-user/' + val.user_id + '">' + val.user_name + '<i class="bx bx-link-external"></i></a></td><td>' + val.bid_tx_id + '</td><td>' + val.game_name + '</td><td>' + val.pana + '</td><td>' + open_digit + '</td><td>' + open_pana + '</td><td>' + val.points + '</td></tr>');
                        });
                    } else {
                        $('#export_btn').hide();
                        $("#bid_data").append('<tr><td colspan="10" class="table_text_align">No Data Available</td></tr>');
                    }
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#error_msg").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });



    $("#getgalidisswarBidHistoryFrm").submit(function(e) {
        var bid_date = $("#bid_date").val();
        var game_name = $("#game_name").val();
        var game_type = $("#game_type").val();

        if (bid_date == '') {
            $("#error_msg").html(valid.error('Please select date')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (game_name == '') {
            $("#error_msg").html(valid.error('Please select game name')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (game_type == '') {
            $("#error_msg").html(valid.error('Please select game type')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#submitBtn").attr("disabled", true);
            var changeBtn = $("#submitBtn").html();
            $("#submitBtn").html("Submitting..");
            $("#bidHistory_date").val(bid_date);
            $("#bid_game_name").val(game_name);
            $("#bid_game_type").val(game_type);
            $('#bidHistory tbody').empty();
            $.ajax({
                type: "POST",
                url: base_url + "get-galidisswar-bid-history-data",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.getBidHistory != '') {
                        $('#export_btn').show();
                        $.each(data.getBidHistory, function(key, val) {

                            if (val.pay_status == 1) {
                                var action = "<b style='text-align:center;'>--</b>";
                            } else {
                                var action = '<a title="Edit" href="javascript:void(0);" data-href="' + base_url + admin + '/edit-bid-galidissawar-history/' + val.bid_id + '" class="openpopupeditgalidissawarbidhistory"><button  class="btn btn-outline-primary btn-xs m-l-5" type="button"  title="edit">Edit</button></a>';
                            }

                            $("#bid_data").append('<tr><td><a href="' + base_url + admin + '/view-user/' + val.user_id + '">' + val.user_name + '<i class="bx bx-link-external"></i></a></td><td>' + val.bid_tx_id + '</td><td>' + val.game_name + '</td><td>' + val.pana + '</td><td>' + val.digits + '</td><td>' + val.points + '</td><td>' + action + '</td></tr>');
                        });
                    } else {
                        $('#export_btn').hide();
                        $("#bid_data").append('<tr><td colspan="10" class="table_text_align">No Data Available</td></tr>');
                    }
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#error_msg").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });



    /*$("#getMarketBidFrm").submit(function(e) 
	{ 
		var game_name = $("#game_name").val();
		
		if(game_name == ''){
			valid.snackbar_error('Please select game name');
		}else {
			$("#submitBtn").attr("disabled",true);	   
			var changeBtn = $("#submitBtn").html();
			$("#submitBtn").html("...");
			$.ajax({
				type: "POST",
				url: base_url + "get-market-bid-details",
				data: new FormData( this ),
				processData: false,
				contentType: false,
				dataType: "json",
				success: function (data) {
					if (data.status == 'success'){
						$('#bid_amt').html(data.points);
					}
					$("#submitBtn").attr("disabled",false);
					$("#submitBtn").html(changeBtn);
				},
				error: function (jqXHR, exception) {
					var msg = valid.ajaxError(jqXHR,exception);
					valid.snackbar_error(msg);
					$("#submitBtn").attr("disabled",false);
					$("#submitBtn").html(changeBtn);
				}
			});
		}
		e.preventDefault();	
    });*/


    $(".getMarketBid").change(function(e) {
        var game_name = $(this).val();

        if (game_name == '') {
            valid.snackbar_error('Please select game name');
        } else {
            $("#submitBtn").attr("disabled", true);
            var changeBtn = $("#submitBtn").html();
            $("#submitBtn").html("...");
            $.ajax({
                type: "POST",
                url: base_url + "get-market-bid-details",
                data: {
                    game_name: game_name
                },
                dataType: "json",
                success: function(data) {
                    if (data.status == 'success') {
                        $('#bid_amt').html(data.points);
                    }
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    valid.snackbar_error(msg);
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });

    $("#geWinningHistoryFrm").submit(function(e) {
        var result_date = $("#result_date").val();
        var market_status = $("#win_market_status").val();
        var game_name = $("#win_game_name").val();

        if (result_date == '') {
            $("#error_msg").html(valid.error('Please select date')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (game_name == '') {
            $("#error_msg").html(valid.error('Please select game')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (market_status == '') {
            $("#error_msg").html(valid.error('Please select market time')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#submitBtn").attr("disabled", true);
            var changeBtn = $("#submitBtn").html();
            $("#submitBtn").html("Submitting..");
            $("#result_date").val(result_date);
            $('#resultHistory tbody').empty();
            $.ajax({
                type: "POST",
                url: base_url + "get-winning-history-data",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.getResultHistory != '') {
                        if ($.fn.DataTable.isDataTable('#resultHistory')) {
                            $('#resultHistory').DataTable().destroy();
                        }
                        $('#resultHistory tbody').empty();

                        $.each(data.getResultHistory, function(key, val) {
                            if (val.pana == 'Single Digit') {
                                if (val.session == 'Open') {
                                    var open_digit = val.digits;
                                    var close_digit = 'N/A';
                                    var open_pana = 'N/A';
                                    var close_pana = 'N/A';
                                } else {
                                    var open_digit = 'N/A';
                                    var close_digit = val.digits;
                                    var open_pana = 'N/A';
                                    var close_pana = 'N/A';
                                }
                            }

                            if (val.pana == 'Jodi Digit') {
                                var open_digit = val.digits[0];
                                var close_digit = val.digits % 10;
                                var open_pana = 'N/A';
                                var close_pana = 'N/A';
                            }

                            if (val.pana == 'Single Pana' || val.pana == 'Double Pana' || val.pana == 'Triple Pana') {
                                if (val.session == 'Open') {
                                    var a = val.digits[0];
                                    var b = val.digits[1];
                                    var c = val.digits[2];

                                    var total_digits = parseInt(a) + parseInt(b) + parseInt(c);
                                    if (total_digits < 10) {
                                        var digits = total_digits;
                                    } else if (total_digits > 9) {
                                        var digits = total_digits % 10;
                                    }
                                    var open_digit = digits;
                                    var close_digit = 'N/A';
                                    var open_pana = val.digits;
                                    var close_pana = 'N/A';
                                } else {
                                    var a = val.digits[0];
                                    var b = val.digits[1];
                                    var c = val.digits[2];
                                    var total_digits = parseInt(a) + parseInt(b) + parseInt(c);
                                    if (total_digits < 10) {
                                        var digits = total_digits;
                                    } else if (total_digits > 9) {
                                        var digits = total_digits % 10;
                                    }
                                    var open_digit = 'N/A';
                                    var close_digit = digits;
                                    var open_pana = 'N/A';
                                    var close_pana = val.digits;
                                }
                            }

                            if (val.pana == 'Half Sangam') {
                                if (val.session == 'Open') {
                                    var open_digit = val.digits;
                                    var close_digit = 'N/A';
                                    var open_pana = 'N/A';
                                    var close_pana = val.closedigits;
                                } else {
                                    var open_digit = 'N/A';
                                    var close_digit = val.digits;
                                    var open_pana = val.closedigits;
                                    var close_pana = 'N/A';
                                }
                            }

                            if (val.pana == 'Full Sangam') {
                                var open_digit = "N/A";
                                var close_digit = "N/A";
                                var open_pana = val.digits;
                                var close_pana = val.closedigits;
                            }



                            $("#result_data").append('<tr><td><a href="' + base_url + admin + '/view-user/' + val.user_id + '">' + val.user_name + '</a></td><td>' + val.game_name + '</td><td>' + val.pana + '</td><td>' + open_pana + '</td><td>' + open_digit + '</td><td>' + close_pana + '</td><td>' + close_digit + '</td><td>' + val.points + '</td><td>' + val.amount + '</td><td>' + val.tx_request_number + '</td><td>' + val.insert_date + '</td></tr>');
                        });
                        $("#resultHistory").dataTable();
                    } else {
                        $("#result_data").append('<tr><td colspan=7 class="table_text_align">No Data Available</td></tr>');
                    }
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#error_msg").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });


    $("#userWinningHistoryFrm").submit(function(e) {
        var result_date = $("#result_date").val();

        if (result_date == '') {
            $("#error_msg").html(valid.error('Please select date')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#submitBtn_2").attr("disabled", true);
            var changeBtn = $("#submitBtn_2").html();
            $("#submitBtn_2").html("Submitting..");
            $("#result_date").val(result_date);
            $('#resultHistory tbody').empty();
            $.ajax({
                type: "POST",
                url: base_url + "user-winning-history-data",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.getResultHistory != '') {
                        $.each(data.getResultHistory, function(key, val) {
                            $("#result_data").append('<tr><td>' + val.amount + '</td><td>' + val.transaction_note + '</td><td>' + val.tx_request_number + '</td><td>' + val.insert_date + '</td></tr>');
                        });
                    } else {
                        $("#result_data").append('<tr><td colspan=7 class="table_text_align">No Data Available</td></tr>');
                    }
                    $("#submitBtn_2").attr("disabled", false);
                    $("#submitBtn_2").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#error_msg").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#submitBtn_2").attr("disabled", false);
                    $("#submitBtn_2").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });


    $("#sendNotificationFrm").submit(function(e) {
        var user_id = $("#user_id").val();

        if (user_id == '') {
            $("#error").html(valid.error('Please select user name')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (notice_title == '') {
            $("#error").html(valid.error('Please enter notice title')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (notification_content == '') {
            $("#error").html(valid.error('Please enter notification content')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#submitBtn").attr("disabled", true);
            var changeBtn = $("#submitBtn").html();
            $("#submitBtn").html("Submitting..");

            $.ajax({
                type: "POST",
                url: base_url + "user-send-notification",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {

                    if (data.status == 'success') {
                        $("#error").html(data.msg);
                        $("#error").fadeIn('slow').delay(5000).fadeOut('slow');
                        $("#sendNotificationFrm")[0].reset();
                    } else {
                        $("#error").html(data.msg);
                        $("#error").fadeIn('slow').delay(5000).fadeOut('slow');
                    }

                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#error").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });


    $("#adminContactFrm").submit(function(e) {
        var whats_mobile = $("#whats_mobile").val();
        var address = $("#address").val();

        if (whats_mobile == '') {
            $("#errormsg").html(valid.error('Please enter whatapp number')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (address == '') {
            $("#errormsg").html(valid.error('Please enter address')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#submitBtn").attr("disabled", true);
            var changeBtn = $("#submitBtn").html();
            $("#submitBtn").html("Submitting..");
            $.ajax({
                type: "POST",
                url: base_url + "add-contact-settings",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.status == 'success') {
                        $("#errormsg").html(data.msg);
                        $("#errormsg").fadeIn('slow').delay(5000).fadeOut('slow');
                    } else {
                        $("#errormsg").html(data.msg);
                        $("#errormsg").fadeIn('slow').delay(5000).fadeOut('slow');
                    }
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#errormsg").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });

    $(document).on('change', '#result_dec_date', function(e) {
        var date = $(this).val();
        var ob1 = '';
        var ob2 = '';
        $.ajax({
            type: "POST",
            url: base_url + "get-game-list-for-result",
            data: {
                date: date
            },
            dataType: "json",
            success: function(data) {
                $.each(data.game_result, function(i, value) {
                    if (value.open_decleare_status == 1 && value.close_decleare_status == 1) {

                    } else {
                        ob1 += '<option value="' + value.game_id + '">' + value.game_name + ' (' + value.open_time + '-' + value.close_time + ')</option>';
                    }
                });
                $('#game_id').html(ob1);

            }
        });


    });

    $(document).on('change', '#result_pik_date', function(e) {
        var date = $(this).val();
        var ob1 = '';
        var ob2 = '';

        $.ajax({
            type: "POST",
            url: base_url + "result-history-list-load-data",
            data: {
                date: date
            },
            dataType: "json",
            success: function(data) {
                $.each(data, function(key, val) {
                    ob2 += '<tr><td>' + val.sn + '</td><td>' + val.game_name + '</td><td>' + val.result_date + '</td><td>' + val.open_date + '</td><td>' + val.close_date + '</td><td>' + val.open_result + '</td><td>' + val.close_result + '</td></tr>'
                });
                $('#getGameResultHistory').html(ob2);

            }
        });


    });

    $(document).on('change', '#result_star_pik_date', function(e) {
        var date = $(this).val();
        var ob1 = '';
        var ob2 = '';

        $.ajax({
            type: "POST",
            url: base_url + "starline-result-history-list-load-data",
            data: {
                date: date
            },
            dataType: "json",
            success: function(data) {
                $.each(data, function(key, val) {
                    ob2 += '<tr><td>' + val.sn + '</td><td>' + val.game_name + '</td><td>' + val.result_date + '</td><td>' + val.open_date + '</td><td>' + val.open_result + '</td></tr>'
                });
                $('#getStarlineResultHistory').html(ob2);

            }
        });


    });


    $(document).on('change', '#result_date', function(e) {
        var date = $(this).val();
        var ob1 = '';
        var ob2 = '';

        $.ajax({
            type: "POST",
            url: base_url + "galidisswar-result-history-list-load-data",
            data: {
                date: date
            },
            dataType: "json",
            success: function(data) {
                $.each(data, function(key, val) {
                    ob2 += '<tr><td>' + val.sn + '</td><td>' + val.game_name + '</td><td>' + val.result_date + '</td><td>' + val.open_date + '</td><td>' + val.open_result + '</td></tr>'
                });
                $('#getgalidisswarResultHistory').html(ob2);

            }
        });


    });


    $("#gameSrchFrm").submit(function(e) {
        var game_id = $("#game_id").val();
        var result_dec_date = $("#result_dec_date").val();
        var market_status = $("#market_status").val();

        if (game_id == '') {
            $("#error").html(valid.error('Please select game name')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (result_dec_date == '') {
            $("#error").html(valid.error('Please select declare date')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#result_div").addClass('display_none');
            $("#openDecBtn").hide();
            $("#closeDecBtn").hide();
            $("#winner_btn").hide();
            $("#winner_close_btn").hide();

            $("#srchBtn").attr("disabled", true);
            var changeBtn = $("#srchBtn").html();
            $("#srchBtn").html("Searching..");

            if (market_status == 1) {
                $(".open_panna_area").show();
                $(".close_panna_area").hide();
            } else if (market_status == 2) {
                $(".open_panna_area").hide();
                $(".close_panna_area").show();
            }

            $("#open_div_msg").html('<label>&nbsp;</label><button type="button" class="btn btn-primary waves-light mr-1" id="openSaveBtn" name="openSaveBtn" onclick="OpenSaveData();">Save</button><button type="button" class="btn btn-warning waves-light mr-1 display_none"  onclick="OpenResultWinner();" id="winner_btn">Show Winner</button><button type="button" class="btn btn-primary waves-light mr-1 display_none" id="openDecBtn" name="openDecBtn" onclick="decleareOpenResult();">Declare</button>');


            $("#close_div_msg").html('<label>&nbsp;</label><button type="button"  class="btn btn-primary waves-light mr-1" id="closeSaveBtn" name="closeSaveBtn" onclick="closeSaveData();" >Save</button><button type="button" class="btn btn-warning waves-light mr-1 display_none"  onclick="CloseResultWinner();" id="winner_close_btn">Show Winner</button><button type="button" class="btn btn-primary waves-light mr-1 display_none" id="closeDecBtn" name="closeDecBtn" onclick="decleareCloseResult();">Declare</button>');

            $.ajax({
                type: "POST",
                url: base_url + "get-decleare-game-data",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.status == 'success') {
                        $("#result_div").removeClass('display_none');
                        $("#open_number").val(data.open_number);
                        $("#close_number").val(data.close_number);
                        $(".select2").select2();
                        $("#open_result").val(data.open_result);
                        $("#close_result").val(data.close_result);
                        $("#id").val(data.id);

                        if (data.open_result != '') {
                            $("#openDecBtn").show();
                        }
                        if (data.close_result != '') {
                            $("#closeDecBtn").show();
                        }

                        if (data.open_decleare_status == 0) {
                            $("#winner_btn").show();
                        }

                        if (data.close_decleare_status == 0) {
                            $("#winner_close_btn").show();
                        }



                        if (data.open_decleare_status == 1) {
                            $("#open_div_msg").html('')
                            $("#withdraw_data_details").show();
                            $("#open_result_data").html(data.open_number);
                            /* $("#open_div_msg").html('<label>&nbsp;</label><badge class="badge badge-success">Result Decleared on '+data.open_decleare_date+'</badge>');
                            $("#open_div_msg").append('<button type="button" class="btn btn-danger waves-light mr-1"  onclick="OpenDeleteResultConfirmData();">Delete Result</button>'); */
                        }

                        if (data.close_decleare_status == 1) {
                            $("#close_div_msg").html('')
                            /* $("#close_div_msg").html('<label>&nbsp;</label><badge class="badge badge-success">Result Decleared on '+data.close_decleare_date+'</badge>');
                            $("#close_div_msg").append('<button type="button" class="btn btn-danger waves-light mr-1"  onclick="closeDeleteResultConfirmData();">Delete Result</button>'); */
                        }
                    }
                    $("#srchBtn").attr("disabled", false);
                    $("#srchBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#error").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#srchBtn").attr("disabled", false);
                    $("#srchBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });




    $("#starlineGameSrchFrm").submit(function(e) {
        var game_id = $("#game_id").val();
        var result_dec_date = $("#starline_result_dec_date").val();

        if (game_id == '') {
            $("#error").html(valid.error('Please select game name')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (result_dec_date == '') {
            $("#error").html(valid.error('Please select declare date')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#result_div").hide();
            $("#openDecBtn").hide();

            $("#srchBtn").attr("disabled", true);
            var changeBtn = $("#srchBtn").html();
            $("#srchBtn").html("Searching..");

            $("#open_div_msg").html('<label>&nbsp;</label><button type="button" class="btn btn-primary waves-light m-t-10" id="openSaveBtn" name="openSaveBtn" onclick="OpenSaveStarlineGameData();">Save</button>&nbsp;&nbsp;<button type="button" class="btn btn-warning waves-light mr-1 display_none"  onclick="OpenStarlineResultWinner();" id="winner_btn">Show Winner</button>&nbsp;&nbsp;<button type="button" class="btn btn-primary waves-light m-t-10 display_none" id="openDecBtn" name="openDecBtn" onclick="decleareOpenStarlineResult();">Declare</button>');

            $.ajax({
                type: "POST",
                url: base_url + "get-decleare-starline-game-data",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.status == 'success') {
                        $("#result_div").show();
                        $("#open_number").val(data.open_number);
                        $("#open_result").val(data.open_result);
                        $("#id").val(data.id);

                        if (data.open_result != '') {
                            $("#openDecBtn").show();
                            $("#winner_btn").show();
                        }



                    }
                    $("#srchBtn").attr("disabled", false);
                    $("#srchBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#error").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#srchBtn").attr("disabled", false);
                    $("#srchBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });



    $("#galidisswarGameSrchFrm").submit(function(e) {
        var game_id = $("#game_id").val();
        var result_dec_date = $("#result_gali_dec_date").val();

        if (game_id == '') {
            $("#error").html(valid.error('Please select game name')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (result_dec_date == '') {
            $("#error").html(valid.error('Please select declare date')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#result_div").hide();
            $("#openDecBtn").hide();

            $("#srchBtn").attr("disabled", true);
            var changeBtn = $("#srchBtn").html();
            $("#srchBtn").html("Searching..");

            $("#open_div_msg").html('<label>&nbsp;</label><button type="button" class="btn btn-primary waves-light mr-1" id="openSaveBtn" name="openSaveBtn" onclick="OpenSaveGalidisswarGameData();">Save</button>&nbsp;&nbsp;<button type="button" class="btn btn-warning waves-light mr-1 display_none"  onclick="OpenGaliResultWinner();" id="winner_btn">Show Winner</button>&nbsp;&nbsp;<button type="button" class="btn btn-primary waves-light mr-1 display_none" id="openDecBtn" name="openDecBtn" onclick="decleareOpenGalidisswarResult();">Declare</button>');

            $.ajax({
                type: "POST",
                url: base_url + "get-decleare-galidisswar-game-data",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.status == 'success') {
                        $("#result_div").show();
                        $("#open_number").val(data.open_number);
                        $("#open_result").val(data.open_result);
                        $("#id").val(data.id);

                        if (data.open_result != '') {
                            $("#openDecBtn").show();
                            $("#winner_btn").show();
                        }


                        /*	if(data.open_decleare_status==1)
                        	{
                        		$("#open_div_msg").html('<badge class="badge badge-success">Result Decleared on '+data.open_decleare_date+'</badge>');
                        		$("#open_div_msg").append('<button type="button" class="btn btn-danger waves-light m-t-10"  onclick="OpenDeleteGalidisswarResultConfirmData();">Delete Result</button>');
                        	}*/
                        getGaliDissawarResultOnChangeEvent();

                    }
                    $("#srchBtn").attr("disabled", false);
                    $("#srchBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#error").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#srchBtn").attr("disabled", false);
                    $("#srchBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });



    $("#walletReportFrm").submit(function(e) {


        var user_name = $("#user_name").val();
        var start_date = $("#start_date").val();
        var last_date = $("#last_date").val();


        if (user_name == "") {
            valid.snackbar_error('Please Select User Name');
        } else if (start_date == "") {
            valid.snackbar_error('Please select start date');
        } else if (last_date == "") {
            valid.snackbar_error('Please select last date');
        } else {
            $("#submitBtn").attr("disabled", true);
            var changeBtn = $("#submitBtn").html();
            $("#submitBtn").html("Getting..");

            $.ajax({
                type: "POST",
                url: base_url + "get-wallet-report",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.status == 'success') {
                        $("#myTable").dataTable().fnClearTable();
                        $('#myTable').dataTable().fnDestroy();
                        $("#wallet_report_list").html(data.listData);
                        var table = $('#myTable').DataTable({
                            rowReorder: {
                                selector: 'td:nth-child(2)'
                            },
                            responsive: true,
                            dom: 'Bfrtip',
                            buttons: [{
                                    extend: 'excelHtml5',
                                    title: ' Wallet Report (' + exceldate + ')',
                                    footer: true,
                                    exportOptions: {
                                        columns: '',
                                        stripNewlines: false
                                    },
                                    orientation: 'landscape',
                                    pageSize: 'LEGAL',
                                },
                                {
                                    extend: 'pageLength',
                                    footer: false,
                                    exportOptions: {
                                        stripNewlines: false
                                    }
                                },

                            ],
                            lengthMenu: [
                                [10, 25, 50, -1],
                                [10, 25, 50, 'All']
                            ],
                            iDisplayLength: 10,
                        });
                        $('#myTable').DataTable();
                    } else {
                        valid.snackbar_error(data.msg);
                    }
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    valid.snackbar_error(msg);
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                }


            });
        }
        e.preventDefault();
    });
    $("#starlineWinningReportFrm").submit(function(e) {
        var result_date = $("#result_date").val();
        var market_status = $("#win_market_status").val();
        var game_name = $("#win_game_name").val();

        if (result_date == '') {
            $("#error_msg").html(valid.error('Please select date')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (game_name == '') {
            $("#error_msg").html(valid.error('Please select game')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (market_status == '') {
            $("#error_msg").html(valid.error('Please select market time')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#submitBtn").attr("disabled", true);
            var changeBtn = $("#submitBtn").html();
            $("#submitBtn").html("Submitting..");
            $("#result_date").val(result_date);
            $('#resultHistory tbody').empty();
            $.ajax({
                type: "POST",
                url: base_url + "get-starline-winning-report",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.getResultHistory != '') {
                        $.each(data.getResultHistory, function(key, val) {
                            if (val.pana == 'Single Digit') {
                                if (val.session == 'Open') {
                                    var open_digit = val.digits;
                                    var close_digit = 'N/A';
                                    var open_pana = 'N/A';
                                    var close_pana = 'N/A';
                                }

                            }



                            if (val.pana == 'Single Pana' || val.pana == 'Double Pana' || val.pana == 'Triple Pana') {
                                if (val.session == 'Open') {
                                    var a = val.digits[0];
                                    var b = val.digits[1];
                                    var c = val.digits[2];

                                    var total_digits = parseInt(a) + parseInt(b) + parseInt(c);
                                    if (total_digits < 10) {
                                        var digits = total_digits;
                                    } else if (total_digits > 9) {
                                        var digits = total_digits % 10;
                                    }
                                    var open_digit = digits;
                                    var close_digit = 'N/A';
                                    var open_pana = val.digits;
                                    var close_pana = 'N/A';
                                }

                            }





                            $("#result_data").append('<tr><td><a href="' + base_url + admin + '/view-user/' + val.user_id + '">' + val.user_name + '</a></td><td>' + val.game_name + '</td><td>' + val.pana + '</td><td>' + open_pana + '</td><td>' + open_digit + '</td><td>' + val.points + '</td><td>' + val.amount + '</td><td>' + val.tx_request_number + '</td><td>' + val.insert_date + '</td></tr>');
                        });
                    } else {
                        $("#result_data").append('<tr><td colspan=7 class="table_text_align">No Data Available</td></tr>');
                    }
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#error_msg").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });


    $("#galidisswarWinningReportFrm").submit(function(e) {
        var result_date = $("#result_date").val();
        var market_status = $("#win_market_status").val();
        var game_name = $("#win_game_name").val();

        if (result_date == '') {
            $("#error_msg").html(valid.error('Please select date')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (game_name == '') {
            $("#error_msg").html(valid.error('Please select game')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (market_status == '') {
            $("#error_msg").html(valid.error('Please select market time')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#submitBtn").attr("disabled", true);
            var changeBtn = $("#submitBtn").html();
            $("#submitBtn").html("Submitting..");
            $("#result_date").val(result_date);
            $('#resultHistory tbody').empty();
            $.ajax({
                type: "POST",
                url: base_url + "get-galidisswar-winning-report",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.getResultHistory != '') {
                        $.each(data.getResultHistory, function(key, val) {




                            $("#result_data").append('<tr><td><a href="' + base_url + admin + '/view-user/' + val.user_id + '">' + val.user_name + '</a></td><td>' + val.game_name + '</td><td>' + val.pana + '</td><td>' + val.digits + '</td><td>' + val.points + '</td><td>' + val.amount + '</td><td>' + val.tx_request_number + '</td><td>' + val.insert_date + '</td></tr>');
                        });
                    } else {
                        $("#result_data").append('<tr><td colspan=7 class="table_text_align">No Data Available</td></tr>');
                    }
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#error_msg").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });



    $(document).on('submit', '#quesFrm', function(e) {

        var ques_title = $("#ques_title").val();
        var ques_ans = $("#ques_ans").val();


        if (ques_title == '') {

            $("#errormsg").html(valid.error('Please Enter Question')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (ques_ans == '') {
            $("#errormsg").html(valid.error('Please Enter Answer')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {


            $("#submitBtn").attr("disabled", true);
            var changeBtn = $("#submitBtn").html();
            $("#submitBtn").html("Submitting..");
            $.ajax({
                type: "POST",
                url: base_url + admin + "/submit-ques",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.status == 'success') {
                        $("#errormsg").html(data.msg);
                        $("#errormsg").fadeIn('slow').delay(5000).fadeOut('slow');
                        $('#quesFrm')[0].reset();

                        /* window.setTimeout(function(){$('#addHsnModal').modal('hide')}, 1000); */
                        dataTable.ajax.reload();
                    } else if (data.status == 'update') {
                        $("#errormsg").html(data.msg);
                        $("#errormsg").fadeIn('slow').delay(5000).fadeOut('slow');
                        /* window.setTimeout(function(){$('#addHsnModal').modal('hide')}, 1000); */
                        dataTable.ajax.reload();
                    } else {
                        $("#errormsg").html(data.msg);
                        $("#errormsg").fadeIn('slow').delay(5000).fadeOut('slow');
                    }
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#errormsg").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                }
            });
        }

        e.preventDefault();
    });



    $(document).on('submit', '#sendMsgFrm', function(e) {


        var admin_msg = $("#admin_msg").val();
        var file = $("#file").val();


        /*  if(admin_msg == ''){
        	 
        		$("#errormsg").html(valid.error('Please Enter Msg')).fadeIn('slow').delay(2500).fadeOut('slow');
        	} 
        	 else
        	 {
          */

        $("#submitBtn").attr("disabled", true);
        var changeBtn = $("#submitBtn").html();
        $("#submitBtn").html("Submitting..");
        $.ajax({
            type: "POST",
            url: base_url + admin + "/submit-admin-msg",
            data: new FormData(this),
            processData: false,
            contentType: false,
            dataType: "json",
            success: function(data) {


                if (data.status == 'success') {


                    $(".name1").html(data.username);
                    $(".id").html("<input type='hidden' name='user_id1' id='user_id1' value='" + data.user_id + "'>");
                    $(".msg_data").html(data.listData);
                    $('.scrolldown').scrollTop($('.scrolldown')[0].scrollHeight);
                    $("#errormsg").html(data.msg);
                    $("#errormsg").fadeIn('slow').delay(5000).fadeOut('slow');
                    $('#sendMsgFrm')[0].reset();


                } else if (data.status == 'update') {
                    $("#errormsg").html(data.msg);
                    $("#errormsg").fadeIn('slow').delay(5000).fadeOut('slow');

                } else {
                    $("#errormsg").html(data.msg);
                    $("#errormsg").fadeIn('slow').delay(5000).fadeOut('slow');
                }
                $("#submitBtn").attr("disabled", false);
                $("#submitBtn").html(changeBtn);
            },
            error: function(jqXHR, exception) {
                var msg = valid.ajaxError(jqXHR, exception);
                $("#errormsg").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                $("#submitBtn").attr("disabled", false);
                $("#submitBtn").html(changeBtn);
            }
        });
        /* 	 */

        e.preventDefault();
    });



    $(document).on('click', '.openpopupeditbid', function(e) {

        var dataURL = $(this).attr('data-href');
        $('.modal_update_edibid').load(dataURL, function() {
            $('#updatebidModal').modal({
                show: true
            });

        });

    });

    $(document).on('click', '.openPopupAddques', function(e) {

        var dataURL = $(this).attr('data-href');
        $('.batch_body').load(dataURL, function() {
            $('#addQuesModal').modal({
                show: true
            });

        });

    });


    $(document).on('click', '.openPopupViewques', function(e) {

        var dataURL = $(this).attr('data-href');
        $('.batch_body').load(dataURL, function() {
            $('#addQuesViewModal').modal({
                show: true
            });

        });

    });

    $(document).on('click', '.openpopupeditbid', function(e) {

        var dataURL = $(this).attr('data-href');
        $('.modal_update_edibid').load(dataURL, function() {
            $('#updatebidModal').modal({
                show: true
            });

        });

    });
    $(document).on('click', '.openpopupeditbidhistory', function(e) {

        var dataURL = $(this).attr('data-href');
        $('.modal_update_edibidhistory').load(dataURL, function() {
            $(".select2").select2();
            $('#updatebidhistoryModal').modal({
                show: true
            });

        });

    });

    $(document).on('click', '.openpopupeditgalidissawarbidhistory', function(e) {

        var dataURL = $(this).attr('data-href');
        $('.modal_update_edibidhistory').load(dataURL, function() {
            $(".select2").select2();
            $('#updatebidhistoryModal').modal({
                show: true
            });

        });

    });








    $(document).on("keyup", "#user_search", function(e) {

        var value = document.getElementById('user_search').value;

        $(".search_data").html("");
        $.ajax({
            url: base_url + 'get-user-search',
            type: 'POST',
            data: {
                value: value
            },
            dataType: "json",
            success: function(data) {
                console.log(data);
                if (data.getData != '') {
                    var listData = '';
                    $.each(data.getData, function(key, val) {
                        listData += '<a href="javascript:void(0);" class="user_data" onclick="getUserData(' + val.user_id + ')"><li class="clearfix remove_color color_bg' + val.user_id + '" style="margin-bottom:10px; padding: 5px 5px 5px 5px;"><img class="rounded-circle user-image" src="' + base_url + '/adminassets/images/user_icon.png" alt=""><div class="about"><div class="name">' + val.user_name + '</div><div class="status">' + val.mobile + '</div></div></li></a>';

                    });


                    $(".search_data").html(listData);
                } else {
                    $(".search_data").html("<li class='clearfix'>No Result Found</li>");
                }
            },
            error: function(jqXHR, exception) {

                var msg = valid.ajaxError(jqXHR, exception);
                valid.snackbar_error((msg));
            }
        });

    });

    $(document).on('click', '.openPopupAddRouletteGame', function(e) {

        var dataURL = $(this).attr('data-href');
        $('.batch_body').load(dataURL, function() {
            $('#addRouletteGameModal').modal({
                show: true
            });

        });

    });


    $(document).on('submit', '#addroulettegameFrm', function(e) {
        var game_name = $("#game_name").val();
        var open_time = $("#open_time").val();
        var close_time = $("#close_time").val();

        if (game_name == '') {
            $("#msg").html(valid.error('Please enter game name')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (open_time == '') {
            $("#msg").html(valid.error('Please enter open time ')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (close_time == '') {
            $("#msg").html(valid.error('Please enter close time')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#submitBtn").attr("disabled", true);
            var changeBtn = $("#submitBtn").html();
            $("#submitBtn").html("Submitting..");
            $.ajax({
                type: "POST",
                url: base_url + "submit-roulette-game",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.status == 'success') {
                        $("#msg").html(data.msg);
                        $("#msg").fadeIn('slow').delay(5000).fadeOut('slow');
                        $('#addroulettegameFrm')[0].reset();
                        dataTable.ajax.reload();
                    } else {
                        $("#msg").html(data.msg);
                        $("#msg").fadeIn('slow').delay(5000).fadeOut('slow');
                        dataTable.ajax.reload();
                    }
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#msg").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });


    $("#getRouletteBidHistoryFrm").submit(function(e) {
        var bid_date = $("#bid_date").val();
        var game_name = $("#game_name").val();
        if (bid_date == '') {
            $("#error_msg").html(valid.error('Please select date')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (game_name == '') {
            $("#error_msg").html(valid.error('Please select game name')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#submitBtn").attr("disabled", true);
            var changeBtn = $("#submitBtn").html();
            $("#submitBtn").html("Submitting..");
            $("#bidHistory_date").val(bid_date);
            $("#bid_game_name").val(game_name);
            $('#bidHistory tbody').empty();
            $.ajax({
                type: "POST",
                url: base_url + "get-roulette-bid-history-data",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.getBidHistory != '') {
                        $('#export_btn').show();
                        $.each(data.getBidHistory, function(key, val) {
                            $("#roulette_bid_data").append('<tr><td><a href="' + base_url + admin + '/view-user/' + val.user_id + '">' + val.user_name + '</a></td><td>' + val.bid_tx_id + '</td><td>' + val.game_name + '</td><td>' + val.digits + '</td><td>' + val.points + '</td></tr>');
                        });
                    } else {
                        $('#export_btn').hide();
                        $("#roulette_bid_data").append('<tr><td colspan="10" class="table_text_align">No Data Available</td></tr>');
                    }
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#error_msg").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });


    $("#rouletteWinningReportFrm").submit(function(e) {
        var result_date = $("#result_date").val();
        var game_name = $("#win_game_name").val();

        if (result_date == '') {
            $("#error_msg").html(valid.error('Please select date')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (game_name == '') {
            $("#error_msg").html(valid.error('Please select game')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#submitBtn").attr("disabled", true);
            var changeBtn = $("#submitBtn").html();
            $("#submitBtn").html("Submitting..");
            $("#result_date").val(result_date);
            $('#resultHistory tbody').empty();
            $.ajax({
                type: "POST",
                url: base_url + "get-roulette-winning-report",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.getResultHistory != '') {
                        $.each(data.getResultHistory, function(key, val) {
                            $("#result_data").append('<tr><td><a href="' + base_url + admin + '/view-user/' + val.user_id + '">' + val.user_name + '</a></td><td>' + val.game_name + '</td><td>' + val.digits + '</td><td>' + val.points + '</td><td>' + val.amount + '</td><td>' + val.tx_request_number + '</td><td>' + val.insert_date + '</td></tr>');
                        });
                    } else {
                        $("#result_data").append('<tr><td colspan=7 class="table_text_align">No Data Available</td></tr>');
                    }
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#error_msg").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });

    $(document).on('change', '.getDigitOpenResult', function(e) {
        var open_number = $(this).val();
        $.ajax({
            type: "POST",
            url: base_url + "get-open-data",
            data: {
                open_number: open_number
            },
            dataType: "json",
            success: function(data) {
                if (data.status == 'success') {
                    $("#open_result").val(data.open_result);
                    $("#error2").html(data.msg).fadeIn('slow').delay(2500).fadeOut('slow');

                }


            },
            error: function(jqXHR, exception) {
                var msg = valid.ajaxError(jqXHR, exception);
                $("#error2").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');

            }
        });
        e.preventDefault();
    });

    $(document).on('change', '.getDigitCloseResult', function(e) {
        var close_number = $(this).val();
        $.ajax({
            type: "POST",
            url: base_url + "get-close-data",
            data: {
                close_number: close_number
            },
            dataType: "json",
            success: function(data) {
                if (data.status == 'success') {
                    $("#close_result").val(data.close_result);
                    $("#error2").html(data.msg).fadeIn('slow').delay(2500).fadeOut('slow');

                }


            },
            error: function(jqXHR, exception) {
                var msg = valid.ajaxError(jqXHR, exception);
                $("#error2").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');

            }
        });
        e.preventDefault();
    });

    $("#transferReportFrm").submit(function(e) {
        var transfer_date = $("#transfer_date").val();

        if (transfer_date == '') {
            $("#error_msg").html(valid.error('Please select date')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#submitBtn").attr("disabled", true);
            var changeBtn = $("#submitBtn").html();
            $("#submitBtn").html("Submitting..");
            $.ajax({
                type: "POST",
                url: base_url + "get-transfer-report",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.status == 'success') {
                        if (data.listData != "") {
                            if ($.fn.DataTable.isDataTable('#resultHistory')) {
                                $('#resultHistory').DataTable().destroy();
                            }
                            $('#resultHistory tbody').empty();
                            $(".totalamt").html(data.total_amt);
                            $("#transfer_data").html(data.listData);
                            $('#resultHistory').DataTable();
                        } else {
                            $(".totalamt").html("0");
                            $("#transfer_data").html("<tr><td>No Data Available</td></tr>");
                            $('#myTable').DataTable();

                        }
                    } else {
                        valid.snackbar_error(data.msg);
                    }
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#error_msg").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });

    $("#stralineWinningpredictFrm").submit(function(e) {
        var game_id = $("#win_game_name").val();
        var result_date = $("#result_date").val();
        var winning_ank = $("#winning_ank").val();
        if (game_id == '') {
            $("#error").html(valid.error('Please select game name')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (result_date == '') {
            $("#error").html(valid.error('Please select  date')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (winning_ank == '') {
            $("#error").html(valid.error('Please Enter Open Number')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#submitBtn").attr("disabled", true);
            var changeBtn = $("#submitBtn").html();
            $("#submitBtn").html("Submiting..");
            $("#winner_result_data").html('');
            $("#t_bid").text('');
            $("#t_winneing_amt").text('');

            $.ajax({
                type: "POST",
                url: base_url + "get-starline-winner-list",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.winner_list != '') {

                        $("#t_bid").text(data.points_amt_sum);
                        $("#t_winneing_amt").text(data.win_amt_sum);
                        var i = 1;

                        $.each(data.winner_list, function(key, val) {

                            $("#winner_result_data").append('<tr><td>' + i + '</td><td><a href="' + base_url + admin + '/view-user/' + val.user_id + '" target="blank">' + val.user_name + '</a></td><td>' + val.points + '</td><td>' + val.win_amt + '</td><td>' + val.pana + '</td><td>' + val.bid_tx_id + '</td></tr>');
                            i++;
                        });

                    } else {
                        $("#winner_result_data").html('<tr><td colspan="5" class="table_text_align">No Data Available</td></tr>');
                    }

                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#error").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });

    $("#galidissawarWinningpredictFrm").submit(function(e) {
        var game_id = $("#win_game_name").val();
        var result_date = $("#result_date").val();
        var winning_ank = $("#winning_ank").val();
        if (game_id == '') {
            $("#error").html(valid.error('Please select game name')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (result_date == '') {
            $("#error").html(valid.error('Please select  date')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (winning_ank == '') {
            $("#error").html(valid.error('Please Enter Open Number')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#submitBtn").attr("disabled", true);
            var changeBtn = $("#submitBtn").html();
            $("#submitBtn").html("Submiting..");
            $("#winner_result_data").html('');
            $("#t_bid").text('');
            $("#t_winneing_amt").text('');

            $.ajax({
                type: "POST",
                url: base_url + "get-galidissawar-winner-list",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.winner_list != '') {

                        $("#t_bid").text(data.points_amt_sum);
                        $("#t_winneing_amt").text(data.win_amt_sum);
                        var i = 1;

                        $.each(data.winner_list, function(key, val) {

                            $("#winner_result_data").append('<tr><td>' + i + '</td><td><a href="' + base_url + admin + '/view-user/' + val.user_id + '" target="blank">' + val.user_name + '</a></td><td>' + val.points + '</td><td>' + val.win_amt + '</td><td>' + val.pana + '</td><td>' + val.bid_tx_id + '</td></tr>');
                            i++;
                        });

                    } else {
                        $("#winner_result_data").html('<tr><td colspan="5" class="table_text_align">No Data Available</td></tr>');
                    }

                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#error").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });


    $("#referralUserAmountFrm").submit(function(e) {
        var check_date = $("#check_date").val();
        if (check_date == '') {
            $("#error").html(valid.error('Please select date')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#submitBtn").attr("disabled", true);
            var changeBtn = $("#submitBtn").html();
            $("#submitBtn").html("Submitting..");
            $.ajax({
                type: "POST",
                url: base_url + "get-referral-amount-history",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.status == 'success') {
                        $("#referral_deposit_data_1").html(data.list_data);
                        //$('#referralTable').DataTable();
                    } else {
                        $("#error").html(data.msg).fadeIn('slow').delay(2500).fadeOut('slow');
                    }
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#error_msg").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });

    $("#bidRevertFrm").submit(function(e) {
        var game_id = $("#win_game_name").val();
        var bid_revert_date = $("#bid_revert_date").val();
        if (game_id == '') {
            $("#error").html(valid.error('Please select game name')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (bid_revert_date == '') {
            $("#error").html(valid.error('Please select  date')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#submitBtn").attr("disabled", true);
            var changeBtn = $("#submitBtn").html();
            $("#submitBtn").html("Submitting..");
            $.ajax({
                type: "POST",
                url: base_url + "get-bid-revert-data",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.status == 'success') {
                        if (data.listData != '') {
                            $("#bid_result_data").html(data.listData);
                            $('#bidRevertTable').DataTable();
                            $('.clear_btn').show();
                        } else {
                            $('.clear_btn').hide();
                            $("#bid_result_data").html(data.listData);
                            $('#bidRevertTable').DataTable();
                        }
                    } else {
                        valid.snackbar_error(data.msg);
                    }
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#error_msg").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });


    $("#autoDepositeFrm").submit(function(e) {
        var bid_revert_date = $("#bid_revert_date").val();
        if (bid_revert_date == '') {
            $("#error").html(valid.error('Please select date')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#submitBtn").attr("disabled", true);
            var changeBtn = $("#submitBtn").html();
            $("#submitBtn").html("Submitting..");
            $.ajax({
                type: "POST",
                url: base_url + "get-auto-deposite-history",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.status == 'success') {
                        $("#auto_deposite_data_1").html(data.list_data);
                        $('#autoDepositeTable').DataTable();
                    } else {
                        $("#error").html(data.msg).fadeIn('slow').delay(2500).fadeOut('slow');
                    }
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#error_msg").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });



    $("#appMaintainenceFrm").submit(function(e) {
        var app_maintainence_msg = $("#app_maintainence_msg").val();

        if (app_maintainence_msg == '') {
            $("#error_maintainence").html(valid.error('Please enter app maintainence msg')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#submitBtnAppMaintainece").attr("disabled", true);
            var changeBtn = $("#submitBtnAppMaintainece").html();
            $("#submitBtnAppMaintainece").html("Submitting..");
            $.ajax({
                type: "POST",
                url: base_url + "add-app-maintainence",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.status == 'success') {
                        $("#error_maintainence").html(data.msg);
                        $("#error_maintainence").fadeIn('slow').delay(5000).fadeOut('slow');
                    } else {
                        $("#error_maintainence").html(data.msg);
                        $("#error_maintainence").fadeIn('slow').delay(5000).fadeOut('slow');
                    }
                    $("#submitBtnAppMaintainece").attr("disabled", false);
                    $("#submitBtnAppMaintainece").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#error_maintainence").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#submitBtnAppMaintainece").attr("disabled", false);
                    $("#submitBtnAppMaintainece").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });


    /*	$(document).on('submit','#bidWinningReportFrm',function(e) 
    	{ */

    $("#bidWinningReportFrm").submit(function(e) {

        var result_date = $("#result_date").val();
        var win_game_name = $("#win_game_name").val();

        if (result_date == '') {
            $("#error_msg").html(valid.error('Please select date')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else if (win_game_name == '') {
            $("#error_msg").html(valid.error('Please select game name')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#submitBtn").attr("disabled", true);
            var changeBtn = $("#submitBtn").html();
            $("#submitBtn").html("Submitting..");
            $("#total_bid_amt").html('<i class="bx bx-rupee"></i>' + 0);
            $("#total_win_amt").html('<i class="bx bx-rupee"></i>' + 0);
            $("#total_profit_amt").html('<i class="bx bx-rupee"></i>' + 0);
            $.ajax({
                type: "POST",
                url: base_url + "get-bid-winning-report-details",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.status == 'success') {
                        $("#total_data").show();
                        $("#total_bid_amt").html('<i class="bx bx-rupee"></i>' + data.total_bid);
                        $("#total_win_amt").html('<i class="bx bx-rupee"></i>' + data.total_win);

                        if (data.total_profit == 0 && data.total_loss == 0) {
                            $(".bid_history_area").html('');
                            $(".smile_box").html('');
                        } else {
                            if (data.total_profit != 0) {
                                $(".bid_history_area").html('<div class="bid_history_box bhb_profit_amt"><div class="row"><div class="col-md-6"><h5 class="text-muted font-weight-medium" id="profit_loss">Total Profit Amount</h5></div><div class="col-md-3"><h5 class="mb-0" id="total_profit_amt"><i class="bx bx-rupee"></i>' + data.total_profit + '</h5></div><div class="col-md-3"></div></div></div>');

                                $(".smile_box").html('<img src="' + base_url + 'adminassets/images/smile.png">');
                                /*$("#profit_loss").html('Total Profit Amount');
                                $("#total_profit_amt").html('<i class="bx bx-rupee"></i>'+data.total_profit);*/
                            }
                            if (data.total_loss != 0) {
                                $(".bid_history_area").html('<div class="bid_history_box bhb_lose_amt"><div class="row"><div class="col-md-6"><h5 class="text-muted font-weight-medium" id="profit_loss">Total Loss Amount</h5></div><div class="col-md-3"><h5 class="mb-0" id="total_profit_amt"><i class="bx bx-rupee"></i>' + data.total_loss + '</h5></div><div class="col-md-3"></div></div></div>');
                                $(".smile_box").html('<img src="' + base_url + 'adminassets/images/sad.png">');

                                /*$("#profit_loss").html('Total Loss Amount');
                                $("#total_profit_amt").html('<i class="bx bx-rupee"></i>'+data.total_loss);*/
                            }
                        }

                    } else {
                        $("#error_msg").html(data.msg);
                        $("#error_msg").fadeIn('slow').delay(5000).fadeOut('slow');
                    }
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#error_msg").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });

    /* $(document).on('submit','#withdrawReportFrm',function(e) 
    {*/
    $("#withdrawReportFrm").submit(function(e) {
        var withdraw_date = $("#withdraw_date").val();

        if (withdraw_date == '') {
            $("#error_msg").html(valid.error('Please select date')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#submitBtn").attr("disabled", true);
            var changeBtn = $("#submitBtn").html();
            $("#submitBtn").html("Submitting..");
            $("#t_withdraw_amt").html(0);
            $("#t_accept_reqts").html(0);
            $("#t_reject_reqts").html(0);
            $.ajax({
                type: "POST",
                url: base_url + "get-withdraw-report-details",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(data) {
                    if (data.status == 'success') {
                        if ($.fn.DataTable.isDataTable('#withdrawList')) {
                            $('#withdrawList').DataTable().destroy();
                        }
                        $('#withdrawList tbody').empty();
                        $("#withdraw_data").html(data.list_data);
                        $('#withdrawList').DataTable();
                        if (data.list_data != '') {
                            $("#t_withdraw_amt").html(data.withdraw_amt);
                            $("#t_accept_reqts").html(data.total_accept);
                            $("#t_reject_reqts").html(data.total_reject);
                            $("#withdraw_data_details").show();
                        }
                    } else {
                        $("#error_msg").html(data.msg);
                        $("#error_msg").fadeIn('slow').delay(5000).fadeOut('slow');
                    }
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                },
                error: function(jqXHR, exception) {
                    var msg = valid.ajaxError(jqXHR, exception);
                    $("#error_msg").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                }
            });
        }
        e.preventDefault();
    });

    /*$(document).on('submit','#clearDataFrm',function(e) 
    {*/

    $("#clearDataFrm").submit(function(e) {
        var result_date = $("#result_date").val();
        if (result_date == '') {
            $("#error_msg").html(valid.error('Please select date')).fadeIn('slow').delay(2500).fadeOut('slow');
        } else {
            $("#submitBtn").attr("disabled", true);
            var changeBtn = $("#submitBtn").html();
            $("#submitBtn").html("Submitting..");
            $.ajax({
                type: "POST",
                url: base_url + "clear-data-date-wise",
                data: {
                    result_date: result_date
                },
                success: function(data) {
                    window.location = base_url + 'clear-data-date-wise/' + result_date;
                    $("#submitBtn").attr("disabled", false);
                    $("#submitBtn").html(changeBtn);
                },
            });
        }
        e.preventDefault();
    });



});

function OpenSaveData() {
    var open_number = $("#open_number").val();
    var result_dec_date = $("#result_dec_date").val();
    var game_id = $("#game_id").val();
    var id = $("#id").val();

    if (open_number == '') {
        $("#error2").html(valid.error('Please enter open number')).fadeIn('slow').delay(2500).fadeOut('slow');
    } else if (open_number.length != 3) {
        $("#error2").html(valid.error('Please enter 3 digit number')).fadeIn('slow').delay(2500).fadeOut('slow');
    } else {
        $("#openSaveBtn").attr("disabled", true);
        var changeBtn = $("#openSaveBtn").html();
        $("#openSaveBtn").html("Sending..");
        $.ajax({
            type: "POST",
            url: base_url + "save-open-data",
            data: {
                open_number: open_number,
                game_id: game_id,
                id: id,
                result_dec_date: result_dec_date
            },
            dataType: "json",
            success: function(data) {
                if (data.status == 'success') {
                    resultHistoryListLoadData();
                    $("#open_result").val(data.open_result);
                    $("#error2").html(data.msg).fadeIn('slow').delay(2500).fadeOut('slow');
                    $("#winner_btn").show();
                }

                $("#openDecBtn").show();

                $("#openSaveBtn").attr("disabled", false);
                $("#openSaveBtn").html(changeBtn);
            },
            error: function(jqXHR, exception) {
                var msg = valid.ajaxError(jqXHR, exception);
                $("#error2").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                $("#openSaveBtn").attr("disabled", false);
                $("#openSaveBtn").html(changeBtn);
            }
        });
    }
}



function OpenSaveStarlineGameData() {
    var open_number = $("#open_number").val();
    var result_dec_date = $("#starline_result_dec_date").val();
    var game_id = $("#game_id").val();
    var id = $("#id").val();

    if (open_number == '') {
        $("#error2").html(valid.error('Please enter  number')).fadeIn('slow').delay(2500).fadeOut('slow');
    } else if (open_number.length != 3) {
        $("#error2").html(valid.error('Please enter 3 digit number')).fadeIn('slow').delay(2500).fadeOut('slow');
    } else {
        $("#openSaveBtn").attr("disabled", true);
        var changeBtn = $("#openSaveBtn").html();
        $("#openSaveBtn").html("Sending..");
        $.ajax({
            type: "POST",
            url: base_url + "save-open-starline-game-data",
            data: {
                open_number: open_number,
                game_id: game_id,
                id: id,
                result_dec_date: result_dec_date
            },
            dataType: "json",
            success: function(data) {
                if (data.status == 'success') {
                    $("#open_result").val(data.open_result);
                    $("#error2").html(data.msg).fadeIn('slow').delay(2500).fadeOut('slow');
                    getStarlineResultOnChangeEvent();
                }
                $("#openDecBtn").show();
                $("#openSaveBtn").attr("disabled", false);
                $("#openSaveBtn").html(changeBtn);
            },
            error: function(jqXHR, exception) {
                var msg = valid.ajaxError(jqXHR, exception);
                $("#error2").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                $("#openSaveBtn").attr("disabled", false);
                $("#openSaveBtn").html(changeBtn);
            }
        });
    }
}


function OpenSaveGalidisswarGameData() {
    var open_number = $("#open_number").val();
    var result_dec_date = $("#result_gali_dec_date").val();
    var game_id = $("#game_id").val();
    var id = $("#id").val();
    //	alert(open_number);
    if (open_number == '') {
        $("#error2").html(valid.error('Please enter  number')).fadeIn('slow').delay(2500).fadeOut('slow');
    } else if (open_number.length != 2) {
        $("#error2").html(valid.error('Please enter 2 digit number')).fadeIn('slow').delay(2500).fadeOut('slow');
    } else {
        $("#openSaveBtn").attr("disabled", true);
        var changeBtn = $("#openSaveBtn").html();
        $("#openSaveBtn").html("Sending..");
        $.ajax({
            type: "POST",
            url: base_url + "save-open-galidisswar-game-data",
            data: {
                open_number: open_number,
                game_id: game_id,
                id: id,
                result_dec_date: result_dec_date
            },
            dataType: "json",
            success: function(data) {
                if (data.status == 'success') {
                    $("#open_result").val(data.open_result);
                    $("#error2").html(data.msg).fadeIn('slow').delay(2500).fadeOut('slow');
                    getGaliDissawarResultOnChangeEvent();
                }
                $("#winner_btn").show();
                $("#openDecBtn").show();
                $("#openSaveBtn").attr("disabled", false);
                $("#openSaveBtn").html(changeBtn);
            },
            error: function(jqXHR, exception) {
                var msg = valid.ajaxError(jqXHR, exception);
                $("#error2").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                $("#openSaveBtn").attr("disabled", false);
                $("#openSaveBtn").html(changeBtn);
            }
        });
    }
}



function OpenResultWinner() {
    var open_number = $("#open_number").val();
    var game_id = $("#game_id").val();
    var result_dec_date = $("#result_dec_date").val();
    var id = $("#id").val();

    $("#winner_btn").attr("disabled", true);
    var changeBtn = $("#winner_btn").html();
    $("#winner_btn").html("Sending..");
    $("#winner_result_data").html('');
    $.ajax({
        type: "POST",
        url: base_url + "get-open-winner-list",
        data: {
            game_id: game_id,
            result_dec_date: result_dec_date
        },
        dataType: "json",
        success: function(data) {

            $("#winnerListModal").modal('show');
            $("#total_bid").text(data.points_amt_sum);
            $("#total_winneing_amt").text(data.win_amt_sum);
            var i = 1;
            if (data.winner_list != '') {
                $.each(data.winner_list, function(key, val) {

                    $("#winner_result_data").append('<tr><td>' + i + '</td><td><a href="' + base_url + admin + '/view-user/' + val.user_id + '" target="blank">' + val.user_name + '</a></td><td>' + val.points + '</td><td>' + val.win_amt + '</td><td>' + val.pana + '</td><td>' + val.bid_tx_id + '</td></tr>');
                    i++;
                });

            } else {
                $("#winner_result_data").append('<tr><td colspan="5" class="table_text_align">No Data Available</td></tr>');
            }

            $("#winner_btn").attr("disabled", false);
            $("#winner_btn").html(changeBtn);
        },
        error: function(jqXHR, exception) {
            var msg = valid.ajaxError(jqXHR, exception);
            $("#error2").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
            $("#winner_btn").attr("disabled", false);
            $("#winner_btn").html(changeBtn);
        }
    });
}


function OpenStarlineResultWinner() {
    var open_number = $("#open_number").val();
    var game_id = $("#game_id").val();
    var result_dec_date = $("#starline_result_dec_date").val();
    var id = $("#id").val();

    $("#winner_btn").attr("disabled", true);
    var changeBtn = $("#winner_btn").html();
    $("#winner_btn").html("Sending..");
    $("#winner_result_data").html('');
    $.ajax({
        type: "POST",
        url: base_url + "get-open-starline-winner-list",
        data: {
            game_id: game_id,
            result_dec_date: result_dec_date
        },
        dataType: "json",
        success: function(data) {

            $("#winnerListModal").modal('show');
            $("#total_bid").text(data.points_amt_sum);
            $("#total_winneing_amt").text(data.win_amt_sum);
            var i = 1;
            if (data.winner_list != '') {
                $.each(data.winner_list, function(key, val) {

                    $("#winner_result_data").append('<tr><td>' + i + '</td><td><a href="' + base_url + admin + '/view-user/' + val.user_id + '" target="blank">' + val.user_name + '</a></td><td>' + val.points + '</td><td>' + val.win_amt + '</td><td>' + val.pana + '</td><td>' + val.bid_tx_id + '</td></tr>');
                    i++;
                });

            } else {
                $("#winner_result_data").append('<tr><td colspan="5" class="table_text_align">No Data Available</td></tr>');
            }

            $("#winner_btn").attr("disabled", false);
            $("#winner_btn").html(changeBtn);
        },
        error: function(jqXHR, exception) {
            var msg = valid.ajaxError(jqXHR, exception);
            $("#error2").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
            $("#winner_btn").attr("disabled", false);
            $("#winner_btn").html(changeBtn);
        }
    });
}

function OpenGaliResultWinner() {
    var open_number = $("#open_number").val();
    var game_id = $("#game_id").val();
    var result_dec_date = $("#result_gali_dec_date").val();
    var id = $("#id").val();

    $("#winner_btn").attr("disabled", true);
    var changeBtn = $("#winner_btn").html();
    $("#winner_btn").html("Sending..");
    $("#winner_result_data").html('');
    $.ajax({
        type: "POST",
        url: base_url + "get-open-galidisswar-winner-list",
        data: {
            game_id: game_id,
            result_dec_date: result_dec_date
        },
        dataType: "json",
        success: function(data) {

            $("#winnerListModal").modal('show');
            $("#total_bid").text(data.points_amt_sum);
            $("#total_winneing_amt").text(data.win_amt_sum);
            var i = 1;
            if (data.winner_list != '') {
                $.each(data.winner_list, function(key, val) {

                    $("#winner_result_data").append('<tr><td>' + i + '</td><td><a href="' + base_url + admin + '/view-user/' + val.user_id + '" target="blank">' + val.user_name + '</a></td><td>' + val.points + '</td><td>' + val.win_amt + '</td><td>' + val.pana + '</td><td>' + val.bid_tx_id + '</td></tr>');
                    i++;
                });

            } else {
                $("#winner_result_data").append('<tr><td colspan="5" class="table_text_align">No Data Available</td></tr>');
            }

            $("#winner_btn").attr("disabled", false);
            $("#winner_btn").html(changeBtn);
        },
        error: function(jqXHR, exception) {
            var msg = valid.ajaxError(jqXHR, exception);
            $("#error2").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
            $("#winner_btn").attr("disabled", false);
            $("#winner_btn").html(changeBtn);
        }
    });
}



function CloseResultWinner() {
    var game_id = $("#game_id").val();
    var result_dec_date = $("#result_dec_date").val();
    var id = $("#id").val();

    $("#winner_close_btn").attr("disabled", true);
    var changeBtn = $("#winner_close_btn").html();
    $("#winner_close_btn").html("Sending..");
    $("#winner_result_data").html('');
    $.ajax({
        type: "POST",
        url: base_url + "get-close-winner-list",
        data: {
            game_id: game_id,
            result_dec_date: result_dec_date
        },
        dataType: "json",
        success: function(data) {

            $("#winnerListModal").modal('show');
            $("#total_bid").text(data.points_amt_sum);
            $("#total_winneing_amt").text(data.win_amt_sum);
            var i = 1;
            if (data.winner_list != '') {
                $.each(data.winner_list, function(key, val) {

                    $("#winner_result_data").append('<tr><td>' + i + '</td><td><a href="' + base_url + admin + '/view-user/' + val.user_id + '" target="blank">' + val.user_name + '</a></td><td>' + val.points + '</td><td>' + val.win_amt + '</td><td>' + val.pana + '</td><td>' + val.bid_tx_id + '</td></tr>');
                    i++;
                });

            } else {
                $("#winner_result_data").append('<tr><td colspan="5" class="table_text_align">No Data Available</td></tr>');
            }

            $("#winner_close_btn").attr("disabled", false);
            $("#winner_close_btn").html(changeBtn);
        },
        error: function(jqXHR, exception) {
            var msg = valid.ajaxError(jqXHR, exception);
            $("#error2").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
            $("#winner_close_btn").attr("disabled", false);
            $("#winner_close_btn").html(changeBtn);
        }
    });
}



function decleareOpenResult() {
    var open_number = $("#open_number").val();
    var game_id = $("#game_id").val();
    var result_dec_date = $("#result_dec_date").val();
    var id = $("#id").val();

    $("#openDecBtn").attr("disabled", true);
    var changeBtn = $("#openDecBtn").html();
    $("#openDecBtn").html("Sending..");
    $.ajax({
        type: "POST",
        url: base_url + "decleare-open-data",
        data: {
            game_id: game_id,
            result_dec_date: result_dec_date
        },
        dataType: "json",
        success: function(data) {
            if (data.status == 'success') {
                $("#error2").html(data.msg).fadeIn('slow').delay(2500).fadeOut('slow');
                if (data.open_decleare_status == 1) {
                    $("#open_div_msg").html('');
                    resultHistoryListLoadData();
                    /* $("#open_div_msg").html('<badge class="badge badge-success">Result Decleared on '+data.open_decleare_date+'</badge>');
                    $("#open_div_msg").append('<button type="button" class="btn btn-danger waves-light m-t-10"  onclick="OpenDeleteResultConfirmData();">Delete Result</button>'); */

                    $("#winner_btn").hide();
                }
            } else {
                $("#error2").html(data.msg).fadeIn('slow').delay(2500).fadeOut('slow');

            }
            $("#openDecBtn").attr("disabled", false);
            $("#openDecBtn").html(changeBtn);
        },
        error: function(jqXHR, exception) {
            var msg = valid.ajaxError(jqXHR, exception);
            $("#error2").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
            $("#openDecBtn").attr("disabled", false);
            $("#openDecBtn").html(changeBtn);
        }
    });
}


function decleareOpenStarlineResult() {
    var open_number = $("#open_number").val();
    var game_id = $("#game_id").val();
    var result_dec_date = $("#starline_result_dec_date").val();
    var id = $("#id").val();

    $("#openDecBtn").attr("disabled", true);
    var changeBtn = $("#openDecBtn").html();
    $("#openDecBtn").html("Sending..");
    $.ajax({
        type: "POST",
        url: base_url + "decleare-open-starline-data",
        data: {
            game_id: game_id,
            result_dec_date: result_dec_date
        },
        dataType: "json",
        success: function(data) {
            if (data.status == 'success') {
                $("#error2").html(data.msg).fadeIn('slow').delay(2500).fadeOut('slow');
                if (data.open_decleare_status == 1) {
                    /* $("#open_div_msg").html('<label>&nbsp;</label><badge class="badge badge-success">Result Decleared on '+data.open_decleare_date+'</badge>');
                    $("#open_div_msg").append('<button type="button" class="btn btn-danger waves-light m-t-10"  onclick="OpenDeleteStarlineResultConfirmData();">Delete Result</button>'); */
                    getStarlineResultOnChangeEvent();
                }
            } else {
                $("#error2").html(data.msg).fadeIn('slow').delay(2500).fadeOut('slow');

            }
            $("#openDecBtn").attr("disabled", false);
            $("#openDecBtn").html(changeBtn);
        },
        error: function(jqXHR, exception) {
            var msg = valid.ajaxError(jqXHR, exception);
            $("#error2").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
            $("#openDecBtn").attr("disabled", false);
            $("#openDecBtn").html(changeBtn);
        }
    });
}



function decleareOpenGalidisswarResult() {
    var open_number = $("#open_number").val();
    var game_id = $("#game_id").val();
    var result_dec_date = $("#result_gali_dec_date").val();
    var id = $("#id").val();

    $("#openDecBtn").attr("disabled", true);
    var changeBtn = $("#openDecBtn").html();
    $("#openDecBtn").html("Sending..");
    $.ajax({
        type: "POST",
        url: base_url + "decleare-open-galidisswar-data",
        data: {
            game_id: game_id,
            result_dec_date: result_dec_date
        },
        dataType: "json",
        success: function(data) {
            if (data.status == 'success') {
                $("#error2").html(data.msg).fadeIn('slow').delay(2500).fadeOut('slow');
                if (data.open_decleare_status == 1) {
                    /*	$("#open_div_msg").html('<badge class="badge badge-success">Result Decleared on '+data.open_decleare_date+'</badge>');
                    	$("#open_div_msg").append('<button type="button" class="btn btn-danger waves-light m-t-10"  onclick="OpenDeleteGalidisswarResultConfirmData();">Delete Result</button>');*/
                    $("#winner_btn").hide();
                    getGaliDissawarResultOnChangeEvent();
                }
            } else {
                $("#error2").html(data.msg).fadeIn('slow').delay(2500).fadeOut('slow');

            }
            $("#openDecBtn").attr("disabled", false);
            $("#openDecBtn").html(changeBtn);
        },
        error: function(jqXHR, exception) {
            var msg = valid.ajaxError(jqXHR, exception);
            $("#error2").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
            $("#openDecBtn").attr("disabled", false);
            $("#openDecBtn").html(changeBtn);
        }
    });
}



function OpenDeleteResultConfirmData(game_id) {
    $("#deleteConfirmOpenResutlt").modal('show');
    $("#delete_game_id").val(game_id);
}


function OpenDeleteStarlineResultConfirmData(game_id) {
    $("#delete_starline_game_id").val(game_id);
    $("#deleteConfirmOpenStarlineResutlt").modal('show');
}

function OpenDeleteGalidisswarResultConfirmData(game_id) {
    $("#deleteConfirmOpenGalidisswarResutlt").modal('show');
    $("#delete_gali_game_id").val(game_id);
}


function OpenDeleteResultData() {

    var game_id = $("#delete_game_id").val();
    var result_dec_date = $("#result_pik_date").val();
    var id = $("#id").val();

    $("#openDecBtn1").attr("disabled", true);
    var changeBtn = $("#openDecBtn1").html();
    $("#openDecBtn1").html("Sending..");
    $.ajax({
        type: "POST",
        url: base_url + "delete-open-result-data",
        data: {
            game_id: game_id,
            result_dec_date: result_dec_date
        },
        dataType: "json",
        success: function(data) {
            if (data.status == 'success') {
                $("#error2").html(data.msg).fadeIn('slow').delay(2500).fadeOut('slow');
                $("#deleteConfirmOpenResutlt").modal('hide');
                $("#error2").html(data.msg).fadeIn('slow').delay(2500).fadeOut('slow');
                resultHistoryListLoadData();
                $("#gameSrchFrm").submit();
            } else {
                $("#error2").html(data.msg).fadeIn('slow').delay(2500).fadeOut('slow');

            }
            $("#openDecBtn1").attr("disabled", false);
            $("#openDecBtn1").html(changeBtn);
        },
        error: function(jqXHR, exception) {
            var msg = valid.ajaxError(jqXHR, exception);
            $("#error2").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
            $("#openDecBtn1").attr("disabled", false);
            $("#openDecBtn1").html(changeBtn);
        }
    });
}



function OpenDeleteStarlineResultData() {

    var game_id = $("#delete_starline_game_id").val();
    var result_dec_date = $("#starline_result_dec_date").val();
    var id = $("#id").val();

    $("#openDecBtn1").attr("disabled", true);
    var changeBtn = $("#openDecBtn1").html();
    $("#openDecBtn1").html("Sending..");
    $.ajax({
        type: "POST",
        url: base_url + "delete-open-starline-result-data",
        data: {
            game_id: game_id,
            result_dec_date: result_dec_date
        },
        dataType: "json",
        success: function(data) {
            if (data.status == 'success') {
                $("#error2").html(data.msg).fadeIn('slow').delay(2500).fadeOut('slow');
                $("#deleteConfirmOpenStarlineResutlt").modal('hide');
                $("#error2").html(data.msg).fadeIn('slow').delay(2500).fadeOut('slow');

                $("#starlineGameSrchFrm").submit();
                getStarlineResultOnChangeEvent();
            } else {
                $("#error2").html(data.msg).fadeIn('slow').delay(2500).fadeOut('slow');

            }
            $("#openDecBtn1").attr("disabled", false);
            $("#openDecBtn1").html(changeBtn);
        },
        error: function(jqXHR, exception) {
            var msg = valid.ajaxError(jqXHR, exception);
            $("#error2").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
            $("#openDecBtn1").attr("disabled", false);
            $("#openDecBtn1").html(changeBtn);
        }
    });
}


function OpenDeleteGalidisswarResultData() {

    var game_id = $("#delete_gali_game_id").val();
    var result_dec_date = $("#result_date").val();
    var id = $("#id").val();

    $("#openDecBtn1").attr("disabled", true);
    var changeBtn = $("#openDecBtn1").html();
    $("#openDecBtn1").html("Sending..");
    $.ajax({
        type: "POST",
        url: base_url + "delete-open-galidisswar-result-data",
        data: {
            game_id: game_id,
            result_dec_date: result_dec_date
        },
        dataType: "json",
        success: function(data) {
            if (data.status == 'success') {
                $("#error2").html(data.msg).fadeIn('slow').delay(2500).fadeOut('slow');
                $("#deleteConfirmOpenGalidisswarResutlt").modal('hide');
                $("#error2").html(data.msg).fadeIn('slow').delay(2500).fadeOut('slow');

                $("#galidisswarGameSrchFrm").submit();
            } else {
                $("#error2").html(data.msg).fadeIn('slow').delay(2500).fadeOut('slow');

            }
            $("#openDecBtn1").attr("disabled", false);
            $("#openDecBtn1").html(changeBtn);
        },
        error: function(jqXHR, exception) {
            var msg = valid.ajaxError(jqXHR, exception);
            $("#error2").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
            $("#openDecBtn1").attr("disabled", false);
            $("#openDecBtn1").html(changeBtn);
        }
    });
}





function decleareCloseResult() {
    var close_number = $("#close_number").val();
    var game_id = $("#game_id").val();
    var result_dec_date = $("#result_dec_date").val();
    var id = $("#id").val();

    $("#closeDecBtn").attr("disabled", true);
    var changeBtn = $("#closeDecBtn").html();
    $("#closeDecBtn").html("Sending..");
    $.ajax({
        type: "POST",
        url: base_url + "decleare-close-data",
        data: {
            game_id: game_id,
            result_dec_date: result_dec_date
        },
        dataType: "json",
        success: function(data) {
            if (data.status == 'success') {
                $("#error2").html(data.msg).fadeIn('slow').delay(2500).fadeOut('slow');
                if (data.close_decleare_status == 1) {
                    resultHistoryListLoadData();
                    $("#close_div_msg").html('');
                    /* $("#close_div_msg").html('<badge class="badge badge-success">Result Decleared on '+data.close_decleare_date+'</badge>');
                    $("#close_div_msg").append('<button type="button" class="btn btn-danger waves-light m-t-10"  onclick="closeDeleteResultConfirmData();">Delete Result</button>'); */

                    $("#winner_close_btn").hide();
                }
            } else {
                $("#error2").html(data.msg).fadeIn('slow').delay(2500).fadeOut('slow');

            }
            $("#closeDecBtn").attr("disabled", false);
            $("#closeDecBtn").html(changeBtn);
        },
        error: function(jqXHR, exception) {
            var msg = valid.ajaxError(jqXHR, exception);
            $("#error2").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
            $("#closeDecBtn").attr("disabled", false);
            $("#closeDecBtn").html(changeBtn);
        }
    });
}


function closeDeleteResultConfirmData(game_id) {
    $("#deleteConfirmCloseResutlt").modal('show');
    $("#delete_close_game_id").val(game_id);
}

function closeDeleteResultData() {

    var game_id = $("#delete_close_game_id").val();
    var result_dec_date = $("#result_pik_date").val();
    var id = $("#id").val();

    $("#closeDecBtn1").attr("disabled", true);
    var changeBtn = $("#closeDecBtn1").html();
    $("#closeDecBtn1").html("Sending..");
    $.ajax({
        type: "POST",
        url: base_url + "delete-close-result-data",
        data: {
            game_id: game_id,
            result_dec_date: result_dec_date
        },
        dataType: "json",
        success: function(data) {
            if (data.status == 'success') {
                $("#error2").html(data.msg).fadeIn('slow').delay(2500).fadeOut('slow');
                $("#deleteConfirmCloseResutlt").modal('hide');
                $("#error2").html(data.msg).fadeIn('slow').delay(2500).fadeOut('slow');
                resultHistoryListLoadData();
                $("#gameSrchFrm").submit();
            } else {
                $("#error2").html(data.msg).fadeIn('slow').delay(2500).fadeOut('slow');

            }
            $("#closeDecBtn1").attr("disabled", false);
            $("#closeDecBtn1").html(changeBtn);
        },
        error: function(jqXHR, exception) {
            var msg = valid.ajaxError(jqXHR, exception);
            $("#error2").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
            $("#closeDecBtn1").attr("disabled", false);
            $("#closeDecBtn1").html(changeBtn);
        }
    });
}

function closeSaveData() {
    var close_number = $("#close_number").val();
    var game_id = $("#game_id").val();
    var id = $("#id").val();
    var result_dec_date = $("#result_dec_date").val();

    if (close_number == '') {
        $("#error2").html(valid.error('Please enter close number')).fadeIn('slow').delay(2500).fadeOut('slow');
    } else if (close_number.length != 3) {
        $("#error2").html(valid.error('Please enter 3 digit number')).fadeIn('slow').delay(2500).fadeOut('slow');
    } else {
        $("#closeSaveBtn").attr("disabled", true);
        var changeBtn = $("#closeSaveBtn").html();
        $("#closeSaveBtn").html("Sending..");
        $.ajax({
            type: "POST",
            url: base_url + "save-close-data",
            data: {
                close_number: close_number,
                game_id: game_id,
                id: id,
                result_dec_date: result_dec_date
            },
            dataType: "json",
            success: function(data) {
                if (data.status == 'success') {
                    resultHistoryListLoadData();
                    $("#close_result").val(data.close_result);
                    $("#winner_close_btn").show();
                    $("#error2").html(data.msg).fadeIn('slow').delay(2500).fadeOut('slow');
                    $("#closeDecBtn").show();
                } else {
                    $("#error2").html(data.msg).fadeIn('slow').delay(2500).fadeOut('slow');
                }
                $("#closeSaveBtn").attr("disabled", false);
                $("#closeSaveBtn").html(changeBtn);
            },
            error: function(jqXHR, exception) {
                var msg = valid.ajaxError(jqXHR, exception);
                $("#error2").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');
                $("#closeSaveBtn").attr("disabled", false);
                $("#closeSaveBtn").html(changeBtn);
            }
        });
    }
}

$(document).on('click', '.openPopupDeleteAutoId', function(e) {
    var id = $(this).attr('data-id');
    $("#delete_auto_id").val(id);
    $('#autoDeleteModal').modal({
        show: true
    });
});

/* $("button[type='reset']").on("click", function(event){
	event.preventDefault();
	var myForm = $(this).closest('form').get(0);
	myForm.reset();
	$("select", myForm).each(
		function () {
			$(this).select2('val',$(this).find('option:selected').val())
		}
	);
}); */

$("select").closest("form").on("reset", function(ev) {
    var targetJQForm = $(ev.target);
    setTimeout((function() {
        this.find("select").trigger("change");
    }).bind(targetJQForm), 0);
});



function blockFunctionData(id) {
    var myArray = id.split('-');
    var table_id = myArray[3];

    $.ajax({
        type: "POST",
        url: base_url + 'block-data-function',
        data: 'id=' + myArray[1] + "&table=" + myArray[2] + "&table_id=" + table_id + "&status_name=" + myArray[4],
        success: function(data) {


            if (myArray[0] == 'danger') {
                $("#" + id).html('<i class="fa fa-ban text-success m-r-10"></i>');

                $("#" + id).attr('id', 'success-' + myArray[1] + '-' + myArray[2] + '-' + table_id + '-' + myArray[4]);
            } else {
                $("#" + id).html('<i class="fa fa-ban text-danger m-r-10"></i>');
                $("#" + id).attr('id', 'danger-' + myArray[1] + '-' + myArray[2] + '-' + table_id + '-' + myArray[4]);
            }
            $("#msg").html(data);
            $("#msg").fadeIn('slow').delay(5000).fadeOut('slow');
        },
        error: function(jqXHR, exception) {
            var msg = valid.ajaxError(jqXHR, exception);
            $("#msg").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');

        }
    });
    return false;
}


function random_password_generate(max, min) {
    var passwordChars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz#@!%&()/";
    var randPwLen = Math.floor(Math.random() * (max - min + 1)) + min;
    var randPassword = Array(randPwLen).fill(passwordChars).map(function(x) {
        return x[Math.floor(Math.random() * x.length)]
    }).join('');
    return randPassword;
}



function deleteConfrim(id) {
    $("#delete_id").val(id);

    $("#block_con").html("Are you sure want to delete this item");
    $('#delete-confirm').modal({
        backdrop: 'static'
    });
}

function deleteFunction() {
    var id = $("#delete_id").val();
    var array = id.split("-");

    $.ajax({
        type: 'POST',
        url: base_url + 'delete-data',
        data: 'id=' + id,
        success: function(result) {
            $("#msg").html(result);
            $("#" + array[0]).hide();
        },
        error: function(jqXHR, exception) {
            var msg = valid.ajaxError(jqXHR, exception);
            $("#msg").html(valid.error(msg)).fadeIn('slow').delay(5000).fadeOut('slow');

        }
    });
}

var _validFileExtensions1 = [".jpeg", ".jpg", ".png"];

function ValidateSingleInput1(oInput, FileExtensions) {
    if (FileExtensions == 1) {
        var _validFileExtensions1 = [".jpeg", ".jpg", ".png"];
    } else if (FileExtensions == 2) {
        var _validFileExtensions1 = [".zip", ".pdf", ".jpeg", ".jpg", ".png"];
    }

    if (oInput.type == "file") {
        var sFileName1 = oInput.value;
        if (sFileName1.length > 0) {
            var blnValid1 = false;
            for (var j = 0; j < _validFileExtensions1.length; j++) {
                var sCurExtension1 = _validFileExtensions1[j];
                if (sFileName1.substr(sFileName1.length - sCurExtension1.length, sCurExtension1.length).toLowerCase() == sCurExtension1.toLowerCase()) {
                    blnValid1 = true;


                    break;
                }
            }
            if (!blnValid1) {
                snackbar("Sorry, file is invalid");
                oInput.value = "";
                return false;
            }
        }
    }
    return true;
}
/* function refreshCaptcha(){
	var img = document.images['captchaimg3']; 
	img.src = img.src.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;
} */

function refreshCaptcha() {

    $.ajax({
        type: 'POST',
        url: base_url + 'refresh-captcha',
        success: function(result) {
            $('#captcha_img').html(result);
        },
    });
}


function snackbar(msg) {
    $("#snackbar").html(msg).fadeIn('slow').delay(6000).fadeOut('slow');
}

function snackbar2(msg) {
    $("#snackbar").html(msg).fadeIn('slow');
}

function mailReply(name, mailId) {
    $("#mailReplyModal").modal("show");
    $("#m_name").val(name);
    $("#email_id").val(mailId);
}

function validateImageExtensionOther(val, id_name) {
    if (id_name == 1)
        var fileUpload = document.getElementById("category_icon");
    if (id_name == 2)
        var fileUpload = document.getElementById("subcategory_icon");
    if (id_name == 3)
        var fileUpload = document.getElementById("sub_subcategory_icon");
    if (id_name == 4)
        var fileUpload = document.getElementById("child_sub_subcategory_icon");
    if (id_name == 5)
        var fileUpload = document.getElementById("product_cover_image");



    if (!valid.validateExtension(val, 1) && id_name == 1) {
        valid.snackbar('Invalid file type (only .jpeg,.jpg,.png allowed)');
        $('#file').val('');
        return false;
    } else if (!valid.validateExtension(val, 3) && id_name == 3) {
        valid.snackbar('Invalid file type (only .Pdf,.doc,.docx allowed)');
        $('#attachment').val('');
        return false;
    }

    //Check whether HTML5 is supported.
    else if (typeof(fileUpload.files) != "undefined") {
        //Initiate the FileReader object.
        var reader = new FileReader();
        //Read the contents of Image File.
        reader.readAsDataURL(fileUpload.files[0]);
        reader.onload = function(e) {
            //Initiate the JavaScript Image object.
            var image = new Image();


            //Set the Base64 string return from FileReader as source.
            image.src = e.target.result;

            //Validate the File Height and Width.
            image.onload = function() {
                var file = fileUpload.files[0]; //get file   
                var sizeKB = file.size / 1024;
                var height = this.height;
                var width = this.width;
                if (height > 256 || width > 256) {
                    valid.snackbar('Height and Width must not exceed 256px.');
                    $("#category_icon").val('');
                    $("#subcategory_icon").val('');
                    $("#sub_subcategory_icon").val('');
                    return false;
                }

                if (sizeKB > 30) {
                    valid.snackbar('Icon size must not exceed 30Kb.');
                    $("#category_icon").val('');
                    $("#subcategory_icon").val('');
                    $("#sub_subcategory_icon").val('');
                    return false;
                }

                $("#submitBtn").attr("disabled", false);

                return true;
            };

        }
    }
}



function validExtension(val, type) {
    if (!valid.validateExtension(val, type)) {
        valid.snackbar('Invalid file type (only .jpeg,.jpg,.png allowed)');
        $(".uimg").val('');
        return false;
    }
}

function copyToClipboard(elem) {

    // create hidden text element, if it doesn't already exist
    var targetId = "_hiddenCopyText_";
    var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
    var origSelectionStart, origSelectionEnd;
    if (isInput) {
        // can just use the original source element for the selection and copy
        target = elem;
        origSelectionStart = elem.selectionStart;
        origSelectionEnd = elem.selectionEnd;
    } else {
        // must use a temporary form element for the selection and copy
        target = document.getElementById(targetId);
        if (!target) {
            var target = document.createElement("textarea");
            target.style.position = "absolute";
            target.style.left = "-9999px";
            target.style.top = "0";
            target.id = targetId;
            document.body.appendChild(target);
        }
        target.textContent = elem.textContent;
    }
    // select the content
    var currentFocus = document.activeElement;
    target.focus();
    target.setSelectionRange(0, target.value.length);

    // copy the selection
    var succeed;
    try {
        succeed = document.execCommand("copy");
    } catch (e) {
        succeed = false;
    }
    // restore original focus
    if (currentFocus && typeof currentFocus.focus === "function") {
        currentFocus.focus();
    }

    if (isInput) {
        // restore prior selection
        elem.setSelectionRange(origSelectionStart, origSelectionEnd);
    } else {
        // clear temporary content
        target.textContent = "";
    }
    return succeed;
}

function getSubCategory(id) {
    $("#sub_cat_name").html('<option value="">Loading Sub Category...</option>');
    $("#u_sub_cat_name").html('<option value="">Loading Sub Category...</option>');
    $.ajax({
        url: base_url + 'get-sub-category',
        type: 'POST',
        data: {
            id: id
        },
        dataType: "json",
        success: function(data) {
            $("#sub_cat_name").html('');
            $("#u_sub_cat_name").html('');
            if (data.getSubCategory != '') {
                $("#sub_cat_name").append('<option value="">Select Sub Category</option>');
                $("#u_sub_cat_name").append('<option value="">Select Sub Category</option>');
                $.each(data.getSubCategory, function(key, val) {
                    $("#sub_cat_name").append('<option value="' + val.sub_category_id + '">' + val.sub_category_name + '</option>');
                    $("#u_sub_cat_name").append('<option value="' + val.sub_category_id + '">' + val.sub_category_name + '</option>');
                });
            } else {
                $("#sub_cat_name").html('<option value="">Select Sub Category</option>');
                $("#u_sub_cat_name").html('<option value="">Select Sub Category</option>');
            }
        }
    });
}

function accept_request(id) {
    $("#accept_request_id").attr("disabled", true);
    $.ajax({
        url: base_url + 'accept-fund-request',
        type: 'POST',
        data: {
            id: id
        },
        dataType: "json",
        success: function(data) {
            if (data.status == 'success') {
                $('#fundRequestAcceptModal').modal('hide');
                $("#msg").html(data.msg);
                $("#msg").fadeIn('slow').delay(5000).fadeOut('slow');
                $("#status" + id).html('<badge class="badge badge-success">' + data.request_status + '</badge>');
                $("#accept").attr("disabled", true);
                $("#reject").attr("disabled", true);
                dataTable.ajax.reload();
            } else {
                $("#msg").html(data.msg);
                $("#msg").fadeIn('slow').delay(5000).fadeOut('slow');
            }
        }
    });
}

function reject_request(id) {
    $("#reject_request_id").attr("disabled", true);
    $.ajax({
        url: base_url + 'reject-fund-request',
        type: 'POST',
        data: {
            id: id
        },
        dataType: "json",
        success: function(data) {
            if (data.status == 'success') {
                $('#fundRequestRejectModal').modal('hide');
                $("#msg").html(data.msg);
                $("#msg").fadeIn('slow').delay(5000).fadeOut('slow');
                $("#status" + id).html('<badge class="badge badge-danger">' + data.request_status + '</badge>');
                $("#accept").attr("disabled", true);
                $("#reject").attr("disabled", true);
                dataTable.ajax.reload();
            } else {
                $("#msg").html(data.msg);
                $("#msg").fadeIn('slow').delay(5000).fadeOut('slow');
            }
        }
    });
}

function delete_this(id) {
    $("#delete_id").attr("disabled", true);
    $.ajax({
        url: base_url + 'delete-image',
        type: 'POST',
        data: {
            id: id
        },
        dataType: "json",
        success: function(data) {
            if (data.status == 'success') {
                $('#imageDeleteModal').modal('hide');
                $("#msg").html(data.msg);
                $("#msg").fadeIn('slow').delay(5000).fadeOut('slow');
                dataTable.ajax.reload();
            } else {
                $("#msg").html(data.msg);
                $("#msg").fadeIn('slow').delay(5000).fadeOut('slow');
            }
        }
    });
}

function changeGameTime(id) {
    var open_time = $("#open_time" + id).val();
    var close_time = $("#close_time" + id).val();
    $("#changeBtn" + id).attr("disabled", true);
    var changeBtn = $("#changeBtn" + id).html();
    $("#changeBtn" + id).html("Changing..");
    $.ajax({
        url: base_url + 'change-game-time',
        type: 'POST',
        data: {
            id: id,
            open_time: open_time,
            close_time: close_time
        },
        dataType: "json",
        success: function(data) {
            if (data.status == 'success') {
                $("#msg").html(data.msg);
                $("#msg").fadeIn('slow').delay(5000).fadeOut('slow');
            } else {
                $("#msg").html(data.msg);
                $("#msg").fadeIn('slow').delay(5000).fadeOut('slow');
            }
            $("#changeBtn" + id).attr("disabled", false);
            $("#changeBtn" + id).html(changeBtn);
        }
    });
    return false;
}


function getBidHistoryExcelData() {
    var bid_date = $("#bid_date").val();
    var game_name = $("#game_name").val();
    var game_type = $("#game_type").val();
    var url = base_url + 'export-option-bid-history-data';
    $('#bidHistory').append('<form id="bidHistoryExcelExport" action="' + url + '" method="POST"><input type="hidden" name="bid_date" value="' + bid_date + '"><input type="hidden" name="game_name" value="' + game_name + '"><input type="hidden" name="game_type" value="' + game_type + '"></form>')
    $('#bidHistoryExcelExport').submit();
    /* $.ajax({
    		url: base_url + 'export-option-bid-history-data',
    		type: "POST",
    		data: {bid_date:bid_date,game_name:game_name,game_type:game_type},
    		success: function(data){
    			window.location = base_url+'export-option-bid-history-data';
    		}
    	});
    	return false; */
}


function getStarlineBidHistoryExcelData() {
    var bid_date = $("#bid_date").val();
    var game_name = $("#game_name").val();
    var game_type = $("#game_type").val();
    var url = base_url + 'export-option-starline-bid-history-data';
    $('#bidHistory').append('<form id="bidHistoryExcelExport" action="' + url + '" method="POST"><input type="hidden" name="bid_date" value="' + bid_date + '"><input type="hidden" name="game_name" value="' + game_name + '"><input type="hidden" name="game_type" value="' + game_type + '"></form>')
    $('#bidHistoryExcelExport').submit();

}


function getgalidisswarBidHistoryExcelData() {
    var bid_date = $("#bid_date").val();
    var game_name = $("#game_name").val();
    var game_type = $("#game_type").val();
    var url = base_url + 'export-option-galidisswar-bid-history-data';
    $('#bidHistory').append('<form id="bidHistoryExcelExport" action="' + url + '" method="POST"><input type="hidden" name="bid_date" value="' + bid_date + '"><input type="hidden" name="game_name" value="' + game_name + '"><input type="hidden" name="game_type" value="' + game_type + '"></form>')
    $('#bidHistoryExcelExport').submit();

}

function getSession(val) {
    if (val == "Jodi Digit" || val == "Full Sangam") {
        $(".session_get").hide();
    } else {
        $(".session_get").show();
    }
}



function getUserData(user_id) {

    $(".remove_color").css("background-color", "white");
    $(".color_bg" + user_id).css("background-color", "#CCE5FF");

    $.ajax({
        url: base_url + 'get-user-data',
        type: 'POST',
        data: {
            user_id: user_id
        },
        dataType: "json",
        success: function(data) {
            $("#usr_div").show();
            $("#no_data").hide();
            $(".name1").html(data.username);
            $(".id").html("<input type='hidden' name='user_id1' id='user_id1' value='" + data.user_id + "'>");
            $(".msg_data").html(data.listData);
            $('.scrolldown').scrollTop($('.scrolldown')[0].scrollHeight);
        },
        error: function(jqXHR, exception) {
            var msg = valid.ajaxError(jqXHR, exception);
            valid.snackbar_error((msg));
        }
    });
}



function geRouletteBidHistoryExcelData() {
    var bid_date = $("#bid_date").val();
    var game_name = $("#game_name").val();
    var url = base_url + 'export-option-roulette-bid-history-data';
    $('#bidHistory').append('<form id="roulettebidHistoryExcelExport" action="' + url + '" method="POST"><input type="hidden" name="bid_date" value="' + bid_date + '"><input type="hidden" name="game_name" value="' + game_name + '"></form>')
    $('#roulettebidHistoryExcelExport').submit();

}

function showclose(close) {
    if (close == 2) {
        $("#showclosediv").show();
    } else {
        $("#showclosediv").hide();
    }
}

function accept_auto_request(id) {
    $("#accept_auto_request_id").attr("disabled", true);
    $.ajax({
        url: base_url + 'accept-auto-fund-request',
        type: 'POST',
        data: {
            id: id
        },
        dataType: "json",
        success: function(data) {
            if (data.status == 'success') {
                $('#fundRequestAutoAcceptModal').modal('hide');
                $("#msg").html(data.msg);
                $("#msg").fadeIn('slow').delay(5000).fadeOut('slow');
                $("#status" + id).html('<badge class="badge badge-success">' + data.request_status + '</badge>');
                $("#accept").attr("disabled", true);
                $("#reject").attr("disabled", true);

                $("#accept_auto_request_id").attr("disabled", false);
                dataTable.ajax.reload();
            } else {
                $("#msg").html(data.msg);
                $("#msg").fadeIn('slow').delay(5000).fadeOut('slow');
            }
        }
    });
}


function reject_auto_request(id) {
    $("#reject_auto_request_id").attr("disabled", true);
    var reject_auto_remark = $("#reject_auto_remark").val();
    $.ajax({
        url: base_url + 'reject-auto-fund-request',
        type: 'POST',
        data: {
            id: id,
            reject_auto_remark: reject_auto_remark
        },
        dataType: "json",
        success: function(data) {
            if (data.status == 'success') {
                $('#fundRequestAutoRejectModal').modal('hide');
                $("#msg").html(data.msg);
                $("#msg").fadeIn('slow').delay(5000).fadeOut('slow');
                $("#status" + id).html('<badge class="badge badge-danger">' + data.request_status + '</badge>');
                $("#accept").attr("disabled", true);
                $("#reject").attr("disabled", true);
                $("#reject_auto_request_id").attr("disabled", false);
                dataTable.ajax.reload();
            } else {
                $("#msg").html(data.msg);
                $("#msg").fadeIn('slow').delay(5000).fadeOut('slow');
            }
        }
    });
}

function delete_auto_request(id) {
    $("#delete_auto_id").attr("disabled", true);
    $.ajax({
        url: base_url + 'delete-auto-request-depo',
        type: 'POST',
        data: {
            id: id
        },
        dataType: "json",
        success: function(data) {
            if (data.status == 'success') {
                $('#autoDeleteModal').modal('hide');
                $("#msg").html(data.msg);
                $("#msg").fadeIn('slow').delay(5000).fadeOut('slow');
                dataTable.ajax.reload();
            } else {
                $("#msg").html(data.msg);
                $("#msg").fadeIn('slow').delay(5000).fadeOut('slow');
            }
        }
    });
}


/* function getDigit(game_type)
{
			alert(game_type);
			$("#digit").html('<option value="">Getting...</option>');
			if(game_type!= "")
			{
				$.ajax({
					url: base_url+'show-digit',
					type: 'POST',
					data: {'game_type':game_type},
					dataType: "json",
					success: function(data)
					{
							$("#digit").html(data.result);
														
						
					}
				});
			}
			else{
		$("#digit").html('<option value="">Select Digit</option>');
		 
	}
} */


// when DOM is ready
$(document).ready(function() {

    // Attach Button click event listener 
    $("#changePin").click(function() {

        // show Modal
        $('#changePinModal').modal('show');
    });
})


function data_refund() {
    var bid_revert_date = $('#bid_revert_date').val();
    var win_game_name = $('#win_game_name').val();
    $.ajax({
        url: base_url + 'refund-amount',
        type: 'POST',
        data: {
            bid_revert_date: bid_revert_date,
            win_game_name: win_game_name
        },
        dataType: "json",
        success: function(data) {
            if (data.status == 'success') {
                $('#revertModel').modal('hide');
                $("#error").html(data.msg);
                $("#error").fadeIn('slow').delay(5000).fadeOut('slow');
                location.reload();
            } else {
                $("#error").html(data.msg);
                $("#error").fadeIn('slow').delay(5000).fadeOut('slow');
            }
        }
    });

}

function OpenBidHistory() {
    var game_id = $("#win_game_name").val();
    var result_date = $("#result_date").val();

    /* $("#winner_btn").attr("disabled",true);	   
    var changeBtn = $("#winner_btn").html();
    $("#winner_btn").html("Sending..");
    $("#winner_result_data").html(''); */
    $("#bid_result_data").html('');
    // $('#bidHistoryDetails').DataTable().destroy(); 


    $.ajax({
        type: "POST",
        url: base_url + "get-bid-report-list",
        data: {
            game_id: game_id,
            result_date: result_date
        },
        dataType: "json",
        success: function(data) {

            $("#bidHistoryModal").modal('show');
            var i = 1;
            $("#bidHistoryDetails").dataTable().fnClearTable();
            $('#bidHistoryDetails').dataTable().fnDestroy();

            if (data.getBidHistory.length > 0) {


                $.each(data.getBidHistory, function(key, val) {

                    if (val.pana == 'Single Digit') {
                        if (val.session == 'Open') {
                            var open_digit = val.digits;
                            var close_digit = 'N/A';
                            var open_pana = 'N/A';
                            var close_pana = 'N/A';
                        } else {
                            var open_digit = 'N/A';
                            var close_digit = val.digits;
                            var open_pana = 'N/A';
                            var close_pana = 'N/A';
                        }
                    }

                    if (val.pana == 'Jodi Digit') {
                        var open_digit = val.digits[0];
                        var close_digit = val.digits % 10;
                        var open_pana = 'N/A';
                        var close_pana = 'N/A';
                    }

                    if (val.pana == 'Single Pana' || val.pana == 'Double Pana' || val.pana == 'Triple Pana') {
                        if (val.session == 'Open') {
                            var a = val.digits[0];
                            var b = val.digits[1];
                            var c = val.digits[2];

                            var total_digits = parseInt(a) + parseInt(b) + parseInt(c);
                            if (total_digits < 10) {
                                var digits = total_digits;
                            } else if (total_digits > 9) {
                                var digits = total_digits % 10;
                            }
                            var open_digit = digits;
                            var close_digit = 'N/A';
                            var open_pana = val.digits;
                            var close_pana = 'N/A';
                        } else {
                            var a = val.digits[0];
                            var b = val.digits[1];
                            var c = val.digits[2];
                            var total_digits = parseInt(a) + parseInt(b) + parseInt(c);
                            if (total_digits < 10) {
                                var digits = total_digits;
                            } else if (total_digits > 9) {
                                var digits = total_digits % 10;
                            }
                            var open_digit = 'N/A';
                            var close_digit = digits;
                            var open_pana = 'N/A';
                            var close_pana = val.digits;
                        }
                    }

                    if (val.pana == 'Half Sangam') {
                        if (val.session == 'Open') {
                            var open_digit = val.digits;
                            var close_digit = 'N/A';
                            var open_pana = 'N/A';
                            var close_pana = val.closedigits;
                        } else {
                            var open_digit = 'N/A';
                            var close_digit = val.digits;
                            var open_pana = val.closedigits;
                            var close_pana = 'N/A';
                        }
                    }

                    if (val.pana == 'Full Sangam') {
                        var open_digit = "N/A";
                        var close_digit = "N/A";
                        var open_pana = val.digits;
                        var close_pana = val.closedigits;
                    }

                    $("#bid_result_data").append('<tr><td><a href="' + base_url + admin + '/view-user/' + val.user_id + '">' + val.user_name + '</a></td><td>' + val.bid_tx_id + '</td><td>' + val.pana + '</td><td>' + val.session + '</td><td>' + open_pana + '</td><td>' + open_digit + '</td><td>' + close_pana + '</td><td>' + close_digit + '</td><td>' + val.points + '</td></tr>');
                    i++;
                });
                $("#bidHistoryDetails").dataTable();
            } else {
                $("#bid_result_data").append('<tr><td colspan="9" class="table_text_align">No Data Available</td></tr>');
            }

        },
    });
}

function OpenWinHistoryDetails() {
    var game_id = $("#win_game_name").val();
    var result_date = $("#result_date").val();
    $("#bid_winning_data").html('');

    $.ajax({
        type: "POST",
        url: base_url + "get-winning-report-details",
        data: {
            game_id: game_id,
            result_date: result_date
        },
        dataType: "json",
        success: function(data) {
            $("#totalWinDetailsModal").modal('show');
            var i = 1;
            if (data.getResultHistory != '') {
                if ($.fn.DataTable.isDataTable('#winningHistoryDetails')) {
                    $('#winningHistoryDetails').DataTable().destroy();
                }
                $('#winningHistoryDetails tbody').empty();

                $.each(data.getResultHistory, function(key, val) {
                    if (val.pana == 'Single Digit') {
                        if (val.session == 'Open') {
                            var open_digit = val.digits;
                            var close_digit = 'N/A';
                            var open_pana = 'N/A';
                            var close_pana = 'N/A';
                        } else {
                            var open_digit = 'N/A';
                            var close_digit = val.digits;
                            var open_pana = 'N/A';
                            var close_pana = 'N/A';
                        }
                    }

                    if (val.pana == 'Jodi Digit') {
                        var open_digit = val.digits[0];
                        var close_digit = val.digits % 10;
                        var open_pana = 'N/A';
                        var close_pana = 'N/A';
                    }

                    if (val.pana == 'Single Pana' || val.pana == 'Double Pana' || val.pana == 'Triple Pana') {
                        if (val.session == 'Open') {
                            var a = val.digits[0];
                            var b = val.digits[1];
                            var c = val.digits[2];

                            var total_digits = parseInt(a) + parseInt(b) + parseInt(c);
                            if (total_digits < 10) {
                                var digits = total_digits;
                            } else if (total_digits > 9) {
                                var digits = total_digits % 10;
                            }
                            var open_digit = digits;
                            var close_digit = 'N/A';
                            var open_pana = val.digits;
                            var close_pana = 'N/A';
                        } else {
                            var a = val.digits[0];
                            var b = val.digits[1];
                            var c = val.digits[2];
                            var total_digits = parseInt(a) + parseInt(b) + parseInt(c);
                            if (total_digits < 10) {
                                var digits = total_digits;
                            } else if (total_digits > 9) {
                                var digits = total_digits % 10;
                            }
                            var open_digit = 'N/A';
                            var close_digit = digits;
                            var open_pana = 'N/A';
                            var close_pana = val.digits;
                        }
                    }

                    if (val.pana == 'Half Sangam') {
                        if (val.session == 'Open') {
                            var open_digit = val.digits;
                            var close_digit = 'N/A';
                            var open_pana = 'N/A';
                            var close_pana = val.closedigits;
                        } else {
                            var open_digit = 'N/A';
                            var close_digit = val.digits;
                            var open_pana = val.closedigits;
                            var close_pana = 'N/A';
                        }
                    }

                    if (val.pana == 'Full Sangam') {
                        var open_digit = "N/A";
                        var close_digit = "N/A";
                        var open_pana = val.digits;
                        var close_pana = val.closedigits;
                    }



                    $("#bid_winning_data").append('<tr><td><a href="' + base_url + admin + '/view-user/' + val.user_id + '">' + val.user_name + '</a></td><td>' + val.game_name + '</td><td>' + val.pana + '</td><td>' + open_pana + '</td><td>' + open_digit + '</td><td>' + close_pana + '</td><td>' + close_digit + '</td><td>' + val.points + '</td><td>' + val.amount + '</td><td>' + val.tx_request_number + '</td><td>' + val.insert_date + '</td></tr>');
                });
                $("#winningHistoryDetails").dataTable();
            } else {
                $("#bid_winning_data").append('<tr><td colspan=7 class="table_text_align">No Data Available</td></tr>');
            }
        },

    });

}

function dataCleanFunction() {
    var result_date = $("#result_date").val();
    $("#data_clean_date").val(result_date);
    $('#cleanDataModel').modal({
        show: true
    });
}


function getStarlineResultOnChangeEvent() {
    var date = $("#result_star_pik_date").val();
    var ob1 = '';
    var ob2 = '';

    $.ajax({
        type: "POST",
        url: base_url + "starline-result-history-list-load-data",
        data: {
            date: date
        },
        dataType: "json",
        success: function(data) {
            $.each(data, function(key, val) {
                ob2 += '<tr><td>' + val.sn + '</td><td>' + val.game_name + '</td><td>' + val.result_date + '</td><td>' + val.open_date + '</td><td>' + val.open_result + '</td></tr>'
            });
            $('#getStarlineResultHistory').html(ob2);

        }
    });
}


function getGaliDissawarResultOnChangeEvent() {
    var date = $("#result_date").val();
    var ob1 = '';
    var ob2 = '';

    $.ajax({
        type: "POST",
        url: base_url + "galidisswar-result-history-list-load-data",
        data: {
            date: date
        },
        dataType: "json",
        success: function(data) {
            $.each(data, function(key, val) {
                ob2 += '<tr><td>' + val.sn + '</td><td>' + val.game_name + '</td><td>' + val.result_date + '</td><td>' + val.open_date + '</td><td>' + val.open_result + '</td></tr>'
            });
            $('#getgalidisswarResultHistory').html(ob2);

        }
    });
}


function data_clean(date) {
    $.ajax({
        url: base_url + 'clean-database-data',
        type: 'POST',
        data: {
            date: date
        },
        dataType: "json",
        success: function(data) {
            if (data.status == 'success') {
                $('#cleanDataModel').modal('hide');
                $("#error_msg").html(data.msg);
                $("#error_msg").fadeIn('slow').delay(5000).fadeOut('slow');
            } else {
                $("#error_msg").html(data.msg);
                $("#error_msg").fadeIn('slow').delay(5000).fadeOut('slow');
            }
        }
    });
}

function walletTxnBackupFunction() {
    var result_date = $("#result_date").val();
    $.ajax({
        type: "POST",
        url: base_url + 'wallet-transaction-backup',
        data: {
            result_date: result_date
        },
        success: function(data) {
            window.location = base_url + 'wallet-transaction-backup/' + result_date;
        }
    });
    return false;
}

function resultHistoryListLoadData() {
    var date = $("#result_pik_date").val();
    $('#getGameResultHistory').html('');
    var ob2 = '';
    $.ajax({
        type: "POST",
        url: base_url + "result-history-list-load-data",
        data: {
            date: date
        },
        dataType: "json",
        success: function(data) {

            $.each(data, function(key, val) {
                ob2 += '<tr><td>' + val.sn + '</td><td>' + val.game_name + '</td><td>' + val.result_date + '</td><td>' + val.open_date + '</td><td>' + val.close_date + '</td><td>' + val.open_result + '</td><td>' + val.close_result + '</td></tr>'
            });
            $('#getGameResultHistory').html(ob2);

        }
    });

}

function checkGameDeclare(id) {
    var result_date = $("#result_date").val();
    $.ajax({
        type: "POST",
        url: base_url + "check-open-market-result-declaration",
        data: {
            game_id: id,
            result_date: result_date
        },
        dataType: "json",
        success: function(data) {
            if (data.open_decleare_status != 0) {
                var objSelect_1 = document.getElementById("win_market_status");
                var objSelect_2 = document.getElementById("winning_ank");
                var open_number = data.open_number;
                setSelectedValue(objSelect_1, "2");
                $("#showclosediv").show();
                setSelectedValueNumber(objSelect_2, open_number);
            }
        }
    });
}


function changeLogoutStatus(user_id) {
    $.ajax({
        type: "POST",
        url: base_url + "change-logout-status",
        data: {
            user_id: user_id
        },
        dataType: "json",
        success: function(data) {

            valid.snackbar_success('User is loged out from all Device');

        }
    });
}


function setSelectedValue(selectObj, valueToSet) {
    for (var i = 0; i < selectObj.options.length; i++) {
        if (selectObj.options[i].value == valueToSet) {
            selectObj.options[i].selected = true;
            return;
        }
    }
}

function setSelectedValueNumber(selectObj, valueToSet) {
    $("#winning_ank").select2('destroy');
    for (var i = 0; i < selectObj.options.length; i++) {
        if (selectObj.options[i].value == valueToSet) {
            selectObj.options[i].selected = true;
            return;
        }
    }
}