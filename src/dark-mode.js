function darkMode() {
    let headerPart = document.getElementById("header-div");
    let headerLogo = document.getElementById("header-logo");
    let headerText = document.getElementById("header-text");
    let headerSignUp = document.getElementById("header-signup");

    let contentPart = document.getElementById("content-div");
    let contentForm = document.getElementById("content-form");
    let contentPosts = document.getElementById("content-posts");
    let contentEachPost = document.getElementsByClassName("each-post");

    let footerPart = document.getElementById("footer-content");

    headerPart.classList.toggle("dark-mode");
    headerLogo.classList.toggle("inner-dark-mode");
    headerText.classList.toggle("inner-dark-mode");
    headerSignUp.classList.toggle("inner-dark-mode");

    contentPart.classList.toggle("dark-mode");
    contentForm.classList.toggle("dark-mode");
    contentPosts.classList.toggle("dark-mode");
    for (let i = 0; i < contentEachPost.length; i++) {
        contentEachPost[i].classList.toggle("dark-mode");
    }

    footerPart.classList.toggle("dark-mode");    
}