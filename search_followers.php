<?php
session_start();
if ($_POST['keyword'] != null) {

    if (!isset($_SESSION['data'])) {
        include_once "getfollowerslist_ajax.php";
    }

    $keyword = $_POST['keyword'];
    $follower_session = $_SESSION['data'];
    $followername_array = array();
    foreach ($follower_session as $key => $follw_value) {
        $followername_array[$key] = $follw_value['name'];
    }

    $input = preg_quote($keyword, '~');
    $result1 = preg_grep('~' . $input . '~', $followername_array);
    echo '<ul id="followers-list">';
    foreach ($result1 as $val) {
        ?>
        <li onClick="selectFollowers('<?php echo $val; ?>');"><?php echo $val; ?></li>

        <?php
    }
    echo '</ul>';
}
?>

