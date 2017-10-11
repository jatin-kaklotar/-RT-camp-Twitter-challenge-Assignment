$(document).ready(function () {
    $('.btnfollowertwitt').click(function () {
        var followerID = $(this).val();
        $.ajax({
            type: 'get',
            url: 'get_tweets.php', //Here you will fetch records
            data: 'followerID=' + followerID, //Pass $id
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
                $("#sliderDiv").html(htmld);
            }
        });
    });

    $.ajax({
        type: 'get',
        url: 'get_tweets.php', //Here you will fetch records
        data: {followerID: null}, //Pass $id
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
            $("#sliderDiv").html(htmld);
        }
    });
});