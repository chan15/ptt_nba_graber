<?php

include 'vendor/autoload.php';
include 'config.php';
include 'NBASearch.php';

$result = '';
$pttId = $_GET['id'];

if ($pttId) {
    $nbaSearch = new NBASearch($pttId);
    $nbaSearch->run();
    $result = $nbaSearch->result();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NBA Search</title>
    <style>
    body {
        font-size: 0.8em;
    }
    .content {
        margin-top: 16px;
        line-height: 20px;
    }
    h3 {
        margin: 0;
        padding: 0;
    }
    </style>
</head>
<body>
    <form action="index.php", method="get">
        <input type="text" name="id" value="<?php echo $pttId; ?>">
        <button type="submit">go</button>
    </form>
    <div class="content"><?php echo $result; ?></div>
</body>
</html>
