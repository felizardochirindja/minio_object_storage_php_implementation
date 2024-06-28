<?php

use Modules\Storage\Libs\Minio;

if (isset($_FILES['fileToUpload'])) {
    $file = $_FILES['fileToUpload'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo "Erro ao fazer upload: " . $file['error'];
    } else {
        $minio = new Minio;

        $minio->write('uploads', $file);

        header('location: /storage/upload');
    }
}
