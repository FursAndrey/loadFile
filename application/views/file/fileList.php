<?
ini_set('display_errors', 1);	//1 - показывать ошибки, 0 - скрывать
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>php файлы</title>
        <meta charset="utf-8" />
        <link href="/css/style.css" type="text/css" rel="stylesheet"/>
    </head>
    <body>
        <p><a href="/regAuth/page2">Ссылка на 2-ю страницу</a></p>
        <p>Привет, <?=$login?></p>
        <p><a href="/File">Добавить файл</a></p>
        <p><a href="/RegAuth/logOut">Выход</a></p>
        <div id="box1">
            <?
            foreach ($file as $row){
                $mas = [];
                foreach ($row as $key => $dat){
                    $mas[$key] = $dat;
                }?>
                <div dataid="<?=$mas['id']?>" dataUserID="<?=$mas['userID']?>">
                    <p><a href="/<?=$mas['adres']?>" download><?=$mas['name']?></a></p>
                    <p>Размер файла: <?echo round($mas['size']/1000000,2)?> МБ</p>
                    <p >Владелец файла: <?=$mas['login']?></p>
                    <p><a href="/File/fileDel/<?=$mas['id']?>">Удалить файл</a></p>
                </div>
            <?}?>
        </div>
    </body>
</html>
