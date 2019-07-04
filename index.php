<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Fitra - Dicoding Azure Cloud Submission 2</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
</head>
<body>
    <h3>Azure Image Analyzer</h3>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="berkas" />
        <input type="submit" name="SubmitButton"/>
    </form>

    <br><br>

<script type="text/javascript">
    function processImage() {
     
        var subscriptionKey = "d7b3fef1dd844d2bbcc479775589001d";

        var uriBase =
            "https://southeastasia.api.cognitive.microsoft.com/vision/v2.0/analyze";
 
        // Request parameters.
        var params = {
            "visualFeatures": "Categories,Description,Color",
            "details": "",
            "language": "en",
        };
 
        // Display the image.
        var sourceImageUrl = document.getElementById("inputImage").value;
        document.querySelector("#sourceImage").src = sourceImageUrl;
 
        // Make the REST API call.
        $.ajax({
            url: uriBase + "?" + $.param(params),
 
            // Request headers.
            beforeSend: function(xhrObj){
                xhrObj.setRequestHeader("Content-Type","application/json");
                xhrObj.setRequestHeader(
                    "Ocp-Apim-Subscription-Key", subscriptionKey);
            },
 
            type: "POST",
 
            // Request body.
            data: '{"url": ' + '"' + sourceImageUrl + '"}',
        })
 
        .done(function(data) {
            // Show formatted JSON on webpage.
            // $("#responseTextArea").val(JSON.stringify(data, null, 2));
            $(".imageDescription").html(data.description.captions[0].text);
        })
 
        .fail(function(jqXHR, textStatus, errorThrown) {
            // Display error message.
            var errorString = (errorThrown === "") ? "Error. " :
                errorThrown + " (" + jqXHR.status + "): ";
            errorString += (jqXHR.responseText === "") ? "" :
                jQuery.parseJSON(jqXHR.responseText).message;
            alert(errorString);
        });
    };
</script>

</body>
</html>

<?php

require_once 'vendor/autoload.php';
require_once "./random_string.php";

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;

if (isset($_POST['SubmitButton'])) { //check if form was submitted

    if (($_FILES['berkas']['name'] != "")) {

        $connectionString = "DefaultEndpointsProtocol=https;AccountName=blibblob2;AccountKey=w0ui1/rBkxZ+GrpslxMRscdfezMOo33/dbz3wJbOTWI388Wgb9o6JMCg9NRgSJK7f5u937fjBT7koYuRWuVNmg==;EndpointSuffix=core.windows.net";

        // Create blob client.
        $blobClient = BlobRestProxy::createBlobService($connectionString);
        $containerName = "imagecontainer";

        //Upload blob
        try {

            $fileToUpload = $_FILES['berkas']['name'];
            $content = file_get_contents($_FILES['berkas']['tmp_name']);

            $blobClient->createBlockBlob($containerName, $fileToUpload, $content);

            echo "<br />";
            echo "<input type='hidden' id='inputImage' value='https://blibblob2.blob.core.windows.net/imagecontainer/". $fileToUpload . "'>";
            echo "<div id='wrapper' style='width:1020px; display:table;'>";
            echo "<div id='imageDiv' style='width:420px; display:table-cell;'>";
            echo "Uploaded image: ";
            echo "<br><br>";
            echo "<img onload='processImage()' id='sourceImage' src='https://blibblob2.blob.core.windows.net/imagecontainer/". $fileToUpload . "' width='20%' />";
            echo "<p class='imageDescription'></p>";
            echo "</div>";
            echo "</div>";

        } catch (ServiceException $e) {
            $code = $e->getCode();
            $error_message = $e->getMessage();
            echo $code . ": " . $error_message . "<br />";

        } catch (InvalidArgumentTypeException $e) {

            $code = $e->getCode();
            $error_message = $e->getMessage();
            echo $code . ": " . $error_message . "<br />";
        }

    }

}

?>