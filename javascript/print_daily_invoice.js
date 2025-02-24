import { Toast } from "../backend/db/collection/sweetalert_config.js";
$(document).ready(()=>
{
    $("#print").click((e) => {
        const ondate = document.getElementById("filter_date");
        console.log(ondate.value);
        if (ondate.value) {
            const date = new Date(ondate.value);
            console.log(date);
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            const formattedDate = `${day}/${month}/${year}`;
            // console.log(formattedDate);
            $.ajax({
                url: "./backend/db/collection/print_collection.php",
                type: "post",
                // dataType: 'json',
                data: { collection_date: formattedDate },

                // beforeSend: function () { },

                success: function (res) {
                    // const check = parseInt(res[0]);
                    if (res) {
                        $("#table_print").html(res);
                        printout("#table_print", {
                            //pageTitle: window.document.title, // Title of the page
        
                            importCSS: true, // Import parent page css
                            inlineStyle: true, // If true it takes inline style tag
                            autoPrint: true, // Print automatically when the page is open
    
                            autoPrintDelay: 1000, // Delay in milliseconds before printing
        
                            header: null, // String or element this will be appended to the top of the printout
                            footer: null, // String or element this will be appended to the bottom of the printout
            
                            //noPrintClass: "no-print", // Class to remove the elements that should not be printed
                        });
                        $("#table_print").html("");
                    }
                }
            });
        }
        else {
            Toast.fire({
                position: "top",
                html: "<p class='alert_content'>kindly select the date to take daily collection</p>",
                icon: "warning",
                customClass: {
                    timerProgressBar: 'bar_warning',
                    icon:"icon_warning"
                }
            });
        }
       
        
    });

});