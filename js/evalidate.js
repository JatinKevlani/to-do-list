submitBtn.addEventListener("click", async (e) => {
    e.preventDefault();
    let username = document.getElementById("reg_userName").value;
    let email = document.getElementById("reg_email").value;
    let password = document.getElementById("reg_pswd").value;
    resultCont.innerHTML = `<div class="d-flex justify-content-center">
                                <div class=" spinner-border text-secondary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>`
    let key = "ema_live_gJVxfFpD3BE2H6E7TKWflkqtJNIStW6OmJYo8W8t";
    let url = `https://api.emailvalidation.io/v1/info?apikey=${key}&email=${email}`;
    let res = await fetch(url);
    result = await res.json();
    for (key of Object.keys(result)) {
        if (result[key] !== "" && result[key] !== " ") {
            if (result.message == "Validation error") {
                resultCont.innerHTML = "";
                alert("Please enter a valid email!");
                return;
            }
            else if (result.smtp_check == false || result.state == "undeliverable") {
                resultCont.innerHTML = "";
                alert("Please enter a valid email!");
                return;
            }
            else {
                resultCont.innerHTML = "";
                document.getElementById("user_Name").value = username;
                document.getElementById("user_Email").value = email;
                document.getElementById("user_Password").value = password;
                document.querySelector("#registerForm").submit();
            }
        }
    }
});