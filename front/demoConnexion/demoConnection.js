function register() {
    let form = document.forms.demoRegister;
    let data = new FormData(form);
    let username = data.get("username");
    let email = data.get("email");
    let password = data.get("password");
    let avatar = data.get("avatar");

    fetch("http://localhost/ecfBack/api.php/api/user", {
        method: "POST",
        body: JSON.stringify({
            "username": username,
            "email": email,
            "password": password,
            "avatar": avatar
        })
    }).then(async response => console.log(await response.text()))

}


function connection() {
    let form = document.forms.demoConnection;
    let data = new FormData(form);
    let username = data.get("username");
    let password = data.get("password");
    fetch("http://localhost/ecfBack/api.php/api/login", {
        method: "POST",
        body: JSON.stringify({
            "username": username,
            "password": password,
        })
    }).then(async response => {
        let data = await response.json();
        console.log(data)
        localStorage.setItem("token", "Bearer "+data.token);
    })

}