<!DOCTYPE html>
<html>
    <head>
        <title>
            Upload an image
        </title>
    </head>
    <body>
        <form action="/upload-an-image" method="post" enctype="multipart/form-data">
        <label>Select image to upload:</label>
            <input type="file" name="fileToUpload" id="fileToUpload">
            <input type="submit" value="Upload Image" name="submit">
        </form>
    </body>
</html>