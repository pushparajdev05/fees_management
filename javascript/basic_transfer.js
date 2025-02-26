const overall_form = document.getElementById("overall_table_form");
const overall_form_data = document.getElementById("overall_form_data");
const close_form = document.getElementById("close_form");
const add_btn = document.getElementById("new");
const user_show = document.getElementById("user_show");
const user_table = document.getElementById("user_table");
const user_close = document.getElementById("user_close");
close_form.addEventListener("click", (e) => {
    e.preventDefault();
    overall_form.style.display = "none";
    overall_form_data.reset();
});

add_btn.addEventListener("click", (e) => {
    e.preventDefault();
    const visited_user = window.user;
    if (visited_user == "admin")
    {
        overall_form.style.display = "flex";
    }
});
user_show.addEventListener("click", (e) => {
    const visited_user = window.user;
    if (visited_user == "admin")
    {
        user_table.style.display = "block";
    }
});
user_close.addEventListener("click", (e) => {
    user_table.style.display = "none";
});
