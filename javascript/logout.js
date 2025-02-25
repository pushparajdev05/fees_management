$(document).ready(function () {
    const visited_user = window.user;
    $("#og_logout").click(function () {
        $.ajax({
                type: 'POST',
                url: 'backend/db/authentication/logout.php',
            data: {
                    user:visited_user
                },
                dataType:"json",
                success: function (res) {
                     console.log(res);
                    if (res[0] == 100) {
                        location.href = "./index.php";
                    }
                    else {
                        Toast.fire({
                            position: "top",
                            html: `<p class='alert_content'>Failed to logout since user could not be found</p>`,
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
    $(".logout .btn").click(function (e) {
        e.stopPropagation();
        const og_logout = document.getElementById("og_logout");
        og_logout.classList.toggle("logout_show");

    });
    document.body.addEventListener("click", (e) => {
        e.stopPropagation();
        const og_logout = document.getElementById("og_logout");
        og_logout.classList.remove("logout_show");
    });
});