<div class="navbar navbar-inverse set-radius-zero" >
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand">

                    <img src="assets/img/logo.png" width="125px" height="125px"/>
                </a>

            </div>

            <div class="right-div">
            <div class="btn-group">
                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        Options <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="change-password.php">Change Password</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="logout.php">Log Me Out</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- LOGO HEADER END-->
    <section class="menu-section">
        <div class="container">
            <div class="row ">
                <div class="col-md-12">
                    <div class="navbar-collapse collapse ">
                        <ul id="menu-top" class="nav navbar-nav navbar-right">
                            <li><a href="reg_request.php" class="menu-top-active">DASHBOARD</a></li>
                           
                            <li>
                                <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown"> Categories <i class="fa fa-angle-down"></i></a>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                                    <li role="presentation"><a role="menuitem" tabindex="-1" href="add-category.php">Add Category</a></li>
                                     <li role="presentation"><a role="menuitem" tabindex="-1" href="manage-categories.php">Manage Categories</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown"> Authors <i class="fa fa-angle-down"></i></a>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                                    <li role="presentation"><a role="menuitem" tabindex="-1" href="add-author.php">Add Author</a></li>
                                     <li role="presentation"><a role="menuitem" tabindex="-1" href="manage-authors.php">Manage Authors</a></li>
                                </ul>
                            </li>
 <li>
                                <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown"> Books <i class="fa fa-angle-down"></i></a>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                                    <li role="presentation"><a role="menuitem" tabindex="-1" href="add-book.php">Add Book</a></li>
                                     <li role="presentation"><a role="menuitem" tabindex="-1" href="manage-books.php">Manage Books</a></li>
                                </ul>
                            </li>

                           <li>
                                <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown"> Issue Books <i class="fa fa-angle-down"></i></a>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                                    <li role="presentation"><a role="menuitem" tabindex="-1" href="issue-book.php">Issue New Book</a></li>
                                     <li role="presentation"><a role="menuitem" tabindex="-1" href="manage-issued-books.php">Manage Issued Books</a></li>
                                </ul>
                            </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Members <i
                                        class="fa fa-angle-down"></i></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="reg-students.php">Reg Students</a></li>
                                    <li><a href="available_students.php">Available Students</a></li>
                                </ul>
                                </li>
                        
                                 
                                 <li>
                                 <li class="dropdown">
                                     <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Report <i class="fa fa-angle-down"></i></a>
                                    <ul class="dropdown-menu" role="menu">
                                      <li><a href="reportgen.php?type=borrowed">Borrowed Books</a></li>
                                     <li><a href="available_books.php?type=available">Available Books</a></li>
                                     <li><a href="Books_lost.php?type=lost">Books Lost</a></li>
                                        <li><a href="Active_users.php?type=active">Active Users</a></li>
                                        <li><a href="Blocked_users.php?type=blocked">Blocked Users</a></li>
                                     </ul>
                                </li>

                                 </li>

                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </section>
   
