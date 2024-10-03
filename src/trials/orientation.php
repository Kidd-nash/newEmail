<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="src/trials/style.css">
        <title>Fixing</title>
        <script>
            function iconForm() {
                var x = document.getElementById('form-wrapper');

                console.log()
                if (
                    (x.style.maxHeight == 0 || x.style.maxHeight == '0px')
                    && 
                    (x.style.maxWidth == 0 || x.style.maxWidth == '0px')
                ) {
                    x.style.maxHeight = "500px";
                    x.style.maxWidth = "500px";
                } else {
                    x.style.maxHeight = 0;
                    x.style.maxWidth = 0;
                }
            }
        </script>
    </head>
    <body>
        <div class="header">
            <div class="main-header">
                <div class="main-header-icon">
                    <img class="header-icon" src="uploads/trial-logo.png">
                </div>
                <div class="main-header-banner">
                    <img class="header-logo" src="uploads/pen-icon.png">
                    <div>
                        <h1>Email Posts</h1>
                        <a>Menu</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="nav-header">
            <button class="icon-button" onclick="iconForm()">Icon</button>
            <div id="form-wrapper" class="form-wrapper">
                <form class="icon-form" id="icon-form">
                    <label>Select image to upload:</label>
                    <input type="file" name="fileToUpload" id="fileToUpload">
                    <input type="submit" value="Upload Image" name="submit">
                </form>
            </div>
            <a class="signout" href="/new-logout">Log out</a>
        </div>
        <section>
            <div><p class="circle">Circle</p></div>
        </section>   
    </body>
</html>