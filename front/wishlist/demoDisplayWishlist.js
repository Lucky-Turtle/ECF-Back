const urlParams = window.location.search;
const params = new URLSearchParams(urlParams);
const wishlistId = params.get("id");

getWishlistData()
function getWishlistData (){
getWishList()
    getWishlistArticles()
    getWishlistComment()
}
function getWishList(userId){
    let wishlistTop = document.querySelector("#wishlistDisplay");
    fetch(`http://ecf-back.alwaysdata.net/api.php/api/wishlist?id=${wishlistId}`, {
        method: "GET"
    }).then(async response =>{
        let data = await response.json();
        console.log(data);
        wishlistTop.innerHTML = `<h1>${data.name}</h1><h2>${data.description}</h2>`
    })
}

function getWishlistArticles(){
    let articleContent = document.querySelector("#articleContent");
    fetch(`http://ecf-back.alwaysdata.net/api.php/api/wishlist/articles?id=${wishlistId}`, {
        method: "GET"
    }).then(async response =>{
        let data = await response.json();
        console.log(data);
        for(let i=0; i<data.length; i++){
            articleContent.innerHTML += `<tr><td>${data[i].name}</td>
<td>${data[i].description}</td>
<td>${data[i].price}</td>
<td><a href="${data[i].link}">Lien</a></td>
</tr>`
        }

    })
}

function getWishlistComment() {
    let wishlistComment = document.querySelector("#displayComment");
    fetch(`http://ecf-back.alwaysdata.net/api.php/api/wishlist/comments?id=${wishlistId}`, {
        method: "GET"
    }).then(async response =>{
        let data = await response.json();
        console.log(data);
        wishlistComment.innerHTML = "";
        for(let i = data.length-1; i>=0; i--){
            wishlistComment.innerHTML += `<p>${data[i].description}</p><time>${data[i].date}</time>`
        }
    })

}

function sendComment(){
    let description = document.querySelector("#sendCommentDiv").value;
    let userId = document.querySelector("#userId").value;
    fetch(`http://ecf-back.alwaysdata.net/api.php/api/comment`, {
        method: "POST",
        headers:{
            "Token": localStorage.getItem("token")
        },
        body:JSON.stringify({
            "description": description,
            "userId": userId,
            "wishlistId": wishlistId
        })
    }).then(async response =>{
        console.log(await response.text());
        getWishlistComment();
    } )
}