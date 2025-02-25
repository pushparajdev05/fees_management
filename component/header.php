<div class="nav-bars">
        <nav class="nav-bar">
        <div class="logo">
            <img src="./images/measi_logo-removebg.png" alt="no logo ">
        </div>
        <ul>
            <li id="nav1" class="li visit1 hover1"><a href="homepage.php?page=1">Home</a></li>
            <li id="nav2" class="li visit2 hover2"><a href="collection.php?page=2">Collection</a></li>
            <li id="nav3" class="li visit3 hover3"><a href="defaulters.php?page=3">Defaulters</a></li>
            <li id="nav4" class="li visit4 hover4"><a href="transfer.php?page=4">Transfer</a></li>
            <li id="nav5" class="li visit5 hover5"><a href="about.php?page=5">About</a></li>
        </ul>
        <div class="logout border">
            <div class="btn">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24"><path d="M4 22C4 17.5817 7.58172 14 12 14C16.4183 14 20 17.5817 20 22H4ZM12 13C8.685 13 6 10.315 6 7C6 3.685 8.685 1 12 1C15.315 1 18 3.685 18 7C18 10.315 15.315 13 12 13Z"></path></svg>
                <span>
                    <?php
                    echo ucfirst($_SESSION["user"]);
                    ?> 
                </span>
            </div>
            <div id="og_logout">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24"><path d="M5 11H13V13H5V16L0 12L5 8V11ZM3.99927 18H6.70835C8.11862 19.2447 9.97111 20 12 20C16.4183 20 20 16.4183 20 12C20 7.58172 16.4183 4 12 4C9.97111 4 8.11862 4.75527 6.70835 6H3.99927C5.82368 3.57111 8.72836 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C8.72836 22 5.82368 20.4289 3.99927 18Z"></path></svg>
                <span>Logout</span>
            </div>
        </div>
    </nav>
</div>