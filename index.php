<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta
            name="viewport"
            content="width=device-width"
    >
    <meta
            http-equiv="X-UA-Compatible"
            content="ie=edge"
    >
    <title>Document</title>
    <link
            rel="stylesheet"
            href="style.css"
    >
</head>
<body>
<form
        action=""
        method="POST"
        enctype="multipart/form-data"
>
    <label class="input-file">
        <span class="input-file-text"></span>
        <input type="file" name="fileName" class="input-form">
        <span class="input-file-btn">Выберите файл</span>
    </label>
    <div>
        <button
                type="submit"
                name="submit"
        >Получить обработаный файл
        </button>
    </div>

</form>
<?php
require 'XLSXWriter.php';
require 'XLSXReader.php';
if (isset($_POST['submit'])) {
    if (move_uploaded_file($_FILES['fileName']['tmp_name'], 'uploads/input.xlsx')) {
        echo 'Успех';
    } else {
        echo 'Ошибка';
    }
    $counter = 1;
    $fileName = 'uploads/input.xlsx';
    $reader = new XLSXReader('uploads/input.xlsx');
    $writer = new XLSXWriter();
    $data = [];
    $reader = $reader->getSheet(1)->getData();
    foreach ($reader as $row) {
        foreach ($row as $key => $ceil) {
            if ($counter % 2 === 0) {
                $row[$key] = mb_strtoupper($ceil . " Чётное");
            } else {
                $row[$key] = mb_strtolower($ceil . " Нечётное");
            }

        }
        $data[] = $row;
        $counter++;
    }
    $writer->writeSheet($data);
    $writer->writeToFile('output.xlsx');

    $file = 'output.xlsx';
    ob_end_clean();
    header('Content-Description: File Transfer');
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Transfer-Encoding: Binary");
    header("Content-disposition: attachment; filename=\"" . basename($file) . "\"");
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    readfile($file);
}
?>

<script>
    let text = document.querySelector('.input-file-text')
    let name = document.querySelector('.input-form');
    name.addEventListener('change', (e) => {
        let file = e.target.value.split('').reverse().join('');
        let rep = file.indexOf('\\')
        text.innerText = file.slice(0, rep).split('').reverse().join('');
    })
</script>
</body>
</html>






