import { Toast } from "../backend/db/collection/sweetalert_config.js";
const switch_btn = document.getElementById("switch");
const select1 = document.getElementById("select1");
const select2 = document.getElementById("select2");
const  select= document.getElementById("select");
const  filter2= document.getElementById("filter2");
const filter_btn = document.querySelectorAll("#filter_btn");
const search_table1 = document.getElementsByClassName("dt-input");
const cheque_table = document.getElementById("cheque_table");
const cash_table = document.getElementById("cash_table");
const form_head = document.getElementById("form_head");
const Table1=document.getElementById("myTable1");
const Table2 = document.getElementById("myTable2");
//getting date from the collection fees pages
const filter_date = document.getElementById("filter_date");
// Table1.style.width="100%"
cheque_table.style.display = "none";
const event1 = new Event("input");
const switch_event = new Event("click");
//switch button 
const visited_user = window.user;

switch_btn.addEventListener("click", (e) => {
    e.preventDefault();

    if (select.getAttribute("key") == 0) {
        // select1.style.animation = "select1 .2s forwards";
        // select2.style.animation = "select2 .2s forwards";
        console.log(search_table1[0]);
        cheque_table.style.display = "block";
        cash_table.style.display = "none";
        if (Table2.style.width == "0px") {
            Table2.style.width = "100%";
            // console.log("hi hello");
        }
        select.setAttribute("key", "1");
        filter2.value = filter2.options[0].value;
        if (search_table1[1]) {
            search_table1[1].value = "";
            search_table1[1].dispatchEvent(event1);
        }
        document.cookie = "switch=1";
        console.log(getCookie("switch"));
    }
    else {
        // select1.style.animation = "select1_rev .2s forwards";
        // select2.style.animation = "select2_rev .2s forwards";
        console.log(search_table1[1]);;
        cheque_table.style.display = "none";
        cash_table.style.display = "block";
        if (Table1.style.width == "0px") {
            Table1.style.width = "100%";
            // console.log("hi hello");
        }
        filter2.value = filter2.options[0].value;
        select.setAttribute("key", "0");
        search_table1[0].value = "";
        search_table1[0].dispatchEvent(event1);
        document.cookie = "switch=0";
        console.log(getCookie("switch"));



    }
    
});

//filter button

filter_btn[0].addEventListener("click", () => {
    if (visited_user == "admin") {
        if (filter_date.value) {
            const date = new Date(filter_date.value);
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            const formattedDate = `${day}/${month}/${year}`;
            if (select.getAttribute("key") == 0) {
                search_table1[0].value = formattedDate;
                search_table1[0].dispatchEvent(event1);
            }
            else {
                search_table1[1].value = formattedDate;
                search_table1[1].dispatchEvent(event1);
            }
        }
        else {
            Toast.fire({
                position: "top",
                html: "<p class='alert_content'>kindly select the date to fiter the table on date</p>",
                icon: "warning",
                customClass: {
                    timerProgressBar: 'bar_warning',
                    icon: "icon_warning"
                }
            });
        }
    }
});
filter_btn[1].addEventListener("click", () => {
    if (visited_user == "admin") {
        const filter_val = filter2.options[filter2.selectedIndex].value;
        if (filter_val) {
            if (select.getAttribute("key") == 0) {
                search_table1[0].value = filter_val;
                search_table1[0].dispatchEvent(event1);
            }
            else {
                search_table1[1].value = filter_val;
                search_table1[1].dispatchEvent(event1);
            }
        }
        else {
            Toast.fire({
                position: "top",
                html: "<p class='alert_content'>kindly select the valid option to fiter the table</p>",
                icon: "warning",
                customClass: {
                    timerProgressBar: 'bar_warning',
                    icon: "icon_warning"
                }
            });
        }
    }
});
document.addEventListener("DOMContentLoaded", function () {
    const table_switch = getCookie("switch");   
    if (table_switch != "") {
        if (table_switch != select.getAttribute("key"))
        {
            switch_btn.dispatchEvent(switch_event);
            console.log(document.cookie);
        }
    }
});
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

//TODO: print out of the daily collection payment list as per date

