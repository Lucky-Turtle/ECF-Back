function listCharger(){
    let userId = document.querySelector("#userId").value;
    getWishList(userId);
    getArticleList();
}

function getWishList(userId){
    fetch(`http://ecf-back.alwaysdata.net/api.php/api/user/wishlists?id=${userId}`, {
        method: "GET"
    }).then(async response =>{
        let data = await response.json();
        console.log(data);
        let listSelect = document.querySelector("#userWishLists");

        for(let i = 0; i<data.length; i++){
            listSelect.innerHTML += `<option value="${data[i].id}">${data[i].name}</option>`
        }
    })
}
function getArticleList() {
    fetch("http://ecf-back.alwaysdata.net/api.php/api/articles", {
        method: "GET"
    }).then(async response =>{
        let data = await response.json();
        console.log(data);
        let listSelect = document.querySelector("#listOfArticles");

        for(let i = 0; i<data.length; i++){
            listSelect.innerHTML += `<option value="${data[i].id}">${data[i].name}</option>`
        }
    })

}

function addArticleToWishlist(){
    let listId = document.querySelector("#userWishLists").value;
    let articleId = document.querySelector("#listOfArticles").value;
    console.log(localStorage.getItem("token"))
    fetch(`http://ecf-back.alwaysdata.net/api.php/api/addArticle/wishlist?articleId=${articleId}&wishlistId=${listId}`, {
        method: "POST",
        headers:{
            "Token": localStorage.getItem("token")
        },
    }).then(async response=>console.log(await response.text()))



}