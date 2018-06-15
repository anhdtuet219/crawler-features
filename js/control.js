//check time to reload list jobs
var reload = true;

function changeTab(evt, tabName) {
    $("#example_filter").hide();
    var i, tabcontent, tablinks;
    //lay danh sach cac div cua moi tab roi an di
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    //lay danh sach cac tab (button) co class name la tablinks roi xet chung deu khong active
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    //cho hien thi div cua tab duoc chon, them class name active vao tab de hien thi xem tab nao duoc chon 
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
    //goi ham de xuat du lieu
    if (tabName === 'crawler') {

    }
    else if (tabName === 'listJob') {
        on_loadListJob();
    }
}

function on_loadListJob(){
    console.log(reload)
    if (reload) {
        reload = false;
        $.ajax({
            type: 'GET',
            url: 'process.php/jobs',
            beforeSend: function () {
                console.log("start load list...");
                $('#preload').fadeIn('fast');
                $(".preloading").css("display", "block");
            },
            success: function (data) {
                $('#preload').fadeOut('fast');
                loadData(data);
            },
            error: function (e) {
                console.log(e);
                $('#preload').fadeOut('fast');
                alert("Gặp lỗi trong quá trình lấy dữ liệu!");
            }
        });
    }

}

function loadDataOneLine(data, i) {
    var html = "";
    html+='<tr>'
    html+='<td>'+data[i].job_name+'</td>'
    html+='<td>'+data[i].job_type+'</td>'
    html+='<td>'+data[i].job_location+'</td>'
    html+='<td>'+data[i].job_company+'</td>'
    html+='<td>'+data[i].job_salary+'</td>'
    html+='<td><a target="_blank" href="' + data[i].job_link + '">Xem chi tiết</a></td>';
    var pos = data[i].source_id;
    switch (pos) {
        case '1':
            html+='<td>https://vieclam24h.vn/</td>';
            break;
        case '2':
            html+='<td>https://careerlink.vn/</td>';
            break;
        case '3':
            html+='<td>https://careerbuilder.vn/</td>';
            break;
    }

    html+='</tr>';
    return html;
}

function loadData(data) {
    if(data.length > 0){
        var br = document.createElement('br');
        var jobTable = document.createElement('table');
        jobTable.id = "example";
        jobTable.className = "display";
        jobTable.style.tableLayout = "fixed";

        var arrHeader = ["Vị trí", "Ngành nghề", "Địa điểm", "Công ty", "Mức lương", "Nguồn", "Xem chi tiết"];
        //add header to  datatable
        var thead = document.createElement('thead');
        var trHead = document.createElement('tr');
        for (var i = 0; i < arrHeader.length; i++) {
            var th = document.createElement('th');
            th.textContent = arrHeader[i];
            if (i == arrHeader.length - 1) {
                th.style.textAlign = "center";
            }
            trHead.appendChild(th);
            
        }
        thead.appendChild(trHead);
        jobTable.appendChild(thead);

        //add data to datatable
        var tbody = document.createElement('tbody');
        for (var i = 0; i < data.length; i++) {
            var trBody = document.createElement('tr');
            var thName = document.createElement('td');
            thName.textContent = data[i].job_name;
            trBody.appendChild(thName);
            
            var thType = document.createElement('td');
            thType.textContent = data[i].job_type;
            trBody.appendChild(thType);
            
            var thLocation = document.createElement('td');
            thLocation.textContent = data[i].job_location;
            trBody.appendChild(thLocation);
            
            var thCompany = document.createElement('td');
            thCompany.textContent = data[i].job_company;
            trBody.appendChild(thCompany);
            
            var thSalary = document.createElement('td');
            thSalary.textContent = data[i].job_salary;
            trBody.appendChild(thSalary);
            
            var thSource = document.createElement('td');
            var pos = data[i].source_id;
            switch (pos) {
                case '1':
                    thSource.textContent = 'https://vieclam24h.vn/';
                    break;
                case '2':
                    thSource.textContent = 'https://careerlink.vn/';
                    break;
                case '3':
                    thSource.textContent = 'https://careerbuilder.vn/';
                    break;
            }
            trBody.appendChild(thSource);

            var thLink = document.createElement('td');
            var a = document.createElement('a');
            a.target = "_blank";
            a.href = data[i].job_link;
            a.textContent = "Xem";
            thLink.style.textAlign = "center";
            thLink.appendChild(a)
            trBody.appendChild(thLink);
            
            tbody.appendChild(trBody);
            tbody.id = "listJob-tbody";
        }
        jobTable.appendChild(tbody);
        var listJob = document.getElementById("listJob");
        listJob.innerHTML = "";
        listJob.appendChild(jobTable);


        $(document).ready(function() {
            // Setup - add a text input to each footer cell
            var head = $('#example thead');
            var tr = document.createElement('tr');

            $('#example thead th').each( function (index) {
                var th = document.createElement('th');
                th.className = "search_input";
                var title = $(this).text();
                var input = document.createElement('input');
                if (index !== 6) {
                    input.className = 'form-control';
                    input.type = 'text';
                    input.placeholder = 'Tìm kiếm ' + title;
                    th.append(input);
                    //$(this).html( '<input type="text" placeholder="Tìm kiếm '+title+'" />' );
                } else {
                    th.style.width = "20%";
                }
                tr.append(th);
            });
            head.append(tr);

            // DataTable
            var table = $('#example').DataTable({
                "order": [],
                "language": {
                    "lengthMenu": "Hiển thị _MENU_ công việc trong một trang",
                    "zeroRecords": "Không có dữ liệu",
                    "info": "Trang _PAGE_/_PAGES_",
                    "infoEmpty": "Không có dữ liệu",
                    "infoFiltered": "(Lọc từ _MAX_ tổng bản ghi)",
                    "paginate": {
                        "next":       "Trang tiếp",
                        "previous":   "Trang trước",
                        "first":      "Trang đầu",
                        "last":       "Trang cuối"
                    },
                },
                rowReorder: {
                    selector: 'td:nth-child(2)'
                },
                responsive: true,
                "scrollX": true,
                "scrollXollapse": true,
                "ordering": false
            });

            if ($.fn.DataTable.isDataTable( '#example' )) {
                table.columns.adjust();
            }

            // Apply the search
            table.columns().every( function () {
                var that = this;

                $( 'input', this.header() ).on( 'keyup change', function () {
                    if ( that.search() !== this.value ) {
                        that
                            .search( this.value )
                            .draw();
                    }
                } );
            } );

            $('#example_filter').css("display", "none");
            // $('#example_info').css("display", "none");


        } );
    }
}
