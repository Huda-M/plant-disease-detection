<?php
session_start();
// ... بقية الكود
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GreenScar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


    <link href="../style/style.css" rel="stylesheet" />

</head>
<body>
    <!-- first page -->
     <div class="parent">
     
         <div class="burger" id="burger">
            <div></div>
             <div></div>
            <div></div>
       </div>

 
       <div class="sidebar" id="sidebar">
    <ul class="sidebar-links">
        <?php

        $role = $_SESSION['user_role'] ?? 'user';
        
        // العناصر المشتركة
        echo '
        <li><a href="comment.php">Add Post</a></li>
        <li><a href="report.php">Report Post</a></li>
        <li><a href="editprofile.php">Edit Profile</a></li>
        <li><a href="changepassword.php">Change Password</a></li>
        <li><a href="my_comments.php">Comment</a></li>';

        // العناصر الخاصة
        if ($role === 'admin') {
            echo '<li><a href="admindashboard.php">Admin Dashboard</a></li>';
        } 
        elseif ($role === 'expert') {
            echo '
            <li><a href="suggestdisease.php">Suggest a Disease</a></li>
            <li><a href="suggesttreatment.php">Suggest a Treatment</a></li>';
        } 
        else {
            echo '<li><a href="uploadcertificate.php">Upload Certificate</a></li>';
        }
        ?>
    </ul>
</div>
        <div class="navbar">
            <div class="logo">
                <img src="../images/logos.png" alt="logoimage"/>
                <div class="logoname">
                    <h1>GreenScar</h1>
                </div>
                <ul class="ul">
                    <a href="index.php"><li>Home</li></a>
                    <a href="#types"><li>PlantsTypes</li></a>
                    <a href="showposts.php"><li>Posts</li></a>
                    <a href="#more"><li>More</li></a>
                    <a href="#contact"><li>Contact</li></a>
                    

                   
                    
                </ul>
            </div>
            <div class="clear"></div>
            <div class="btns">
                <?php if (isset($_SESSION['user'])): ?>
                    
                <a class="btn signup" href="../auth/logout.php">Log out</a>
                <?php else: ?>
                    <a class="btn signup" href="signup.php">Sign up</a>
                    <a class="btn login" href="login.php">Log in</a>
                <?php endif; ?>
            </div>

        </div>
        <div class="parentcontent">
            <h1>Healthy Plants Start with Smart Diagnosis</h1>
            <p>Our website helps you detect and diagnose plant diseases using advanced image analysis.
                 Simply upload<br> a photo of your plant, and get instant insights to protect and treat it effectively.</p>
            <a href="Explore.php" class="btn2">Explore</a>
            
            <a href="adddisease.php" class="btn2">Add Disease</a>
             </div>
             <div class="sideimage">
                <img src="../images/image11.png" alt="plantimage"/>
             </div>
             <div class="getstarted">
                <a href="upload.php" class="btn">GET STARTED</a>
            </div>
            



        </div>
     </div>
 <!--second page-->
 
<div class="second" id="types">
    <div class="secondcontent">
        <h1>Types you would adore!</h1>
    </div>
    <div class="allimages">
        <div class="image-container">
            <img class="img1" src="../images/11.png" alt="image 1"/>
            
            <p class="image-text image-text1">Chinese Evergreen<br> These plants are not just aesthetically pleasing; they are also known for their air-purifying qualities.<br> Studies by NASA have shown that Aglaonema can help remove common household toxins like formaldehyde and benzene from the air, making them a great choice for improving indoor air quality.</p>
        </div>
        <div class="image-container">
            <img class="img2" src="../images/2.png" alt="image 2"/>
            <p class="image-text image-text1">Zebra Plant<br>Despite its striking appearance with its white, raised bands resembling zebra stripes, the Haworthiopsis fasciata is native to the Eastern Cape province of South Africa, a region with a relatively moderate climate compared to some other succulent habitats. This means it's often more tolerant of slightly cooler temperatures and more frequent watering than some desert succulents, although it still thrives with bright.</p>
        </div>
        <div class="image-container">
            <img src="../images/3.png" alt="image 3"/>
            <p class="image-text image-text1 text3"> Monstera deliciosa<br>he Monstera deliciosa gets its name from its "delicious" fruit, which is edible when ripe and tastes like a combination of fruits, although it takes over a year to ripen. Lorem ipsum dolor sit, amet consectetur adipisicing elit. Error ducimus suscipit sunt fugit placeat nemo repellat nulla provident maxime voluptatibus.</p>
        </div>
        <div class="image-container">
            <img src="../images/4.png" alt="image 4"/>
            <p class="image-text image-text1">Heliconia<br>Heliconias are known for their vibrant and distinctive bracts (modified leaves that surround the flowers), which come in a wide array of colors, from bright reds and oranges to yellows and pinks. These colorful bracts attract pollinators such as hummingbirds, who are specially adapted to feed on the nectar within the flowers. </p>
        </div>
        <div class="image-container">
            <img src="../images/55.png" alt="image 5"/>
            <p class="image-text image-text1 text5">Tiger Aloe<br>Many Aloe species, including the well-known Aloe vera, are succulents with medicinal properties. The gel found within their leaves has been used for centuries to treat burns, wounds, and skin irritations.</p>
        </div>
        <div class="image-container">
            <img class="img6" src="../images/666.png" alt="image 6"/>
            <p class="image-text image-text1">Golden Pothos <br>its incredible resilience and adaptability. It can thrive in a wide range of conditions, tolerating low light, inconsistent watering, and various soil types. This hardiness makes it an exceptionally popular houseplant for beginners and experienced plant enthusiasts alike. 1  It's also known for its ability to purify indoor air to some extent. </p>
        </div>
    </div>
</div>
<!--third page-->
<div class="third" id="more">
    <div class="thirdpage">
        <h1>Customer Reviews</h1>
        <div class="container">
            <div class="pagecontainer">
                <img src="../images/p11.png" alt="pimage"/>
                <p class="review">  <span>
                    PlantMomSarah
                    &nbsp;
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                    <i class="far fa-star"></i>
                  </span><br>This website is a lifesaver! I was so worried when I noticed strange spots on my Calathea, and the diagnosis tool here was incredibly accurate. Within minutes, I knew what the problem was and the recommended treatment worked perfectly. Thank you for helping me keep my plants healthy and happy!<br></p>
            </div>
            <div class="pagecontainer">
                <img src="../images/p22.png" alt="pimage"/>
                <p class="review"> <span>
                    GreenBeginner88
                    &nbsp;
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="far fa-star"></i>
                    <i class="far fa-star"></i>
                  </span><br>Finally, a plant website that isn't overwhelming! The layout is clean and easy to navigate, and the disease information is straightforward and understandable, even for a beginner like me. Uploading a photo of my sick succulent was simple, and the results were quick. Highly recommend!<br></p>
            </div>
            <div class="pagecontainer">
                <img src="../images/p333.png" alt="pimage"/>
                <p class="review"><span>
                    LeafyExpert
                    &nbsp;
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="far fa-star"></i>
                  </span><br>I've tried searching online for plant problems before, but this website is much more effective. The image analysis is impressive, and I appreciate the tailored advice on how to treat different diseases. It's saved me from potentially losing some of my favorite plants. Definitely worth bookmarking!<br></p>
            </div>
        </div>
    </div>

</div>
<!--fourth page-->
<section id="contact">
    <div class="contact-container">
        <h2>Contact Us</h2>
        <p>Need help with your plants? Get in touch with us:</p>

        <!-- Contact Form -->
        <form id="contact-form">
            <label for="name">Name :</label>
            <input type="text" id="name" name="name" required placeholder="Your name">

            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required placeholder="Your email">

            <label for="subject">Subject :</label>
            <select id="subject" name="subject" required>
                <option value="Plant Disease Diagnosis">Plant Disease Diagnosis</option>
                <option value="General Inquiry">General Inquiry</option>
                <option value="Technical Support">Technical Support</option>
            </select>

            <label for="message">Message :</label>
            <textarea id="message" name="message" required placeholder="Your message here..."></textarea>

            <label for="file">Upload an Image :</label>
            <input type="file" id="file" name="file">

            <button type="submit">Submit</button>
        </form>

        <!-- Contact Information -->
        <div class="contact-info">
            <h3>Or Reach Out Directly</h3>
            <p><strong>Email:</strong> <a href="mailto:email@example.com">GreenScar@gmail.com</a></p>
            <p><strong>Phone:</strong> <a href="tel:+123456789">+1 (234) 567-890</a></p>
            <p><strong>WhatsApp:</strong> <a href="https://wa.me/123456789" target="_blank">Chat on WhatsApp</a></p>

            <p><strong>Follow us on social media:</strong></p>
            <div class="social-links">
                <a href="https://instagram.com/yourpage" target="_blank" class="social-icon">Instagram</a>
                <a href="https://facebook.com/yourpage" target="_blank" class="social-icon">Facebook</a>
                <a href="https://twitter.com/yourpage" target="_blank" class="social-icon">Twitter</a>
            </div>
        </div>
    </div>
</section>
<script>
   const burger = document.getElementById("burger");
const sidebar = document.getElementById("sidebar");
const name = localStorage.getItem("name") || "User";

// Insert greeting without removing existing content
if (name) {
  const greeting = document.createElement("h2");
  greeting.textContent = `Hello, <?= $_SESSION['user']['name'] ?>`;
  greeting.style.marginTop = "80px";
  greeting.style.color = "white";
  sidebar.prepend(greeting);
}

burger.addEventListener("click", () => {
  burger.classList.toggle("active");
  sidebar.classList.toggle("active");
});

</script>



</body>
</html>