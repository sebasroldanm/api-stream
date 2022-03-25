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
    <style>
        .img-thumbnail {
            max-height: 150px;
        }
    </style>

    <div class="container">
        <h2>List Mods</h2>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Nickname</th>
                        <th>Avatar</th>
                        <th>Preview</th>
                        <th>widget</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($result as $key => $info) {
                    ?>
                        <tr>
                            <td><?php echo $info->username ?></td>
                            <td>
                                <img src="<?php echo $info->avatarUrl ?>" alt="Model" class="img-thumbnail">
                            </td>
                            <td>
                                <img src="<?php echo $info->previewUrlThumbSmall ?>" alt="Model" class="img-thumbnail">
                            </td>
                            <td>
                                <img src="<?php echo $info->widgetPreviewUrl ?>" alt="Model" class="img-thumbnail">
                            </td>
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
