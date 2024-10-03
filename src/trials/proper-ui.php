<!DOCTYPE html>
<html>
    <head>
        <title>Proper UI</title>
        <link rel="stylesheet" href="src/trials/style.css">
    </head>
    <body>
        <div class="one">
            <button class="grid-button" onclick="navigationTab()">
                <img src="grid.svg" class="grid"/>
            </button>
            <div class="ui-header-banner">
                <img class="ui-header-logo" src="uploads/pen-icon.png">
                <div>
                    <h1>Email Posts</h1>
                    <a>Menu</a>
                </div>
            </div>
        </div>
        <div class="two">
            <div class="left" id="left"> 
                <div class="hidden-left">
                    <div class="left-icon">
                        <img class="header-icon" src="uploads/trial-logo.png">
                    </div>
                    <button class="nav-buttons" onclick="modifyIcon()" id="icon-button">Icon</button>
                    <button class="nav-buttons" >Home</button>
                    <button class="nav-buttons" >Menu</button>
                    <button class="nav-buttons" >Logout</button>
                </div>
            </div>
            <div class="right" id="main">
                <div class="posting">
                    <h3>Posting Part</h3>
                </div>
                <div class="posts">
                    <h3>Posts</h3>
                </div>
            </div>
        </div>
        <script>
            function navigationTab() {
                var x = document.getElementById('left');

                console.log()
                if (
                    (x.style.maxHeight == 0 || x.style.maxHeight == '0px')
                    && 
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
            let mainPage  = document.getElementById('main');
            
            function modifyIcon() {
                let divPopUp = newElementCreation(
                    'div', '', 'icon-form-div', ['pop-up-form'], [{ attributeName: "type", attributeValue: ""}], mainPage
                );

                let formPopUp = newElementCreation(
                    'form', 'Modify-Icon', 'icon-form', ['modify-icon-form'], [{ attributeName: "type", attributeValue: ""}], divPopUp
                );

                let inputPart = newElementCreation(
                    'input', 
                    '', 
                    'fileToUpload', 
                    ['fileToUpload'], 
                    [{ attributeName: "type", attributeValue: "file"}, { attributeName: "name", attributeValue: "fileToUpload"}],
                    formPopUp
                );

                let submitPart = newElementCreation(
                    'input', 
                    '', 
                    '', 
                    [], 
                    [{ attributeName: "type", attributeValue: "submit"}, 
                    { attributeName: "name", attributeValue: "submit"},
                    { attributeName: "value", attributeValue: "Upload Image"}],
                    formPopUp
                );

                let exitButton = newElementCreation("button", "&times;", "exit-button", ["exit-button"],
                    [{ attributeName: "type", attributeValue: ""}], formPopUp
                );

                exitButton.onclick = function () {
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


        </script>
    </body>
</html>