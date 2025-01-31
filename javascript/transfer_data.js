import { Toast } from "../backend/db/collection/sweetalert_config.js";
var table1 = new DataTable('#myTable1',
    {
        dom: 'Bfrtip',
        buttons: [{
            extend: 'excelHtml5',
            text: 'Excel',
            exportOptions: { columns: ':not(:last-child)' }
        }, {
            extend: 'csvHtml5',
            text: 'CSV',
            exportOptions: { columns: ':not(:last-child)' }
        }, {
            extend: 'copyHtml5',
            text: 'Copy',
            exportOptions: { columns: ':not(:last-child)' }
        },]
    }
);
$(document).ready(function () {
    var update_sno = 0;
    var update_btn;
    $("#overall_form_data").submit(function (e) {
        e.preventDefault();
        var decide = $("#decide").val();
        const overall_data = $("#overall_form_data").serializeArray();
        const row_length = table1.data().length;
        if (decide == "0") {
            overall_data.push({ name: 'arr_index', value: row_length });
        }
        else {
            overall_data.push({ name: "sno", value: update_sno });
            
        }
        $.ajax({
            url: "./backend/db/transfer/action.php",
            type: "post",
            data: overall_data,
            dataType: "json",
            beforeSend: function () {
                $("#collection_submit").find(".text").val("Loading..");
            },
            success: function (res) {
                console.log("Clicked");
                if (res[0] == 1) {
                    if (decide == "0") {
                            table1.row.add(res[1]).draw(false);
                        Toast.fire({
                            html: `<p class='alert_content'>The data has inserted in overall Table</p>`,
                            icon: "success",
                            customClass: {
                                timerProgressBar: 'bar_success',
                                icon: "icon_success"
                            }
                        });
                        $("#overall_form_data").trigger("reset");
                    } else {
                            table1.row(update_btn.parents("tr")).data(res[1]).draw(false);
                        Toast.fire({
                            html: `<p class='alert_content'>The data is updated in overall table</p>`,
                            icon: "success",
                            customClass: {
                                timerProgressBar: 'bar_success',
                                icon: "icon_success"
                            }
                        });
                        $("#decide").val(0);
                        $("#overall_form_data").trigger("reset");
                        $('#overall_table_form').css('display', 'none');
                    }
                }
                else if (res[0] = 404) {
                    Toast.fire({
                        html: `<p class='alert_content'>${res[1]}</p>`,
                        icon: "error",
                        customClass: {
                            timerProgressBar: 'bar_error',
                            icon: "icon_error"
                        }
                    });
                }
                else {
                    swal.fire({
                        title: "ERROR",
                        html: `<p class='alert_content'>${res}</p>`,
                        icon: "error",
                        customClass: {
                            timerProgressBar: 'bar_success',
                            icon: "icon_success"
                        }
                        
                    });
                }
                $("#collection_submit").val("Save");
                // console.log($("#overall_form_data"));
            }
        });
    });
    $("body").on("click", ".delete", function (e) {
        e.preventDefault();
        var ad = $(this).attr("ad");
        let btn = $(this);
        console.log('delete');
        $.ajax({
            type: 'POST',
            url: './backend/db/transfer/delete.php',
            data: { admin: ad },
            beforeSend: function () {
                $(btn).find(".text").text("Deleting...");
            },
            success: function (res) {
                const taken = parseInt(res);
                if (taken == 0) {
                    table1.row(btn.parents('tr')).remove().draw(false);
                    Toast.fire({
                        html: `<p class='alert_content'>The data is deleted in overall table</p>`,
                        icon: "success",
                        customClass: {
                            timerProgressBar: 'bar_success',
                            icon: "icon_success"
                        }
                    });
                }
                else {
                    Toast.fire({
                        html: `<p class='alert_content'>The data is not deleted in overall table</p>`,
                        icon: "error",
                        customClass: {
                            timerProgressBar: 'bar_error',
                            icon: "icon_error"
                        }
                    });
                }
            }
        });
    });
    $("body").on("click", ".update", function (e) {
        e.preventDefault();
        console.log("update");
        var ad = $(this).attr("ad");
        update_btn = $(this);
        $("#decide").val(ad);
        var row = $(this);
        update_sno = row.closest("tr").find("td:eq(0)").text();
        var admin = row.closest("tr").find("td:eq(1)").text();
        $("#admission").val(admin);
        var stu_name = row.closest("tr").find("td:eq(2)").text();
        $("#stu_name").val(stu_name);
        var class_ = row.closest("tr").find("td:eq(3)").text();
        $("#class").val(class_);
        var section = row.closest("tr").find("td:eq(4)").text();
        $("#section").val(section);
        var year = row.closest("tr").find("td:eq(5)").text();
        $("#year").val(year);
        var pending = row.closest("tr").find("td:eq(6)").text();
        $("#pending").val(pending);
        $('#overall_table_form').css('display', 'flex');

    });

});