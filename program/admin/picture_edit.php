<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" type="image/png" href="../img/icon.png" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/ionicons.min.css">
    <link rel="stylesheet" href="dist/css/AdminLTE.css">
    <link rel="stylesheet" href="dist/css/skins/skin-blue-light.css">
    <link href="https://fonts.googleapis.com/css?family=Kanit" rel="stylesheet">

    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <?php
        include("php/htmlCore.php");
        include('indyEngine/engineControl.php');
        setInclude('htmlControl,dbControl,IDUControl,sessionControl');
        setPlugin('jquery,jquery-ui,alert,loaderbar,ckeditor');
        setPermission('login');

        HtmlCore::getTitle();

        $query = new DBControl();
        $type = isset($_GET['type']) ? $_GET['type'] : '';
        $data = array('name' => '', 'detail' => '');

        if (!isset($_GET['id']) || $_GET['id'] === '') {
            $page_type = 'insert';
        } else {
            $page_type = 'edit';
        }

        if ($page_type === 'insert') {
            $id = intval($query->queryDB_to_string("SELECT MAX(id) FROM picture")) + 1;
        } else {
            $id = intval($_GET['id']);
            $sql = 'SELECT * FROM picture WHERE id=' . $id;
            $row = $query->queryDB_to_row($sql);
            if (is_array($row) && count($row) > 0) {
                $data = array_merge($data, $row);
            }
        }

        $safeType = htmlspecialchars($type, ENT_QUOTES, 'UTF-8');
        $safeName = htmlspecialchars($data['name'], ENT_QUOTES, 'UTF-8');
        $safeDetail = htmlspecialchars($data['detail'], ENT_QUOTES, 'UTF-8');
    ?>
</head>

<body class="hold-transition skin-blue-light" style="font-family: 'Kanit', sans-serif;">
<div class="wrapper">
    <header class="main-header">
        <?php HtmlCore::getTextHeader(); ?>
        <nav class="navbar navbar-static-top" role="navigation">
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <?php HtmlCore::getMenuHeader(); ?>
        </nav>
    </header>

    <aside class="main-sidebar">
        <section class="sidebar">
            <?php HtmlCore::getMenu('book'); ?>
        </section>
    </aside>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <?php echo $safeType; ?>
                <small>TRC admin</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                <li class="active"><?php echo $safeType; ?></li>
            </ol>
        </section>

        <section class="content" style="font-family: 'Kanit'">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <form id="form" class="form-horizontal form-label-left" enctype="multipart/form-data">
                            <div class="box-header">
                                <h3 class="box-title"><b><?php echo $safeType; ?></b></h3>
                            </div>

                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <input type="hidden" name="id" value="<?php echo intval($id); ?>">

                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="form-group" style="padding-right:10px;">
                                                <label>หัวข้อ</label>
                                                <input type="text" class="form-control" placeholder="หัวข้อ ..." name="name" value="<?php echo $safeName; ?>" />
                                                <input type="hidden" name="type" value="<?php echo $safeType; ?>">
                                            </div>
                                        </div>

                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label for="uploadFile">รูปภาพ</label>
                                                <input id="uploadFile" style="display:none" type="file" accept="image/jpeg,image/png,image/gif,image/webp,application/pdf" />
                                                <br>
                                                <div id="upload" class="btn btn-info">เลือกไฟล์</div>
                                                <span id="thumbnail"></span>
                                                <p id="show_loader" class="help-block"></p>
                                                <div id="show_error" class="help-block text-danger"></div>
                                                <input name="detail" type="hidden" value="<?php echo $safeDetail; ?>">

                                                <?php
                                                    if (!empty($data['detail'])) {
                                                        $detailPath = $data['detail'];
                                                        $fileName = basename($detailPath);
                                                        $fileId = pathinfo($fileName, PATHINFO_FILENAME);
                                                        $ext = strtolower(pathinfo($detailPath, PATHINFO_EXTENSION));
                                                        $previewSrc = '../' . $detailPath;
                                                        $safePreviewSrc = htmlspecialchars($previewSrc, ENT_QUOTES, 'UTF-8');
                                                        $safeFileName = htmlspecialchars($fileName, ENT_QUOTES, 'UTF-8');
                                                        $safeFileId = htmlspecialchars($fileId, ENT_QUOTES, 'UTF-8');

                                                        if ($ext === 'pdf') {
                                                            echo '<div id="' . $safeFileId . '" class="file-preview"><iframe src="' . $safePreviewSrc . '" width="100%" height="500px"></iframe><div class="card_bt" onclick="deleteFile(\'' . $safeFileName . '\', event)"><i class="fa fa-trash"></i></div></div>';
                                                        } else {
                                                            echo '<div id="' . $safeFileId . '" class="card file-preview"><img src="' . $safePreviewSrc . '" style="height:150px"><div class="card_bt" onclick="deleteFile(\'' . $safeFileName . '\', event)"><i class="fa fa-trash"></i></div></div>';
                                                        }

                                                        echo '<script>document.addEventListener("DOMContentLoaded", function(){ $("#upload").hide(); });</script>';
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="box-footer text-center">
                                <button type="submit" class="btn btn-primary">บันทึก</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <footer class="main-footer">
        <?php HtmlCore::getMenuFooter(); ?>
    </footer>
</div>

<script src="bootstrap/js/bootstrap.min.js"></script>
<script src="dist/js/app.min.js"></script>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/drilldown.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/heatmap.js"></script>

<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
</body>
</html>

<script>
$(function () {
    $("#form").submit(function(event){
        event.preventDefault();

        var id = $("input[name='id']").val();
        var name = $("input[name='name']").val().split(',').join('&#44;');
        var detail = $("input[name='detail']").val().split(',').join('&#44;');
        var type = $("input[name='type']").val().split(',').join('&#44;');
        var values = id + ',' + name + ',' + detail + ',' + type;

        <?php
            $fieldsname = "id,name,detail,type";
            $values_add = '';
            $link = 'picture.php?type=' . $type;

            if ($page_type === 'insert') {
                echo "insertDB('picture','" . $fieldsname . "',values" . $values_add . ",'" . $link . "');";
            } else {
                echo "updateDB('picture','" . $id . "','" . $fieldsname . "',values" . $values_add . ",'" . $link . "');";
            }
        ?>
    });

    $("#upload").on("click", function(event){
        event.preventDefault();
        $("#uploadFile").trigger("click");
    });

    $('#uploadFile').on('change', function() {
        var fileData = $('#uploadFile').prop('files')[0];

        if (!fileData) {
            $('#show_loader').html('<span class="text-danger">กรุณาเลือกไฟล์ก่อน</span>');
            return;
        }

        var allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'application/pdf'];
        if ($.inArray(fileData.type, allowedTypes) === -1) {
            $('#show_loader').html('<span class="text-danger">Invalid file format. รองรับเฉพาะ jpg, jpeg, png, gif, webp, pdf</span>');
            $('#uploadFile').val('');
            return;
        }

        var formData = new FormData();
        formData.append('file', fileData);

        $.ajax({
            url: 'php/ajax_upload_image.php?name=detail',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            type: 'post',
            beforeSend: function(){
                $('#show_error').html('');
                $('#show_loader').html('<img src="images/loader.gif" alt="รอสักครู่">');
            },
            success: function(response){
                if (!response || response.success !== true) {
                    var msg = response && response.message ? response.message : 'อัปโหลดไฟล์ไม่สำเร็จ';
                    $('#show_loader').html('<span class="text-danger">' + msg + '</span>');
                    return;
                }

                $("input[name='detail']").val(response.path);
                $('.file-preview').remove();
                $('#show_loader').html(response.preview_html);
                $('#upload').hide();
            },
            error: function(xhr){
                $('#show_loader').html('<span class="text-danger">เกิดข้อผิดพลาดในการอัปโหลดไฟล์</span>');
                console.log(xhr.responseText);
            }
        });
    });
});

function deleteRecord(table, id){
    bootbox.confirm("Are you sure?", function(result){
        if(result){
            deleteDB(table, id, 'picture.php?type=<?php echo addslashes($type); ?>');
        }
    });
}

function deleteFile(file, event){
    if (event) {
        event.preventDefault();
    }

    $.ajax({
        type: "POST",
        url: "php/ajax_delete_file.php",
        data: {
            file: file,
            tb: 'picture',
            field: 'detail',
            key: 'id',
            id: <?php echo intval($id); ?>
        },
        success: function(result){
            $('#show_error').html(result);
            $("input[name='detail']").val('');
            $('.file-preview').remove();
            $('#show_loader').html('');
            $('#upload').fadeIn();
        }
    });
}
</script>
