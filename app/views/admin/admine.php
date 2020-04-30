<?php require_once APPROOT . '/views/includes/header.php'; ?>

    <div class="page-wrapper">
        <div class='text-center'>
            <h1>Admin Login</h1>
        </div>
        <div class="page-content--bgf7">
            <section class="statistic statistic2">
                <div class="container ">
                    <div class="row ">
                        <div class="col-md-6 col-lg-8">
                            <div class="card">
                                <div class="card-header">Enter your details</div>
                                <div class="card-body card-block">
                                    <form action="<?php echo URLROOT;?>Admine" method="post" class="">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-user"></i>
                                                </div>
                                                <input type="text" id="email" name="email" placeholder="Admin id*" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-envelope"></i>
                                                </div>
                                                <input type="password" id="password" name="password" placeholder="Password*" class="form-control" required>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-actions form-group">
                                            <button type="submit" class="btn btn-success btn-sm">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

<?php require_once APPROOT . '/views/includes/footer.php'; ?>