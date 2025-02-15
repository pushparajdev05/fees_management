const close = document.getElementById("close");
const new_btn = document.getElementById("new");
const amt1 = document.getElementById("amt");
const collection = document.getElementById("collection-form");
const form = document.getElementById("collection_data");
const decide = document.getElementById("decide");
const payment_input = document.getElementById("payment");
const types_select = document.getElementById("type");
const element_id=["admin","type","t1","t2","t3",]
const readonly_Element = [];
element_id.forEach((value,index) => {
    readonly_Element[index] = document.getElementById(value);
});


// close button event

close.addEventListener("click", (event) => {
    event.preventDefault();
    collection.style.display = "none";
    payment_input.removeAttribute("disabled", "");
    // console.log(readonly_Element);
    readonly_Element.forEach((value,index) => {
        if (index == 1) {
            value.removeAttribute("disabled","");   
        }
        else {
            value.removeAttribute("readonly","");   
        }
    });
    console.log("closed");
    form.reset();
    types_select.innerHTML = "<option value=' style='border: none;'>select the class to get fees type</option>";
});
    
//new button event

new_btn.addEventListener("click", () => {
    // event.preventDefault();
    const type = document.getElementById("type");
    decide.value = "0";
    form.reset();
    types_select.innerHTML = "<option value=' style='border: none;'>select the class to get fees type</option>";
    console.log(type);
    collection.style.display = "flex";
});