<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/bootstrap-select.css">
</head>

<body>
    <?php
        include "helpers/DBHelper.php";
        header("content-type: text/html; charset=UTF-8");
        set_time_limit(2147483647);
    ?>

    <div class="tab">
        <button class="tablinks active" onclick="changeTab(event, 'crawler')">Lấy danh sách công việc</button>
        <button class="tablinks" onclick="changeTab(event, 'listJob')">Danh sách việc làm</button>
    </div>
    <div id="background">
        <div id="background-inside">
            <div id="crawler" class="tabcontent">
                <h2>Lấy danh sách công việc</h2>
                <br>
                <form name="get-jobs-form">
                    <div class="form-group" >
                        <label for="source-get-jobs">Lựa chọn nguồn lấy tin:</label>
                        <select class="selectpicker form-control" name="source" id="source-get-jobs" required="true" onchange="getCareers()" multiple>
                        </select>
                        <br>
                        <br>
                        <label for="type-get-jobs">Lựa chọn ngành nghề:</label>
                        <select class="selectpicker form-control" data-live-search="true" data-actions-box="true" multiple name="career"  id="career-get-jobs">
                        </select>
                        <br>
                        <br>
                        <label for="limit-get-jobs">Giới hạn số công việc lấy được của mỗi ngành nghề: </label>
                        <input class="form-control" type="number" name="limit_jobs" id="limit-get-jobs" min="1" max="1000" required="true">
                        <br>
                        <button type="submit" id="submit-crawler-button" class="btn btn-info btn-block">Start</button>
                    </div>
                </form>
            </div>

            <!--End setting 1-->

            <div id="listJob" class="tabcontent">
            </div>
        </div>
    </div>
    <div id="id-loading" class="preloading" style="display: none">
        <div id="preload" class="preload-container">
            <span class="preload-icon rotating">
                <img src="images/loading.png" width="100" height="100"/>
            </span>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="js/bootstrap-select.js"></script>
    <script src="js/control.js"></script>
    <script>
        //danh sach tat ca cac source id cua select
        var listAllSourceIds = [];
        var listAllSourceNames = [];
        //mang luu trang thai viec lay du lieu danh sach cong viec tu mot nguon
        //voi true la da lay va false la chua lay
        var checkCrawlSource = [false, false, false];
        //mang su dung de luu tru cac gia tri duoc chon
        function getCareers() {
          console.log(listAllSourceIds);
            var selectSource = document.getElementById('source-get-jobs');
            //lay tat ca sourceId cua cac nguon du lieu dang duoc lua chon
            var selectedSourceIds = $('#source-get-jobs').val();
            for (var index = 0; index < listAllSourceIds.length; index++) {
                console.log(index);
                var sourceId = listAllSourceIds[index];
                //lay index cua phan tu trong mang source duoc chon
                var indexChosenSource = selectedSourceIds.indexOf(sourceId);
                //neu indexChosenSource khong ton tai, nghia la khong nam trong array thi se xoa phan tu do di khoi select
                if (indexChosenSource < 0) {
                    checkCrawlSource[index] = false;
                    $('[data-id="' + sourceId + '"').remove();
                    $('#career-get-jobs').selectpicker('refresh');
                }
                //neu indexChosenSource ton tai thi ta se lay du lieu voi source nao co trang thai la chua lay du lieu
                else {
                    if (checkCrawlSource[index] == false) {
                        //append options to careers dropdown list
                        var sourceName = listAllSourceNames[index];
                        getCareersAjax(sourceId, sourceName);
                        checkCrawlSource[index] = true;
                    }
                }
            }
        }

        function getCareersAjax(sourceId, sourceName) {
            var selectCareer = document.getElementById('career-get-jobs');
            $.ajax({
                type: 'GET',
                url: 'process.php/careers?source_id=' + sourceId,
                beforeSend: function() {
                    //to do
                    $('#preload').fadeIn('fast');
                    $(".preloading").css("display", "block");
                    console.log("Starting to get option for career form");
                },
                success: function(data) {
                    $('#preload').fadeOut('fast');
                    //create option and add to select
                    for (var i = 0; i < data.length; i++) {
                        var option = document.createElement('option');
                        option.value = data[i].link;
                        option.text = data[i].title;
                        option.setAttribute('data-id', sourceId);
                        option.setAttribute('data-subtext', "(" + sourceName + ")")
                        selectCareer.appendChild(option);
                    }
                    $('#career-get-jobs').selectpicker('refresh');
                },
                error: function() {
                    $('#preload').fadeOut('fast');
                    console.log("Network or api is Failed")
                }
            });
        }
    </script>

    <script>
        $("#example_filter").hide();
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        document.getElementById('crawler').style.display = "block";

        $(document).ready(function() {
            //append options to sources dropdown list
            var selectSource = document.getElementById('source-get-jobs');
            $.ajax({
                type: 'GET',
                url: 'process.php/sources',
                beforeSend: function() {
                    //to do
                    console.log("Starting to get option for form");
                },
                success: function(data) {
                    //create option and add to select
                    for (var i = 0; i < data.length; i++) {
                        console.log("Running...");
                        var option = document.createElement('option');
                        option.value = data[i].source_id;
                        option.text = data[i].source_name;
                        selectSource.appendChild(option);
                    }
                    $('#source-get-jobs').selectpicker('refresh');
                    $('#source-get-jobs option').each(function() {
                        listAllSourceIds.push($(this).val());
                        listAllSourceNames.push($(this).text());
                    });
                },
                error: function() {
                    console.log("Network or api is Failed")
                }
            });
        });

        $(function() {
            //handle submit form event
            var form = $('form');
            form.submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: 'process.php/jobs',
                    data: {
                        source: $('#source-get-jobs').val().join(),
                        career_link: $('#career-get-jobs').val().join(),
                        career_title: $('[data-id="career-get-jobs"]').attr("title"),
                        limit_jobs: $('#limit-get-jobs').val()
                    },
                    beforeSend: function () {
                        console.log("start crawling...");
                        $('#preload').fadeIn('fast');
                        $(".preloading").css("display", "block");
                    },
                    success: function (data) {
                        $('#preload').fadeOut('fast');
                        reload = true;
                        alert("Lấy dữ liệu thành công!");
                    },
                    error: function (e) {
                        console.log(e);
                        $('#preload').fadeOut('fast');
                        reload = true;
                        alert("Gặp lỗi trong quá trình lấy dữ liệu!");
                    }
                });
            });
        })
    </script>

</body>
<?php
//autoload of classes
function __autoload($className) {
    $filename = $className . ".php";
    if (is_readable($filename)) {
        require $filename;
    }
}
?>
</html>
