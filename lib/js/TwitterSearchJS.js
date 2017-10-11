function callPDF() {
    $.ajax({
        type: 'get',
        url: 'DownloadPdf.php', //Here you will fetch records
        dataType: 'html',
        success: function (result) {
            var docDefinition = {content: result};
            pdfMake.createPdf(docDefinition).download();
        }
    });
}

$('#downloadAll').on('change', function () {
    var url = $(this).val();
    if (url) {
        if (url == 'pdf') {
            callPDF();
        }
        else {
            window.location = url;
        }
    }
});

$(function () {
    $('.search-box').autocomplete({
        search: function (event, ui) {
            $('.searchMessage').show();
        },
        open: function (event, ui) {
            $('.searchMessage').hide();
        },
        source: "search_followers.php",
        minLength: 1
    }).off('blur').on('blur', function () {
        if (document.hasFocus()) {
            $('ul.ui-autocomplete').hide();
        }
    });
});

$(document).ready(function () {
    $('.search-box').on('autocompleteselect', function (e, ui) {
        selectFollowers(ui.item.value);
    });
});

function selectFollowers(val) {
    $("#search-box").val(val);
    $.ajax({
        type: 'get',
        url: 'get_tweets.php', //Here you will fetch records
        data: 'followerID=' + val, //Pass $id
        dataType: 'json',
        success: function (result) {
            var htmld = "";
            if (result['display_array'] == '') {
                htmld += '<li></li><li>No tweets found</li>';
            }
            else {
                var tweet_lenth = result['display_array'].length;
                if (tweet_lenth == 1) {
                    htmld += '<li></li>';
                }
                $.each(result['display_array'], function (index, data) {
                    htmld += '<li>' + data["text"] + '<br>' + data["images"] + '</li>';
                });
            }
            $("#sliderDiv").html(htmld);
        }
    });
    $("#suggesstion-box").hide();
}

$('.download_tweet').click(function () {
    $(".download_select").prop("disabled", true);
    $(".download_msg").show();
    $.ajax({
        type: 'get',
        url: 'my_tweets.php', //Here you will fetch records
        dataType: 'json',
        success: function (result) {
            if (result == 1) {
                $(".download_select").prop("disabled", false);
                $(".download_msg").hide();
            }
            else {
                alert("something went wrong please try again");
            }
        }
    });
});