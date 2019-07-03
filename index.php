<!-- <!DOCTYPE html>
<html>
<head>
    <title>Analyze Sample</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
</head>
<body>

<script type="text/javascript">

<form></form>

    function processImage() {
        // **********************************************
        // *** Update or verify the following values. ***
        // **********************************************

        // Replace <Subscription Key> with your valid subscription key.
        var subscriptionKey = "d7b3fef1dd844d2bbcc479775589001d";

        // You must use the same Azure region in your REST API method as you used to
        // get your subscription keys. For example, if you got your subscription keys
        // from the West US region, replace "westcentralus" in the URL
        // below with "westus".
        //
        // Free trial subscription keys are generated in the "westus" region.
        // If you use a free trial subscription key, you shouldn't need to change
        // this region.
        var uriBase =
            "https://southeastasia.api.cognitive.microsoft.com/vision/v2.0/analyze";
            // "https://southeastasia.api.cognitive.microsoft.com/vision/v2.0/analyze";

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
            $("#responseTextArea").val(JSON.stringify(data, null, 2));
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

<h1>Analyze image:</h1>
Upload an image to be analyzed!.
<br><br>
Image to analyze:
<input type="file" name="inputImage" id="inputImage" />
<button onclick="processImage()">Upload</button>
<br><br>
<div id="wrapper" style="width:1020px; display:table;">
    <div id="jsonOutput" style="width:600px; display:table-cell;">
        Response:
        <br><br>
        <textarea id="responseTextArea" class="UIInput"
                  style="width:580px; height:400px;"></textarea>
    </div>
    <div id="imageDiv" style="width:420px; display:table-cell;">
        Source image:
        <br><br>
        <img id="sourceImage" width="400" />
    </div>
</div>
</body>
</html> -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="berkas" />
        <input type="submit" name="SubmitButton"/>
    </form>
</body>
</html>

<?php

require_once 'vendor/autoload.php';
require_once "./random_string.php";

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;

$connectionString = "DefaultEndpointsProtocol=https;AccountName=blibblob2;AccountKey=w0ui1/rBkxZ+GrpslxMRscdfezMOo33/dbz3wJbOTWI388Wgb9o6JMCg9NRgSJK7f5u937fjBT7koYuRWuVNmg==;EndpointSuffix=core.windows.net";

// Create blob client.
$blobClient = BlobRestProxy::createBlobService($connectionString);

if (isset($_POST['SubmitButton'])) { //check if form was submitted

    //UPLOAD FILES
    if (($_FILES['berkas']['name'] != "")) {
        // echo "<pre>";
        // echo var_dump($_FILES);
        // echo "</pre>";
        // Where the file is going to be stored
        // $target_dir = "upload/";
        // $file = $_FILES['berkas']['name'];
        // $path = pathinfo($file);
        // $filename = $path['filename'];
        // $ext = $path['extension'];
        // $temp_name = $_FILES['berkas']['tmp_name'];
        // $path_filename_ext = $target_dir . $filename . "." . $ext;

        // // Check if file already exists
        // if (file_exists($path_filename_ext)) {
        //     echo "Sorry, file already exists.";
        // } else {
        //     //MOVE FILES TO PROJECT UPLOAD DIRECTORY
        //     move_uploaded_file($temp_name, $path_filename_ext);
        //     echo "Congratulations! File Uploaded to Project Directory.";

        //     $fileToUpload = $path_filename_ext;

        // }

      

        // try {

            // Getting local file so that we can upload it to Azure
            // $myfile = fopen($fileToUpload, "w") or die("Unable to open file!");
            // fclose($myfile);

            # Upload file as a block blob
            // echo "Uploading BlockBlob: " . PHP_EOL;
            // echo $fileToUpload;
            // echo "<br />";

            $containerName = "imagecontainer";

            //Upload blob
            // $blobClient->createBlockBlob($containerName, $fileToUpload, $content);
            $content = fopen($_FILES['berkas']['tmp_name'] . '', "r");
            $uploadData = $blob->uploadToContainer($content, $containerName);

            if($uploadData) {
                echo "Success";
            } else {
                echo "Fail!";
            }

            // $type = $_FILES['berkas']['type'];
            // $blob_name = $_FILES['berkas']['name'];
            // $blob = BlobRestProxy::getBlob($containerName, $blob_name);
            // header("Content-Type:".$type);
            // header('Content-Disposition: attachment; filename="' . $blob_name . '"');
            // fpassthru($blob->getContentStream());

        // } catch (ServiceException $e) {
        //     $code = $e->getCode();
        //     $error_message = $e->getMessage();
        //     echo $code . ": " . $error_message . "<br />";

        // } catch (InvalidArgumentTypeException $e) {

        //     $code = $e->getCode();
        //     $error_message = $e->getMessage();
        //     echo $code . ": " . $error_message . "<br />";
        // }

       
    }

}

?>