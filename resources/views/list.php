<!DOCTYPE html>
<html lang="es">

<head>
    <title>List Mods</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

    <div class="container">
        <h2>List Mods</h2>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Nickname</th>
                        <th>Platform</th>
                        <th>Last Stream</th>
                        <th>Online</th>
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
                            <td><?php echo ($info->online) ? '<span class="badge badge-pill badge-success">Online</span>' : '<span class="badge badge-pill badge-danger">Offline</span>' ?></td>
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
