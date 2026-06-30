<!DOCTYPE html>
<html data-theme="cupcake" lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="/css/style.css" rel="stylesheet" />
</head>
<body>
    <h1 class="text-red-500 text-4xl">Welcome to the FlightPHP Skeleton Example!</h1>
    <?php if(!empty($message)) { ?>
    <h3 class="text-blue-500"><?=$message?></h3>
    <?php } ?>
    <form action="/" method="POST">
        <button class="btn btn-primary" type="submit">Bom dia</button>
    </form>
</body>
</html>
