function createList(){
    let form = document.forms.demoWishList;
    let data = new FormData(form);
    let nameWishList = data.get("nameOfWishList");
    let description = data.get("descriptionOfWishList");
    let userId = data.get("userId")
    fetch("http://localhost/ecfBack/api.php/api/wishlist", {
        method: "POST",
        headers:{
            "Token": localStorage.getItem("token")
        },
        body: JSON.stringify({
            "name": nameWishList,
            "description": description,
            "userId": userId
        })

    }).then(response=> console.log(response.text()))
}