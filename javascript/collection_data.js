//TODO: tooltip button style add and removing in container
import { Toast } from "../backend/db/collection/sweetalert_config.js";
const tooltip_con = document.getElementById("tooltip_con");
const main_tool = document.getElementById("main_tool");
const table_close = document.getElementById("table_close");
const fees_table = document.getElementById("fees_structure");
const table_view = document.getElementById("table_view");
main_tool.addEventListener("click", (e) => {
    // e.stopPropagation();
    tooltip_con.classList.toggle("tooltip-contain");
});
table_close.addEventListener("click", () =>{
    fees_table.style.display = "none";
});
table_view.addEventListener("click", () =>{
    fees_table.style.display = "block";
});
$(document).ready(function () {
    const visited_user = window.user;
    console.log(visited_user);
    if (visited_user == "admin") {
        $("#csv_submit").click(function () {
            const csv_file = document.getElementById("csv_fees").files;
            console.log(csv_file);
            const file = new FormData();
            if (csv_file.length > 0) {
                file.append("csv", csv_file[0]);
                $.ajax({
                    type: 'POST',
                    url: 'backend/db/collection/fees_table.php',
                    data: file,
                    contentType: false,
                    processData: false,
                    success: function (res) {
                        let taken = parseInt(res);
                        console.log(res);
                        if (taken == 1) {
                            Toast.fire({
                                html: "<p class='alert_content'>CSV data successfully loaded into the database!</p>",
                                icon: "success",
                                customClass: {
                                    timerProgressBar: 'bar_success',
                                    icon: "icon_success"
                                }
                            });
                        }
                        else {
                            Toast.fire({
                                position: "top",
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
                    html: "<p class='alert_content'>kindly select fees csv file to push to fees table</p>",
                    icon: "warning",
                    customClass: {
                        timerProgressBar: 'bar_warning',
                        icon: "icon_warning"
                    }
                });
            }
        });
    }
    
});