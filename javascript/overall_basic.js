const overall_form = document.getElementById("overall_table_form");
const overall_form_data = document.getElementById("overall_form_data");
const close_form = document.getElementById("close_form");
const add_btn = document.getElementById("new");

close_form.addEventListener("click", (e) => {
    e.preventDefault();
    overall_form.style.display = "none";
    overall_form_data.reset();
});

add_btn.addEventListener("click", (e) => {
    e.preventDefault();
    overall_form.style.display = "flex";
});
