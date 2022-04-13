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
                        <th>Avatar</th>
                        <th>Descripción</th>
                        <th class="desktop">Ultima Conexión</th>
                        <th>Identificador</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($result as $key => $info) {
                    ?>
                        <tr>
                            <td>
                                <img src="<?php echo $info->avatarUrl ?>" alt="Model" class="img-thumbnail">
                            </td>
                            <td><?php echo $info->description ?></td>
                            <td class="desktop"><?php echo $info->offlineStatusUpdatedAt ?></td>
                            <td><?php echo $info->user_id ?></td>
                            <td><?php echo ($info->state) ? '<a href="' . url('/') . '/ver/' . $info->user_id . '"><span class="badge badge-pill badge-success">Online</span></a>' : '<span class="badge badge-pill badge-danger">Offline</span>' ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <style>
        @media (max-width: 600px) {
            .desktop {
                display: none;
            }
        }
    </style>

</body>

</html>
