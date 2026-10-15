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
    <div class="icon-bar">
        <a href="#" class="facebook"><i class="fa fa-facebook"></i></a>
        <a href="#" class="instagram"><i class="fa fa-instagram"></i></a>
    </div>
    <div class="container">
        <div class="col-sm-12">
            <h1>Landscaping / Lawn Care</h1>
            <p>There will be some text about the company.<br />
                There will be some text about the company.<br>
                There will be some text about the company.</p>
        </div>
        <div class="col-sm-12 service-block">
            <div class="col-sm-4">
                <img src="assets/images/landsraping-img.png" class="img-responsive">
            </div>
            <div class="col-sm-8">
                <h2>Description title</h2>
                <p>Some Text about the service, Some Text about the service, Some Text about the service</p>
            </div>
        </div>
        <div class="col-sm-12 service-block">
            <div class="col-sm-8">
                <h2>Description title</h2>
                <p>Some Text about the service, Some Text about the service, Some Text about the service</p>
            </div>
            <div class="col-sm-4">
                <img src="assets/images/landsraping-img.png" class="img-responsive">
            </div>
        </div>
        <div class="col-sm-12 service-block">
            <div class="col-sm-4">
                <img src="assets/images/landsraping-img.png" class="img-responsive">
            </div>
            <div class="col-sm-8">
                <h2>Description title</h2>
                <p>Some Text about the service, Some Text about the service, Some Text about the service</p>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid services">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2 service-div">
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
                            <option selected>Landscaping / Lawn Care</option>
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
