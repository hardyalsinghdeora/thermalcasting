<?php include_once('header.php'); ?>
<div class="container-fluid banner">
    <div class="row">
        <div id="myCarousel" class="carousel slide" data-ride="carousel">
            <!-- Wrapper for slides -->
            <div class="carousel-inner">
                <div class="item active">
                    <img src="assets/images/banner1.jpg" alt="Anant Services" style="width:100%;">
                </div>

                <div class="item">
                    <img src="assets/images/banner2.jpg" alt="Anant Services" style="width:100%;">
                </div>

                <div class="item">
                    <img src="assets/images/banner3.jpg" alt="Anant Services" style="width:100%;">
                </div>
            </div>

            <!-- Left and right controls -->
            <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="#myCarousel" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
</div>
<div class="container-fluid slogan">
    <div class="container" >
        <h1><span>Welcome to Anant Services &nbsp; &nbsp; </span></h1>
        <p>There will be some text about the company.<br />
            There will be some text about the company.<br>
            There will be some text about the company.</p>
    </div>
</div>
<div class="container-fluid services">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-6 service-div">
                <h1 class="landscaping-title">Landscaping / Lawn Care</h1>
                <img src="assets/images/landsraping.jpg" class="img-responsive w-100" />
            </div>
            <div class="col-sm-6 service-div">
                <h1 class="pressure-wash-title">Pressure Wash</h1>
                <img src="assets/images/landsraping.jpg" class="img-responsive w-100" />
            </div>
            <div class="clearfix"></div>
            <div class="col-sm-6 service-div">
                <h1 class="roof-cleaning-title">Roof Cleaning</h1>
                <img src="assets/images/landsraping.jpg" class="img-responsive w-100" />
            </div>
            <div class="col-sm-6 service-div">
                <h1 class="house-cleaning-title">House / Office Cleaning</h1>
                <img src="assets/images/landsraping.jpg" class="img-responsive w-100" />
            </div>
            <div class="clearfix"></div>
            <div class="col-sm-6 service-div">
                <h1 class="maid-service-title">Maid / Janitorial Services</h1>
                <img src="assets/images/landsraping.jpg" class="img-responsive w-100" />
            </div>
            <div class="col-sm-6 service-div">
                <h1 class="enquiry-service-title">Enquiry</h1>
                <form class="enquiry-form">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Name">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Email">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Contact">
                    </div>
                    <div class="form-group">
                        <select class="form-control">
                            <option>Select Service</option>
                            <option>Landscaping / Lawn Care</option>
                            <option>Pressure Washing</option>
                            <option>Roof Cleaning</option>
                            <option>House / Office Cleaning</option>
                            <option>Maid / Janitorial Services</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control enquiries-textbox" placeholder="Enter your query"></textarea>
                    </div>
                    <div class="">
                        <input type="button" class="btn btn-success" value="Submit">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
        </div>
    </div>
</div>
<?php include_once('footer.php'); ?>
