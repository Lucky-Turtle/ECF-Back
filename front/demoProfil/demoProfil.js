
let userData;
function updateProfil(userIdUpdate){
    let usernameUpdate = document.querySelector("#usernameUpdate").value;
    let emailUpdate = document.querySelector("#emailUpdate").value;
    let passwordUpdate = document.querySelector("#passwordUpdate").value;
    let avatarUpdate = document.querySelector("#avatarUpdate").value;

    fetch(`http://localhost/ecfBack/api.php/api/user?id=${userData.id}`, {
        method: "PUT",
        headers:{
            "Token": localStorage.getItem("token")
        },
        body: JSON.stringify({
            username: usernameUpdate === "" ? userData.username : usernameUpdate,
            email: emailUpdate === "" ? userData.email : emailUpdate,
            password: passwordUpdate === "" ? userData.password : passwordUpdate,
            avatar: avatarUpdate === "" ? userData.avatarUrl : avatarUpdate,
            isActive: userData.isActive,
            role: userData.role
        })
    }).then(async response => console.log(await response.text()))
}

function getUserData(){
    let userIdUpdate = document.querySelector("#userIdUpdate").value;
    console.log(userIdUpdate);

    fetch(`http://localhost/ecfBack/api.php/api/user?id=${userIdUpdate}`,{
        method: "GET"
    }).then(async response=>{
        userData = await response.json();
        console.log(userData);
        let usernameUpdate = document.querySelector("#usernameUpdate");
        let emailUpdate = document.querySelector("#emailUpdate");
        let avatarUpdate = document.querySelector("#avatarUpdate");

        usernameUpdate.value = userData.username;
        emailUpdate.value = userData.email;
        avatarUpdate.value = userData.avatarUrl;
    })
}