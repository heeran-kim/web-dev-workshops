<?php
    // Define an associative array to store posts
    $posts = array();
    
    // Simply hard code 3 posts into the array.
    $posts[] = array( 
        'user' => 'Amy',
        'message' => "The First Cat",
        'image' => 'images/cat1.jpeg',
        'date' => '01/07/2024'
    );
    $posts[] = array(
        'user'=>'Bryan',
        'message'=>"The Second Cat",
        'image' => 'images/cat2.jpeg',
        'date'=>'02/07/2024'
    );
    $posts[] = array(
        'user'=>'Carl',
        'message'=>"The Third Cat",
        'image' => 'images/cat3.jpeg',
        'date'=>'03/07/2024'
    );
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <title>Social Media</title>
    </head>
    <body>
        <div class="container">
          
            <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
            <a href="index.html" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
                <svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"/></svg>
                <span class="fs-4">Social Media</span>
            </a>
                  
            <div class="col-md-3 mb-2 mb-md-0">
                <a href="/" class="d-inline-flex link-body-emphasis text-decoration-none">
                <svg class="bi" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"/></svg>
                </a>
            </div>
        
            <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
                <li><a href="#" class="nav-link px-2">Photos</a></li>
                <li><a href="#" class="nav-link px-2">Friends</a></li>
            </ul>
        
            <div class="col-md-3 text-end">
                <button type="button" class="btn btn-outline-primary me-2">Login</button>
                <button type="button" class="btn btn-primary">Sign-up</button>
            </div>
            </header>

    

            <div class="row" id="content">
                <div class="d-flex flex-column flex-shrink-0 p-3 text-bg-dark col-sm-4">
                    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                      <svg class="bi pe-none me-2" width="0" height="32"><use xlink:href="#bootstrap"/></svg>
                      <span class="fs-4">Sidebar</span>
                    </a>
                    <hr>
                    <label>Name:</label><input type="text"><br>
                    <label>Message:</label><textarea id="messageInput" placeholder="Enter new message.."></textarea>
                    <br>
                    <ul class="nav nav-pills flex-column mb-auto">
                        <li class="center">
                            <a href="#" class="nav-link active" aria-current="page">
                            Submit
                            </a>
                        </li>
                    </ul>
                    <hr>
                    
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">
                        <strong>John</strong>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">Sign out</a></li>
                        </ul>
                    </div>
                </div>



                <!-- Posting Part -->
                <div class="col-sm-8">
                    <?php foreach($posts as $post) {?>
                        <div class="post border border-dark-subtle rounded-end my-3 p-2 d-flex">
                            <img src="<?= $post['image'] ?>" width=200 alt="cat" class="rounded-5 float-start me-5"><br>
                            <?= $post['user'] ?><br>
                            <?= $post['message'] ?><br>
                            <?= $post['date'] ?><br>
                        </div>
                    <?php } ?>
                </div>


            </div>
        </div><!--/.container-->


        <div class="container">
            <footer class="py-3 my-4">
              <ul class="nav justify-content-center border-bottom pb-3 mb-3">
                <li class="nav-item"><a href="#" class="nav-link px-2 text-body-secondary">Home</a></li>
                <li class="nav-item"><a href="#" class="nav-link px-2 text-body-secondary">Photos</a></li>
                <li class="nav-item"><a href="#" class="nav-link px-2 text-body-secondary">Friends</a></li>
                <li class="nav-item"><a href="#" class="nav-link px-2 text-body-secondary">FAQs</a></li>
                <li class="nav-item"><a href="#" class="nav-link px-2 text-body-secondary">About</a></li>
              </ul>
              <p class="text-center text-body-secondary">&copy; 2024 Company, Inc</p>
            </footer>
          </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>