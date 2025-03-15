import { Toast } from "../backend/db/collection/sweetalert_config.js";
$(document).ready(function () {
    $("#sign_in").click(function (e) {
        e.preventDefault();
        const user_type = $(".radio_:checked").val();
        console.log(user_type);
        const uname = $("#uname").val();
        const user_pwd = $("#user_pwd").val();
        const gohead = login_validate(uname, user_pwd,"login");
        if (gohead == 2)
        {
            $.ajax({
                type: 'POST',
                url: 'backend/db/authentication/auth.php',
                data: {
                    uname: uname,
                    user_pwd: user_pwd,
                    user: user_type,
                    action: "in"
                },
                dataType:"json",
                success: function (res) {
                     console.log(res);
                    if (res[0] == 100) {
                        location.href = "./homepage.php";
                        Toast.fire({
                            html: `<p class='alert_content'>${res[1]}</p>`,
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
                            html: `<p class='alert_content'>${res[1]}</p>`,
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
    $("#sign_up").click(function (e) {
        e.preventDefault();
        const user_type = $(".radio_:checked").val();
        console.log(user_type);
        const uname = $("#uname").val();
        const user_pwd = $("#user_pwd").val();
        const gohead = login_validate(uname, user_pwd,"signUp");
        if (gohead == 2)
        {
             $.ajax({
                type: 'POST',
                url: 'backend/db/authentication/auth.php',
                 data: {
                     uname: uname,
                     user_pwd: user_pwd,
                     user:user_type,
                     action:"up"
                 },
                 dataType:"json",
                 success: function (res) {
                     console.log(res);
                    if (res[0] == 100)
                    {
                        Toast.fire({
                            html: `<p class='alert_content'>${res[1]}</p>`,
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
                            position: "top",
                            html: `<p class='alert_content'>${res[1]}</p>`,
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

function login_validate(uname, user_pwd,action) {
    const email_pattern = /^[\w\-\.]+@([\w-]+\.)+[\w-]{2,}$/;
    const Error_uname = $("#uname_msg");
    const Error_pwd = $("#pwd_msg");
    const input_field = $(".inputForm");
    let gohead = 0;
    if (!uname) {
        Error_uname.text("Kindly enter the Email");
        input_field[0].style.borderColor = "red";
    }
    else {
        if (!email_pattern.test(uname)) {
            Error_uname.text("Kindly enter valid Email");
            input_field[0].style.borderColor = "red";
        }
        else {
            Error_uname.text("");
            input_field[0].style.borderColor = "green";
            gohead += 1;
        }

    }
    if (!user_pwd) {
        Error_pwd.text("Kindly enter the Password");
        input_field[1].style.borderColor = "red";
    }
    else {
        let success = true;
        let pwd_error = "Password must contain "
        if (action != "login") {
            if (!/(?=.*\d)/.test(user_pwd)) {
                success = false;
                pwd_error += "'Atleast one digit' ";
            }
            if (!/(?=.*[a-z])/.test(user_pwd)) {
                success = false;
                pwd_error += "'Atleast one lowercase letter' ";
            
            }
            if (!/(?=.*[A-Z])/.test(user_pwd)) {
                success = false;
                pwd_error += "'Atleast one uppercase letter' ";
                
            }
            if (!/(?=.*[@#$%^&+=])/.test(user_pwd)) {
                success = false;
                pwd_error += "'Special characters like @#$%^&+=' ";
                
            }
            if (!/.{8,}/.test(user_pwd)) {
                success = false;
                pwd_error += "'minimum 8 characters' ";

            }
        }
        if (success == true) {
            Error_pwd.text("");
            input_field[1].style.borderColor = "green";
            gohead += 1;
        }
        else {
            Error_pwd.text(pwd_error);
            input_field[1].style.borderColor = "red";
                
        }
    }
    return gohead;
}