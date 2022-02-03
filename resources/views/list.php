<!DOCTYPE html>
<html lang="es">

<head>
    <title>List Mods</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>

    <div class="container">
        <h2>List Mods</h2>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nickname</th>
                        <th>Platform</th>
                        <th>Last Stream</th>
                        <th>Update at</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($result as $key => $info) {
                    ?>
                        <tr>
                            <td><?php echo $info->nickname ?></td>
                            <td><?php echo $info->platform ?></td>
                            <td><?php echo $info->stream ?></td>
                            <td><?php echo $info->updated_at ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>
