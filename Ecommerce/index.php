<!DOCTYPE html>
<html>

<head>
    <title>E-commerce API</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <div class="navbar">
        <h1>E-commerce API</h1>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2>Users</h2>
                <button id="getUsers" class="btn btn-primary">Get All Users</button>
                <button id="getUser" class="btn btn-secondary">Get User by ID</button>
                <button id="createUser" class="btn btn-success">Create User</button>
                <button id="login" class="btn btn-info">Login</button>
            </div>
            <div class="col-md-6">
                <h2>Products</h2>
                <button id="getProducts" class="btn btn-primary">Get All Products</button>
                <button id="searchProducts" class="btn btn-secondary">Search Products</button>
                <button id="createProduct" class="btn btn-success">Create Product</button>
            </div>
        </div>
        <div id="output" class="mt-4"></div>
    </div>

    <script>
        document.getElementById('getUsers').addEventListener('click', function() {
            fetch('api/users.php')
                .then(response => response.json())
                .then(data => document.getElementById('output').innerText = JSON.stringify(data, null, 2));
        });

        document.getElementById('getUser').addEventListener('click', function() {
            const userId = prompt('Enter User ID');
            fetch(`api/users.php?id=${userId}`)
                .then(response => response.json())
                .then(data => document.getElementById('output').innerText = JSON.stringify(data, null, 2));
        });

        document.getElementById('createUser').addEventListener('click', function() {
            const name = prompt('Enter Name');
            const email = prompt('Enter Email');
            const password = prompt('Enter Password');
            fetch('api/users.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        name,
                        email,
                        password
                    })
                })
                .then(response => response.json())
                .then(data => document.getElementById('output').innerText = JSON.stringify(data, null, 2));
        });

        document.getElementById('login').addEventListener('click', function() {
            const email = prompt('Enter Email');
            const password = prompt('Enter Password');
            fetch('api/auth.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        email,
                        password
                    })
                })
                .then(response => response.json())
                .then(data => document.getElementById('output').innerText = JSON.stringify(data, null, 2));
        });

        document.getElementById('getProducts').addEventListener('click', function() {
            fetch('api/products.php')
                .then(response => response.json())
                .then(data => document.getElementById('output').innerText = JSON.stringify(data, null, 2));
        });

        document.getElementById('searchProducts').addEventListener('click', function() {
            const productName = prompt('Enter Product Name');
            fetch(`api/products.php?name=${productName}`)
                .then(response => response.json())
                .then(data => document.getElementById('output').innerText = JSON.stringify(data, null, 2));
        });

        document.getElementById('createProduct').addEventListener('click', function() {
            const name = prompt('Enter Product Name');
            const description = prompt('Enter Product Description');
            const price = prompt('Enter Product Price');
            fetch('api/products.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        name,
                        description,
                        price
                    })
                })
                .then(response => response.json())
                .then(data => document.getElementById('output').innerText = JSON.stringify(data, null, 2));
        });
    </script>
</body>

</html>