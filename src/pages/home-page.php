<!DOCTYPE html>
<html>

<head>
    <title>Home</title>
    <link rel="stylesheet" href="src/style/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
</head>

<body>
    <div class="one">
        <button class="grid-button" onclick="navigationTab()">
            <img src="grid.svg" class="grid" />
        </button>
        <div class="ui-header-banner">
            <img class="ui-header-logo" src="uploads/pen-icon.png">
            <div>
                <h1 class="page-title">Email Posts</h1>
            </div>
        </div>
        <div class="header-search-bar">
            <form class="header-search" method="POST" action="/search-list" id="search-form">
                <div class="form-parts">
                    <label class="form-parts search-text">Search:</label>
                </div>
                <div class="search-form">
                    <input class="search-input" type="text" name="search"  placeholder="Type something" class="form-parts"
                        value="<?php echo !empty($search) ? $search : ''?>"
                    />
                    <button class="form-parts search-button-icon" type="submit"><img class="search-icon" src="magnifying.svg" /></button>
                </div>
            </form>
        </div>
    </div>
    <div class="two">
        <div class="left" id="left">
            <?php if (!$isLoggedIn): ?>
                <div class="left-icon">
                    <img class="header-icon" src="uploads/trial-logo.png">
                </div>
                <a class="nav-buttons" href="/new-register" target="_self">Register</a>
                <a class="nav-buttons" href="/new-login" target="_self">Log In</a>
            <?php else: ?>
                <div class="hidden-left">
                    <div class="left-icon">
                        <img class="header-icon" src="<?php echo !empty($_SESSION['profile_pic']) ? $_SESSION['profile_pic'] : 'uploads/trial-logo.png' ?>">
                    </div>
                    <?php echo '<p class="user-name">' ?>
                    <?= $_SESSION['username'] ?>
                    <?php echo '</p>' ?>
                    <button class="nav-buttons icon-button" onclick="modifyIcon()" id="icon-button">Icon</button>
                    <button class="nav-buttons home-button" onclick="homePage()">Home</button>
                    <button class="nav-buttons menu-button" onclick="menuPage()">Menu</button>
                    <button class="nav-buttons logout-button" onclick="logOut()">Logout</button>
                </div>
            <?php endif; ?>
        </div>
        <div class="right" id="main">
            <?php if (!$isLoggedIn): ?>
                <div class="posting">
                    <p class="page-title">You can post stuff in here, but you have to be logged in first</p>
                    <a class="nav-buttons" href="/new-register" target="_self">Register</a>
                    <a class="nav-buttons" href="/new-login" target="_self">Log In</a>
                </div>
            <?php else: ?>
                <div class="posting">
                    <h3 class="post-content">Posting Something?</h3>
                    <form class="post-content" method="POST" action="/new-posting" id="content-form" enctype="multipart/form-data">
                        <label class="post-content">Enter Post...</label>
                        <br>
                        <textarea class="post-content-area" name="content" rows="4" cols="50"></textarea>
                        <br>
                        <label class="post-content">Select image to upload:</label>
                        <input type="file" name="fileToUpload" id="fileToUpload" class="post-content">
                        <br>
                        <button class="post-content post-content-button" type="submit">POST</button>
                    </form>
                    <?php if (isset($isSaved)): ?>
                        <p>Saved succesfully</p>
                    <?php endif; ?>
                </div>
                <div class="posts">
                    <h3 class="posts-title">Posts</h3>
                    <?php foreach($formattedPosts as $a_post): ?>
                        <?php $formId = 'temp-form-' . $a_post['id']; ?>
                        <?php include('./src/pages/user-posts.php'); ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <script>
        function navigationTab() {
            var x = document.getElementById('left');

            console.log()
            if (
                (x.style.maxHeight == 0 || x.style.maxHeight == '0px') &&
                (x.style.maxWidth == 0 || x.style.maxWidth == '0px')
            ) {
                x.style.maxHeight = "500px";
                x.style.maxWidth = "15%";
            } else {
                x.style.maxHeight = 0;
                x.style.maxWidth = 0;
            }
        }


        let iconButton = document.getElementById('icon-button');
        let mainPage = document.getElementById('main');

        function modifyIcon() {
            let divPopUp = newElementCreation(
                'div', '', 'icon-form-div', ['pop-up-form'], [{
                    attributeName: "type",
                    attributeValue: ""
                }], mainPage
            );

            let formPopUp = newElementCreation(
                'form', 'Modify-Icon', 'icon-form', ['modify-icon-form'], [
                    { attributeName: "action", attributeValue: "/upload-and-crop" },
                    { attributeName: "method", attributeValue: "POST"},
                    { attributeName: "enctype", attributeValue: "multipart/form-data"}
            ], divPopUp
            );
            // action="/upload-and-crop" method="post" enctype="multipart/form-data"

            let inputPart = newElementCreation(
                'input',
                '',
                'fileToUpload',
                ['fileToUpload'],
                [{
                    attributeName: "type",
                    attributeValue: "file"
                }, {
                    attributeName: "name",
                    attributeValue: "fileToUpload"
                }],
                formPopUp
            );

            let submitPart = newElementCreation(
                'input',
                '',
                '',
                [],
                [{
                        attributeName: "type",
                        attributeValue: "submit"
                    },
                    {
                        attributeName: "name",
                        attributeValue: "submit"
                    },
                    {
                        attributeName: "value",
                        attributeValue: "Upload Image"
                    }
                ],
                formPopUp
            );

            let exitButton = newElementCreation("button", "&times;", "exit-button", ["exit-button"],
                [{
                    attributeName: "type",
                    attributeValue: ""
                }], formPopUp
            );

            exitButton.onclick = function() {
                divPopUp.remove();
            }

        };

        function newElementCreation(
            elementType,
            innerHTML,
            elementId,
            elementClasses,
            elementAttributes,
            parent
        ) {
            var newElement = document.createElement(elementType);
            if (innerHTML != '') {
                newElement.innerHTML = innerHTML;
            }

            newElement.id = elementId;
            elementClasses.forEach((className) => {
                newElement.classList.add(className);
            });
            elementAttributes.forEach((elementAttribute) => {
                newElement.setAttribute(elementAttribute.attributeName, elementAttribute.attributeValue);
            });
            parent.appendChild(newElement);
            return newElement;
        };

        function commentFunction(elementId) {
            var x = document.getElementById(elementId);
            // console.log();
            if (x.style.maxHeight == 0 || x.style.maxHeight == '0px') {
                x.style.maxHeight = "500px";
            } else {
                x.style.maxHeight = 0;
            }
        }

        function homePage() {
            window.location.href = "http://email.api:8080/home-page";
        }

        function menuPage() {
            window.location.href = "http://email.api:8080/all-posts";
        }

        function logOut() {
            window.location.href = "http://email.api:8080/new-logout";
        }

    </script>
</body>

</html>