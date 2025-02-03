import { Toast } from "../backend/db/collection/sweetalert_config.js";
    const sort_in_class= document.getElementById("class_types");
const class_sort_event = new Event("change");
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
var table2 = new DataTable('#defaulters_list',
    {
        ordering: false,
        pageLength: -1,
        layout:
        {
            topStart:  {
                buttons: ['excel']
            },
            bottomEnd: null,
            bottomStart:null
        },
    }
);
$(document).ready(function () {
    var index = 0;
    var update_btn;
    const table_body=document.getElementById("tbody1");
    const defaults_close = document.querySelector("#defaults_close svg");
    const defaulter_table = document.getElementById("defaulter_table");
    defaults_close.addEventListener("click", (e) => {
        e.preventDefault();
        defaulter_table.style.display = "none";
        table2.rows().remove();
        console.log("removed");
    });
    //TODO:n change event that is sort the table based on the selected option 

    $("#save_csv").click(function () {
        const csv_file = document.getElementById("overall_csv").files;
        console.log(csv_file);
        const file = new FormData();
        const action = $("#mode").val();
        file.append("action",action)
        if (csv_file.length > 0) {
            file.append("csv_file", csv_file[0]);
            $.ajax({
                type: 'POST',
                url: 'backend/db/defaulters/overall_sheet.php',
                data: file,
                contentType: false,
                processData: false,
                success: function (res) {
                    let taken = parseInt(res);
                    console.log(res);
                    if (taken == 1) {

                        Toast.fire({
                            html: `<p class='alert_content'>CSV data successfully loaded into the database!</p>`,
                            icon: "success",
                            customClass: {
                                timerProgressBar: 'bar_success',
                                icon: "icon_success"
                            }
                        });
                    }
                    else {
                        Toast.fire({
                            html: `<p class='alert_content'>${res}</p>`,
                            icon: "error",
                            customClass: {
                                timerProgressBar: 'bar_error',
                                icon: "icon_error"
                            }
                        });
                    }
                }
            });
        }
        else {
            Toast.fire({
                position: "top",
                html: `<p class='alert_content'>kindly select overall fees csv file first</p>`,
                icon: "warning",
                customClass: {
                    timerProgressBar: 'bar_warning',
                    icon: "icon_warning"
                }
            });
        }
    });
    $("#calc_").click(function () {
        const calc_option = $("#calc_select").val();
        console.log(calc_option);
        $.ajax(
            {
                url: "./backend/db/defaulters/calculation.php",
                type: "post",
                data:{option:calc_option},
                dataType:'json',
                beforeSend: function () {
                    $("#calc_").find(".text").val("Loading..");
                },
                success: function (res) {
                    console.log(res);
                    if (res[0] == 200)
                    {
                        table2.destroy();
                        $("#default_heading").text("Total Fee Collections and Defaulters");
                        $("#defaulter_head").html(res[1]);
                        $("#defaulter_body").html(res[2]);
                        table2 = new DataTable('#defaulters_list',
                            {
                                ordering: false,
                                pageLength: -1,
                                layout:
                                {
                                    topStart: {
                                        buttons: ['excel']
                                    },
                                    bottomEnd: null,
                                    bottomStart: null
                                },
                            }
                        );
                        $("#defaulter_table").css("display", "block");
                    }
                    else if (res[0] == 201)
                    {
                        table2.destroy();
                        $("#default_heading").text("Total Defaulters of Term I");
                        $("#defaulter_head").html(res[1]);
                        $("#defaulter_body").html(res[2]);
                        table2 = new DataTable('#defaulters_list',
                            {
                                ordering: false,
                                pageLength: -1,
                                layout:
                                {
                                    topStart: {
                                        buttons: ['excel']
                                    },
                                    bottomEnd: null,
                                    bottomStart: null
                                },
                            }
                        );
                        $("#defaulter_table").css("display", "block");
                    }
                    else if (res[0] == 202)
                    {
                        table2.destroy();
                        $("#default_heading").text("Total Defaulters of Term II");
                        $("#defaulter_head").html(res[1]);
                        $("#defaulter_body").html(res[2]);
                        table2 = new DataTable('#defaulters_list',
                            {
                                ordering: false,
                                pageLength: -1,
                                layout:
                                {
                                    topStart: {
                                        buttons: ['excel']
                                    },
                                    bottomEnd: null,
                                    bottomStart: null
                                },
                            }
                        );
                        $("#defaulter_table").css("display", "block");
                    }
                    else if (res[0] == 203)
                    {
                        table2.destroy();
                        $("#default_heading").text("Total Defaulters of Term III");
                        $("#defaulter_head").html(res[1]);
                        $("#defaulter_body").html(res[2]);
                        table2 = new DataTable('#defaulters_list',
                            {
                                ordering: false,
                                pageLength: -1,
                                layout:
                                {
                                    topStart: {
                                        buttons: ['excel']
                                    },
                                    bottomEnd: null,
                                    bottomStart: null
                                },
                            }
                        );
                        $("#defaulter_table").css("display", "block");
                    }
                    else if (res[0] == 204)
                    {
                        table2.destroy();
                        $("#default_heading").text("Total Defaulters of Term III");
                        $("#defaulter_head").html(res[1]);
                        $("#defaulter_body").html(res[2]);
                        table2 = new DataTable('#defaulters_list',
                            {
                                ordering: false,
                                pageLength: -1,
                                layout:
                                {
                                    topStart: {
                                        buttons: ['excel']
                                    },
                                    bottomEnd: null,
                                    bottomStart: null
                                },
                            }
                        );
                        $("#defaulter_table").css("display", "block");
                    }
                }
            }
        );
    });
    $("#clear_").click(function () {
        const class_clear = $("#class_types").val();
        Swal.fire({
            title: `Are you sure to clear ${class_clear} class in table `,
            showCancelButton: true,
            confirmButtonText: "Clear",
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                $.ajax(
                    {
                        url: "./backend/db/defaulters/clear_class.php",
                        type: "post",
                        data: {class:class_clear},
                        beforeSend: function () {
                            $("#transfer_btn").find(".text").val("Loading..");
                        },
                        success: function (res) {
                            console.log(res);
                            const taken = parseInt(res);
                            if (taken == 0) {
                                window.resultSet[class_clear] = [];
                                table1.clear().draw();
                                Toast.fire({
                                    html: `<p class='alert_content'>Records of ${class_clear} class cleared in overall table</p>`,
                                    icon: "success",
                                    customClass: {
                                        timerProgressBar: 'bar_success',
                                        icon: "icon_success"
                                    }
                                });
                            }
                            else {
                                Toast.fire({
                                    html: `<p class='alert_content'>Records of ${class_clear} class has not cleared in overall table</p>`,
                                    icon: "error",
                                    customClass: {
                                        timerProgressBar: 'bar_error',
                                        icon: "icon_error"
                                    }
                                });
                            }
                        }
                    });
            }
        });
    });
    var update_sno = 0;
    $("#overall_form_data").submit(function (e) {
        e.preventDefault();
        var decide = $("#decide").val();
        const overall_data = $("#overall_form_data").serializeArray();
        const input_date = document.getElementById("paid_date").value;
        if (input_date != "")
        {
            let [year, month, day] = input_date.split('-');
            var formattedDate = `${day}/${month}/${year}`;
        }
        else
        {
            var formattedDate = "nil";
            }
        overall_data.push({ name: 'formatted_date', value: formattedDate })
        const row_length = table1.data().length;
        if (decide == "0") {
            overall_data.push({ name: 'arr_index', value: row_length });
        }
        else {
            overall_data.push({ name: "sno", value: update_sno });
            overall_data.push({ name: 'arr_index', value: index });
            
        }
        $.ajax({
            url: "./backend/db/defaulters/action.php",
            type: "post",
            data: overall_data,
            dataType: "json",
            beforeSend: function () {
                $("#collection_submit").find(".text").val("Loading..");
            },
            success: function (res) {
                console.log("Clicked");
                if (res[0] == 1) {
                    console.log(res);
                    console.log("the decision"+decide);
                    const selected_option = $("#class_types").val();
                    const class_var = res[1][3];
                    console.log("class is " + class_var);
                    if (decide == "0") {
                        console.log(selected_option == class_var);
                        const current_row = res[1].slice();
                        current_row.splice(16, 1);
                        current_row.splice(0, 1);
                        console.log("the current array is" + current_row);
                        if (selected_option == class_var) {
                        console.log("after splice"+current_row);
                            table1.row.add(res[1]).draw(false);
                        }
                        window.resultSet[class_var].push(current_row);
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
                        const current_row = res[1].slice();
                        current_row.splice(16, 1);
                        current_row.splice(0, 1);
                        console.log("the current array is" + current_row);
                        if (selected_option == class_var) {
                            console.log("after the splice" + res[1]);
                            table1.row(update_btn.parents("tr")).data(res[1]).draw(false);
                            window.resultSet[selected_option][index] = current_row;
                            console.log(current_row);
                        }
                        else {
                            window.resultSet[class_var].push(current_row);
                            window.resultSet[selected_option].splice(index, 1);
                            table1.row(update_btn.parents("tr")).remove().draw(false);
                        }
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
                else if (res[0] = 404)
                {
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
        const selected_option = $("#class_types").val();
        e.preventDefault();
        var ad = $(this).attr("ad");
        index = $(this).attr("index");
        let btn = $(this);
        Swal.fire({
            title: "Are you sure delete the transaction",
            showCancelButton: true,
            confirmButtonText: "Delete",
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: './backend/db/defaulters/delete.php',
                    data: { admin: ad },
                    beforeSend: function () {
                        $(btn).find(".text").text("Deleting...");
                    },
                    success: function (res) {
                        const taken = parseInt(res);
                        if (taken == 0) {
                            table1.row(btn.parents('tr')).remove().draw(false);
                            window.resultSet[selected_option].splice(index, 1);
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
            }
        });
    });
    $("body").on("click", ".update", function (e) {
        e.preventDefault();
        console.log("update");
        var ad = $(this).attr("ad");
        index = $(this).attr("index");
        update_btn = $(this);
        console.log("index is" + index);
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
        var term1 = row.closest("tr").find("td:eq(5)").text();
        $("#term1").val(term1);
        var term2 = row.closest("tr").find("td:eq(6)").text();
        $("#term2").val(term2);
        var term3 = row.closest("tr").find("td:eq(7)").text();
        $("#term3").val(term3);
        
        //date formatting
        var paid_date = row.closest("tr").find("td:eq(8)").text();
        let [day, month, year] = paid_date.split('/');
        // Format the date to YYYY-MM-DD 
        let formattedDate = `${year}-${month}-${day}`;
        // Set the formatted date to the date input 
        $("#paid_date").val(formattedDate);

        var scholarship = row.closest("tr").find("td:eq(9)").text();
        $("#scholarship").val(scholarship);
        var scholarship_amt = row.closest("tr").find("td:eq(10)").text();
        $("#scholarship_amt").val(scholarship_amt);
        var pending = row.closest("tr").find("td:eq(11)").text();
        $("#pending").val(pending);
        var write_off = row.closest("tr").find("td:eq(12)").text();
        $("#write_off").val(write_off);
        $('#overall_table_form').css('display', 'flex');

    });
});


//TODO: Get the cookie by passing the parameter
function getCookie(cname) {
    let name = cname + "=";
    let ca = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

//TODO: LOAD function of the page
document.addEventListener("DOMContentLoaded", function () {
    const classSort = getCookie("classSort");
    console.log(document.cookie);
    if (classSort != "") {
        sort_in_class.value = classSort;
        sort_in_class.dispatchEvent(class_sort_event);
    }
    else
    {
        sort_in_class.dispatchEvent(class_sort_event);
    }
});
sort_in_class.addEventListener("change", function () {
    document.cookie = "classSort=" + sort_in_class.value;
    console.log(document.cookie);
    table1.clear().draw();
    const selected_option = sort_in_class.value;
    // console.log("hello");
    const sorted_data = window.resultSet[selected_option];
    let sno = 0;
    sorted_data.forEach((arr, index) => {
        sno += 1;
        let newRow = [sno,
            arr[0],
            arr[1],
            arr[2],
            arr[3],
            arr[4],
            arr[5],
            arr[6],
            arr[7],
            arr[8],
            arr[9],
            arr[10],
            arr[11],
            arr[12],
            arr[13],
            arr[14],
            `<div id='action'>
                            <button type='button' style='height:35px;width:70px;' class='download-btn pixel-corners delete' ad=${arr[0]} index=${index}>
                        <div class='button-content' style=''>
                            <div class='svg-container'>
                            <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 700 700' height='24' widht='24' style='fill:white;margin-bottom:3px;margin-left:8px;'><path d='M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z'/></svg>
                            </div>
                            <div class='text-container' style='height:37px'>
                            <div class='text'>Delete</div>
                            </div> 
                        </div>
                        </button>
                        <button typr='submit' style='height:35px;width:70px;' class='download-btn pixel-corners update' ad=${arr[0]} index=${index}>
                        <div class='button-content' style=''>
                            <div class='svg-container'>
                                <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' style='fill: rgb(255, 255, 255);margin-bottom: 10px;'><path d='M11 15h2V9h3l-4-5-4 5h3z'></path><path d='M20 18H4v-7H2v7c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2v-7h-2v7z'></path></svg>
                            </div>
                            <div class='text-container' style='height:37px'>
                            <div class='text'>Update</div>
                            </div> 
                        </div>
                        </button>
                        </div>`];
        let newRowNode = table1.row.add(newRow).draw(false).node();
    });
});