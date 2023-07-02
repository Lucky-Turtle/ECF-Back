<?php
cors();
//header('Access-Control-Allow-Origin: *');
//header("Access-Control-Allow-Methods: HEAD, GET, POST, PUT, PATCH, DELETE, OPTIONS");
//header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization,Token");
//header('Content-Type: application/json');
//$method = $_SERVER['REQUEST_METHOD'];
//if ($method == "OPTIONS") {
//    header('Access-Control-Allow-Origin: *');
//    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
//    header("HTTP/1.1 200 OK");
//    die();
//}
// Database configuration
require __DIR__ . '/vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$dbHost = 'localhost';
$dbUser = 'root';
$dbPassword = '';
$dbName = 'ecf_back';
$secretJWT = "secretJWT";

// Establish database connection
$db = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPassword);

// Get the HTTP method and request URI
$method = $_SERVER['REQUEST_METHOD'];
$ogUri = $_SERVER['REQUEST_URI'];
$uri = substr($ogUri, strpos($ogUri, '/api/'));

// Remove query string from the URI
$uri = explode('?', $uri)[0];
// Define your routes and corresponding handlers
$routes = [
    'GET' => [
        '/api/users' => 'getUsers',
        '/api/user' => 'getUser',
        '/api/user/wishlists' => 'getUserWishlists',
        '/api/wishlist' => 'getWishlist',
        '/api/wishlist/articles' => 'getWishlistArticles',
        '/api/article/' => 'getArticle',
        '/api/wishlist/comments' => 'getWishlistComments',
        '/api/comments/' => 'getComment',
        '/api/articles' => 'getArticles',
        '/api/comments' => 'getComments',
        '/api/wishlists' => 'getWishlists'
    ],
    'POST' => [
        '/api/user' => 'createUser',
        '/api/wishlist' => 'createWishlist',
        '/api/article' => 'createArticle',
        '/api/comment' => 'createComment',
        '/api/addArticle/wishlist' => 'createArticleToWishlist',
        '/api/login'=>'login'
    ],
    'PUT' => [
        '/api/user' => 'updateUser',
        '/api/wishlist' => 'updateWishlist',
        '/api/article' => 'updateArticle',
        '/api/comment' => 'updateComment',
    ],
    'DELETE' => [
        '/api/user' => 'deleteUser',
        '/api/wishlist' => 'deleteWishlist',
        '/api/article' => 'deleteArticle',
        '/api/comment' => 'deleteComment',
        '/api/removeArticle/wishlist' => 'deleteArticleFromWishlist'
    ],
];

// Define your API handlers

// Get all users
function getUsers()
{
    global $db;
    $query = $db->query("SELECT * FROM User");
    $users = $query->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($users);
}

// Get a user by ID
function getUser($id)
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM User WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($user);
}

// Get user's wishlists
function getUserWishlists($id)
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM Wishlist WHERE user_id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $wishlists = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($wishlists);
}

// Get a wishlist by ID
function getWishlist($id)
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM Wishlist WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $wishlist = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($wishlist);
}

// Get articles in a wishlist
function getWishlistArticles($id)
{
    global $db;
    $stmt = $db->prepare("SELECT Articles.* FROM Articles
        JOIN Article_Wishlist ON Articles.id = Article_Wishlist.article_id
        WHERE Article_Wishlist.wishlist_id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($articles);
}

// Get an article by ID
function getArticle($id)
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM Articles WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $article = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($article);
}

// Get comments in a wishlist
function getWishlistComments($id)
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM Comment WHERE wishlist_id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($comments);
}

// Get a comment by ID
function getComment($id)
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM Comment WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $comment = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($comment);
}

//Get all articles
function getArticles()
{
    global $db;
    $query = $db->query("SELECT * FROM Articles");
    $articles = $query->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($articles);
}

//Get all comments

function getComments()
{
    global $db;
    $query = $db->query("SELECT * FROM Comment");
    $comments = $query->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($comments);
}

//Get all wishlists
function getWishlists()
{
    global $db;
    $query = $db->query("SELECT * FROM Wishlist");
    $wishlists = $query->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($wishlists);
}

// Create a user
function createUser()
{
    global $db;
    var_dump($_POST);
    $values = json_decode(file_get_contents('php://input'), false);
    $stmt = $db->prepare("SELECT * FROM User WHERE username = :username OR email = :email");
    $stmt->bindParam(':username', $values->username);
    $stmt->bindParam(':email', $values->email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        echo 'User already exists';
        return;
    }
    $username = $values->username;
    $email = $values->email;
    $password = password_hash($values->password, PASSWORD_DEFAULT);
    $isActive = true;
    $role = "user";
    $avatar = $values->avatar;
    $stmt = $db->prepare("INSERT INTO User (username, email, password, isActive, role, avatarUrl) VALUES (:username, :email, :password, :isActive, :role, :avatar)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':isActive', $isActive, PDO::PARAM_BOOL);
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':avatar', $avatar);
    $stmt->execute();
    echo 'User created successfully';
}




// Create a wishlist
function createWishlist()
{
    authenticate();
    global $db;
    // Implement your logic to create a wishlist
    // You can access the request body using $_POST or file_get_contents('php://input')
    // Don't forget to set the appropriate HTTP headers, e.g., header('Content-Type: application/json')
    // Example:
    $values = json_decode(file_get_contents('php://input'), false);
    $description = $values->description;
    $name = $values->name;
    $userId = $values->userId;
    $stmt = $db->prepare("INSERT INTO Wishlist (description, name, user_id) VALUES (:description, :name, :userId)");
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':userId', $userId);
    $stmt->execute();
    echo 'Wishlist created successfully';
}

// Create an article
function createArticle()
{
    authenticate();

    global $db;
    // Implement your logic to create an article
    // You can access the request body using $_POST or file_get_contents('php://input')
    // Don't forget to set the appropriate HTTP headers, e.g., header('Content-Type: application/json')
    // Example:
    $values = json_decode(file_get_contents('php://input'), false);
    $name = $values->name;
    $description = $values->description;
    $price = $values->price;
    $link = $values->link;
    $imgLink = $values->imgLink;
    $stmt = $db->prepare("INSERT INTO Articles (name, description, price, link, imgLink) VALUES (:name, :description, :price, :link, :imgLink)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':link', $link);
    $stmt->bindParam(':imgLink', $imgLink);
    $stmt->execute();
    echo 'Article created successfully';
}

// Create a comment for a wishlist
function createComment()
{
    authenticate();

    global $db;
    // Implement your logic to create a comment for a wishlist
    // You can access the request body using $_POST or file_get_contents('php://input')
    // Don't forget to set the appropriate HTTP headers, e.g., header('Content-Type: application/json')
    // Example:
    $values = json_decode(file_get_contents('php://input'), false);
    $description = $values->description;
    $date = $values->date;
    $userId = $values->userId;
    $wishlistId = $values->wishlistId;
    $stmt = $db->prepare("INSERT INTO Comment (description, date, user_id, wishlist_id) VALUES (:description, :date, :userId, :wishlistId)");
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':userId', $userId);
    $stmt->bindParam(':wishlistId', $wishlistId);
    $stmt->execute();
    echo 'Comment created successfully';
}

function createArticleToWishlist($articleId, $wishlistId)
{
    authenticate();

    global $db;
    // Check if the article and wishlist exist
    $articleExists = checkRecordExists('Articles', 'id', $articleId);
    $wishlistExists = checkRecordExists('Wishlist', 'id', $wishlistId);

    if ($articleExists && $wishlistExists) {
        // Insert the record into the Article_Wishlist table
        $stmt = $db->prepare("INSERT INTO Article_Wishlist (article_id, wishlist_id) VALUES (:article_id, :wishlist_id)");
        $stmt->bindParam(':article_id', $articleId, PDO::PARAM_INT);
        $stmt->bindParam(':wishlist_id', $wishlistId, PDO::PARAM_INT);
        $stmt->execute();

        echo 'Article added to wishlist successfully.';
    } else {
        echo 'Invalid article ID or wishlist ID.';
    }
}


// Update a user by ID
function updateUser($id)
{
    global $db;

    //Get User by ID
    $stmt = $db->prepare("SELECT * FROM User WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $values = json_decode(file_get_contents('php://input'), false);
    $username = $values->username;
    $email = $values->email;
    if($values->password === $user['password']){
        $password = $values->password;
    }else{
        $password = password_hash($values->password, PASSWORD_DEFAULT);
    }
    $isActive = $values->isActive;
    $role = $values->role;
    $avatar = $values->avatar;
    $stmt = $db->prepare("UPDATE User SET username = :username, email = :email, password = :password, isActive = :isActive, role = :role, avatarUrl = :avatar WHERE id = :id");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':isActive', $isActive, PDO::PARAM_BOOL);
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':avatar', $avatar);
    $stmt->execute();
    echo 'User ' . $id . ' updated successfully';
}

// Update a wishlist by ID
function updateWishlist($id)
{
    global $db;
    // Implement your logic to update a wishlist by ID
    // Example:
    $values = json_decode(file_get_contents('php://input'), false);
    $description = $values->description;
    $name = $values->name;
    $stmt = $db->prepare("UPDATE Wishlist SET description = :description, name = :name WHERE id = :id");
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    echo 'Wishlist ' . $id . ' updated successfully';
}

// Update an article by ID
function updateArticle($id)
{
    global $db;
    // Implement your logic to update an article by ID
    // Example:
    $values = json_decode(file_get_contents('php://input'), false);
    $name = $values->name;
    $description = $values->description;
    $price = $values->price;
    $link = $values->link;
    $imgLink = $values->imgLink;
    $stmt = $db->prepare("UPDATE Articles SET name = :name, description = :description, price = :price, link = :link, imgLink = :imgLink WHERE id = :id");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':link', $link);
    $stmt->bindParam(':imgLink', $imgLink);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    echo 'Article ' . $id . ' updated successfully';
}

// Update a comment by ID
function updateComment($id)
{
    global $db;
    // Implement your logic to update a comment by ID
    // Example:
    $values = json_decode(file_get_contents('php://input'), false);
    $description = $values->description;
    $date = $values->date;
    $stmt = $db->prepare("UPDATE Comment SET description = :description, date = :date WHERE id = :id");
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    echo 'Comment ' . $id . ' updated successfully';
}

// Delete a user by ID
function deleteUser($id)
{
    global $db;
    // Implement your logic to delete a user by ID
    // Example:
    $stmt = $db->prepare("DELETE FROM User WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    echo 'User ' . $id . ' deleted successfully';
}

// Delete a wishlist by ID
function deleteWishlist($id)
{
    global $db;
    // Implement your logic to delete a wishlist by ID
    // Example:
    $stmt = $db->prepare("DELETE FROM Wishlist WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    echo 'Wishlist ' . $id . ' deleted successfully';
}

// Delete an article by ID
function deleteArticle($id)
{
    global $db;
    // Implement your logic to delete an article by ID
    // Example:
    $stmt = $db->prepare("DELETE FROM Articles WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    echo 'Article ' . $id . ' deleted successfully';
}

// Delete a comment by ID
function deleteComment($id)
{
    global $db;
    // Implement your logic to delete a comment by ID
    // Example:
    $stmt = $db->prepare("DELETE FROM Comment WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    echo 'Comment ' . $id . ' deleted successfully';
}

function deleteArticleFromWishlist($articleId, $wishlistId)
{
    global $db;

    // Delete the record from the Article_Wishlist table
    $stmt = $db->prepare("DELETE FROM Article_Wishlist WHERE article_id = :article_id AND wishlist_id = :wishlist_id");
    $stmt->bindParam(':article_id', $articleId, PDO::PARAM_INT);
    $stmt->bindParam(':wishlist_id', $wishlistId, PDO::PARAM_INT);
    $stmt->execute();

    // Check if any rows were affected by the delete operation
    $rowCount = $stmt->rowCount();

    if ($rowCount > 0) {
        echo 'Article removed from wishlist successfully.';
    } else {
        echo 'Invalid article ID or wishlist ID.';
    }
}

//Login user by username and password and return a JWT token
function login()
{
    global $db;
    // Implement your logic to login user by username and password
    // Example:
    $values = json_decode(file_get_contents('php://input'), false);
    $username = $values->username;
    $password = password_hash($values->password, PASSWORD_DEFAULT);
    $stmt = $db->prepare("SELECT * FROM User WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($values->password, $user['password']) ) {
        $token = generateJWT($user['id'], $user['username']);
        $result = (object)array("token"=>$token);
        echo json_encode($result);
    } else {
        echo 'Invalid username or password';
    }
}





// Handle the API request
if (isset($routes[$method][$uri])) {
    $handler = $routes[$method][$uri];
    $params = [];

    // Extract query parameters
    foreach ($_GET as $param => $value) {
        $params[] = $value;
    }
    //if Method is PUT or DELETE check authorization
    if ($method === 'PUT' || $method === 'DELETE') {
        authenticate();
    }

    // Call the appropriate handler with the extracted parameters
    call_user_func_array($handler, $params);
} else {
    // Invalid route
    header("HTTP/1.1 404 Not Found");
    echo 'Invalid route';
}


// Authentication middleware
function authenticate()
{
    // Check if the Authorization header is present
    global $secretJWT;
    if (!isset($_SERVER['HTTP_TOKEN'])) {
        http_response_code(401);
        echo 'Authorization header is missing.';
        exit;
    }

    $authorizationHeader = $_SERVER['HTTP_TOKEN'];

    // Check if the Authorization header has the Bearer scheme
    if (!preg_match('/Bearer\s(\S+)/', $authorizationHeader, $matches)) {
        http_response_code(401);
        echo 'Invalid authorization header format.';
        exit;
    }

    $jwt = $matches[1];

    // Verify the JWT
    try {
        $decoded = JWT::decode($jwt, new Key($secretJWT, 'HS256'));
        // Proceed with the API logic
    } catch (Exception $e) {
        http_response_code(401);
        echo 'Invalid or expired token.';
        exit;
    }
}

function checkRecordExists($tableName, $columnName, $value): bool
{
    global $db;

    $stmt = $db->prepare("SELECT COUNT(*) FROM $tableName WHERE $columnName = :value");
    $stmt->bindParam(':value', $value);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    return $count > 0;
}

function generateJWT($userId, $username): string
{
    global $secretJWT;
    $payload = [
        'user_id' => $userId,
        'username' => $username,
        'exp' => time() + (60 * 60) // Expiration time: 1 hour from now
    ];
    return JWT::encode($payload, $secretJWT, 'HS256');
}



function cors() {

    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
        // you want to allow, and if so:
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE,PATCH");

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }
}
