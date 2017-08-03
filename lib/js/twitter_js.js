$(document).ready(function () {
    $('.btnfollowertwitt').click(function () {
        var followerID = $(this).val();
        $.ajax({
            type: 'get',
            url: 'get_tweets.php',
            data: 'followerID=' + followerID,
            dataType: 'json',
            success: function (result) {
                var htmld = "";
                $.each(result['display_array'], function (index, data) {
                    htmld += '<li>' + data["text"] + '<br>' + data["images"] + '</li>';
                });
                var tweet_lenth = result['display_array'].length;
                if (tweet_lenth == 1) {
                    htmld += '<li></li>';
                }
                else if (tweet_lenth == 0) {
                    htmld += '<li></li><li>No tweets found</li>';
                }
                $("#div66").html(htmld);
            }
        });
    });
    $.ajax({
        type: 'get',
        url: 'get_tweets.php',
        data: {followerID: null},
        dataType: 'json',
        success: function (result) {
            var htmld = "";
            $.each(result['display_array'], function (index, data) {
                htmld += '<li>' + data["text"] + '<br>' + data["images"] + '</li>';
            });
            var tweet_lenth = result['display_array'].length;
            if (tweet_lenth == 1) {
                htmld += '<li></li>';
            }
            else if (tweet_lenth == 0) {
                htmld += '<li></li><li>No tweets found</li>';
            }
            $("#div66").html(htmld);
        }
    });
});
$(document).ready(function () {
    $("#search-box").keyup(function () {
        var timer;
        if ($(this).val() != '') {
            $.ajax({
                type: "POST",
                url: "search_followers.php",
                data: 'keyword=' + $(this).val(),
                success: function (data) {
                    $("#suggesstion-box").show();
                    $("#suggesstion-box").html(data);
                    $("#search-box").css("background", "#FFF");
                },
                beforeSend: function () {
                    timer = setTimeout(function()
                        {
                            $("#msg-box").show();
                        },
                        3000);
                },
                complete: function () {
                    clearTimeout(timer);
                    $("#msg-box").hide();
                }
            });
        }
        else {
            $("#suggesstion-box").empty();
        }
    });
});
function selectFollowers(val) {
    $("#search-box").val(val);
    $.ajax({
        type: 'get',
        url: 'get_tweets.php',
        data: 'followerID=' + val,
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
            $("#div66").html(htmld);
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
            }else {
                alert("something went wrong please try again");
            }
        }
    });
});