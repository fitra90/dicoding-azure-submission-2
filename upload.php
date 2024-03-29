<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=<, initial-scale=1.0">
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
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;

$connectionString = "DefaultEndpointsProtocol=https;AccountName=blibblob2;AccountKey=w0ui1/rBkxZ+GrpslxMRscdfezMOo33/dbz3wJbOTWI388Wgb9o6JMCg9NRgSJK7f5u937fjBT7koYuRWuVNmg==;EndpointSuffix=core.windows.net";

// Create blob client.
$blobClient = BlobRestProxy::createBlobService($connectionString);

$fileToUpload = $_FILES['berkas'];

if (isset($_POST['SubmitButton'])) { //check if form was submitted
    // echo "<pre>";
    // print_r($_FILES);
    // echo "</pre>";

    $createContainerOptions = new CreateContainerOptions();

    $createContainerOptions->setPublicAccess(PublicAccessType::CONTAINER_AND_BLOBS);

    // Set container metadata.
    $createContainerOptions->addMetaData("key1", "value1");
    $createContainerOptions->addMetaData("key2", "value2");

    $containerName = "blockblobs" . generateRandomString();

    try {
        // Create container.
        $blobClient->createContainer($containerName, $createContainerOptions);

        // Getting local file so that we can upload it to Azure
        // $myfile = fopen($fileToUpload, "w") or die("Unable to open file!");
        // fclose($myfile);

        # Upload file as a block blob
        echo "Uploading BlockBlob: " . PHP_EOL;
        echo $fileToUpload;
        echo "<br />";

        // $content = fopen($fileToUpload, "r");

        //Upload blob
        // $blobClient->createBlockBlob($containerName, $fileToUpload, $content);
        $blobClient->createBlockBlob($containerName, $fileToUpload);

        // List blobs.
        $listBlobsOptions = new ListBlobsOptions();
        $listBlobsOptions->setPrefix("myImage");

        echo "These are the blobs present in the container: ";

        do {
            $result = $blobClient->listBlobs($containerName, $listBlobsOptions);
            foreach ($result->getBlobs() as $blob) {
                echo $blob->getName() . ": " . $blob->getUrl() . "<br />";
            }

            $listBlobsOptions->setContinuationToken($result->getContinuationToken());
        } while ($result->getContinuationToken());
        echo "<br />";

        // Get blob.
        echo "This is the content of the blob uploaded: ";
        $blob = $blobClient->getBlob($containerName, $fileToUpload);
        fpassthru($blob->getContentStream());
        echo "<br />";
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

?>