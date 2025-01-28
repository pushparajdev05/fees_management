import { Toast } from "./sweetalert_config.js";
$(document).ready(function () {
    var ad;
    var sno1;
    var sno2;
    var insert_sno1;    
    var insert_sno2;
    var today = 0;
    var cashList = "0";
    var update_btn;
    const pattern = /term/i;
   /*  var Toast = Swal.mixin({
  toast: true,
  position: "top-end",
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.onmouseenter = Swal.stopTimer;
    toast.onmouseleave = Swal.resumeTimer;
  }
}); */
    const table1 = new DataTable('#myTable1', {
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
    });
    const table2 = new DataTable('#myTable2', {
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
    });
    const total_amount = document.getElementById("amt");
    $("#amt").focus(function (event) {
        const [types_array, types_values] = fees_amt_prepare();
        let total = 0;
        types_values.forEach((val) => {
            total += parseInt(val);
        });
        total_amount.value = total;
    });
    $("#collection_data").submit(function (event) {
        event.preventDefault();
        var decide = $("#decide").val();
        const payment = $("#payment");
        var payment_value = payment.val();
        console.log("the payment mode value" + payment_value);
        const [types_array, types_values] = fees_amt_prepare();
        console.log(`the type values ${types_array}`);
        cashList = types_values.join(",");
        const types_value = types_array.join(",");
        console.log(types_value);
        var data_form;
        if (decide == "1") {
            data_form = [];
        }
        else {
            data_form = $("#collection_data").serializeArray();
        }
       
        var update_form = new FormData();
        const studName = $("#std").val();
        const class_name = $("#class").val();
        const section_ = $("#section").val();
        const admission = $("#admin").val();
        if (payment_value == "0") {
            insert_sno1 =table1.data().length;
            if (decide == "1") {
                data_form.push({name:'old_ad',value:ad});
                data_form.push({name:'sno1',value:sno1});
                data_form.push({name:'date',value:today});             
                data_form.push({name:'admission',value:admission});             
                data_form.push({name:'std',value:studName});             
                data_form.push({name:'class',value:class_name});             
                data_form.push({name:'section', value:section_});  
                data_form.push({name:'decide', value:decide});  
                console.log(update_form);
            }
            else {
                data_form.push({ name: 'insert_sno1', value: parseInt(insert_sno1) });
                data_form.push({ name: 'types', value: types_value});
                data_form.push({ name: 'cashList', value: cashList});
            }
            $.ajax({
                url: "./backend/db/collection/cash/cash_table.php",
                type: "post",
                data: data_form,
                dataType: "json",
                beforeSend: function () {
                    $("#collection_submit").find(".text").val("Loading..");
                },
                success: function (data) {
                    console.log(data);
                    if (decide == "0") {
                        const message = data["message"] ?? [null];
                        const res = data["row"] ?? [null];
                        if (res[0] == 0) {
                            table1.row.add(res[1]).draw(false);
                        }
                        if (message[0] == 404) {
                            Toast.fire({
                                title: "Cash Table",
                                html: `<p class='alert_content'>${message[1]}</p>`,
                                icon: "error",
                                customClass: {
                                    timerProgressBar: 'bar_error',
                                    icon: "icon_error"
                                }
                            });
                        }
                        else if (message[0] == 101) {
                            Toast.fire({
                                title: "Cash Table",
                                html: `<p class='alert_content'>${message[1]}</p>`,
                                icon: "success",
                                customClass: {
                                    timerProgressBar: 'bar_success',
                                    icon: "icon_success"
                                }
                            });
                        }
                        else if (message[0] == 505) {
                            Swal.fire({
                                title: "ERROR",
                                html: `<p class='alert_content'>${message[1]}</p>`,
                                icon: "error",
                                customClass: {
                                    icon: "icon_error",
                                    timerProgressBar: 'bar_error'

                                }
                            });
                        }
                        $("#collection_data")[0].reset();

                    } else {
                        const message = data["message"] ?? [null];
                        const res = data["row"] ?? [null];
                        let current_value;
                        if (res[0] == 0) {
                            current_value = table1.row(update_btn.parents("tr"));
                            res[1].forEach(function (value, colIndex) {
                                let ind = 2;
                                table1.cell({ row: current_value.index(), column: (colIndex + ind) }).data(value);
                            });
                            table1.draw(false);
                        }
                        if (message[0] == 101) {
                            Toast.fire({
                                title: "Cash Table",
                                html: `<p class='alert_content'>${message[1]}</p>`,
                                icon: "success",
                                customClass: {
                                    timerProgressBar: 'bar_success',
                                    icon: "icon_success"
                                }
                            });
                        }
                        else if (message[1] == 404) {
                            Toast.fire({
                                title: "Cash Table",
                                html: `<p class='alert_content'>${message[1]}</p>`,
                                icon: "error",
                                customClass: {
                                    timerProgressBar: 'bar_error',
                                    icon: "icon_error"
                                }
                            });
                        }
                                                
                        $("#collection_data")[0].reset();
                        $('#collection-form').css('display', 'none');
                        const payment = $("#payment");
                        payment.prop("disabled", false);
                        $("#admin").prop("readonly", false);
                        $("#type").prop("disabled", false);
                        //terms field
                        $("#t1").prop("readonly", false);
                        $("#t2").prop("readonly", false);
                        $("#t3").prop("readonly", false);
                    }
                        $("#collection_submit").val("Save");
                    }
                });
        }
        else
        {
            
            insert_sno2 =table2.data().length;
            
            if (decide == "1")
            {
                data_form.push({name:'old_ad',value:ad});
                data_form.push({name:'sno2',value:sno2});
                data_form.push({name:'date',value:today});             
                data_form.push({name:'admission',value:admission});             
                data_form.push({name:'std',value:studName});             
                data_form.push({name:'class',value:class_name});             
                data_form.push({name:'section', value:section_});  
                data_form.push({name:'decide', value:decide}); 
            }
            else{
                data_form.push({ name: 'insert_sno2', value: insert_sno2 });
                data_form.push({ name: 'cashList', value: cashList});
                data_form.push({ name: 'types', value: types_value});
            }
        // console.log("hi pushparaj")
        $.ajax({
            url: "./backend/db/collection/cheque_online/cheque_table.php",
            type: "post",
            dataType:"json",
            data: data_form,
            beforeSend: function () {
                $("#collection_submit").val("Loading..");
            },
            success: function (data) {
                    console.log(data);
                if (decide == "0") {
                    const message = data["message"] ?? [null];
                    const res = data["row"] ?? [null];
                    if (res[0] == 0) {
                        table2.row.add(res[1]).draw(false);
                    }
                    if (message[0] == 404) {
                        Toast.fire({
                            title: "Cheque/online Table",
                            html: `<p class='alert_content'>${message[1]}</p>`,
                            icon: "error",
                            customClass: {
                                timerProgressBar: 'bar_error',
                                icon: "icon_error"
                            }
                        });
                    }
                    else if (message[0] == 101) {
                        Toast.fire({
                            title: "Cheque/online Table",
                            html: `<p class='alert_content'>${message[1]}</p>`,
                            icon: "success",
                            customClass: {
                                timerProgressBar: 'bar_success',
                                icon: "icon_success"
                            }
                        });
                    }
                    else if (message[0] == 505) {
                        Swal.fire({
                            title: "ERROR",
                            html: `<p class='alert_content'>${message[1]}</p>`,
                            icon: "error",
                            customClass: {
                                icon: "icon_error",
                                timerProgressBar: 'bar_error',
                            }
                        });
                    }
                    $("#collection_data")[0].reset();
                } else {
                    const message = data["message"] ?? [null];
                    const res = data["row"] ?? [null];
                    let current_value;
                    if (res[0] == 0) {
                        current_value = table2.row(update_btn.parents("tr"));
                        res[1].forEach(function (value, colIndex) {
                            let ind = 2;
                            table1.cell({ row: current_value.index(), column: (colIndex + ind) }).data(value);
                        });
                        table2.draw(false);
                    }
                    if (message[0] == 101) {
                        Toast.fire({
                            title: "Cheque/online Table",
                            html: `<p class='alert_content'>${message[1]}</p>`,
                            icon: "success",
                            customClass: {
                                timerProgressBar: 'bar_success',
                                icon: "icon_success"
                            }
                        });
                    }
                    else if (message[1] == 404) {
                        Toast.fire({
                            title: "Cheque/online Table",
                            html: `<p class='alert_content'>${message[1]}</p>`,
                            icon: "error",
                            customClass: {
                                timerProgressBar: 'bar_error',
                                icon: "icon_error"
                            }
                        });
                    }
                    $("#collection_data")[0].reset();
                    $('#collection-form').css('display', 'none');
                    const payment = $("#payment");
                    payment.prop("disabled", false);
                    $("#admin").prop("readonly", false);
                    $("#type").prop("disabled", false);
                    //terms field
                    $("#t1").prop("readonly", false);
                    $("#t2").prop("readonly", false);
                    $("#t3").prop("readonly", false);
                }
                $("#collection_submit").val("Save");
            }
        })
        }
    });
    $("body").on("click", ".delete", function (e) {
        e.preventDefault();
        var ad = $(this).attr("ad");
        var btn = $(this);
        const revert_term = delete_data_prepare(btn);
        const admission = btn.closest("tr").find("td:eq(1)").text();
        console.log(revert_term);
        if (select.getAttribute("key") == 0) {
            Swal.fire({
                title:"Are you sure delete the transaction",
                showCancelButton: true,
                confirmButtonText: "Delete",
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: './backend/db/collection/cash/delete_cash.php',
                        dataType:'json',
                        data: {
                            admin: ad,
                            revertTerm: revert_term,
                            admission: admission,
                        },
                        beforeSend: function () {
                            $(btn).find(".text").text("Deleting...");
                        },
                        success: function (res) {
                            console.log(res);
                            const message = res["message"] ?? [null];
                            const error = res["error"] ?? [null];
                            if (message[0] == 101) {
                                table1.row(btn.parents('tr')).remove().draw(false);
                                Toast.fire({
                                    title: "Cash Table",
                                    html: `<p class='alert_content'>${message[1]}</p>`,
                                    icon: "success",
                                    customClass: {
                                        timerProgressBar: 'bar_success',
                                        icon: "icon_success"
                                    }
                                });
                            }
                            else
                            {
                                Toast.fire({
                                    title: "Cash Table",
                                    html: `<p class='alert_content'>${error[1]}</p>`,
                                    icon: "error",
                                    customClass: {
                                        timerProgressBar: 'bar_error',
                                        icon: "icon_error"
                                    }
                                });

                            }
                            $(btn).find(".text").text("Delete");
                    
                        }
                    });
                }
            });

        }
        else {
            Swal.fire({
                title:"Are you sure delete the transaction",
                showCancelButton: true,
                confirmButtonText: "Delete",
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: './backend/db/collection/cheque_online/delete_cheque.php',
                        dataType:"json",
                        data: {
                            admin: ad,
                            revertTerm: revert_term,
                            admission: admission,
                        },
                        beforeSend: function () {
                            $(btn).find(".text").text("Deleting...");
                        },
                        success: function (res) {
                           const message = res["message"] ?? [null];
                            const error = res["error"] ?? [null];
                            if (message[0] == 101) {
                                table2.row(btn.parents('tr')).remove().draw(false);
                                Toast.fire({
                                    title: "Cheque/Online Table",
                                    html: `<p class='alert_content'>${message[1]}</p>`,
                                    icon: "success",
                                    customClass: {
                                        timerProgressBar: 'bar_success',
                                        icon: "icon_success"
                                    }
                                });
                            }
                            else
                            {
                                Toast.fire({
                                    title: "Cheque/Online Table",
                                    html: `<p class='alert_content'>${error[1]}</p>`,
                                    icon: "error",
                                    customClass: {
                                        timerProgressBar: 'bar_error',
                                        icon: "icon_error"
                                    }
                                });

                            }
                            $(btn).find(".text").text("Delete");

                        }
                    });
                }
            });

        }
    });
    $("body").on("click", ".update", function (e) {
        e.preventDefault();
        ad = $(this).attr("ad");
        $("#decide").val("1");
        update_btn = $(this);
        const type = document.getElementById("type");
        const amt1 = document.getElementById("amt");
        const class_trigger = document.getElementById("class"); 
        const change_event = new Event("change");
        var row = $(this);
        if (select.getAttribute("key") == 0) {
            sno1 = row.closest("tr").find("td:eq(0)").text();
            var admin = row.closest("tr").find("td:eq(1)").text();
            $("#admin").val(admin);
            $("#admin").prop("readonly",true);
            var name = row.closest("tr").find("td:eq(2)").text();
            $("#std").val(name);
            var class1 = row.closest("tr").find("td:eq(3)").text();
            $("#class").val(class1);
            class_trigger.dispatchEvent(change_event);
            var section = row.closest("tr").find("td:eq(4)").text();
            $("#section").val(section);
            var mode = row.closest("tr").find("td:eq(5)").text();
            const mode_array = mode.split(",");
            $("#type").val(mode_array);
            $("#type").prop("disabled",true);
            //terms field
            $("#t1").prop("readonly", true);
            $("#t2").prop("readonly", true);
            $("#t3").prop("readonly", true);
            //TODO:term fees checking
            term_fees(mode_array,row);

            const amount = row.closest("tr").find("td:eq(7)").text();
            $("#amt").val(amount);
            console.log(amount);
            today = row.closest("tr").find("td:eq(8)").text();
            const payment = $("#payment");
            payment.val("0");
            payment.prop("disabled", true);
            type.removeAttribute("required");
            amt1.removeAttribute("required");
            $('#collection-form').css('display', 'flex');
        }
        else {
            sno2 = row.closest("tr").find("td:eq(0)").text();
            var admin = row.closest("tr").find("td:eq(1)").text();
            $("#admin").val(admin);
            $("#admin").prop("readonly",true);
            var name = row.closest("tr").find("td:eq(2)").text();
            $("#std").val(name);
            var class1 = row.closest("tr").find("td:eq(3)").text();
            $("#class").val(class1);
            class_trigger.dispatchEvent(change_event);
            var section = row.closest("tr").find("td:eq(4)").text();
            $("#section").val(section);
            var mode = row.closest("tr").find("td:eq(5)").text();
            const mode_array = mode.split(",");
            $("#type").val(mode_array );
            $("#type").prop("disabled",true);
            //terms field
            $("#t1").prop("readonly", true);
            $("#t2").prop("readonly", true);
            $("#t3").prop("readonly", true);
            //TODO:term fees checking
            term_fees(mode_array,row);

            var amt = row.closest("tr").find("td:eq(7)").text();
            $("#amt").val(amt);
            today = row.closest("tr").find("td:eq(8)").text();
            const payment = $("#payment");
            payment.val("1");
            payment.prop("disabled", true);
            type.removeAttribute("required");
            amt1.removeAttribute("required");
            $('#collection-form').css('display', 'flex');
        }
        // $("#but").text("Update User");
    });
    function term_fees(mode_array,row)
    {
        const fees_amt = row.closest("tr").find("td:eq(6)").text();
            const fees_amt_array = fees_amt.split(",");
            const types = $("#type option:selected");
            let terms = window.term_array;
            const full_amt=[]
            types.each(function () {
                let types_val = $(this).text();
                let types_amount = parseInt($(this).attr("amount"));
                if (pattern.test(types_val))
                {
                    full_amt.push(types_amount);
                }
            });
            mode_array.forEach((val, index) => {
                if (pattern.test(val))
                {
                    if (val == terms[0])
                    {
                        if (full_amt[0] != fees_amt_array[index])
                        {
                            $("#t1").val(fees_amt_array[index]);
                        }
                    }
                    if (val == terms[1])
                    {
                        if (full_amt[1] != fees_amt_array[index])
                        {
                            $("#t2").val(fees_amt_array[index]);
                        }
                    }
                    if (val == terms[2])
                    {
                        if (full_amt[2] != fees_amt_array[index])
                        {
                            $("#t3").val(fees_amt_array[index]);
                        }
                    }
                }
            });
    }
    function fees_amt_prepare()
    {
        const types = $("#type option:selected");
        console.log(types);
        let terms = window.term_array;
        console.log("the term  array is :" + terms);
        const types_array = [];
        const types_values = [];
        types.each(function () {
            let types_val = $(this).text();
            let types_amount = parseInt($(this).attr("amount"));
            const t1 = $("#t1").val();
            const t2 = $("#t2").val();
            const t3 = $("#t3").val();
            const term1_amt = (t1 == "") ? 0 : t1;
            const term2_amt = (t2 == "") ? 0 : t2;
            const term3_amt = (t3 == "") ? 0 : t3;
            console.log("each is executed");
            types_array.push(types_val);
            if (pattern.test(types_val))
            {
                if (terms[0] == types_val) {
                    if ((term1_amt != 0) && (term1_amt <= types_amount)) {
                        types_values.push(term1_amt);
                        console.log("term1 amount pushed");
                        
                    }
                    else {
                        types_values.push(types_amount);

                    }
                }
                if (terms[1] == types_val)
                {
                    if ((term2_amt != 0) && (term2_amt <= types_amount))
                    {
                        types_values.push(term2_amt);
                        
                    } 
                    else {
                        types_values.push(types_amount);

                    }
                }
                if (terms[2] == types_val)
                {
                    if ((term3_amt != 0) && (term3_amt <= types_amount))
                    {
                        types_values.push(term3_amt);
                        
                    } 
                    else {
                        types_values.push(types_amount);
    
                    }
                }
            }
            else
            {
            types_values.push(types_amount);
                
                }
        });
        return [types_array,types_values]
    }
    function delete_data_prepare(btn)
    {
        const revert_term = [];
        const terms = window.term_array;
        const term_types =btn.closest("tr").find("td:eq(5)").text();
        const term_values =btn.closest("tr").find("td:eq(6)").text();
        const term_types_array = term_types.split(",");
        const term_values_array = term_values.split(",");
        while (revert_term.length < 3)
        {
            revert_term.push("null");
        }
        term_types_array.forEach(function (val,index) {
            if (pattern.test(val))
            {
                if (terms[0] == val)
                {
                    revert_term[0] = term_values_array[index];
                }
                else if (terms[1] == val)
                {
                    revert_term[1] = term_values_array[index];
                }
                else if (terms[2] == val)
                {
                    revert_term[2] = term_values_array[index];
                }
            }
        });
        return revert_term;
    }
            
});