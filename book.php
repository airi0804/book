<?php require '../header.php'; ?>
<style>
    h1 {
        font-size: x-large;
    }

    .ib {
	    display:inline-block;
        padding-left: 10px;
    }

    .th0, .th1, .td0, .td1, .td2 {
	    display:inline-block;
	    padding-bottom:5px;
    }

    .th0, .th1 {
	    text-align:center;
	    font-weight:bold;
    }

    .th0, .td0 {
	    width:5em;
        text-align: center;
    }

    .th1, .td1 {
    	width:12em;
        text-align: center;
    }

    .td1 {
        padding-left: 20px;
    }

    .td0 {
        font-size: large;
    }
</style>
<h1>本の管理システム</h1>
<div class="th0">商品番号</div>
<div class="th1">商品名</div>
<div class="th1">価格</div><br>
<?php
$pdo=new PDO('mysql:host=localhost;dbname=practice;charset=utf8', 
'root', 'mariadb');
if (isset($_REQUEST['command'])) {
    switch ($_REQUEST['command']) {
    case 'insert':
        if(empty($_REQUEST['title']) ||
        !preg_match('/^[0-9]+$/', $_REQUEST['price'])) break;
        $sql=$pdo->prepare('insert into books values(null,?,?)');
        $sql->execute(
            [htmlspecialchars($_REQUEST['title']), $_REQUEST['price']]);
        break;

    case 'update':
        if(empty($_REQUEST['title']) ||
        !preg_match('/^[0-9]+$/', $_REQUEST['price'])) break;
        $sql=$pdo->prepare(
            /* SQL文の場所に「-」は使えない
            「-」ハイフンを使っている場合は、「`」バッククォート（英数字で「shift」+「@」のところ）で囲む */
                'update books set title=?, price=? where `book-id`=?');
        $sql->execute(
                [htmlspecialchars($_REQUEST['title']), $_REQUEST['price'],$_REQUEST['book-id']]);
        break;

    case 'delete':
        $sql=$pdo->prepare('delete from books where `book-id`=?');
        $sql->execute([$_REQUEST['book-id']]);
        break;
    }
}
foreach ($pdo->query('select * from books') as $row) {
    echo '<form class="ib" action="book.php" method="post">';
    echo '<input type="hidden" name="command" value="update">';
    echo '<input type="hidden" name="book-id" value="', $row['book-id'], '">';

    echo '<div class="td0">';
    echo $row['book-id'];
    echo '</div>';

    echo '<div class="td1">';
    echo '<input type="text" name="title" value="', $row['title'], '">';
    echo '</div>';

    echo '<div class="td1">';
    echo '<input type="text" name="price" value="', $row['price'], '">';
    echo '</div>';

    echo '<div class="td2">';
    echo '<input type="submit" value=" 更 新 ">';
    echo '</div>';

    echo '</form>';

    echo '<form class="ib" action="book.php" method="post">';
    echo '<input type="hidden" name="command" value="delete">';
    echo '<input type="hidden" name="book-id" value="', $row['book-id'], '">';
    echo '<input type="submit" value=" 削 除 ">';
    echo '</form><br>';
    echo "\n";
}
?>
<form action="book.php" method="post">
    <input type="hidden" name="command" value="insert">
    <div class="td0"></div>
    <div class="td1"><input type="text" name="title"></div>
    <div class="td1"><input type="text" name="price"></div>
    <div class="td2"><input type="submit" value=" 追 加 "></div>
</form>
<?php require '../footer.php'; ?>